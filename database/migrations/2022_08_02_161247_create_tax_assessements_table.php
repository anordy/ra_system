<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxAssessementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_assessements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('responsible_person_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->enum('currency', ['TZS', 'USD', 'EUR']);
            $table->decimal('principal_amount');
            $table->decimal('penalty');
            $table->decimal('interest');
            $table->date('ass_date');
            $table->date('due_date');
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
        Schema::dropIfExists('tax_assessements');
    }
}
