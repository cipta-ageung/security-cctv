<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppsLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fra_apps_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('occupant_id');
            $table->string('logs_name');
            $table->timestamps();

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
        Schema::dropIfExists('fra_apps_logs');
    }
}
