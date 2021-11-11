<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TugasTambahan extends Model
{
    protected $table = 'tugas_tambahan';

    public $timestamps = true;

    protected $fillable = [
        'tahun', 'nama_tugas', 'no_sk', 'skp_tahunan_header_id', 'status'
    ];

    public function skp_tahunan_header() {
        return $this->hasOne(SkpTahunanHeader::class);
    }

    public function status() {
        return $this->hasOne(Status::class);
    }
}
