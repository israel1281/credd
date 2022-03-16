<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFwBillPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fw_bill_payments', function (Blueprint $table) {
            $table->id();
            $table->string('bp_type');
            $table->string('name');
            $table->string('biller_code');
            $table->string('biller_name');
            $table->float('commission');
            $table->string('country');
            $table->string('item_code');
            $table->string('short_name')->nullable();
            $table->integer('amount');
            $table->integer('fee');
            $table->boolean('commission_on_fee');
            $table->string('label_name');
            $table->string('image');
            $table->foreignId('status_id')->constrained()->restrictOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fw_bill_payments');
    }
}
