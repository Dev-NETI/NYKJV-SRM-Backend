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
        Schema::create('supplier_users', function (Blueprint $table) {
            $table->id();
            $table->text('slug')->nullable();
            $table->text('company')->nullable();
            $table->text('contact_person');
            $table->text('contact_number')->nullable();
            $table->text('email_address')->nullable();
            $table->text('address')->nullable();
            $table->text('products')->nullable();
            $table->text('modified_by')->default('system');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_users');
    }
};
