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
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')->constrained()->restrictOnDelete(); //constrained itu jembatan
            $table->foreignId('author_id')->constrained()->restrictOnDelete();
            $table->foreignId('publisher_id')->constrained()->restrictOnDelete();

            $table->string('title', 100);
            $table->string('isbn', 100)->unique();
            $table->text('description')->nullable();
            $table->year('published_year');
            $table->unsignedInteger('stock')->default(0);
            $table->string('cover', 100)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
