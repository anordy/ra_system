<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentExtensionsRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_extensions_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('installable_id');
            $table->unsignedBigInteger('list_id');
            $table->string('installable_type');
            $table->string('reasons');
            $table->timestamp('extension_date');
            $table->unsignedBigInteger('created_by_id');
            $table->string('created_by_type');
            $table->enum('status', \App\Enum\InstallmentRequestStatus::getConstants());
            $table->string('marking')->nullable();
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
        Schema::dropIfExists('installment_extensions_requests');
    }
}
