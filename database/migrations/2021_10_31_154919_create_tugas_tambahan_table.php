<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTugasTambahanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tugas_tambahan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tahun');
            $table->text('nama_tugas');
            $table->text('no_sk');
            $table->unsignedBigInteger('skp_tahunan_header_id');
            $table->foreign('skp_tahunan_header_id')->references('id')->on('skp_tahunan_header')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('tugas_tambahan');
    }
}
