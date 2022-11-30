<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnContactsToWithholdingAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('withholding_agents', function (Blueprint $table) {
            $table->unsignedBigInteger('business_id')->nullable()->after('email');
            $table->string('fax')->nullable()->after('email');
            $table->string('alt_mobile')->nullable()->after('email');

        });
    }

}
