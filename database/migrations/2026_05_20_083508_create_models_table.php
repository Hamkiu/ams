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
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->string('module');
            $table->bigInteger('component_no')->nullable();
            $table->string('components');
            $table->unsignedBigInteger('sub_components_no');
            $table->string('sub_components_name')->nullable();
            $table->string('sub_components');
            $table->string('route')->nullable();
            $table->string('icon')->nullable();
            $table->string('comp_icon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('models');
    }
};
