<?php

namespace App\Http\Controllers;

use App\SkpTahunanHeader;
use App\SkpTahunanLines;
use App\PenilaianPerilaku;
use App\SatuanKegiatan;
use App\TugasTambahan;
use App\Kreativitas;
use App\User;
use App\Status;
use App\ValidationTemp;


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
            ->whereIn('status', ['01', '02', '04', '05', '06', '07', '09'])
            ->get(['skp_tahunan_header.*','users.name']);
            $users = User::all()->pluck('name', 'id'); 

            $skps->map(function ($skp) {
                $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $skp->id)->first();
                if ($penilaian) {
                    $status = Status::where('status', $penilaian->status)->first();
                    if ($status) {
                        $skp['keterangan'] = $status->keterangan;
                    } 
                } else {
                    $skp['keterangan'] = 'Belum ada nilai Penilaian Perilaku';
                }
            });


            return view('skp.penilaian-perilaku.index', compact('skps', 'users'));
        } else {
            $skps = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
                ->where('skp_tahunan_header.user_id', Auth::id())
                ->whereIn('status', ['01', '02', '04', '05', '06', '07', '09'])
                ->get(['skp_tahunan_header.*','users.name']);

                $skps->map(function ($skp) {
                    $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $skp->id)->first();
                    if ($penilaian) {
                        $status = Status::where('status', $penilaian->status)->first();
                        if ($status) {
                            $skp['keterangan'] = $status->keterangan;
                        } 
                    } else {
                        $skp['keterangan'] = 'Belum ada nilai Penilaian Perilaku';
                    }
                });

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
        $request->merge(['status' => '01' ]);

        $penilaian = PenilaianPerilaku::create($request->only('orientasi_pelayanan', 'integritas', 'komitmen', 
            'disiplin', 'kerjasama', 'kepemimpinan', 'jumlah', 'rata_rata', 'skp_tahunan_header_id', 'status'));
        
        return redirect()->route('penilaian.index')
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
        $penilaian = PenilaianPerilaku::findOrFail($id);

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
    $request->merge(['old_id' => $penilaian->id ]);
    $request->merge(['table_name' => 'penilaian_perilaku' ]);

    ValidationTemp::create($request->only('orientasi_pelayanan', 'integritas', 'komitmen', 
        'disiplin', 'kerjasama', 'kepemimpinan', 'jumlah', 'rata_rata', 'old_id', 'table_name'));


    $penilaian->status = '04';
    $penilaian->save();
    
    return redirect()->route('penilaian.index')
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
        $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $id)->where('status', 
        '!=', '03')->first();
        $tugass = TugasTambahan::where('skp_tahunan_header_id', $id)->where('status', 
        '!=', '03')->get();
        $kreativitas = Kreativitas::where('skp_tahunan_header_id', $id)->where('status', 
        '!=', '03')->get();

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

    public function validate_data($id)
    {
        $skpheader = SkpTahunanHeader::find($id);
        $penilaian = PenilaianPerilaku::where('skp_tahunan_header_id', $id)->first();
        $user = User::find($skpheader->user_id);
        $users = User::all()->pluck('name', 'id');
        $validation_temp = ValidationTemp::where('old_id', $penilaian->id)->where('table_name', 'penilaian_perilaku')->first();

        return view('skp.penilaian-perilaku.validation', compact('skpheader', 'penilaian', 'id', 'user', 'users', 'validation_temp'));
    }

    public function validation(Request $request, $id)
    {
        switch ($request->get('action')) {
            case 'Confirm':
                $penilaian = PenilaianPerilaku::findOrFail($id);
                $skpheaderid = $penilaian->skp_tahunan_header_id;
                $penilaian->status = '02';
                $penilaian->save();
                return redirect()->route('penilaian.index')
                ->with('flash_message',
                'Berhasil menerima tugas tambahan SKP tahunan');
                break;
            case 'Decline':
                $penilaian = PenilaianPerilaku::findOrFail($id);
                $skpheaderid = $penilaian->skp_tahunan_header_id;
                $penilaian->status = '03';
                $penilaian->save();
                return redirect()->route('penilaian.index')
                ->with('flash_message',
                'Berhasil menolak target SKP tahunan.');
                break;
            case 'Confirm Update':
                $penilaian = PenilaianPerilaku::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'penilaian_perilaku')->first();
                $skpheaderid = $penilaian->skp_tahunan_header_id;
                $penilaian->orientasi_pelayanan = $validation->orientasi_pelayanan;
                $penilaian->integritas = $validation->integritas;
                $penilaian->disiplin = $validation->disiplin;
                $penilaian->kerjasama = $validation->kerjasama;
                $penilaian->kepemimpinan = $validation->kepemimpinan;
                $penilaian->disiplin = $validation->disiplin;
                $penilaian->jumlah = $validation->jumlah;
                $penilaian->rata_rata = $penilaian->rata_rata;
                $penilaian->status = '05';

                $penilaian->save();
                $validation->delete();
                return redirect()->route('penilaian.index')
                ->with('flash_message',
                'Berhasil menerima perubahan SKP tahunan.');
                break;
            case 'Decline Update':
                $penilaian = PenilaianPerilaku::findOrFail($id);
                $validation = ValidationTemp::where('old_id', $id)->where('table_name', 'penilaian_perilaku')->first();
                $skpheaderid = $penilaian->skp_tahunan_header_id;
                $penilaian->status = '06';

                $penilaian->save();
                $validation->delete();

                return redirect()->route('penilaian.index')
                ->with('flash_message',
                'Berhasil menolak perubahan SKP tahunan.');
                break;
            default:
                $penilaian = Kreativitas::findOrFail($id);
                $skpheaderid = $penilaian->skp_tahunan_header_id;
                return redirect()->route('kreativitas.show', [$skpheaderid])
                ->with('flash_message',
                'Error undetected action.');
                break;
        }
    }
}
