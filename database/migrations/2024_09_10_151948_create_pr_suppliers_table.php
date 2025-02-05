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
        Schema::create('pr_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('zno');
            $table->string('business_location_id');
            $table->string('contact_name');
            $table->string('contact_mobile');
            $table->string('contact_email')->nullable();
            $table->string('physical_address');
            $table->string('status');
            $table->string('ledger');

            $table->boolean('is_approved')->default(false);
            $table->boolean('is_updated')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pr_suppliers');
    }
};
