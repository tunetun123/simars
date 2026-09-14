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
        Schema::create('siparsi_document_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('siparsi_document_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_group_id')->constrained('siparsi_document_groups')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('siparsi_assessment_elements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_category_id')->constrained('siparsi_document_categories')->cascadeOnDelete();
            $table->string('name'); // Nama EP (misal: "EP 1")
            $table->timestamps();
        });

        Schema::create('siparsi_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_element_id')->constrained('siparsi_assessment_elements')->cascadeOnDelete();
            $table->string('title');
            $table->string('file_name')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('sub_point', 10)->nullable();
            $table->integer('point')->default(0);
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siparsi_documents');
        Schema::dropIfExists('siparsi_assessment_elements');
        Schema::dropIfExists('siparsi_document_categories');
        Schema::dropIfExists('siparsi_document_groups');
    }
};
