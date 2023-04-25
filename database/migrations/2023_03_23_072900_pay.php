<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pay', function (Blueprint $table) {
            $table->integer('pay_id')->autoIncrement();
            $table->integer('user_id');
            $table->string('seller_name', 300);
            $table->string('order_number', 100);
            $table->string('order_ip', 30);
            $table->float('order_total');
            $table->integer('order_installment');
            $table->string('pay_bank', 100);
            $table->longText('pay_json');
            $table->dateTime('pay_date')->useCurrent();
            $table->dateTime('pay_success_date')->useCurrent();
            $table->enum('pay_result', ['process', 'error', 'success']);
            $table->text('pay_message');
            $table->string('pay_card_owner', 100);
            $table->string('pay_ip', 15);
            $table->string('user_phone', 11);
            $table->string('user_email', 120);
            $table->enum('pay_visible', ['0', '1'])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
