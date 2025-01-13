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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('roomname', 20);
            $table->string('roompass', 16);
            $table->string('gamepass')->nullable();
            $table->unsignedInteger('number_of_winners',)->length(2)->default(1);
            $table->unsignedInteger('max_win')->length(2)->default(1);
            $table->boolean("is_active")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
