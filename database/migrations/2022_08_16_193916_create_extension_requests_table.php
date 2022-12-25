<?php

use App\Enum\ExtensionRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExtensionRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extension_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('extensible_id');
            $table->string('extensible_type');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('tax_type_id');
            $table->string('reasons');
            $table->string('ground');
            $table->dateTime('extend_from')->nullable();
            $table->dateTime('extend_to')->nullable();
            $table->string('marking')->nullable();
            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_type');
            $table->enum('status', ExtensionRequestStatus::getConstants());
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
        Schema::dropIfExists('extension_requests');
    }
}
