<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            if (!Schema::hasColumn('sponsors', 'cug_number')) {
                $table->text('cug_number')->nullable()->after('contact_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            if (Schema::hasColumn('sponsors', 'cug_number')) {
                $table->dropColumn('cug_number');
            }
        });
    }
};
