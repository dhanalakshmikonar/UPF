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
    Schema::create('volunteers', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->enum('gender', ['Male', 'Female', 'Other']);
        $table->date('date_of_birth');
        $table->date('joining_date')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->string('photo')->nullable();
        $table->string('aadhaar_document')->nullable();
        $table->string('status')->default('Active');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteers');
    }
};
