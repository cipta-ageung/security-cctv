<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentInstallmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fra_payment_installment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('occupant_id');
            $table->date('month')->nullable();
            $table->string('nominal')->nullable();
            $table->unsignedTinyInteger('read_flag')->default(0);
            $table->unsignedInteger('created_by');
            $table->timestamps();

            $table->index(['occupant_id', 'month', 'read_flag']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_installment');
    }
}
