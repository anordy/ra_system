<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('financia_year_id');
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('taxtype_id')->nullable()->foreign('taxtype_id')->references('id')->on('tax_types');
            $table->string('code');
            $table->string('name');
            $table->enum('row_type', ['dynamic', 'unremovable'])->default('dynamic');
            $table->boolean('value_calculated')->default(false);
            $table->enum('col_type', ['total', 'subtotal', 'normal'])->default('normal');
            $table->boolean('rate_applicable')->default(true);
            $table->enum('rate_type', ['percentage', 'fixed'])->nullable();
            $table->enum('currency', ['TZS', 'USD', 'BOTH'])->default('TZS');
            $table->decimal('rate')->unsigned()->default(0);
            $table->decimal('rate_usd')->nullable();
            $table->string('formular')->nullable();
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('port_configs');
    }
}
