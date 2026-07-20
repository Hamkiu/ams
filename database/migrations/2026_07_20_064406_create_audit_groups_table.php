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
        Schema::create('audit_groups', function (Blueprint $table) {
            $table->string('id',15)->primary();
            $table->string('audit_template_id',15);
            $table->string('name');
            $table->string('jabatan');
            $table->date('tarikh')->nullable();
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
        Schema::dropIfExists('audit_groups');
    }
};
