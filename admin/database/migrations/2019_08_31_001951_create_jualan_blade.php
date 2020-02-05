<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJualanBlade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fra_jualan_blade', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('occupant_id');
            $table->string('judul', 150);
            $table->string('nama', 150);
            $table->string('harga', 12);
            $table->string('no_hp', 19);
            $table->string('gambar_1')->nullable();
            $table->string('gambar_2')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['occupant_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fra_jualan_blade');
    }
}
