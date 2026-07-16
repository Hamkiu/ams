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

            // Email boleh kosong
            $table->string('email')->nullable()->change();

            // Status pengguna
            $table->string('status')
                  ->default('AKTIF')
                  ->after('remember_token');

            // Sync Oracle
            $table->timestamp('sync_oracle')
                  ->nullable()
                  ->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();

            $table->dropColumn([
                'status',
                'sync_oracle'
            ]);
        });
    }
};
