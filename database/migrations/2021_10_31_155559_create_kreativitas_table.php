<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKreativitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kreativitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('tanggal_kreativitas');
            $table->text('kegiatan_kreativitas');
            $table->integer('kuantitas');
            $table->unsignedBigInteger('satuan_kegiatan_id');
            $table->foreign('satuan_kegiatan_id')->references('id')->on('satuan_kegiatan')->onUpdate('cascade');
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
        Schema::dropIfExists('kreativitas');
    }
}
