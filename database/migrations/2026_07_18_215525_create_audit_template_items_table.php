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
        Schema::create('audit_template_items', function (Blueprint $table) {
            $table->id();
            $table->string('audit_template_id',15);
            $table->unsignedInteger('sort')->default(1);
            $table->string('perkara');
            $table->string('no_klausa');
            $table->string('klausa');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->foreign('audit_template_id')->references('id')->on('audit_templates')->restrictOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_template_items');
    }
};
