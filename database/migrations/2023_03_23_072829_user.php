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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id')->autoIncrement();
            $table->string('user_name', 400);
            $table->string('user_username', 100);
            $table->string('user_email', 100);
            $table->string('user_phone', 11);
            $table->mediumText('user_password');
            $table->enum('user_authority', ['admin', 'seller', 'user'])->default('user');
            $table->enum('official_distributor', ['0', '1'])->default('0');
            $table->dateTime('user_date')->useCurrent();
            $table->enum('user_visible', ['0', '1'])->default('1');
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
