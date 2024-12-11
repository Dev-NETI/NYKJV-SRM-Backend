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
        Schema::create('order_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('order_document_type_id')->nullable();
            $table->text('slug')->nullable();
            $table->text('file_name')->nullable();
            $table->text('file_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('modified_by')->nullable();
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('order_document_type_id')->references('id')->on('order_document_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_documents');
    }
};
