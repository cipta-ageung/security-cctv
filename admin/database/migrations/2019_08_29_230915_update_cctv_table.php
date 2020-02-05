<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCctvTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fra_cctv', function (Blueprint $table) {
            $table->unsignedInteger('product_id')->after('id');
            $table->unsignedInteger('created_by')->after('link_stream');
            
            $table->index(['product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fra_cctv', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('created_by');
        });
    }
}
