<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\User;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'tambah user']);
        Permission::create(['name' => 'hapus user']);
        Permission::create(['name' => 'ubah user']);
        Permission::create(['name' => 'lihat user']);
        Permission::create(['name' => 'tambah role']);
        Permission::create(['name' => 'hapus role']);
        Permission::create(['name' => 'ubah role']);
        Permission::create(['name' => 'lihat role']);
        Permission::create(['name' => 'tambah permission']);
        Permission::create(['name' => 'hapus permission']);
        Permission::create(['name' => 'ubah permission']);
        Permission::create(['name' => 'lihat permission']);
        Permission::create(['name' => 'tambah skp']);
        Permission::create(['name' => 'ubah skp']);
        Permission::create(['name' => 'hapus skp']);
        Permission::create(['name' => 'lihat skp']);
        Permission::create(['name' => 'validasi skp']);

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

        $role2 = Role::create(['name' => 'Pegawai']);
        $role2->givePermissionTo('tambah skp');
        $role2->givePermissionTo('ubah skp');
        $role2->givePermissionTo('hapus skp');
        $role2->givePermissionTo('lihat skp');

        $role3 = Role::create(['name' => 'Kepegawaian']);
        $role3->givePermissionTo('tambah user');
        $role3->givePermissionTo('hapus user');
        $role3->givePermissionTo('ubah user');
        $role3->givePermissionTo('lihat user');
        $role3->givePermissionTo('tambah skp');
        $role3->givePermissionTo('ubah skp');
        $role3->givePermissionTo('hapus skp');
        $role3->givePermissionTo('validasi skp');
        $role3->givePermissionTo('lihat skp');

        $role4 = Role::create(['name' => 'Super-Admin']);
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
