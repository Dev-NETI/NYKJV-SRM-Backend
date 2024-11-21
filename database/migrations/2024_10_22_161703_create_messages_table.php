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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chats_id')->constrained('chats')->onDelete('cascade'); 
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade'); 
            $table->text('content'); 
            $table->boolean('unread')->default(false); 
            $table->string('attachment_file_name')->nullable(); 
            $table->string('attachment_type')->nullable(); 
            $table->string('attachment_size')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
