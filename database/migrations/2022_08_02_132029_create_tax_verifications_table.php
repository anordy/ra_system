<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->unsignedBigInteger('tax_return_id');
            $table->string('tax_return_type');
            $table->text('scope')->nullable();
            $table->date('period_from')->nullable();
            $table->date('period_to')->nullable();
            $table->string('created_by_type');
            $table->unsignedBigInteger('created_by_id');
            $table->enum('status', ['draft', 'pending', 'approved', 'correction'])->default('draft');
            $table->string('marking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_verifications');
    }
}
