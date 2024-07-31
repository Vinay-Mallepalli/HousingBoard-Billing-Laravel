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
        Schema::create('housing_bills', function (Blueprint $table) {
            $table->id();

            $table->foreignId('housing_id')->constrained('housing_data')->onDelete('cascade');
            $table->string('year')->nullable();
            $table->decimal('yearly_maintenance', 10, 2)->nullable();
            $table->json('bill_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_bills');
    }
};
