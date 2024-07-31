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
        Schema::create('housing_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('housing_bills_id');
            $table->string('mobile_number');
            $table->string('resident_name');
            $table->string('flat_number');
            $table->integer('amount_paid');
            $table->integer('payment_mode');
            $table->boolean('receipt_status')->default(false);
            $table->timestamp('receipt_sent_at')->nullable();
            $table->timestamps();

            $table->foreign('housing_bills_id')->references('id')->on('housing_bills')->onDelete('cascade');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_reports');
    }
};
