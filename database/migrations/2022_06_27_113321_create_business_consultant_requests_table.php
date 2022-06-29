<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessConsultantRequestsTable extends Migration
{

    public function up()
    {
        Schema::create('business_consultant_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('business_id');
            $table->unsignedInteger('taxpayer_id');
            $table->dateTime('reviewed_at')->nullable();
            $table->boolean('is_accepted')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_consultant_requests');
    }
}
