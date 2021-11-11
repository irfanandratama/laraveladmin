<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatuanKegiatan extends Model
{
    protected $table = 'satuan_kegiatan';

    public $timestamps = 'true';

    protected $fillable = [
        'satuan_kegiatan'
    ];

    public function skp_tahunan_lines() {
        return $this->hasMany(SkpTahunanLines::class);
    }

    public function kreativitas() {
        return $this->hasMany(Kreativitas::class);
    }
}
