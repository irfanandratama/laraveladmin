<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\SatuanKerja;

use Session;

class SatuanKerjaController extends Controller
{
    public function __construct() {
        $this->middleware(['auth', 'isAdmin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $satkers = SatuanKerja::all();//Get all roles

        return view('satuan-kerja.index')->with('satkers', $satkers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('satuan-kerja.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $this->validate($request, [
                'satuan_kerja'=>'required|unique:satuan_kerja',
                'alamat' =>'required',
            ],
            [
                'satuan_kerja.required' => 'Mohon isi kolom Satuan Kerja',
                'satuan_kerja.unique' => 'Satuan Kerja dengan nama yang sama sudah ada',
                'alamat.required' => 'Mohon isi kolom Alamat'
            ]
        );

        $satuan_kerja = $request['satuan_kerja'];
        $alamat = $request['alamat'];
        $satker = SatuanKerja::create(['satuan_kerja'=> $satuan_kerja, 'alamat'=> $alamat]);

        return redirect()->route('satker.index')
            ->with('flash_message',
             'Satuan Kerja'. $satker->satuan_kerja.' added!'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('satker');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $satker = SatuanKerja::findOrFail($id);

        return view('satuan-kerja.edit', compact('satker'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $satker = SatuanKerja::findOrFail($id);

        $this->validate($request, [
                'satuan_kerja'=>'required|unique:satuan_kerja',
                'alamat' =>'required',
            ],
            [
                'satuan_kerja.required' => 'Mohon isi kolom Satuan Kerja',
                'satuan_kerja.unique' => 'Satuan Kerja dengan nama yang sama sudah ada',
                'alamat.required' => 'Mohon isi kolom Alamat'
            ]
        );

        $input = $request->only('satuan_kerja', 'alamat');
        $satker->fill($input)->save();

        return redirect()->route('satker.index')
            ->with('flash_message',
             'Satuan Kerja'. $satker->satuan_kerja.' updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $satker = SatuanKerja::findOrFail($id);
        $satker->delete();

        return redirect()->route('satker.index')
            ->with('flash_message',
             'Satuan Kerja deleted!');

    }
}
