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
        Schema::table('sismadak_documents', function (Blueprint $table) {
            $table->string('sub_point', 10)->nullable()->after('assessment_element_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sismadak_documents', function (Blueprint $table) {
            $table->dropColumn('sub_point');
        });
    }
};
