<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeviceIdOccupantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fra_occupant', function (Blueprint $table) {
            $table->string('device_id',60)->nullable()->after('email');

            $table->index(['device_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fra_occupant', function (Blueprint $table) {
            $table->string('device_id');
        });
    }
}
