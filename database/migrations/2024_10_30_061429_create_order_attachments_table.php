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
        Schema::create('order_attachments', function (Blueprint $table) {
            $table->id();
            $table->text('slug')->nullable();
            $table->string('reference_number')->nullable();
            $table->text('name')->nullable();
            $table->text('file_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('modified_by')->nullable();
            $table->timestamps();

            $table->foreign('reference_number')
                ->references('reference_number')
                ->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_attachments');
    }
};
