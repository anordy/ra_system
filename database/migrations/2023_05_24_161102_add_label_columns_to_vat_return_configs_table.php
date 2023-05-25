<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLabelColumnsToVatReturnConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vat_return_configs', function (Blueprint $table) {
            $table->string('value_label')->nullable()->after('heading_type');
            $table->string('rate_label')->nullable()->after('heading_type');
            $table->string('tax_label')->nullable()->after('heading_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vat_return_configs', function (Blueprint $table) {
            //
        });
    }
}
