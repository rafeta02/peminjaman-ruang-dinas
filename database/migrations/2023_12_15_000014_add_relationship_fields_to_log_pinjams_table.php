<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLogPinjamsTable extends Migration
{
    public function up()
    {
        Schema::table('log_pinjams', function (Blueprint $table) {
            $table->unsignedBigInteger('peminjaman_id')->nullable();
            $table->foreign('peminjaman_id', 'peminjaman_fk_9306012')->references('id')->on('pinjams');
            $table->unsignedBigInteger('ruang_id')->nullable();
            $table->foreign('ruang_id', 'ruang_fk_9306013')->references('id')->on('ruangs');
            $table->unsignedBigInteger('peminjam_id')->nullable();
            $table->foreign('peminjam_id', 'peminjam_fk_9306014')->references('id')->on('users');
        });
    }
}
