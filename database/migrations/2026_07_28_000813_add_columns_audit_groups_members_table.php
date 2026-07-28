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
        Schema::table('audit_groups_members', function (Blueprint $table) {
            $table->enum('status', [
                'BELUM BERMULA',
                'DALAM PROSES',
                'SELESAI'
            ])->default('BELUM BERMULA')->after('remarks');

            $table->timestamp('started_at')
                ->nullable()
                ->after('status');

            $table->timestamp('completed_at')
                ->nullable()
                ->after('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_groups_members', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('started_at');
            $table->dropColumn('completed_at');
        });
    }
};
