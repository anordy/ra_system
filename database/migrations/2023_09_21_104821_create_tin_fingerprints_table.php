<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTinFingerprintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('tin_fingerprints', function (Blueprint $table) {
           $table->id();
           $table->unsignedBigInteger('tin_id');
           $table->enum('hand', ['right', 'left'])->nullable();
           $table->string('finger_label')->nullable();
           $table->integer('index');
           $table->longText('fingerprint', 8000)->nullable();
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
        Schema::dropIfExists('tin_fingerprints');
    }
}
