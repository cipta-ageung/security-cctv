<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateOccupantForSecurityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fra_occupant', function (Blueprint $table) {
            $table->unsignedInteger('security_id')->nullable()->after('product_id');
            $table->renameColumn('poto', 'avatar');
            
            $table->index(['security_id']);
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
            $table->unsignedInteger('security_id');
        });
    }
}
