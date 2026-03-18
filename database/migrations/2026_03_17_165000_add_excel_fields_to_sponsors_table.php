<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            if (!Schema::hasColumn('sponsors', 'serial_no')) {
                $table->string('serial_no')->nullable()->after('id');
            }

            if (!Schema::hasColumn('sponsors', 'age')) {
                $table->unsignedInteger('age')->nullable()->after('name');
            }

            if (!Schema::hasColumn('sponsors', 'gender')) {
                $table->string('gender')->nullable()->after('age');
            }

            if (!Schema::hasColumn('sponsors', 'category')) {
                $table->string('category')->nullable()->after('gender');
            }

            if (!Schema::hasColumn('sponsors', 'home')) {
                $table->string('home')->nullable()->after('address');
            }

            if (!Schema::hasColumn('sponsors', 'aadhaar_number')) {
                $table->string('aadhaar_number')->nullable()->after('home');
            }

            if (!Schema::hasColumn('sponsors', 'contact_number')) {
                $table->text('contact_number')->nullable()->after('aadhaar_number');
            }

            if (!Schema::hasColumn('sponsors', 'remarks')) {
                $table->text('remarks')->nullable()->after('contact_number');
            }

            if (!Schema::hasColumn('sponsors', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable()->after('remarks');
            }

            if (!Schema::hasColumn('sponsors', 'date_of_joining')) {
                $table->date('date_of_joining')->nullable()->after('date_of_birth');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sponsors', function (Blueprint $table) {
            $columns = [
                'serial_no',
                'age',
                'gender',
                'category',
                'home',
                'aadhaar_number',
                'contact_number',
                'remarks',
                'date_of_birth',
                'date_of_joining',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('sponsors', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
