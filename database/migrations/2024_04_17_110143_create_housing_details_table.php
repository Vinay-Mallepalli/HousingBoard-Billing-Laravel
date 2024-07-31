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
        Schema::create('housing_details', function (Blueprint $table) {
            $table->id();
            $table->string('mobile_number');
            $table->string('name');
            $table->string('unit');
            $table->string('utility');
            $table->string('additional_utility')->nullable();
            $table->string('total_utility');
            $table->string('select');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_details');
    }
};
