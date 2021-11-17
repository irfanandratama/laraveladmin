<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\User;

class PermissionsAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Administrator']);
        $role1->givePermissionTo('tambah user');
        $role1->givePermissionTo('hapus user');
        $role1->givePermissionTo('ubah user');
        $role1->givePermissionTo('lihat user');
        $role1->givePermissionTo('tambah role');
        $role1->givePermissionTo('hapus role');
        $role1->givePermissionTo('ubah role');
        $role1->givePermissionTo('lihat role');
        $role1->givePermissionTo('tambah permission');
        $role1->givePermissionTo('hapus permission');
        $role1->givePermissionTo('ubah permission');
        $role1->givePermissionTo('lihat permission');

        // gets all permissions via Gate::before rule; see AuthServiceProvider

        $admin = User::find(1);
        $admin->assignRole($role1);
        // $pegawai = User::find(2);
        // $pegawai->assignRole($role2);
        // $kepegawaian = User::find(3);
        // $kepegawaian->assignRole($role3);
        // $superadmin = User::find(4);
        // $superadmin->assignRole($role4);


    }
}
