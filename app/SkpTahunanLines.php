<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkpTahunanLines extends Model
{
    protected $table = 'skp_tahunan_lines';

    public $timestamps = true;

    protected $fillable = [
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
        'nilai_capaian'
    ];

    public function skp_tahunan_header() {
        return $this->hasOne(SkpTahunanHeader::class);
    }
    
    public function satuan_kegiatan() {
        return $this->hasOne(SatuanKegiatan::class);
    }
}
