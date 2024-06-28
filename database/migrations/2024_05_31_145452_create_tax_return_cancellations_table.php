<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxReturnCancellationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_return_cancellations', function (Blueprint $table) {
            $table->id();

            // General trace
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('tax_type_id');

            // To tax return
            $table->unsignedBigInteger('tax_return_id');

            // To morphed return
            $table->unsignedBigInteger('return_id');
            $table->string('return_type');

            // reasons
            $table->text('reasons');

            // Requested by
            $table->unsignedBigInteger('requested_by_id');
            $table->string('requested_by_type');
            $table->string('status');

            // Track time
            $table->dateTime('submitted_at');
            $table->dateTime('approved_on')->nullable();

            // Workflow
            $table->text('marking')->nullable();

            // Soft Delete
            $table->softDeletes();

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
        Schema::dropIfExists('tax_return_cancellations');
    }
}
