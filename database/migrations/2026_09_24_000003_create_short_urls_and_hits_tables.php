<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code', 12)->unique();
            $table->text('original_url');
            $table->unsignedBigInteger('hits_count')->default(0);
            $table->timestamps();
            $table->index(['client_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
        Schema::create('url_hits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('short_url_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at');
            $table->index(['short_url_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('url_hits');
        Schema::dropIfExists('short_urls');
    }
};
