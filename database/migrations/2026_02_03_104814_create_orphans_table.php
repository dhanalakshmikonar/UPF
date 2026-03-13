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
    Schema::create('orphans', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->enum('gender', ['Male', 'Female', 'Other']);
        $table->date('date_of_birth');
        $table->date('admission_date')->nullable();
        $table->string('photo')->nullable();
        $table->enum('status', ['Active', 'Adopted', 'Transferred'])->default('Active');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orphans');
    }
};
