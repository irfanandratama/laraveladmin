<?php

use Illuminate\Database\Seeder;

class SatuanKerjaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $satker = [];
        $satker[] = [
            'satuan_kerja' => 'Pengadilan Tinggi Mataram',
            'alamat' => 'Jl. Majapahit No.58 Mataram – NTB'
        ];

        $satker[] = [
            'satuan_kerja' => 'Pengadilan Agama Karangasem',
            'alamat' => 'Jalan RA Kartini, Karangasem, Kec. Karangasem, Kabupaten Karangasem, Bali 80811'
        ];

        foreach ($satker as $row) {
            DB::table('satuan_kerja')->insert([
                'satuan_kerja' => $row['satuan_kerja'],
                'alamat' => $row['alamat']
            ]);
        }
    }
}
