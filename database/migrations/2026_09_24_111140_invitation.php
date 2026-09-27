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
        if (! Schema::hasTable('invitations')) {
            Schema::create('invitations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
                $table->string('client_name', 150)->nullable();
                $table->string('email')->unique();
                $table->string('role', 20)->default('admin');
                $table->string('token_hash', 64)->unique();
                $table->foreignId('invited_by_id')->constrained('users')->cascadeOnDelete();
                $table->timestamp('expires_at');
                $table->timestamp('accepted_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
