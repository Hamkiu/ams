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
        Schema::create('audit_answer_reviews', function (Blueprint $table) {
            $table->id();

            $table->foreignId('audit_answer_id')
                ->constrained('audit_answers')
                ->cascadeOnDelete();

            $table->longText('penemuan_lain');
            $table->longText('bukti_audit');

            $table->timestamps();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            // Satu answer hanya mempunyai satu versi pindaan Ketua
            $table->unique('audit_answer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_answer_reviews');
    }
};
