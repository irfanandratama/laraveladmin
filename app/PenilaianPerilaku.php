<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenilaianPerilaku extends Model
{
    protected $table = 'penilaian_perilaku';

    public $timestamps = true;

    protected $fillable = [
        'orientasi_pelayanan', 'integritas', 'komitmen', 'disiplin', 'kerjasama', 'kepemimpinan', 'jumlah', 'rata_rata', 'skp_tahunan_header_id',
        'status'
    ];

    public function getCapaianOrientasiPelayananAttribute() {
        return (
            ($this->orientasi_pelayanan <= 50) ? "Buruk" :
             (($this->orientasi_pelayanan <= 60 && $this->orientasi_pelayanan > 50) ? "Sedang" :
              (($this->orientasi_pelayanan <= 75 && $this->orientasi_pelayanan > 60) ? "Cukup" :
               (($this->orientasi_pelayanan <= 90 && $this->orientasi_pelayanan > 75) ? "Baik" : "Sangat Baik")))
            );
    }

    public function getCapaianIntegritasAttribute() {
        return (
            ($this->integritas <= 50) ? "Buruk" :
             (($this->integritas <= 60 && $this->integritas > 50) ? "Sedang" :
              (($this->integritas <= 75 && $this->integritas > 60) ? "Cukup" :
               (($this->integritas <= 90 && $this->integritas > 75) ? "Baik" : "Sangat Baik")))
            );
    }

    public function getCapaianKomitmenAttribute() {
        return (
            ($this->komitmen <= 50) ? "Buruk" :
             (($this->komitmen <= 60 && $this->komitmen > 50) ? "Sedang" :
              (($this->komitmen <= 75 && $this->komitmen > 60) ? "Cukup" :
               (($this->komitmen <= 90 && $this->komitmen > 75) ? "Baik" : "Sangat Baik")))
            );
    }

    public function getCapaianDisiplinAttribute() {
        return (
            ($this->disiplin <= 50) ? "Buruk" :
             (($this->disiplin <= 60 && $this->disiplin > 50) ? "Sedang" :
              (($this->disiplin <= 75 && $this->disiplin > 60) ? "Cukup" :
               (($this->disiplin <= 90 && $this->disiplin > 75) ? "Baik" : "Sangat Baik")))
            );
    }

    public function getCapaianKerjasamaAttribute() {
        return (
            ($this->kerjasama <= 50) ? "Buruk" :
             (($this->kerjasama <= 60 && $this->kerjasama > 50) ? "Sedang" :
              (($this->kerjasama <= 75 && $this->kerjasama > 60) ? "Cukup" :
               (($this->kerjasama <= 90 && $this->kerjasama > 75) ? "Baik" : "Sangat Baik")))
            );
    }

    public function getCapaianKepemimpinanAttribute() {
        return (
            ($this->kepemimpinan <= 50) ? "Buruk" :
             (($this->kepemimpinan <= 60 && $this->kepemimpinan > 50) ? "Sedang" :
              (($this->kepemimpinan <= 75 && $this->kepemimpinan > 60) ? "Cukup" :
               (($this->kepemimpinan <= 90 && $this->kepemimpinan > 75) ? "Baik" : "Sangat Baik")))
            );
    }

    public function getCapaianRerataAttribute() {
        return (
            ($this->rata_rata <= 50) ? "Buruk" :
             (($this->rata_rata <= 60 && $this->rata_rata > 50) ? "Sedang" :
              (($this->rata_rata <= 75 && $this->rata_rata > 60) ? "Cukup" :
               (($this->rata_rata <= 90 && $this->rata_rata > 75) ? "Baik" : "Sangat Baik")))
            );
    }

    public function skp_tahunan_header() {
        return $this->hasOne(SkpTahunanHeader::class);
    }

    public function status() {
        return $this->hasOne(Status::class);
    }
}
