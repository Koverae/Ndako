<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Conversations
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 2) Participants (polymorphic: user OR guest)
        Schema::create('conversation_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();

            // morph columns replace user_id
            $table->string('participant_type');             // 'user' | 'guest' (or model FQCN if you prefer)
            $table->unsignedBigInteger('participant_id');

            $table->enum('role', ['guest','support','intern'])->default('guest');
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();

            // prevent duplicates for same actor in same conversation
            $table->unique(
                ['conversation_id','participant_type','participant_id'],
                'conv_participants_convid_type_id_unique'
            );
            $table->index(['participant_type','participant_id'], 'conv_participants_actor_idx');
        });

        // 3) Messages (polymorphic sender)
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();

            // morph columns replace user_id
            $table->string('sender_type');                  // 'user' | 'guest'
            $table->unsignedBigInteger('sender_id');

            $table->enum('type', ['text','system'])->default('text');
            $table->text('body')->nullable();
            $table->timestamps();

            $table->index(['conversation_id','created_at'], 'messages_convid_created_idx');
            $table->index(['sender_type','sender_id'], 'messages_sender_morph_idx');
        });

        // 4) Attachments
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->cascadeOnDelete();
            $table->string('path');               // storage/app/public/...
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->timestamps();

            $table->index('message_id');
        });
    }

    public function down(): void
    {
        // drop in reverse order of dependencies
        Schema::dropIfExists('message_attachments');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversation_participants');
        Schema::dropIfExists('conversations');
    }
};
