<?php
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $users = [[
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'nip' => '0',
            'jabatan' => 'Administrator',
            'atasan_1_id' => 0,
            'atasan_2_id' => 0,
            'atasan_3_id' => 0,
            'pangkat_id' => 1,
            'satuan_kerja_id' => 1,
            'is_atasan' => false,
        ],
        [
            'name' => 'Pegawai 1',
            'email' => 'pegawai@gmail.com',
            'password' => bcrypt('pegawai'),
            'nip' => '0',
            'jabatan' => 'Administrator',
            'atasan_1_id' => 0,
            'atasan_2_id' => 0,
            'atasan_3_id' => 0,
            'pangkat_id' => 1,
            'satuan_kerja_id' => 1,
            'is_atasan' => false,
        ],
        [
            'name' => 'kepegawaian',
            'email' => 'kepegawaian@gmail.com',
            'password' => bcrypt('kepegawaian'),
            'nip' => '0',
            'jabatan' => 'Administrator',
            'atasan_1_id' => 0,
            'atasan_2_id' => 0,
            'atasan_3_id' => 0,
            'pangkat_id' => 1,
            'satuan_kerja_id' => 1,
            'is_atasan' => false,
        ],
        [
            'name' => 'SuperAdmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('superadmin'),
            'nip' => '0',
            'jabatan' => 'Administrator',
            'atasan_1_id' => 0,
            'atasan_2_id' => 0,
            'atasan_3_id' => 0,
            'pangkat_id' => 1,
            'satuan_kerja_id' => 1,
            'is_atasan' => false,
        ]];
        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
        
    }
}
