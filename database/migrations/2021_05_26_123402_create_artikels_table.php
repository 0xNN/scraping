<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtikelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('rating')->nullable();
            $table->string('durasi')->nullable();
            $table->text('alamat_film')->nullable();
            $table->string('diterbitkan')->nullable();
            $table->string('sutradara')->nullable();
            $table->text('image_link')->nullable();
            $table->string('negara')->nullable();
            $table->text('sinopsis')->nullable();
            $table->string('kualitas')->nullable();
            $table->string('aktor')->nullable();
            $table->string('link_download')->nullable();
            $table->string('link_id');
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
        Schema::dropIfExists('artikels');
    }
}
