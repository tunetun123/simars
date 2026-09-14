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
        Schema::rename('sismadak_document_groups', 'siparsi_document_groups');
        Schema::rename('sismadak_document_categories', 'siparsi_document_categories');
        Schema::rename('sismadak_assessment_elements', 'siparsi_assessment_elements');
        Schema::rename('sismadak_documents', 'siparsi_documents');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('siparsi_documents', 'sismadak_documents');
        Schema::rename('siparsi_assessment_elements', 'sismadak_assessment_elements');
        Schema::rename('siparsi_document_categories', 'sismadak_document_categories');
        Schema::rename('siparsi_document_groups', 'sismadak_document_groups');
    }
};
