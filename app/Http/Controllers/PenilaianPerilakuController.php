<?php

namespace App\Http\Controllers;

use App\SkpTahunanHeader;
use App\SkpTahunanLines;
use App\PenilaianPerilaku;
use App\SatuanKegiatan;
use App\TugasTambahan;
use App\Kreativitas;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Carbon\Carbon;

class PenilaianPerilakuController extends Controller
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
            ->get(['skp_tahunan_header.*', 'users.name']);
            $users = User::all()->pluck('name', 'id'); 
            return view('skp.penilaian-perilaku.index', compact('skps', 'users'));
        } else {
            $skps = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
                ->where('skp_tahunan_header.user_id', Auth::id())->get(['skp_tahunan_header.*', 'users.name']);
            return view('skp.penilaian-perilaku.index')->with('skps', $skps);
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
                'orientasi_pelayanan'=>'required',
                'integritas'=>'required',
                'komitmen'=>'required',
                'disiplin'=>'required',
                'kerjasama'=>'required',
                'kepemimpinan'=>'required',
                // 'jumlah'=>'required',
                // 'rata-rata'=>'required'

            ],
            $this->messages()
        );

        $jumlah = $request->orientasi_pelayanan + $request->integritas + $request->komitmen + $request->disiplin 
            + $request->kerjasama + $request->kepemimpinan;
        $rerata = $jumlah / 6;

        $request->merge(['jumlah' => $jumlah ]);
        $request->merge(['rata_rata' => $rerata ]);

        $penilaian = PenilaianPerilaku::create($request->only('orientasi_pelayanan', 'integritas', 'komitmen', 
            'disiplin', 'kerjasama', 'kepemimpinan', 'jumlah', 'rata_rata', 'skp_tahunan_header_id'));
        
        return redirect()->route('penilaian.show', [$request->skp_tahunan_header_id])
        ->with('flash_message',
            'Penilaian Perilaku successfully added.');
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
        $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $id)->first();
        $user = User::find($skpheader->user_id);
        $users = User::all()->pluck('name', 'id');

        return view('skp.penilaian-perilaku.detail', compact('skpheader', 'penilaian', 'id', 'user', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $this->validate($request, [
            'orientasi_pelayanan'=>'required',
            'integritas'=>'required',
            'komitmen'=>'required',
            'disiplin'=>'required',
            'kerjasama'=>'required',
            'kepemimpinan'=>'required',
            // 'jumlah'=>'required',
            // 'rata-rata'=>'required'

        ],
        $this->messages()
    );

    $penilaian = PenilaianPerilaku::create($request->only('orientasi_pelayanan', 'integritas', 'komitmen', 
        'disiplin', 'kerjasama', 'kepemimpinan', 'jumlah', 'rata-rata'));
    
    return redirect()->route('penilaian.show', [$request->skp_tahunan_header_id])
    ->with('flash_message',
        'Penilaian Perilaku successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function messages()
    {
        return [
            'orientasi_pelayanan.required'=> 'Mohon isi kolom Orientasi Pelayanan',
            'integritas.required'=> 'Mohon isi kolom Integritas',
            'komitmen.required'=> 'Mohon mengisi kolom Komitmen',
            'disiplin.required'=> 'Mohon isi kolom Disiplin',
            'kerjasama.required'=> 'Mohon isi kolom Kerjasama',
            'kepemimpinan.required'=> 'Mohon mengisi kolom Kepemimpinan', 
            'jumlah.required'=> 'Mohon isi kolom Jumlah',
            'rata-rata.required'=> 'Mohon isi kolom Rata-rata',
        ];
    }

    public function export($id)
    {
        $skpheader = SkpTahunanHeader::find($id);
        $skplines = SkpTahunanLines::where('skp_tahunan_header_id', $id)->get();
        $satuan = SatuanKegiatan::all();
        $user = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $skpheader->user_id)->first();
        $user_atasan = User::query()->with(['pangkat', 'satuan_kerja'])->where('id', $user->atasan_1_id)->first();
        $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $id)->first();
        $tugass = TugasTambahan::where('skp_tahunan_header_id', $id)->get();
        $kreativitas = Kreativitas::where('skp_tahunan_header_id', $id)->get();

        $nilai_capaian = $skplines->sum('nilai_capaian');
        $count_lines = count($skplines);
        $count_tugas = count($tugass);
        $count_kreativitas = count($kreativitas);
        $total_nilai = $nilai_capaian / $count_lines + $count_tugas + $count_kreativitas;

        $pdf = \PDF::loadView('exports.penilaian-perilaku-pdf', compact(
            'skpheader',
            'skplines',
            'satuan',
            'user',
            'user_atasan',
            'total_nilai',
            'penilaian'
        ));
        // return $pdf->stream();
        return $pdf->download('PERILAKU KERJA.pdf');
    }
}
