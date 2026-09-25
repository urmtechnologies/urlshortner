<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        // Existing Laravel project may already have these tables.
        if (! Schema::hasTable('clients')) {
            Schema::create('clients', function (Blueprint $table) {
                $table->id();
                $table->string('name', 150);
                $table->timestamps();
            });
        }
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('invited_by_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('name', 150);
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('role', 20);
                $table->rememberToken();
                $table->timestamps();
                $table->index(['client_id', 'role']);
            });
        }
    }
    // Existing tables may predate this migration; never drop them on rollback.
    public function down(): void {}
};
