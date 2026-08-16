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
        Schema::create('audit_admin_reviews', function (Blueprint $table) {
            $table->id();

            $table->string('audit_group_id', 15);

            $table->longText('review')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->foreign('audit_group_id')->references('id')->on('audit_groups')->restrictOnDelete();

            $table->unique('audit_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_admin_reviews');
    }
};
