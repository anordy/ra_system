<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithheldCertificateAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withheld_certificate_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tax_return_id')->nullable();

            // For morphing to specific return.
            $table->unsignedBigInteger('return_id');
            $table->string('return_type');
            $table->string('location');

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
        Schema::dropIfExists('withheld_certificate_attachments');
    }
}
