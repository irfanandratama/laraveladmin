<?php

namespace App\Http\Controllers;

use App\SkpTahunanHeader;
use App\Kreativitas;
use App\SatuanKegiatan;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Carbon\Carbon;

class KreativitasController extends Controller
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
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');

        return view('skp.tahunan.kreativitas.create', compact('skpheader', 'tahun', 'user', 'satuankegiatan'));

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
            'tanggal_kreativitas'=>'required',
            'kegiatan_kreativitas'=>'required',
            'kuantitas'=>'required',
            'satuan_kegiatan_id' => 'required'
        ],
        $this->messages()
    );

    $user = Kreativitas::create($request->only('tanggal_kreativitas', 'kegiatan_kreativitas', 'kuantitas', 'satuan_kegiatan_id', 'skp_tahunan_header_id'));

    return redirect()->route('kreativitas.show', [$request->skp_tahunan_header_id])
        ->with('flash_message',
         'Kreativitas successfully added.');
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
        $kreatifs = Kreativitas::where('skp_tahunan_header_id', $id)->paginate(10);
        $user = User::find($skpheader->user_id);
        $satuan = SatuanKegiatan::all();
        $users = User::all()->pluck('name', 'id');
        
        return view('skp.tahunan.kreativitas.index', compact('skpheader', 'kreatifs', 'id', 'user', 'satuan', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kreatif = Kreativitas::findOrFail($id);
        $skpheader = SkpTahunanHeader::find($kreatif->skp_tahunan_header_id);
        $user = User::find($skpheader->user_id);
        $tahun = Carbon::createFromFormat('Y-m-d', $skpheader->periode_selesai)->format('Y');
        $satuankegiatan = SatuanKegiatan::get()->pluck('satuan_kegiatan', 'id');

        return view('skp.tahunan.kreativitas.edit', compact('skpheader', 'kreatif', 'id', 'user', 'tahun', 'satuankegiatan'));

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
        $tugas = Kreativitas::findOrFail($id);

        $this->validate($request, [
                'tanggal_kreativitas'=>'required',
                'kegiatan_kreativitas'=>'required',
                'kuantitas'=>'required',
                'satuan_kegiatan_id' => 'required'
            ],
            $this->messages()
        );

        $input = $request->except('tahun');
        $tugas->fill($input)->save();

        return redirect()->route('kreativitas.show', [$request->skp_tahunan_header_id])
            ->with('flash_message',
            'Kreativitas successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kreatif = Kreativitas::findOrFail($id); 
        $skpheaderid = $kreatif->skp_tahunan_header_id;
        $kreatif->delete();

        return redirect()->route('kreativitas.show', [$skpheaderid])
            ->with('flash_message',
             'Tugas Tambahan successfully deleted.');
    }

    public function messages()
    {
        return [
            'tanggal_kreativitas.required'=> 'Mohon isi kolom Tanggal',
            'kegiatan_kreativitas.required'=> 'Mohon isi kolom Kegiatan Kreativitas',
            'kuantitas.required'=> 'Mohon mengisi kolom Kuantitas', 
            'satuan_kegiatan_id.required'=> 'Mohon pilih satuan kuantitas yang sesuai',    
        ];
    }
}
