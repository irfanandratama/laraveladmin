<?php

use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [];
        $status[] = [
            'status' => '01',
            'keterangan' => 'Dalam Pengajuan Pembuatan'
        ];

        $status[] = [
            'status' => '02',
            'keterangan' => 'Pembuatan Diterima / Disetujui'
        ];

        $status[] = [
            'status' => '03',
            'keterangan' => 'Pembuatan Ditolak'
        ];

        $status[] = [
            'status' => '04',
            'keterangan' => 'Dalam Pengajuan Perubahan'
        ];

        $status[] = [
            'status' => '05',
            'keterangan' => 'Perubahan Diterima / Disetujui'
        ];

        $status[] = [
            'status' => '06',
            'keterangan' => 'Perubahan Ditolak'
        ];

        $status[] = [
            'status' => '07',
            'keterangan' => 'Dalam Pengajuan Penghapusan'
        ];

        $status[] = [
            'status' => '08',
            'keterangan' => 'Penghapusan Diterima / Disetujui'
        ];

        $status[] = [
            'status' => '09',
            'keterangan' => 'Penghapusan Ditolakn'
        ];

        foreach ($status as $row) {
            DB::table('status')->insert([
                'status' => $row['status'],
                'keterangan' => $row['keterangan']
            ]);
        }
    }
}
