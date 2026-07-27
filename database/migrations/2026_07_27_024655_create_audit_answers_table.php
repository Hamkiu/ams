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
        Schema::create('audit_answers', function (Blueprint $table) {
            $table->id();
            $table->string('audit_group_id', 15);
            $table->unsignedBigInteger('audit_item_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('penemuan_lain');
            $table->longText('bukti_audit');


            $table->timestamps();
            $table->foreign('audit_group_id')->references('id')->on('audit_groups')->restrictOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('audit_item_id')->references('id')->on('audit_template_items')->restrictOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->unique([
                'audit_group_id',
                'audit_item_id',
                'user_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_answers');
    }
};
