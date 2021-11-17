<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SkpTahunanHeader;
use App\User;
use App\SkpTahunanLines;
use App\SatuanKegiatan;
use App\TugasTambahan;
use App\Kreativitas;
use App\ValidationTemp;
use App\Status;
use App\PenilaianPerilaku;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

use App\Exports\SkpExport;
use Maatwebsite\Excel\Facades\Excel;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class SkpTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole(['Super-Admin', 'Administrator', 'Kepegawaian'])) {
            $skps = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
            ->whereIn('status', ['01', '02', '04', '05', '06', '07', '09'])
            ->get(['skp_tahunan_header.*', 'users.name']);
            $users = User::all()->pluck('name', 'id');

            $skps->map(function ($skp) {
                $status = Status::where('status', $skp->status)->first();
                if ($status) {
                    $skp['keterangan'] = $status->keterangan;
                }
                $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $skp->id)->whereNotIn('status', ['01', '03', '04', '07'])->first();

                if ($penilaian) {
                    $skp['printable'] = true;
                } else {
                    $skp['printable'] = false;
                }
            });

            return view('skp.tahunan.index', compact('skps', 'users'));
        } else {
            $skps = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
                ->where('skp_tahunan_header.user_id', Auth::id())
                ->whereIn('status', ['01', '02', '04', '05', '06', '07', '09'])
                ->get(['skp_tahunan_header.*', 'users.name']);

            $skps->map(function ($skp) {
                $status = Status::where('status', $skp->status)->first();
                if ($status) {
                    $skp['keterangan'] = $status->keterangan;
                } 
                $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $skp->id)->whereNotIn('status', ['01', '03', '04', '07'])->first();

                if ($penilaian) {
                    $skp['printable'] = true;
                } else {
                    $skp['printable'] = false;
                }
            });

            return view('skp.tahunan.index')->with('skps', $skps);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'periode_mulai'=>'required',
                'periode_selesai'=>'required'
            ],
            $this->messages()
        );

        $request->merge(['status' => '01' ]);

        $skptahunanheader = SkpTahunanHeader::create($request->only('periode_mulai', 'periode_selesai', 'user_id', 'status'));

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'Berhasil mengajukan pembuatan SKP tahunan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $skp = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
            ->where('skp_tahunan_header.id', $id)
            ->first(['skp_tahunan_header.*', 'users.name']);
            
        $users = User::all()->pluck('name', 'id');

        return view('skp.tahunan.edit', compact('skp', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $skp = SkpTahunanHeader::findOrFail($id);

        $this->validate($request, [
                'periode_mulai'=>'required',
                'periode_selesai'=>'required'
            ],
            $this->messages()
            
        );
        // $input = $request->only('periode_mulai', 'periode_selesai', 'user_id');
        // $skp->fill($input)->save();

        $skp->status = '04';
        $skp->save();

        $request->merge(['old_id' => $skp->id ]);
        $request->merge(['table_name' => 'skp_tahunan_header' ]);

        ValidationTemp::create($request->only('periode_mulai', 'periode_selesai', 'user_id', 'old_id', 'table_name'));

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'Berhasil mengajukan perubahan SKP tahunan.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $skp = SkpTahunanHeader::findOrFail($id); 
        $skp->status = '07';
        $skp->save();

        $request = new Request();
        $request->merge(['periode_mulai' => $skp->periode_mulai ]);
        $request->merge(['periode_selesai' => $skp->periode_selesai]);
        $request->merge(['user_id' => $skp->user_id ]);
        $request->merge(['old_id' => $skp->id ]);
        $request->merge(['table_name' => 'skp_tahunan_header' ]);

        ValidationTemp::create($request->only('periode_mulai', 'periode_selesai', 'user_id', 'old_id', 'table_name'));
        // $skp->delete();

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'Berhasil mengajukan penghapusan SKP tahunan.');
    }

    public function messages()
    {
        return [
            'periode_mulai.required'=> 'Mohon isi kolom Periode Mulai',
            'periode_selesai.required'=> 'Mohon isi kolom Periode Selesai'            
        ];
    }

    public function export($id)
    {
        $skpheader = SkpTahunanHeader::findOrFail($id);
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)->whereNotIn('status', ['01', '03', '04', '07'])->get();
        $satuan = SatuanKegiatan::all();
        $user = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $skpheader->user_id)->first();
        $user_atasan = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $user->atasan_1_id)->first();
        $user_atasan_atasan = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $user->atasan_2_id)->first();

        if (!$user_atasan || !$user_atasan_atasan) {
            return redirect()->route('tahunan.index')
            ->with('error',
             'Silakan mengisi data atasan baik atasan langsung atau pun atasasn dari pejabat penilai di menu user lists');
        }
        $tugass = TugasTambahan::where('skp_tahunan_header_id', $id)->whereNotIn('status', ['01', '03', '04', '07'])->get();
        $kreativitas = Kreativitas::where('skp_tahunan_header_id', $id)->whereNotIn('status', ['01', '03', '04', '07'])->get();
        $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $id)->whereNotIn('status', ['01', '03', '04', '07'])->first();

        $path = base64_encode(file_get_contents(\public_path('img/garuda.jpg')));
        $type = \pathinfo('garuda.jpg', PATHINFO_EXTENSION);
        $base64 = 'data:image/' . $type . ';base64,' . $path;

        $nilai_capaian = $skplines->sum('nilai_capaian');
        $count_lines = count($skplines);
        $count_tugas = count($tugass);
        $count_kreativitas = count($kreativitas);

        $total_nilai = $nilai_capaian / $count_lines + $count_tugas + $count_kreativitas;
        $capaian = (
            ($total_nilai <= 50) ? "Buruk" :
             (($total_nilai <= 60 && $total_nilai > 50) ? "Sedang" :
              (($total_nilai <= 75 && $total_nilai > 60) ? "Cukup" :
               (($total_nilai <= 90 && $total_nilai > 75) ? "Baik" : "Sangat Baik")))
            );

        $nilai_skp = $total_nilai * 60 / 100;
        $nilai_perilaku = $penilaian->rata_rata * 40 / 100;
        $jumlah_nilai = $nilai_skp + $nilai_perilaku;
        $capaian_final = (
            ($jumlah_nilai <= 50) ? "Buruk" :
             (($jumlah_nilai <= 60 && $jumlah_nilai > 50) ? "Sedang" :
              (($jumlah_nilai <= 75 && $jumlah_nilai > 60) ? "Cukup" :
               (($jumlah_nilai <= 90 && $jumlah_nilai > 75) ? "Baik" : "Sangat Baik")))
            );
        $satker = \explode(' ', $user->satuan_kerja->satuan_kerja);
        $lokasi = array_pop($satker);
        $oMerger = PDFMerger::init();
        $pdf1 = \PDF::loadView('exports.form-skp-pdf', compact(
            'skplines',
            'satuan',
            'user',
            'user_atasan',
            'tugass',
            'kreativitas',
            'lokasi'
        ));
        $pdf2 = \PDF::loadView('exports.penilaian-capaian-pdf', compact(
            'skpheader',
            'skplines',
            'satuan',
            'user',
            'user_atasan',
            'tugass',
            'kreativitas',
            'total_nilai',
            'capaian',
            'lokasi'
        ));
        $pdf3 = \PDF::loadView('exports.penilaian-perilaku-pdf', compact(
            'skpheader',
            'skplines',
            'satuan',
            'user',
            'user_atasan',
            'total_nilai',
            'penilaian'
        ));
        $pdf = \PDF::loadView('exports.penilaian-akhir-pdf', compact(
            'skpheader',
            'skplines',
            'satuan',
            'user',
            'user_atasan',
            'user_atasan_atasan',
            'penilaian',
            'total_nilai',
            'capaian',
            'lokasi',
            'nilai_skp',
            'nilai_perilaku',
            'jumlah_nilai',
            'capaian_final',
            'base64'
        ));

        Storage::disk('local')->put('FORM_SKP.pdf', $pdf1->download()->getOriginalContent());
        Storage::disk('local')->put('PENGUKURAN.pdf', $pdf2->download()->getOriginalContent());
        Storage::disk('local')->put('PERILAKU.pdf', $pdf3->download()->getOriginalContent());
        Storage::disk('local')->put('PENILAIAN.pdf', $pdf->download()->getOriginalContent());

        $oMerger->addPdf(Storage::disk('local')->path('FORM_SKP.pdf'), 'all');
        $oMerger->addPdf(Storage::disk('local')->path('PENGUKURAN.pdf'), 'all');
        $oMerger->addPdf(Storage::disk('local')->path('PERILAKU.pdf'), 'all');
        $oMerger->addPdf(Storage::disk('local')->path('PENILAIAN.pdf'), 'all');
        $oMerger->merge();
        // $oMerger->save('PENILAIAN_KESELURUHAN.pdf');
        Storage::disk('local')->delete('FORM_SKP.pdf');
        Storage::disk('local')->delete('PENGUKURAN.pdf');
        Storage::disk('local')->delete('PERILAKU.pdf');
        Storage::disk('local')->delete('PENILAIAN.pdf');
        $oMerger->setFileName('PENILAIAN_KESELURUHAN.pdf');
        return $oMerger->download(); //$pdf->download('PENILAIAN.pdf');
    }

    public function validate_data($id)
    {
        $skp = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
            ->where('skp_tahunan_header.id', $id)
            ->first(['skp_tahunan_header.*', 'users.name']);
        $validation_temp = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();
            
        $users = User::all()->pluck('name', 'id');

        return view('skp.tahunan.validation', compact('skp', 'users', 'validation_temp'));
    }

    public function validation(Request $request, $id)
    {
        switch ($request->get('action')) {
            case 'Confirm':
                $skp = SkpTahunanHeader::findOrFail($id);
                $skp->status = '02';
                $skp->save();
                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menerima SKP tahunan');
                break;
            case 'Decline':
                $skp = SkpTahunanHeader::findOrFail($id);
                $skp->status = '03';
                $skp->save();
                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menolak SKP tahunan.');
                break;
            case 'Confirm Update':
                $skp = SkpTahunanHeader::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();

                $skp->periode_mulai = $validation->periode_mulai;
                $skp->periode_selesai = $validation->periode_selesai;
                $skp->user_id = $validation->user_id;
                $skp->status = '05';

                $skp->save();
                $validation->delete();
                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menerima perubahan SKP tahunan.');
                break;
            case 'Decline Update':
                $skp = SkpTahunanHeader::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();
                $skp->status = '06';

                $skp->save();
                $validation->delete();

                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menolak perubahan SKP tahunan.');
                break;
            case 'Confirm Delete':
                $skp = SkpTahunanHeader::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();

                $skp->delete();
                $validation->delete();

                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menerima penghapusan SKP tahunan.');
            case 'Decline Delete':
                $skp = SkpTahunanHeader::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_header')->first();
                $skp->status = '09';
                $skp->save();
                $validation->delete();

                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Berhasil menolak penghapusan SKP tahunan.');
            default:
                return redirect()->route('tahunan.index')
                ->with('flash_message',
                'Error undetected action.');
                break;
        }
    }
}
