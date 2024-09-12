<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateSignaturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_signatures', function (Blueprint $table) {
            $table->id();
            $table->string('title', 50);
            $table->string('name', 100);
            $table->string('signature_path', 255);
            $table->text('image');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->boolean('is_approved')->default(0);
            $table->boolean('is_updated')->default(0);
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
        Schema::dropIfExists('certificate_signatures');
    }
}
