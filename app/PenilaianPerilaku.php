<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenilaianPerilaku extends Model
{
    protected $table = 'penilaian_perilaku';

    public $timestamps = true;

    protected $fillable = [
        'orientasi_pelayanan', 'integritas', 'komitmen', 'disiplin', 'kerjasama', 'kepemimpinan', 'jumlah', 'rata_rata', 'skp_tahunan_header_id',
        'status'
    ];

    public function skp_tahunan_header() {
        return $this->hasOne(SkpTahunanHeader::class);
    }

    public function status() {
        return $this->hasOne(Status::class);
    }
}
