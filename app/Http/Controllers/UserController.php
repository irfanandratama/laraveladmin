<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Pangkat;
use App\SatuanKerja;
use Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//Enables us to output flash messaging
use Session;

class UserController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index() {
    //Get all users and pass it to the view
        $users = User::all(); 
        return view('users.index')->with('users', $users);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
    //Get all roles and pass it to the view
        $roles = Role::get();
        $atasans = User::where('is_atasan', true)->pluck('name', 'id');
        $pangkats = Pangkat::get()->pluck('pangkat_golongan', 'id');
        $satkers = SatuanKerja::get()->pluck('satuan_kerja', 'id');
        return view('users.create', [
            'roles'=>$roles,
            'atasans'=>$atasans,
            'pangkats'=>$pangkats,
            'satkers'=>$satkers
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {
    //Validate name, email and password fields
        $this->validate($request, [
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users',
                'password'=>'required|min:6|confirmed',
                'nip'=>'required|min:18|max:20',
                'jabatan'=>'required',
                // 'atasan_1_id'=>'required',
                'pangkat_id'=>'required',
                'satuan_kerja_id'=>'required'
            ],
            $this->messages()
        );

        if ($request->has('is_atasan')) {
            $request->merge(['is_atasan'=>true]);
        } else {
            $request->merge(['is_atasan'=>false]);
        }

        $user = User::create($request->only('email', 'name', 'password', 'nip', 'jabatan', 
        'atasan_1_id', 'atasan_2_id', 'atasan_3_id', 'pangkat_id', 'satuan_kerja_id', 'is_atasan')); //Retrieving only the email and password data

        $roles = $request['roles']; //Retrieving the roles field
        //Checking if a role was selected
        if (isset($roles)) {

            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();            
                $user->assignRole($role_r); //Assigning role to user
            }
        }        
        //Redirect to the users.index view and display message
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully added.');
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id) {
        return redirect('users'); 
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id) {
        $user = User::findOrFail($id); //Get user with specified id
        $roles = Role::get(); //Get all roles
        $atasans = User::where('is_atasan', true)->pluck('name', 'id');
        $pangkats = Pangkat::get()->pluck('pangkat_golongan', 'id');
        $satkers = SatuanKerja::get()->pluck('satuan_kerja', 'id');
        return view('users.edit', compact('user', 'roles', 'atasans', 'pangkats', 'satkers')); //pass user and roles data to view
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id) {
        $user = User::findOrFail($id); //Get role specified by id

    //Validate name, email and password fields    
        $this->validate($request, [
                'name'=>'required|max:120',
                'email'=>'required|email|unique:users,email,'.$id,
                'password'=>'nullable|min:6|confirmed',
                'nip'=>'required|min:18|max:20',
                'jabatan'=>'required',
                // 'atasan_1_id'=>'required',
                'pangkat_id'=>'required',
                'satuan_kerja_id'=>'required'
            ],
            $this->messages()
        );
        
        if ($request->has('is_atasan')) {
            $request->merge(['is_atasan'=>true]);
        } else {
            $request->merge(['is_atasan'=>false]);
        }

        
        if(!is_null($request['password'])) {
            $input = $request->only(['name', 'email', 'password', 'nip', 'jabatan', 
                'atasan_1_id', 'atasan_2_id', 'atasan_3_id', 'pangkat_id', 'satuan_kerja_id', 'is_atasan']); //Retrieving only the email and password data
        } else {
            $input = $request->except(['password', 'password_confirmation', 'roles']);
        }

        $roles = $request['roles']; //Retreive all roles
        $user->fill($input)->save();

        if (isset($roles)) {        
            $user->roles()->sync($roles);  //If one or more role is selected associate user to roles          
        }        
        else {
            $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
        }
        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully edited.');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id) {
    //Find a user with a given id and delete
        $user = User::findOrFail($id); 
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    }

    public function messages()
    {
        return [
            'name.required'=> 'Mohon isi kolom nama',
            'email.required'=> 'Mohon isi kolom email',
            'password.required'=> 'Mohon isi kolom password',
            'password.confirmed'=> 'Konfirmasi password salah',
            'nip.required'=> 'Mohon isi kolom nip',
            'nip.required'=> 'Mohon isi kolom nip',
            'jabatan.required'=> 'Mohon isi kolom jabatan',
            'atasan_1_id.required'=> 'Mohon isi kolom atasan langsung',
            'pangkat_id.required'=> 'Mohon isi kolom pangkat',
            'satuan_kerja_id.required'=> 'Mohon isi kolom satuan kerja'
        ];
    }
}