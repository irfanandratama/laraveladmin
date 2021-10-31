<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Pangkat;
use App\SatuanKerja;
use Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;

//Enables us to output flash messaging
use Session;

class ProfileController extends Controller
{
    public function edit($id)
    {
        $user = User::findOrFail($id); //Get user with specified id
        $roles = Role::get(); //Get all roles
        $atasans = User::where('is_atasan', true)->pluck('name', 'id');
        $pangkats = Pangkat::get()->pluck('pangkat_golongan', 'id');
        $satkers = SatuanKerja::get()->pluck('satuan_kerja', 'id');
        return view('users.profile', compact('user', 'roles', 'atasans', 'pangkats', 'satkers')); //pass user and roles data to view

    }
}
