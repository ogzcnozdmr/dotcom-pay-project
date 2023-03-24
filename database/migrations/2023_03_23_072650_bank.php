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
        Schema::create('bank', function (Blueprint $table) {
            $table->integer('bank_id')->autoIncrement();
            $table->string('bank_name', 100);
            $table->string('bank_variable', 30);
            $table->text('api_url');
            $table->text('bank_json');
            $table->integer('max_installment')->default(12);
            $table->integer('min_installment_amount');
            $table->integer('plus_installment')->default(0);
            $table->integer('virtual_pos_type')->default(1);
            $table->string('bank_image');
            $table->enum('bank_visible', ['0', '1'])->default('1');
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
