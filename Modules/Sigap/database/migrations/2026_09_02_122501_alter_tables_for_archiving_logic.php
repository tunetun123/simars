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
        Schema::table('document_categories', function (Blueprint $table) {
            $table->string('code')->unique()->nullable()->after('id');
            $table->unique('name');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->after('description');
        });

        Schema::table('documents', function (Blueprint $table) {
            $table->string('reference_code')->unique()->nullable()->after('title');
            $table->date('document_date')->nullable()->after('description');
            $table->string('file_name')->nullable()->after('file_path');
            $table->unsignedBigInteger('file_size')->nullable()->after('file_name');
            $table->string('file_mime')->nullable()->after('file_size');
            
            // Note: Since 'title' is a text column maybe in old db, wait. Let's check 'documents' migration to be sure 'title' is string.
            // If it's a string, we can index it.
            $table->index(['title', 'reference_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['title', 'reference_code']);
            $table->dropColumn(['reference_code', 'document_date', 'file_name', 'file_size', 'file_mime']);
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropUnique(['name']);
            $table->dropColumn(['code', 'created_by']);
        });
    }
};
