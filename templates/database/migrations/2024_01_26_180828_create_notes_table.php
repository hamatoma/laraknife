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
            $table->string('title');
            $table->text('body')->nullable();
            // foreign key of sproperties.
            $table->integer('category_scope');
            // foreign key of sproperties.
            $table->integer('visibility_scope');
            // foreign key of sproperties.
            $table->integer('notestatus_scope');
            $table->foreignId('owner_id')->references('id')->on('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
