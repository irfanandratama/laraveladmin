<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 20)->default('0');
            $table->string('jabatan')->default('');
            $table->integer('atasan_1_id')->nullable();
            $table->integer('atasan_2_id')->nullable();
            $table->integer('atasan_3_id')->nullable();
            $table->unsignedBigInteger('pangkat_id')->default(0);
            $table->unsignedBigInteger('satuan_kerja_id')->default(0);
            $table->foreign('satuan_kerja_id')->references('id')->on('satuan_kerja');
            $table->foreign('pangkat_id')->references('id')->on('pangkat');
            $table->boolean('is_atasan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('satuan_kerja_id');
            $table->dropForeign('pangkat_id');
            $table->dropColumn('nip');
            $table->dropColumn('jabatan');
            $table->dropColumn('atasan_1_id');
            $table->dropColumn('atasan_2_id');
            $table->dropColumn('atasan_3_id');
            $table->dropColumn('pangkat_id');
            $table->dropColumn('satuan_kerja_id');
            $table->dropColumn('is_atasan');
        });
    }
}
