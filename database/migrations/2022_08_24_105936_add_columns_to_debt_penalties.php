<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToDebtPenalties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('debt_penalties', function (Blueprint $table) {
            //
            $table->dateTime('end_date')->nullable()->after('debt_id');
            $table->dateTime('starting_date')->nullable()->after('debt_id');
            $table->decimal('penalty_amount', 20, 2)->after('debt_id');
            $table->decimal('rate_amount', 20, 2)->after('debt_id');
            $table->decimal('rate_percentage', 20, 2)->after('debt_id');
            $table->decimal('late_payment', 20, 2)->after('debt_id');
            $table->decimal('late_filing', 20, 2)->after('debt_id');
            $table->decimal('tax_amount', 20, 2)->after('debt_id');
            $table->string('financial_month_name')->after('debt_id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('debt_penalties', function (Blueprint $table) {
            //
            $table->dropColumn('financial_month_name');
            $table->dropColumn('tax_amount');
            $table->dropColumn('late_filing');
            $table->dropColumn('late_payment');
            $table->dropColumn('rate_percentage');
            $table->dropColumn('rate_amount');
            $table->dropColumn('penalty_amount');
            $table->dropColumn('end_date');
            $table->dropColumn('starting_date');
        });
    }
}
