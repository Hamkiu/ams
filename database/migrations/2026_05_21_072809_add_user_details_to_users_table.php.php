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
            $table->string('jawatan')->nullable()->after('email_verified_at');
            $table->string('jabatan')->nullable()->after('jawatan');
            $table->string('no_telefon')->nullable()->after('jabatan');
            $table->string('no_pekerja')->nullable()->after('no_telefon');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('jawatan');
            $table->dropColumn('jabatan');
            $table->dropColumn('no_telefon');
            $table->dropColumn('no_pekerja');
        });
    }
};
