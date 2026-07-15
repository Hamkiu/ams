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
        Schema::create('audit_trails', function (Blueprint $table) {
            $table->string('audit_trail_id',15)->primary();
            $table->string('audit_trail_module')->nullable();
            $table->string('audit_trail_component')->nullable();
            $table->string('audit_trail_action')->nullable();
            $table->string('audit_trail_data_id')->nullable();
            $table->string('audit_trail_data_name')->nullable();
            $table->text('audit_trail_desc')->nullable();
            $table->unsignedBigInteger('users_id')->nullable();
            $table->timestamps();

            $table->foreign('users_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
