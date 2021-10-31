<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    protected $table = 'pangkat';

    public $timestamps = true;

    protected $fillable = [
        'pangkat', 'ruang', 'golongan'
    ];

    public function getPangkatGolonganAttribute() {
        return $this->pangkat . ' ' . $this->golongan .'/'. $this->ruang;
    }

    public function user() {
        return $this->hasMany(User::class);
    }
}
