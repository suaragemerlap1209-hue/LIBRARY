<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'member'])->default('member')->after('password');
            $table->enum('status', ['active', 'suspended', 'blocked'])->default('active')->after('role');
            $table->date('birth_date')->nullable()->after('status');
            $table->string('address')->nullable()->after('birth_date');
            $table->string('member_code')->nullable()->unique()->after('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'status', 'birth_date', 'address', 'member_code']);
        });
    }
};