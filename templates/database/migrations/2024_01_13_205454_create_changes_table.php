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
        Schema::create('changes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('changetype_scope');
            $table->foreignId('module_id')->nullable()->references('id')->on('modules');
            $table->integer('reference_id')->nullable();
            $table->string('description')->nullable();
            $table->text('current')->nullable();
            $table->string('link')->nullable();
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('changes');
    }
};
