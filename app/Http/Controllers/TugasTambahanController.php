<?php

namespace App\Http\Controllers;

use App\SkpTahunanHeader;
use App\TugasTambahan;
use App\SatuanKegiatan;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Carbon\Carbon;

class TugasTambahanController extends Controller
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
        $skpheader = SkpTahunanHeader::find($id);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $user = User::find($skpheader->user_id);

        return view('skp.tahunan.tugas.create', compact('skpheader', 'tahun', 'user'));
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
                'tahun'=>'required',
                'nama_tugas'=>'required',
                'no_sk'=>'required'
            ],
            $this->messages()
        );

        $user = TugasTambahan::create($request->only('tahun', 'nama_tugas', 'no_sk', 'skp_tahunan_header_id'));

        return redirect()->route('tugas.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
             'Tugas Tambahan successfully added.');

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
        $tugass = TugasTambahan::where('skp_tahunan_header_id', $id)->paginate(10);
        $user = User::find($skpheader->user_id);
        
        return view('skp.tahunan.tugas.index', compact('skpheader', 'tugass', 'id', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tugas = TugasTambahan::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($tugas->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');

        return view('skp.tahunan.tugas.edit', compact('skpheader', 'tugas', 'id', 'user', 'tahun'));
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
        $tugas = TugasTambahan::findOrFail($id);

        $this->validate($request, [
                'tahun'=>'required',
                'nama_tugas'=>'required',
                'no_sk'=>'required'
            ],
            $this->messages()
        );

        $input = $request->except('tahun');
        $tugas->fill($input)->save();

        return redirect()->route('tugas.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Tugas Tambahan successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tugas = TugasTambahan::findOrFail($id); 
        $skpheaderid = $tugas->skp_tahunan_header_id;
        $tugas->delete();

        return redirect()->route('tugas.show', [$skpheaderid])
            ->with('flash_message',
             'Tugas Tambahan successfully deleted.');
    }

    public function messages()
    {
        return [
            'tahun.required'=> 'Mohon isi kolom Tahun',
            'nama_tugas.required'=> 'Mohon isi kolom Nama Tugas',
            'no_sk.required'=> 'Mohon mengisi kolom Nomor Surat Kuasa'      
        ];
    }
}
