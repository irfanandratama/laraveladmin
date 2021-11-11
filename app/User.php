<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'nip', 'jabatan', 
        'atasan_1_id', 'atasan_2_id', 'atasan_3_id','pangkat_id', 
        'satuan_kerja_id', 'is_atasan'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {   
      $this->attributes['password'] = bcrypt($password);
    }

    public function pangkat() {
        return $this->belongsTo(Pangkat::class);
    }

    public function satuan_kerja() {
        return $this->belongsTo(SatuanKerja::class);
    }

    public function skp_tahunan_header() {
        return $this->hasMany(SkpTahunanHeader::class);
    }
}
