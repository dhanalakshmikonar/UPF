<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orphans', function (Blueprint $table) {
            if (!Schema::hasColumn('orphans', 'serial_no')) {
                $table->string('serial_no')->nullable()->after('id');
            }

            if (!Schema::hasColumn('orphans', 'age')) {
                $table->unsignedInteger('age')->nullable()->after('full_name');
            }

            if (!Schema::hasColumn('orphans', 'category')) {
                $table->string('category')->nullable()->after('gender');
            }

            if (!Schema::hasColumn('orphans', 'address')) {
                $table->text('address')->nullable()->after('category');
            }

            if (!Schema::hasColumn('orphans', 'home')) {
                $table->string('home')->nullable()->after('address');
            }

            if (!Schema::hasColumn('orphans', 'aadhaar_number')) {
                $table->string('aadhaar_number')->nullable()->after('home');
            }

            if (!Schema::hasColumn('orphans', 'contact_number')) {
                $table->text('contact_number')->nullable()->after('aadhaar_number');
            }

            if (!Schema::hasColumn('orphans', 'remarks')) {
                $table->text('remarks')->nullable()->after('contact_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orphans', function (Blueprint $table) {
            $columns = [
                'serial_no',
                'age',
                'category',
                'address',
                'home',
                'aadhaar_number',
                'contact_number',
                'remarks',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('orphans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
