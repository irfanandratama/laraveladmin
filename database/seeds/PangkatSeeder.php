<?php

use Illuminate\Database\Seeder;

class PangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pangkat = [];
        $pangkat[] = [
            'pangkat' => 'Juru Muda',
            'golongan' => 'I',
            'ruang' => 'A'
        ];
        $pangkat[] = [
            'pangkat' => 'Juru Muda Tingkat I',
            'golongan' => 'I',
            'ruang' => 'B'
        ];

        $pangkat[] = [
            'pangkat' => 'Juru',
            'golongan' => 'I',
            'ruang' => 'C'
        ];
        $pangkat[] = [
            'pangkat' => 'Juru Tingkat I',
            'golongan' => 'I',
            'ruang' => 'D'
        ];
        $pangkat[] = [
            'pangkat' => 'Juru Muda',
            'golongan' => 'I',
            'ruang' => 'A'
        ];
        $pangkat[] = [
            'pangkat' => 'Pengatur Muda',
            'golongan' => 'II',
            'ruang' => 'A'
        ];
        $pangkat[] = [
            'pangkat' => 'Pengatur Muda Tingkat I',
            'golongan' => 'II',
            'ruang' => 'B'
        ];
        $pangkat[] = [
            'pangkat' => 'Pengatur',
            'golongan' => 'II',
            'ruang' => 'C'
        ];
        $pangkat[] = [
            'pangkat' => 'Pengatur Tingkat I',
            'golongan' => 'II',
            'ruang' => 'D'
        ];
        $pangkat[] = [
            'pangkat' => 'Penata Muda',
            'golongan' => 'III',
            'ruang' => 'A'
        ];
        $pangkat[] = [
            'pangkat' => 'Penata Muda Tingkat I',
            'golongan' => 'III',
            'ruang' => 'B'
        ];
        $pangkat[] = [
            'pangkat' => 'Penata',
            'golongan' => 'III',
            'ruang' => 'C'
        ];
        $pangkat[] = [
            'pangkat' => 'Penata Tingkat I',
            'golongan' => 'III',
            'ruang' => 'D'
        ];
        $pangkat[] = [
            'pangkat' => 'Juru Muda',
            'golongan' => 'I',
            'ruang' => 'A'
        ];
        $pangkat[] = [
            'pangkat' => 'Pembina',
            'golongan' => 'IV',
            'ruang' => 'A'
        ];
        $pangkat[] = [
            'pangkat' => 'Pembina Tingkat I',
            'golongan' => 'IV',
            'ruang' => 'B'
        ];
        $pangkat[] = [
            'pangkat' => 'Pembina Utama Muda',
            'golongan' => 'IV',
            'ruang' => 'C'
        ];
        $pangkat[] = [
            'pangkat' => 'Pembina Utama Madya',
            'golongan' => 'IV',
            'ruang' => 'D'
        ];
        $pangkat[] = [
            'pangkat' => 'Pembina Utama',
            'golongan' => 'IV',
            'ruang' => 'E'
        ];

        foreach ($pangkat as $row) {
            DB::table('pangkat')->insert([
                'pangkat' => $row['pangkat'],
                'ruang' => $row['ruang'],
                'golongan' => $row['golongan']
            ]);
        }
    }
}
