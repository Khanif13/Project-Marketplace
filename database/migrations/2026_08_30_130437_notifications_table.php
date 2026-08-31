<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // e.g. 'listing_viewed', 'bookmark_added', 'seller_approved'
            $table->string('message');
            $table->string('url')->nullable(); // redirect saat notif diklik
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']); // untuk query notif belum dibaca
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
