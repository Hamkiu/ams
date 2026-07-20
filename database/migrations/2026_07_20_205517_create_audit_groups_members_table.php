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
        Schema::create('audit_groups_members', function (Blueprint $table) {
            $table->id();
            $table->string('audit_group_id',15);
            $table->unsignedBigInteger('user_id');
            $table->string('jabatan')->nullable();
            $table->unsignedTinyInteger('sort')->default(1);
            $table->enum('role', [
                'Leader',
                'Member'
            ])->default('Member');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->foreign('audit_group_id')->references('id')->on('audit_groups')->restrictOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->unique(['audit_group_id','user_id'], 'audit_group_member_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_groups_members');
    }
};
