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
        Schema::create('rg_sub_category_notifiables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rg_sub_category_id');
            $table->unsignedBigInteger('role_id');
            $table->boolean('receive_notification')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rg_sub_category_notifiables');
    }
};
