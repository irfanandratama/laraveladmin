<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatuanKerja extends Model
{
    protected $table = 'satuan_kerja';

    public $timestamps = 'true';

    protected $fillable = [
        'satuan_kerja', 'alamat'
    ];

    public function user() {
        return $this->hasMany(User::class);
    }
}
