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
        Schema::table('users', function(Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained("roles")->cascadeOnUpdate()->nullOnDelete();
            $table->string('localization', 8)->default('en_GB');
            $table->string('autologin', 129)->nullable();
            $table->timestamp('endautologin')->nullable();
            $table->string('options')->nullable();
            $table->string('rights')->nullable();
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
