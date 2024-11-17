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
            $table->text('options')->nullable();
            // foreign key of sproperties.
            $table->integer('category_scope');
            // foreign key of sproperties.
            $table->integer('visibility_scope');
            // foreign key of sproperties.
            $table->integer('notestatus_scope');
            $table->foreignId('owner_id')->nullable()->references('id')->on('users');
            $table->foreignId('group_id')->nullable()->references('id')->on('groups');
            $table->foreignId('module_id')->nullable()->references('id')->on('modules');
            $table->unsignedBigInteger('reference_id')->nullable();
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
