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
        Schema::create('notifications', function (Blueprint $table) {
            $table->integer('notifications_id')->autoIncrement();
            $table->text('notifications_title');
            $table->text('notifications_content');
            $table->text('notifications_icon');
            $table->enum('notifications_result', ['0', '1'])->default('0');
            $table->dateTime('notifications_date')->useCurrent();
            $table->enum('notifications_read', ['0', '1'])->default('0');
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
