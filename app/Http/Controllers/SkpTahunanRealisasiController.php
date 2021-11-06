<?php

namespace App\Http\Controllers;

use App\SkpTahunanHeader;
use App\SkpTahunanLines;
use App\SatuanKegiatan;
use App\User;

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

        $input = $request->only('kegiatan', 'kuantitas_realisasi', 'satuan_kegiatan_id', 'kualitas_realisasi',
        'angka_kredit_realisasi', 'waktu_realisasi', 'biaya_realisasi', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian');
        $skp->fill($input)->save();

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
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)->paginate(10);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $user = User::find($skpheader->user_id);
        $users = User::all()->pluck('name', 'id');
        
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

        $input = $request->only('kegiatan', 'kuantitas_realisasi', 'satuan_kegiatan_id', 'kualitas_realisasi',
        'angka_kredit_realisasi', 'waktu_realisasi', 'biaya_realisasi', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian');
        $skp->fill($input)->save();

        return redirect()->route('realisasi.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Kegiatan SKP Tahunan successfully updated.' );
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

        $skp->kuantitas_realisasi = null;
        $skp->kualitas_realisasi = null;
        $skp->waktu_realisasi = null;
        $skp->biaya_realisasi = null;
        $skp->angka_kredit_realisasi = null;
        $skp->perhitungan = null;
        $skp->nilai_capaian = null;
        $skpheaderid = $skp->skp_tahunan_header_id;

        $skp->save();

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
}
