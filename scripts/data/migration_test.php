<?php

class Migration_test{
    public function up(): void
    {
       Schema::create('nouns', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name', 64);
            $table->string('plural', 64);
            // foreign key of sproperties: but sproperties.id is integer, not biginteger
            $table->integer('genus');
            $table->text('usage');
            // foreign key of users
            $table->foreignId('verifiedby')->references('id')->on('users')->nullable();
        });
    }
}