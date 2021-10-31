<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\SkpTahunanHeader;
use App\User;

use Illuminate\Support\Facades\Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

class SkpTahunanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->hasRole(['Super-Admin', 'Administrator'])) {
            $skps = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
            ->get(['skp_tahunan_header.*', 'users.name']);
            $users = User::all()->pluck('name', 'id'); 
            return view('skp.tahunan.index', compact('skps', 'users'));
        } else {
            $skps = SkpTahunanHeader::join('users', 'users.id', '=', 'skp_tahunan_header.user_id')
                ->where('skp_tahunan_header.user_id', Auth::id())->get(['skp_tahunan_header.*', 'users.name']);
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

        $skptahunanheader = SkpTahunanHeader::create($request->only('periode_mulai', 'periode_selesai', 'user_id'));

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'Periode SKP berhasil ditambahkan.');
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
        $input = $request->only('periode_mulai', 'periode_selesai', 'user_id');
        $skp->fill($input)->save();

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'SKP Tahunan successfully edited.');

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
        $skp->delete();

        return redirect()->route('tahunan.index')
            ->with('flash_message',
             'Periode SKP berhasil dihapuskan.');
    }

    public function messages()
    {
        return [
            'periode_mulai.required'=> 'Mohon isi kolom Periode Mulai',
            'periode_selesai.required'=> 'Mohon isi kolom Periode Selesai'            
        ];
    }
}
