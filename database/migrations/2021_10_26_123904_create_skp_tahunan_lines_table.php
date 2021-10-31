<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkpTahunanLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skp_tahunan_lines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('skp_tahunan_header_id');
            $table->foreign('skp_tahunan_header_id')->references('id')->on('skp_tahunan_header')->onUpdate('cascade')->onDelete('cascade');
            $table->text('kegiatan');
            $table->integer('kuantitas_target');
            $table->unsignedBigInteger('satuan_kegiatan_id');
            $table->foreign('satuan_kegiatan_id')->references('id')->on('satuan_kegiatan');
            $table->integer('kualitas_target');
            $table->integer('waktu_target');
            $table->integer('biaya_target');
            $table->integer('angka_kredit_target');
            $table->integer('kuantitas_realisasi')->nullable();
            $table->integer('kualitas_realisasi')->nullable();
            $table->integer('waktu_realisasi')->nullable();
            $table->integer('biaya_realisasi')->nullable();
            $table->integer('angka_kredit_realisasi')->nullable();
            $table->integer('perhitungan')->nullable();
            $table->decimal('nilai_capaian')->nullable();
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
        Schema::dropIfExists('skp_tahunan_lines');
    }
}
