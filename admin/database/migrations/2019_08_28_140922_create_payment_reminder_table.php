<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentReminderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fra_payment_reminder', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category', 7)->comment('ipl, cicilan');
            $table->unsignedInteger('occupant_id');
            $table->date('month')->nullable();
            $table->string('nominal')->nullable();
            $table->unsignedTinyInteger('read_flag')->default(0);
            $table->unsignedInteger('created_by');
            $table->timestamps();

            $table->index(['category','occupant_id', 'month', 'read_flag']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fra_payment_reminder');
    }
}
