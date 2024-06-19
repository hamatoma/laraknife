<?php

class Migration_test{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->text('body');
            // foreign key of sproperties.
            $table->integer('category_scope');
            // foreign key of sproperties.
            $table->integer('notestatus_scope');
            $table->foreignId('user_id')->nullable()->references('id')->on('users');
        });
    }
}