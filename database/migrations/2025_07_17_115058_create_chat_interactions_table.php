<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_interactions', function (Blueprint $table) {
            $table->id();
            $table->text('question'); // Assuming 'question' is the field for user input
            $table->text('answer'); // Assuming 'answer' is the field for bot response
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade'); // Assuming 'user_id' is the foreign key to the users table
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_interactions');
    }
};
