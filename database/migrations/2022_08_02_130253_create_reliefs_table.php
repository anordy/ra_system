<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReliefsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reliefs', function (Blueprint $table) {
            $table->id();
            $table->string('relief_number')->nullable();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('project_list_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('business_id');
            $table->decimal('rate');
            $table->decimal('vat');
            $table->decimal('total_amount',40,2);
            $table->decimal('vat_amount',40,2);
            $table->decimal('relieved_amount',40,2);
            $table->decimal('amount_payable',40,2);
            $table->date('expire');
            $table->enum('status', ['pending','draft', 'approved', 'rejected']);
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('reliefs');
    }
}
