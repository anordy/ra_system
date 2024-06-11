<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTaxClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_claims', function (Blueprint $table) {
            $table->decimal('original_figure', 20, 2)->nullable()->after('new_return_type');
            $table->string('supporting_document_for_agreed_figure')->nullable()->after('original_figure');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_claims', function (Blueprint $table) {
            $table->dropColumn('original_figure');
            $table->dropColumn('supporting_document_for_agreed_figure');
        });
    }
}
