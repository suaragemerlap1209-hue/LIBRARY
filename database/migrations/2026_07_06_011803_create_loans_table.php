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
    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('book_id')->constrained()->cascadeOnDelete();
        $table->date('borrowed_at')->nullable();
        $table->date('due_at')->nullable();        
        $table->date('returned_at')->nullable();
        $table->enum('status', ['pending', 'active', 'returned', 'overdue'])->default('pending');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('loans');
}
};
