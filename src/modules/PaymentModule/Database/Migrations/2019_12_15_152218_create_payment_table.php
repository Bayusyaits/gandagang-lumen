<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment', function (Blueprint $table) {
            $table->integer('paymentId');
            $table->integer('paymentCategoryId')
            ->comment('cash, credit card, debit card, pay later, e wallet');
            $table->decimal('paymentAmount', 8, 2);
            $table->enum('paymentState', ['delivery', 'pending', 'failed', 'cancel'])
            ->nullable()->comment('update progress');
            $table->timestampTz('paymentCreatedDate')->useCurrent();
            $table->timestampTz('paymentUpdatedDate')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletesTz('paymentDeletedDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment');
    }
}
