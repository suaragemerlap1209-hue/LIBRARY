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
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedTinyInteger('max_loans')->default(3)->after('member_id');
        $table->date('expired_at')->nullable()->after('max_loans');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['max_loans', 'expired_at']);
    });
}
};
