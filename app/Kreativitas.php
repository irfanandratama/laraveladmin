<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kreativitas extends Model
{
    protected $table = 'kreativitas';

    public $timestamps = true;

    protected $fillable = [
        'tanggal_kreativitas', 'kegiatan_kreativitas', 'kuantitas', 'satuan_kegiatan_id', 'skp_tahunan_header_id', 'status'
    ];

    public function skp_tahunan_header() {
        return $this->hasOne(SkpTahunanHeader::class);
    }

    public function status() {
        return $this->hasOne(Status::class);
    }

    public function satuan_kegiatan()
    {
        return $this->hasOne(SatuanKegiatan::class);
    }
}
