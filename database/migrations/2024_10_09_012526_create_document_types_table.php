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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->text('slug')->nullable();
            $table->unsignedBigInteger('document_type_category_id')->nullable();
            $table->text('name')->nullable();
            $table->text('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('modified_by')->nullable();
            $table->timestamps();

            $table->foreign('document_type_category_id')->references('id')->on('document_type_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
