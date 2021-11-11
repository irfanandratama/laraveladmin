<?php

namespace App\Http\Controllers;

use App\SkpTahunanLines;
use App\SkpTahunanHeader;
use App\SatuanKegiatan;
use App\TugasTambahan;
use App\Kreativitas;
use App\User;
use App\ValidationTemp;
use App\Status;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Carbon\Carbon;

class SkpTahunanRealisasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $skpline = SkpTahunanLines::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($skpline->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');

        return view('skp.tahunan.realisasi.create', compact('skpheader', 'skpline', 'user', 'satuankegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $skp = SkpTahunanLines::findOrFail($request->skplineid);

        $this->validate($request, [
                'kegiatan'=>'required',
                'kuantitas_realisasi'=>'required|numeric',
                'satuan_kegiatan_id'=>'required',
                'angka_kredit_realisasi'=>'required|numeric',
                'waktu_realisasi'=>'required|numeric',
                'biaya_realisasi'=>'required|numeric'
            ],
            $this->messages()
            
        );

        $kuantitas_total = ($skp->kuantitas_target / $request->kuantitas_realisasi) * 100;
        $kualitas_total = ($skp->kualitas_target / $request->kualitas_realisasi) * 100;
        $persen_waktu = 100 - ($skp->waktu_target / $request->waktu_realisasi * 100);
        $persen_biaya = $skp->biaya_target === 0 || $request->biaya_realisasi === 0 ? 
            0 : 100 - ($skp->biaya_target / $request->biaya_realisasi * 100);

        $nilai_waktu = 0;
        if ($persen_waktu > 24) {
            $nilai_waktu = 76 - ((((1.76 * $skp->waktu_target - $request->waktu_realisasi) / $skp->waktu_target) * 100) - 100);
        } else {
            $nilai_waktu = (( 1.76 * $skp->waktu_target - $request->waktu_realisasi) / $skp->waktu_target ) * 100;
        }

        $nilai_biaya = 0;
        if ($persen_biaya > 24) {
            $nilai_biaya = 76 - ((((1.76 * $skp->biaya_target - $request->biaya_realisasi) / $skp->biaya_target) * 100) - 100);
        } else {
            $nilai_waktu = $skp->biaya_target === 0 || $request->biaya_realisasi === 0 ? 
                0 : (( 1.76 * $skp->biaya_target - $request->biaya_realisasi) / $skp->biaya_target ) * 100;
        }

        $total_hitung = $kuantitas_total + $kualitas_total + $nilai_waktu + $nilai_biaya;

        $nilai_capaian = 0;

        if ($request->biaya_realisasi === 0) {
            $nilai_capaian = $total_hitung / 3;
        } else {
            $nilai_capaian = $total_hitung / 4;
        }

        $request->merge(['perhitungan' => $total_hitung ]);
        $request->merge(['nilai_capaian' => $nilai_capaian ]);
        $request->merge(['status' => '01' ]);
        $request->merge(['old_id' => $skp->id ]);
        $request->merge(['table_name' => 'skp_tahunan_lines_realisasi' ]);

        // $input = $request->only('kegiatan', 'kuantitas_realisasi', 'satuan_kegiatan_id', 'kualitas_realisasi',
        // 'angka_kredit_realisasi', 'waktu_realisasi', 'biaya_realisasi', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian',
        // 'status');

        ValidationTemp::create($request->only('kegiatan', 'kuantitas_realisasi', 'satuan_kegiatan_id', 'kualitas_realisasi',
        'angka_kredit_realisasi', 'waktu_realisasi', 'biaya_realisasi', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian',
        'old_id', 'table_name'));
        $skp->status = '01';
        $skp->save();
        // $skp->fill($input)->save();

        return redirect()->route('realisasi.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Kegiatan SKP Tahunan successfully updated.');
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
        ->whereIn('status', ['01', '02', '04', '05', '06', '07', '08', '09'])
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
        
        return view('skp.tahunan.realisasi.index', compact('skpheader', 'skplines', 'tahun', 'id', 'user', 'users'));
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

        return view('skp.tahunan.realisasi.edit', compact('skpheader', 'skpline', 'user', 'satuankegiatan'));
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
                'kuantitas_realisasi'=>'required|numeric',
                'satuan_kegiatan_id'=>'required',
                'angka_kredit_realisasi'=>'required|numeric',
                'waktu_realisasi'=>'required|numeric',
                'biaya_realisasi'=>'required|numeric'
            ],
            $this->messages()
            
        );

        $kuantitas_total = ($request->kuantitas_realisasi/ $skp->kuantitas_target) * 100;
        $kualitas_total = ($request->kualitas_realisasi / $skp->kualitas_target) * 100;
        $persen_waktu = 100 - ($request->waktu_realisasi / $skp->waktu_target * 100);
        $persen_biaya = $skp->biaya_target === 0 || $request->biaya_realisasi === 0 ? 
            0 : 100 - ($request->biaya_realisasi / $skp->biaya_target * 100);

        $nilai_waktu = 0;
        if ($persen_waktu > 24) {
            $nilai_waktu = (76 - ((((1.76 * $skp->waktu_target - $request->waktu_realisasi) / $skp->waktu_target) * 100) - 100));
        } else {
            $nilai_waktu = ((( 1.76 * $skp->waktu_target - $request->waktu_realisasi) / $skp->waktu_target ) * 100);
        }

        $nilai_biaya = 0;
        if ($persen_biaya > 24) {
            $nilai_biaya = (76 - ((((1.76 * $skp->biaya_target - $request->biaya_realisasi) / $skp->biaya_target) * 100) - 100));
        } else {
            $nilai_biaya = $skp->biaya_target === 0 || $request->biaya_realisasi === 0 ? 
                0 : ((( (1.76 * $skp->biaya_target) - $request->biaya_realisasi) / $skp->biaya_target ) * 100);
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

        $request->merge(['old_id' => $skp->id ]);
        $request->merge(['table_name' => 'skp_tahunan_lines_realisasi' ]);

        // $input = $request->only('kegiatan', 'kuantitas_realisasi', 'satuan_kegiatan_id', 'kualitas_realisasi',
        // 'angka_kredit_realisasi', 'waktu_realisasi', 'biaya_realisasi', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian',
        // 'old_id', 'table_name');

        ValidationTemp::create($request->only('kegiatan', 'kuantitas_realisasi', 'satuan_kegiatan_id', 'kualitas_realisasi',
        'angka_kredit_realisasi', 'waktu_realisasi', 'biaya_realisasi', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian',
        'old_id', 'table_name'));

        $skp->status = '04';
        $skp->save();

        return redirect()->route('realisasi.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Berhasil mengajukan perubahan data realisasi SKP tahunan.' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $skp = SkpTahunanLines::findOrFail($id);
        $skp->status = '07';
        $skp->save();

        // $skp->kuantitas_realisasi = null;
        // $skp->kualitas_realisasi = null;
        // $skp->waktu_realisasi = null;
        // $skp->biaya_realisasi = null;
        // $skp->angka_kredit_realisasi = null;
        // $skp->perhitungan = null;
        // $skp->nilai_capaian = null;
        $skpheaderid = $skp->skp_tahunan_header_id;

        $request = new Request();
        $request->merge(['kuantitas_realisasi' => $skp->kuantitas_target ]);
        $request->merge(['satuan_kegiatan_id' => $skp->satuan_kegiatan_id ]);
        $request->merge(['kualitas_realisasi' => $skp->kualitas_target ]);
        $request->merge(['angka_kredit_realisasi' => $skp->angka_kredit_target ]);
        $request->merge(['waktu_realisasi' => $skp->waktu_target ]);
        $request->merge(['biaya_realisasi' => $skp->biaya_target ]);
        $request->merge(['skp_tahunan_header_id' => $skp->skp_tahunan_header_id ]);
        $request->merge(['perhitungan' => $skp->perhitungan ]);
        $request->merge(['nilai_capaian' => $skp->nilai_capaian ]);
        $request->merge(['old_id' => $skp->id ]);
        $request->merge(['table_name' => 'skp_tahunan_lines_realisasi' ]);

        ValidationTemp::create($request->only('kegiatan', 'kuantitas_realisasi', 'satuan_kegiatan_id', 'kualitas_realisasi',
        'angka_kredit_realisasi', 'waktu_realisasi', 'biaya_realisasi', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian',
        'old_id', 'table_name'));

        return redirect()->route('realisasi.show', [$skpheaderid])
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
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)->get();
        $satuan = SatuanKegiatan::all();
        $user = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $skpheader->user_id)->first();
        $user_atasan = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $user->atasan_1_id)->first();
        $tugass = TugasTambahan::where('skp_tahunan_header_id', $id)->get();
        $kreativitas = Kreativitas::where('skp_tahunan_header_id', $id)->get();

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
        // $capaian = (($total_nilai <= 50) ? 'Buruk' : ($total_nilai <= 60 && $total_nilai > 50) ? 'Sedang' : ($total_nilai <= 75 && $total_nilai > 60) ? 'Cukup' : ($total_nilai <= 90 && $total_nilai > 75) ? 'Baik' : 'Sangat Baik');

        $satker = \explode(' ', $user->satuan_kerja->satuan_kerja);
        $lokasi = array_pop($satker);
        $pdf = \PDF::loadView('exports.penilaian-capaian-pdf', compact(
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
        // return $pdf->stream();
        return $pdf->download('PENGUKURAN.pdf');
    }

    public function validate_data($id)
    {
        $skpline = SkpTahunanLines::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($skpline->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');

        $validation_temp = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_target')->first();
            
        $users = User::all()->pluck('name', 'id');

        return view('skp.tahunan.realisasi.validation', compact('skpheader', 'skpline', 'user', 'satuankegiatan', 'validation_temp'));
    }

    public function validation(Request $request, $id)
    {
        switch ($request->get('action')) {
            case 'Confirm':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_realisasi')->first();

                $skpheaderid = $skp->skp_tahunan_header_id;
                $skp->kegiatan = $validation->kegiatan;
                $skp->kuantitas_realisasi = $validation->kuantitas_realisasi;
                $skp->satuan_kegiatan_id = $validation->satuan_kegiatan_id;
                $skp->kualitas_realisasi = $validation->kualitas_realisasi;
                $skp->angka_kredit_realisasi = $validation->angka_kredit_realisasi;
                $skp->waktu_realisasi = $validation->waktu_realisasi;
                $skp->biaya_realisasi = $validation->biaya_realisasi;
                $skp->perhitungan = $validation->perhitungan;
                $skp->nilai_capaian = $validation->nilai_capaian;
                $skp->status = '02';
                $skp->save();
                $validation->delete();

                return redirect()->route('realisasi.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima realisasi SKP tahunan');
                break;
            case 'Decline':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_realisasi')->first();

                $skpheaderid = $skp->skp_tahunan_header_id;
                $skp->status = '03';
                $skp->save();
                $validation->delete();
                return redirect()->route('realisasi.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak realisasi SKP tahunan.');
                break;
            case 'Confirm Update':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_realisasi')->first();
                $skpheaderid = $skp->skp_tahunan_header_id;

                $skp->kegiatan = $validation->kegiatan;
                $skp->kuantitas_realisasi = $validation->kuantitas_realisasi;
                $skp->satuan_kegiatan_id = $validation->satuan_kegiatan_id;
                $skp->kualitas_realisasi = $validation->kualitas_realisasi;
                $skp->angka_kredit_realisasi = $validation->angka_kredit_realisasi;
                $skp->waktu_realisasi = $validation->waktu_realisasi;
                $skp->biaya_realisasi = $validation->biaya_realisasi;
                $skp->perhitungan = $validation->perhitungan;
                $skp->nilai_capaian = $validation->nilai_capaian;
                $skp->status = '05';

                $skp->save();
                $validation->delete();
                return redirect()->route('realisasi.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima perubahan realisasi SKP tahunan.');
                break;
            case 'Decline Update':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_realisasi')->first();
                $skpheaderid = $skp->skp_tahunan_header_id;
                $skp->status = '06';

                $skp->save();
                $validation->delete();

                return redirect()->route('realisasi.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak perubahan SKP tahunan.');
                break;
            case 'Confirm Delete':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_realisasi')->first();
                $skpheaderid = $skp->skp_tahunan_header_id;

                $skp->kuantitas_realisasi = null;
                $skp->kualitas_realisasi = null;
                $skp->waktu_realisasi = null;
                $skp->biaya_realisasi = null;
                $skp->angka_kredit_realisasi = null;
                $skp->perhitungan = null;
                $skp->nilai_capaian = null;
                $skp->status = '08';
                $skp->save();
                $validation->delete();

                return redirect()->route('realisasi.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menerima penghapusan SKP tahunan.');
            case 'Decline Delete':
                $skp = SkpTahunanLines::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'skp_tahunan_lines_realisasi')->first();
                $skpheaderid = $skp->skp_tahunan_header_id;

                $skp->status = '09';
                $skp->save();
                $validation->delete();

                return redirect()->route('realisasi.show', [$skpheaderid])
                ->with('flash_message',
                'Berhasil menolak penghapusan SKP tahunan.');
            default:
                $skp = SkpTahunanLines::findOrFail($id);
                $skpheaderid = $skp->skp_tahunan_header_id;
                return redirect()->route('realisasi.show', [$skpheaderid])
                ->with('flash_message',
                'Error undetected action.');
                break;
        }
    }
}
