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
        Schema::table('audit_answer_checklists', function (Blueprint $table) {
            $table->enum('status', [
                'AKUR',
                'TIDAK AKUR',
                'TIDAK BERKAITAN'
            ])->nullable()->after('audit_checklist_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_answer_checklists', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
