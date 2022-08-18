<?php

use App\Enum\ExtensionStatus;
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
            $table->unsignedBigInteger('debt_id');
            $table->unsignedBigInteger('location_id');
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('taxpayer_id');
            $table->text('reasons');
            $table->text('ground');
            $table->string('attachment')->nullable();
            $table->string('marking')->nullable();
            $table->enum('status', ExtensionStatus::getConstants());
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
