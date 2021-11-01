<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SkpTahunanHeader extends Model
{
    protected $table = 'skp_tahunan_header';

    public $timestamps = true;

    protected $fillable = [
        'periode_mulai', 'periode_selesai', 'user_id'
    ];

    public function skp_tahunan_lines() {
        return $this->hasMany(SkpTahunanLines::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function tugas_tambahan() {
        return $this->hasMany(TugasTambahan::class);
    }

    public function kreativitas() {
        return $this->hasMany(Kreativitas::class);
    }

}
