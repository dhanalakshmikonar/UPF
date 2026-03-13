<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orphans', function (Blueprint $table) {

            // only add aadhaar_document
            if (!Schema::hasColumn('orphans', 'aadhaar_document')) {
                $table->string('aadhaar_document')->nullable()->after('photo');
            }

        });
    }

    public function down()
    {
        Schema::table('orphans', function (Blueprint $table) {

            if (Schema::hasColumn('orphans', 'aadhaar_document')) {
                $table->dropColumn('aadhaar_document');
            }

        });
    }
};

