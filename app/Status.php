<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';

    public $timestamps = true;

    protected $fillable = [
        'status', 'keterangan'
    ];

    public function skp_tahunan_header()
    {
        return $this->hasMany(SkpTahunanHeader::class);
    }

    public function skp_tahunan_lines()
    {
        return $this->hasMany(SkpTahunanLines::class);
    }

    public function tugas_tambahan()
    {
        return $this->hasMany(TugasTambahan::class);
    }

    public function kreativitas()
    {
        return $this->hasMany(Kreativitas::class);
    }

    public function penilaian()
    {
        return $this->hasMany(PenilaianPerilaku::class);
    }

}
