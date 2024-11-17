<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('filename');
            $table->integer('filegroup_scope');
            $table->integer('visibility_scope');
            $table->float('size');
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
            $table->foreignId('module_id')->nullable()->references('id')->on('modules');
            $table->unsignedBigInteger('reference_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
