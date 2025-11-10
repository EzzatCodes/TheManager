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
        Schema::table('users',function(Blueprint $table) {
            $table->enum('role', ["manager","employee"])->default('employee');
            $table->enum('status', ["free","busy"])->default('free');
            $table->enum('activation', ["offline","online"])->default('offline');
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
