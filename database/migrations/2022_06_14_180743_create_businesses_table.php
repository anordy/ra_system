<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_category_id'); // Sole / Partner / Company / NGO
            $table->unsignedBigInteger('taxpayer_id'); // Main owner
            $table->string('bpra_no');
            $table->enum('status', ['draft', 'pending', 'approved', 'correction']);

            // TODO: Remove use approval instead
            $table->timestamp('verified_at')->nullable();
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
        Schema::dropIfExists('businesses');
    }
}
