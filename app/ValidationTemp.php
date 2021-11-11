<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidationTemp extends Model
{
    protected $table = 'validation_temp';

    public $timestamps = true;

    protected $fillable = [
        'periode_mulai', 'periode_selesai', 'user_id',
        'skp_tahunan_header_id', 
        'kegiatan', 
        'kuantitas_target',
        'satuan_kegiatan_id',
        'kualitas_target',
        'waktu_target',
        'biaya_target',
        'angka_kredit_target',
        'kuantitas_realisasi',
        'kualitas_realisasi',
        'waktu_realisasi',
        'biaya_realisasi',
        'angka_kredit_realisasi',
        'perhitungan',
        'nilai_capaian',
        'tahun', 'nama_tugas', 'no_sk',
        'tanggal_kreativitas', 'kegiatan_kreativitas', 'kuantitas',
        'orientasi_pelayanan', 'integritas', 'komitmen', 'disiplin',
        'kerjasama', 'kepemimpinan', 'jumlah', 'rata_rata',
        'old_id',
        'table_name'
    ];
}
