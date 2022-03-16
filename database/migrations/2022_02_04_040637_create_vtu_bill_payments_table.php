<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVtuBillPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vtu_bill_payments', function (Blueprint $table) {
            $table->id();
            $table->string('bp_type');
            $table->string('name');
            $table->string('short_name')->nullable();
            $table->string('service_id');
            $table->string('variation_id')->nullable();
            $table->integer('amount');
            $table->integer('fee');
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
        Schema::dropIfExists('vtu_bill_payments');
    }
}
