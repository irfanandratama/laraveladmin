<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenilaianPerilakuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penilaian_perilaku', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('orientasi_pelayanan');
            $table->decimal('integritas');
            $table->decimal('komitmen');
            $table->decimal('disiplin');
            $table->decimal('kerjasama');
            $table->decimal('kepemimpinan');
            $table->decimal('jumlah');
            $table->decimal('rata_rata');
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
        Schema::dropIfExists('penilaian_perilaku');
    }
}
