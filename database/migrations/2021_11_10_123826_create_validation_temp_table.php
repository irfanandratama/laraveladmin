<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidationTempTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('validation_temp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->integer('skp_tahunan_header_id')->nullable();
            $table->text('kegiatan')->nullable();
            $table->integer('kuantitas_target')->nullable();
            $table->integer('satuan_kegiatan_id')->nullable();
            $table->integer('kualitas_target')->nullable();
            $table->integer('waktu_target')->nullable();
            $table->integer('biaya_target')->nullable();
            $table->integer('angka_kredit_target')->nullable();
            $table->integer('kuantitas_realisasi')->nullable();
            $table->integer('kualitas_realisasi')->nullable();
            $table->integer('waktu_realisasi')->nullable();
            $table->integer('biaya_realisasi')->nullable();
            $table->integer('angka_kredit_realisasi')->nullable();
            $table->integer('perhitungan')->nullable();
            $table->decimal('nilai_capaian')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('tahun')->nullable();
            $table->text('nama_tugas')->nullable();
            $table->text('no_sk')->nullable();
            $table->date('tanggal_kreativitas')->nullable();
            $table->text('kegiatan_kreativitas')->nullable();
            $table->integer('kuantitas')->nullable();
            $table->decimal('orientasi_pelayanan')->nullable();
            $table->decimal('integritas')->nullable();
            $table->decimal('komitmen')->nullable();
            $table->decimal('disiplin')->nullable();
            $table->decimal('kerjasama')->nullable();
            $table->decimal('kepemimpinan')->nullable();
            $table->decimal('jumlah')->nullable();
            $table->decimal('rata_rata')->nullable();
            $table->integer('old_id')->nullable();
            $table->string('table_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('validation_temp');
    }
}
