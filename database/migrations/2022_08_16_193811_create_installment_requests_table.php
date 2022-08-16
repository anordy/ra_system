<?php

use App\Enum\InstallmentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installment_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->string('return_type');
            $table->text('reasons');
            $table->text('ground');
            $table->string('attachment')->nullable();
            $table->string('marking')->nullable();
            $table->enum('status', InstallmentStatus::getConstants());
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
        Schema::dropIfExists('installment_requests');
    }
}
