<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUniqueIndexSecuritySchedulTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fra_security_schedule', function (Blueprint $table) {
            $table->unique(['security_id','shift','date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fra_security_schedule', function (Blueprint $table) {
            $table->string('device_id');
        });
    }
}
