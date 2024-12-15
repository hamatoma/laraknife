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
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('firstname', 64)->nullable();
            $table->string('middlename', 64)->nullable();
            $table->string('lastname', 128);
            $table->string('nickname', 128);
            $table->string('titles', 128)->nullable();
            $table->integer('gender_scope');
            $table->integer('persongroup_scope');
            $table->text('info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
