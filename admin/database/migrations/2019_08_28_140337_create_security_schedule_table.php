<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecurityScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fra_security_schedule', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('security_id');
            $table->string('shift')->comment('siang, malam');
            $table->date('date');
            $table->unsignedInteger('created_by');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['security_id', 'shift', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fra_security_schedule');
    }
}
