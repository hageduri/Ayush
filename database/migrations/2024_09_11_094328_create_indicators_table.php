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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('nin')->nullable();
            $table->string('district_code')->nullable();
            $table->integer('indicator_1')->nullable();
            $table->integer('indicator_2')->nullable();
            $table->integer('indicator_3')->nullable();
            $table->integer('indicator_4')->nullable();
            $table->integer('indicator_5')->nullable();
            $table->integer('indicator_6')->nullable();
            $table->integer('indicator_7')->nullable();
            $table->integer('indicator_8')->nullable();
            $table->integer('indicator_9')->nullable();
            $table->integer('indicator_10')->nullable();
            $table->string('month')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
