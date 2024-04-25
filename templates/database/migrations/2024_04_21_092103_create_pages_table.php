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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->string('name', 32);
            $table->text('contents');
            $table->text('info')->nullable();
            $table->integer('pagetype_scope');
            $table->integer('markup_scope');
            $table->integer('order')->nullable();
            $table->integer('language_scope');
            $table->integer('columns')->default(1);
            $table->foreignId('audio_id')->nullable()->references('id')->on('files');
            $table->foreignId('cacheof_id')->nullable()->references('id')->on('pages');
            $table->foreignId('owner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
