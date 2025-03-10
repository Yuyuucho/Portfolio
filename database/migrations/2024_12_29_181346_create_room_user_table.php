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
        Schema::create('room_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->cascadeOnDelete();
            $table->foreignId("room_id")->constrained()->cascadeOnDelete();
            $table->boolean("is_owner")->default(0);
            $table->boolean("is_winner")->default(0);
            $table->unsignedInteger('win_count')->default(0);
            $table->boolean("is_active")->default(0);
            $table->string("status", 6)->nullable();
            $table->timestamps();
            $table->timestamp("enter_timing")->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_user');
    }
};
