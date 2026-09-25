<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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

            return;
        }
        $needsClientId = ! Schema::hasColumn('invitations', 'client_id');
        $needsRole = ! Schema::hasColumn('invitations', 'role');
        if ($needsClientId || $needsRole) {
            Schema::table('invitations', function (Blueprint $table) use ($needsClientId, $needsRole) {
                if ($needsClientId) {
                    $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
                }
                if ($needsRole) {
                    $table->string('role', 20)->default('admin');
                }
            });
        }
    }

    public function down(): void {}
};
