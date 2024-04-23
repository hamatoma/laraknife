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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 64);
            $table->longText('body');
            // foreign key of sproperties: but sproperties.id is integer, not biginteger
            $table->integer('category_scope');
            $table->integer('status_scope');
            $table->integer('age');
            $table->boolean('active');
            $table->timestamp('timestamp');
            $table->date('date');
            // foreign key of users
            $table->foreignId('owner_id')->nullable()->default(null)->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('noun');
    }
};

