<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kreativitas extends Model
{
    protected $table = 'kreativitas';

    public $timestamps = true;

    protected $fillable = [
        'tanggal_kreativitas', 'kegiatan_kreativitas', 'kuantitas', 'satuan_kegiatan_id', 'skp_tahunan_header_id'
    ];

    public function skp_tahunan_header() {
        return $this->hasOne(SkpTahunanHeader::class);
    }
}
