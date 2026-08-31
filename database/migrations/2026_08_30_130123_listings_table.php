<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->decimal('price', 15, 0); // rupiah, tanpa desimal
            $table->boolean('is_negotiable')->default(false);
            $table->unsignedInteger('stock')->nullable(); // null = tidak ditentukan
            $table->enum('condition', ['new', 'used'])->default('used');
            $table->enum('status', ['active', 'empty', 'inactive'])->default('active');
            $table->string('address'); // alamat teks bebas
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']); // untuk query listing aktif terbaru
            $table->index('user_id');
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listings');
    }
};
