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
        Schema::create('housing_bill_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('housing_bill_id');
            $table->string('mobile_number');
            $table->string('status');
            $table->timestamps();

            $table->foreign('housing_bill_id')->references('id')->on('housing_bills');
    

         
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('housing_bill_status');
    }
};
