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
        Schema::table('users', function (Blueprint $table) {
            $table->text('slug')->after('id')->nullable();
            $table->text('suffix')->after('l_name')->nullable();
            $table->unsignedBigInteger('company_id')->after('slug')->nullable();
            $table->unsignedBigInteger('department_id')->after('company_id')->nullable();
            $table->unsignedBigInteger('supplier_id')->after('department_id')->nullable();
            $table->text('contact_number')->after('suffix')->nullable();
            $table->boolean('is_active')->after('password')->default(true);
            $table->text('modified_by')->after('remember_token')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
