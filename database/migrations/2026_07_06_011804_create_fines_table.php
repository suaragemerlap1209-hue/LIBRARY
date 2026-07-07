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
    Schema::create('fines', function (Blueprint $table) {
        $table->id();
        $table->foreignId('loan_id')->constrained()->cascadeOnDelete();
        $table->unsignedInteger('amount');
        $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('fines');
}
};
