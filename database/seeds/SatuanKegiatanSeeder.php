<?php

use Illuminate\Database\Seeder;

use App\SatuanKegiatan;

class SatuanKegiatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $satkeg = [];
        $satkeg[] = [
            'satuan_kegiatan' => 'Dokumen'
        ];

        $satkeg[] = [
            'satuan_kegiatan' => 'Berkas'
        ];

        $satkeg[] = [
            'satuan_kegiatan' => 'Kegiatan'
        ];

        foreach ($satkeg as $row) {
            DB::table('satuan_kegiatan')->insert([
                'satuan_kegiatan' => $row['satuan_kegiatan'],
            ]);
        }
    }
}
