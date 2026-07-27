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
        Schema::create('audit_answer_checklists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_answer_id');
            $table->unsignedBigInteger('audit_checklist_id');
            $table->timestamps();
            $table->foreign('audit_answer_id')->references('id')->on('audit_answers')->restrictOnDelete();
            $table->foreign('audit_checklist_id')->references('id')->on('audit_item_checklists')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_answer_checklists');
    }
};
