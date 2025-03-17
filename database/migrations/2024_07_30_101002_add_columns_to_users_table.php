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
        Schema::table('users', function (Blueprint $table) {

            $table->string('nin', 50)->unique()->nullable();
            $table->string('role', 50)->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('image')->nullable();
            $table->date('dob')->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('account_no', 20)->nullable();
            $table->string('ifsc_code', 11)->nullable();
            $table->text('address')->nullable();
            $table->string('district_code', 26)->nullable();
            $table->string('contact_1', 15)->nullable();
            $table->string('contact_2', 15)->nullable();
            $table->string('status', 20)->nullable();
            $table->string('designation',255)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
