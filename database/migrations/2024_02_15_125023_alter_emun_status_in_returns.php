<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterEmunStatusInReturns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tables = [
            'TAX_RETURNS',
            'VAT_RETURNS',
            'STAMP_DUTY_RETURNS',
            'HOTEL_RETURNS',
            'BFO_RETURNS',
            'MNO_RETURNS',
            'EM_TRANSACTION_RETURNS',
            'MM_TRANSFER_RETURNS',
            'LUMP_SUM_RETURNS',
            'PETROLEUM_RETURNS',
            'PORT_RETURNS',
        ];

        foreach ($tables as $table) {
            if ($table != 'TAX_RETURNS') {
                $constraint = DB::select("SELECT acc.constraint_name,acc.column_name, ac.search_condition, ac.constraint_type FROM all_constraints ac JOIN all_cons_columns acc ON ac.constraint_name = acc.constraint_name WHERE acc.table_name = '{$table}' AND acc.column_name = 'STATUS' ORDER BY ACC.CONSTRAINT_NAME DESC FETCH FIRST 1 ROWS ONLY");
                if ($constraint[0]) {
                   DB::statement("alter table {$table} drop constraint {$constraint[0]->constraint_name}");
                   DB::statement("alter table {$table} add check (status in ('submitted', 'control-number-generating', 'control-number-generated', 'control-number-generating-failed', 'paid-partially', 'completed-partially', 'complete', 'on-claim', 'paid-by-debt', 'nill'))");
                }
            } else {
                $constraint = DB::select("SELECT acc.constraint_name,acc.column_name, ac.search_condition, ac.constraint_type FROM all_constraints ac JOIN all_cons_columns acc ON ac.constraint_name = acc.constraint_name WHERE acc.table_name = '{$table}' AND acc.column_name = 'PAYMENT_STATUS' ORDER BY ACC.CONSTRAINT_NAME DESC FETCH FIRST 1 ROWS ONLY");
                if ($constraint[0]) {
                    DB::statement("alter table {$table} drop constraint {$constraint[0]->constraint_name}");
                    DB::statement("alter table {$table} add check (payment_status in ('submitted', 'control-number-generating', 'control-number-generated', 'control-number-generating-failed', 'paid-partially', 'completed-partially', 'complete', 'on-claim', 'paid-by-debt', 'nill'))");
                }

            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('returns', function (Blueprint $table) {
            //
        });
    }
}
