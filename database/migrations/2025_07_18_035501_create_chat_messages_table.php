<?php

// Ganti nama model dari ChatInteraction menjadi ChatMessage sesuai kode Anda
// php artisan make:migration create_chat_messages_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            // Tambahkan foreign key ke conversations
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->text('answer');
            $table->timestamps();
        });
    }
    // ...
};
