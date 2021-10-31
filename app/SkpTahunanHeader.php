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

}
