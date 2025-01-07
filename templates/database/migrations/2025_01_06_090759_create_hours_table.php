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
        Schema::create('hours', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('time');
            /// in minutes:
            $table->integer('duration');
            $table->integer('hourtype_scope');
            $table->integer('hourstate_scope');
            $table->text('description');
            $table->foreignId('owner_id')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hours');
    }
};
