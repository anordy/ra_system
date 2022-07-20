<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessFileTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_file_types', function (Blueprint $table) {
            $table->id();
            $table->string('short_name');
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('file_type', ['pdf', 'image'])->default('pdf');
            $table->enum('business_type', ['sole-proprietor', 'partnership', 'ngo', 'company', 'hotel']);
            $table->boolean('is_required')->default(false);
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
        Schema::dropIfExists('business_file_types');
    }
}
