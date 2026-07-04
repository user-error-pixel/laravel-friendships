<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('friendships.tables.fr_groups_pivot', 'friend_friendship_groups'), function (Blueprint $table) {
            $table->unsignedBigInteger('friendship_id');
            $table->morphs('friend');
            $table->unsignedInteger('group_id');
            $table->foreign('friendship_id')
                ->references('id')
                ->on(config('friendships.tables.fr_pivot', 'friendships'))
                ->cascadeOnDelete();
            $table->unique(
                ['friendship_id', 'friend_id', 'friend_type', 'group_id'],
                'friendship_group_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('friendships.tables.fr_groups_pivot', 'friend_friendship_groups'));
    }
};
