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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->text('info')->nullable;
            $table->decimal('amount');
            $table->integer('transactiontype_scope');
            $table->integer('transactionstate_scope');
            $table->date('date')->nullable;
            $table->foreignId('account_id')->nullable()->references('id')->on('accounts');
            $table->foreignId('twin_id')->nullable()->references('id')->on('transactions');
            $table->foreignId('owner_id')->nullable()->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
