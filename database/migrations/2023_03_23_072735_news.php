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
        Schema::create('news', function (Blueprint $table) {
            $table->integer('news_id')->autoIncrement();
            $table->text('news_title');
            $table->text('news_seo');
            $table->text('news_content');
            $table->text('news_photo');
            $table->dateTime('news_date')->useCurrent();
            $table->enum('news_visible', ['0', '1'])->default('1');
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
