<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZmReconTransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zm_recon_trans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recon_id');
            $table->string('SpBillId', 50)->nullable();
            $table->string('BillCtrNum')->nullable();
            $table->string('pspTrxId', 127)->nullable();
            $table->decimal('PaidAmt', 38, 40)->nullable();
            $table->string('CCy', 3)->nullable();
            $table->string('PayRefId', 100)->nullable();
            $table->string('TrxDtTm',50)->nullable();
            $table->string('CtrAccNum',15)->nullable();
            $table->string('UsdPayChnl',50)->nullable();
            $table->string('PspName', 100)->nullable();
            $table->string('DptCellNum',12)->nullable();
            $table->string('DptName',100)->nullable();
            $table->string('DptEmailAddr',100)->nullable();
            $table->string('Remarks',127)->nullable();
            $table->string('ReconcRvs1',50)->nullable();
            $table->string('ReconcRvs2',50)->nullable();
            $table->string('ReconcRvs3',50)->nullable();
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
        Schema::dropIfExists('zm_recon_trans');
    }
}
