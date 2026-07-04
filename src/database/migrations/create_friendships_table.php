<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create(config('friendships.tables.fr_pivot', 'friendships'), function (Blueprint $table) {
            $table->id();
            $table->morphs('sender');
            $table->morphs('recipient');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->index(['sender_id', 'sender_type', 'status'], 'friendships_sender_status_index');
            $table->index(['recipient_id', 'recipient_type', 'status'], 'friendships_recipient_status_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('friendships.tables.fr_pivot', 'friendships'));
    }
};
