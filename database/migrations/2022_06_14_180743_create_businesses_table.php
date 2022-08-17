<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_category_id'); // Sole / Partner / Company / NGO
            $table->unsignedBigInteger('taxpayer_id'); // Main owner
            $table->string('bpra_no')->nullable();
            $table->enum('status', ['draft', 'pending', 'approved', 'correction', 'closed', 'temp_closed', 'deregistered'])->default('draft');
            $table->enum('business_type', ['hotel', 'other','electricity'])->default('other');
            $table->unsignedBigInteger('business_activities_type_id'); // Wholesale or Retail
            $table->unsignedBigInteger('currency_id');

            $table->string('name');
            $table->string('tin');
            $table->string('reg_no');
            $table->string('owner_designation');
            $table->string('mobile');
            $table->string('alt_mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('place_of_business');
            $table->string('physical_address');
            $table->string('pre_estimated_turnover');
            $table->string('post_estimated_turnover');
            $table->string('goods_and_services_types');
            $table->string('goods_and_services_example');

            // Contact person
            $table->unsignedBigInteger('responsible_person_id')->nullable();

            // Tax Filling Person
            $table->boolean('is_own_consultant')->default(true);

            // Not sure
            $table->dateTime('reg_date')->nullable();
            $table->string('marking')->nullable();
            $table->dateTime('approved_on')->nullable();

            $table->unsignedBigInteger('isiic_i')->nullable();
            $table->unsignedBigInteger('isiic_ii')->nullable();
            $table->unsignedBigInteger('isiic_iii')->nullable();
            $table->unsignedBigInteger('isiic_iv')->nullable();

            // TODO: Remove use approval instead
            $table->timestamp('verified_at')->nullable();
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
        Schema::dropIfExists('businesses');
    }
}
