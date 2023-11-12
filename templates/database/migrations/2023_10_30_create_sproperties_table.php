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
        Schema::create('sproperties', function (Blueprint $table) {
            $table->integer('id')->primary()->unique();
            $table->timestamps();
            $table->string('scope');
            $table->string('name');
            $table->integer('order');
            $table->string('shortname');
            $table->string('value')->nullable();
            $table->string('info')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sproperties');
    }
};
