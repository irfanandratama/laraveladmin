<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\SkpTahunanLines;
use App\SkpTahunanHeader;
use App\SatuanKegiatan;
use App\User;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Carbon\Carbon;

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
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)->get();
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

        $user = SkpTahunanLines::create($request->only('kegiatan', 'kuantitas_target', 'satuan_kegiatan_id', 'kualitas_target',
        'angka_kredit_target', 'waktu_target', 'biaya_target', 'skp_tahunan_header_id'));
  
        return redirect()->route('target.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Kegiatan SKP Tahunan successfully added.');
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

        $input = $request->only('kegiatan', 'kuantitas_target', 'satuan_kegiatan_id', 'kualitas_target',
        'angka_kredit_target', 'waktu_target', 'biaya_target', 'skp_tahunan_header_id', 'perhitungan', 'nilai_capaian');

        
        $skp->fill($input)->save();

        return redirect()->route('target.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Kegiatan SKP Tahunan successfully updated.');
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
        $skpline->delete();

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
}
