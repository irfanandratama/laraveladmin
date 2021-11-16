<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\SkpTahunanLines;
use App\SkpTahunanHeader;
use App\SatuanKegiatan;
use App\TugasTambahan;
use App\Kreativitas;
use App\User;
use App\ValidationTemp;
use App\Status;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Carbon\Carbon;

use App\Exports\SkpTahunanTargetExport;
use Maatwebsite\Excel\Facades\Excel;
use \PDF;

class SkpTahunanTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $skpheader = SkpTahunanHeader::findOrFail($id);
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)
            ->whereIn('status', ['01', '02', '04', '05', '06', '07', '09'])
            ->get();
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $users = User::all()->pluck('name', 'id');

        return view('skp.tahunan.target.index', compact('skpheader', 'skplines', 'tahun', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');
        $skpheader = SkpTahunanHeader::findOrFail($id);
        $user = User::find($skpheader->user_id);
        $users = User::all()->pluck('name', 'id');

        return view('skp.tahunan.target.create', compact('satuankegiatan', 'id', 'user', 'skpheader', 'users'));
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
                'kegiatan'=>'required',
                'kuantitas_target'=>'required|numeric',
                'satuan_kegiatan_id'=>'required',
                'angka_kredit_target'=>'required|numeric',
                'waktu_target'=>'required|numeric',
                'biaya_target'=>'required|numeric'
            ],
            $this->messages()
        );

        $request->merge(['status' => '01' ]);

        $user = SkpTahunanLines::create($request->only('kegiatan', 'kuantitas_target', 'satuan_kegiatan_id', 'kualitas_target',
        'angka_kredit_target', 'waktu_target', 'biaya_target', 'skp_tahunan_header_id', 'status'));
  
        return redirect()->route('target.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Berhasil mengajukan penambahan data target SKP tahunan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $skpheader = SkpTahunanHeader::find($id);
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)
            ->whereIn('status', ['01', '02', '04', '05', '06', '07', '09'])
            ->paginate(10);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $user = User::find($skpheader->user_id);
        $users = User::all()->pluck('name', 'id');
        $skplines->map(function ($skp) {
            $status = Status::where('status', $skp->status)->first();
            if ($status) {
                $skp['keterangan'] = $status->keterangan;
            } 
        });
        
        return view('skp.tahunan.target.index', compact('skpheader', 'skplines', 'tahun', 'id', 'user', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $skpline = SkpTahunanLines::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($skpline->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');

        return view('skp.tahunan.target.edit', compact('skpheader', 'skpline', 'user', 'satuankegiatan'));
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
        $skp = SkpTahunanLines::findOrFail($id);

        $this->validate($request, [
                'kegiatan'=>'required',
                'kuantitas_target'=>'required|numeric',
                'satuan_kegiatan_id'=>'required',
                'angka_kredit_target'=>'required|numeric',
                'waktu_target'=>'required|numeric',
                'biaya_target'=>'required|numeric'
            ],
            $this->messages()
            
        );

        if ($skp->perhitungan) {
            $kuantitas_total = ($skp->kuantitas_realisasi/ $request->kuantitas_target) * 100;
            $kualitas_total = ($skp->kualitas_realisasi / $request->kualitas_target) * 100;
            $persen_waktu = 100 - ($skp->waktu_realisasi / $request->waktu_target * 100);
            $persen_biaya = $request->biaya_target === 0 || $skp->biaya_realisasi === 0 ? 
                0 : 100 - ($skp->biaya_realisasi / $request->biaya_target * 100);

            $nilai_waktu = 0;
            if ($persen_waktu > 24) {
                $nilai_waktu = (76 - ((((1.76 * $request->waktu_target - $skp->waktu_realisasi) / $request->waktu_target) * 100) - 100));
            } else {
                $nilai_waktu = ((( 1.76 * $request->waktu_target - $skp->waktu_realisasi) / $request->waktu_target ) * 100);
            }

            $nilai_biaya = 0;
            if ($persen_biaya > 24) {
                $nilai_biaya = (76 - ((((1.76 * $request->biaya_target - $skp->biaya_realisasi) / $request->biaya_target) * 100) - 100));
            } else {
                $nilai_biaya = $request->biaya_target === 0 || $skp->biaya_realisasi === 0 ? 
                    0 : ((( (1.76 * $request->biaya_target) - $skp->biaya_realisasi) / $request->biaya_target ) * 100);
            }

            $total_hitung = $kuantitas_total + $kualitas_total + $nilai_waktu + $nilai_biaya;

            $nilai_capaian = 0;

            if ($request->biaya_realisasi < 1) {
                $nilai_capaian = $total_hitung / 3;
            } else {
                $nilai_capaian = $total_hitung / 4;
            }

            $request->merge(['perhitungan' => $total_hitung ]);
            $request->merge(['nilai_capaian' => $nilai_capaian ]);
        }

        $request->merge(['old_id' => $skp->id ]);
        $request->merge(['table_name' => 'skp_tahunan_lines_target' ]);


        ValidationTemp::create($request->only('kegiatan', 'kuantitas_target', 'satuan_kegiatan_id', 'kualitas_target',
        'angka_kredit_target', 'waktu_target', 'biaya_target', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian',
        'old_id', 'table_name'));

        $skp->status = '04';
        $skp->save();
        // $skp->fill($input)->save();

        return redirect()->route('target.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Berhasil mengajukan perubahan target SKP tahunan.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $skpline = SkpTahunanLines::findOrFail($id);
        $skpheaderid = $skpline->skp_tahunan_header_id;
        $skpline->status = '07';
        $skpline->save();
        // $skpline->delete();

        $request = new Request();
        $request->merge(['kegiatan' => $skpline->kegiatan ]);
        $request->merge(['kuantitas_target' => $skpline->kuantitas_target ]);
        $request->merge(['satuan_kegiatan_id' => $skpline->satuan_kegiatan_id ]);
        $request->merge(['kualitas_target' => $skpline->kualitas_target ]);
        $request->merge(['angka_kredit_target' => $skpline->angka_kredit_target ]);
        $request->merge(['waktu_target' => $skpline->waktu_target ]);
        $request->merge(['biaya_target' => $skpline->biaya_target ]);
        $request->merge(['skp_tahunan_header_id' => $skpline->skp_tahunan_header_id ]);
        $request->merge(['perhitungan' => $skpline->perhitungan ]);
        $request->merge(['nilai_capaian' => $skpline->nilai_capaian ]);
        $request->merge(['old_id' => $skpline->id ]);
        $request->merge(['table_name' => 'skp_tahunan_lines_target' ]);

        ValidationTemp::create($request->only('kegiatan', 'kuantitas_target', 'satuan_kegiatan_id', 'kualitas_target',
        'angka_kredit_target', 'waktu_target', 'biaya_target', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian',
        'old_id', 'table_name'));

        return redirect()->route('target.show', [$skpheaderid])
            ->with('flash_message',
            'Kegiatan SKP Tahunan successfully added.');
    }

    public function messages()
    {
        return [
            'kegiatan.required'=> 'Mohon isi kolom Kegiatan',
            'kuantitas_target.required'=> 'Mohon isi kolom Target Kuantitas',
            'kuantitas_target.numeric'=> 'Mohon mengisi kolom Target Kuantitas dengan angka',
            'satuan_kegiatan_id.required'=> 'Mohon pilih Satuan Kegiatan yang sesuai',
            'angka_kredit_target.required'=> 'Mohon isi kolom Angka Kredit',
            'angka_kredit.numeric'=> 'Mohon mengisi kolom Angka Kredit dengan angka',
            'waktu_target.required'=> 'Mohon isi kolom Target Waktu',
            'waktu_target.numeric'=> 'Mohon mengisi kolom Target Waktu dengan angka',
            'biaya_target.required'=> 'Mohon isi kolom Target Biaya',
            'biaya_target.numeric'=> 'Mohon mengisi kolom Target Biaya dengan angka'        
        ];
    }

    public function export($id)
    {
        // return Excel::download(new SkpTahunanTargetExport($id), 'FORM SKP.xlsx');
        // $id = $this->id;
        $skpheader = SkpTahunanHeader::find($id);
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)->whereNotIn('status', ['01', '03', '04', '07'])->get();
        $satuan = SatuanKegiatan::all();
        $user = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $skpheader->user_id)->first();
        $user_atasan = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $user->atasan_1_id)->first();
        $tugass = TugasTambahan::where('skp_tahunan_header_id', $id)->get();
        $kreativitas = Kreativitas::where('skp_tahunan_header_id', $id)->get();

        $satker = \explode(' ', $user->satuan_kerja->satuan_kerja);
        $lokasi = array_pop($satker);

        $pdf = \PDF::loadView('exports.form-skp-pdf', compact(
            'skplines',
            'satuan',
            'user',
            'user_atasan',
            'tugass',
            'kreativitas',
            'lokasi'
        ));
        // return $pdf->stream();
        return $pdf->download('FORM_SKP.pdf');
    }

    public function validate_data($id)
    {
        $skpline = SkpTahunanLines::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($skpline->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');

        $validation_temp = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_target')->first();
            
        $users = User::all()->pluck('name', 'id');

        return view('skp.tahunan.target.validation', compact('skpheader', 'skpline', 'user', 'satuankegiatan', 'validation_temp'));
    }

    public function validation(Request $request, $id)
    {
        switch ($request->get('action')) {
            case 'Confirm':
                $skp = SkpTahunanLines::findOrFail($id);
                $skpheaderid = $skp->skp_tahunan_header_id;
                $skp->status = '02';
                $skp->save();
                return redirect()->route('target.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima target SKP tahunan');
                break;
            case 'Decline':
                $skp = SkpTahunanLines::findOrFail($id);
                $skpheaderid = $skp->skp_tahunan_header_id;
                $skp->status = '03';
                $skp->save();
                return redirect()->route('target.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak target SKP tahunan.');
                break;
            case 'Confirm Update':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_target')->first();
                $skpheaderid = $skp->skp_tahunan_header_id;

                $skp->kegiatan = $validation->kegiatan;
                $skp->kuantitas_target = $validation->kuantitas_target;
                $skp->satuan_kegiatan_id = $validation->satuan_kegiatan_id;
                $skp->angka_kredit_target = $validation->angka_kredit_target;
                $skp->waktu_target = $validation->waktu_target;
                $skp->biaya_target = $validation->biaya_target;
                if ($skp->perhitungan) {
                    $skp->perhitungan = $validation_temp->perhitungan;
                    $skp->nilai_capaian = $validation_temp->nilai_capaian;
                }
                $skp->status = '05';

                $skp->save();
                $validation->delete();
                return redirect()->route('target.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima perubahan SKP tahunan.');
                break;
            case 'Decline Update':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_target')->first();
                $skpheaderid = $skp->skp_tahunan_header_id;
                $skp->status = '06';

                $skp->save();
                $validation->delete();

                return redirect()->route('target.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak perubahan SKP tahunan.');
                break;
            case 'Confirm Delete':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_target')->first();
                $skpheaderid = $skp->skp_tahunan_header_id;

                $skp->delete();
                $validation->delete();

                return redirect()->route('target.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima penghapusan SKP tahunan.');
            case 'Decline Delete':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_target')->first();
                $skpheaderid = $skp->skp_tahunan_header_id;

                $skp->status = '09';
                $skp->save();
                $validation->delete();

                return redirect()->route('target.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak penghapusan SKP tahunan.');
            default:
                $skp = SkpTahunanLines::findOrFail($id);
                $skpheaderid = $skp->skp_tahunan_header_id;
                return redirect()->route('target.show', [$skpheaderid])
                ->with('flash_message',
                'Error undetected action.');
                break;
        }
    }
}
