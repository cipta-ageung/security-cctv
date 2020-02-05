<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fra_product', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama', 45)->unique();
            $table->string('lokasi', 250);
            $table->string('brosur_1', 190);
            $table->string('brosur_2', 190)->nullable();
            $table->string('brosur_3', 190)->nullable();
            $table->string('master_plan', 190);
            $table->string('video', 250);
            $table->unsignedInteger('created_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fra_product');
    }
}
