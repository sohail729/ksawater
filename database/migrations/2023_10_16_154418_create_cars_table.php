<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->tinyInteger('delivery_possible')->default(0);
            $table->tinyInteger('insurance_included')->default(0);
            $table->string('cost_per_day');
            $table->string('pickup_location');
            $table->string('type');
            $table->unsignedInteger('brand_id');
            $table->unsignedInteger('model_id');
            $table->string('year');
            $table->string('power_size');
            $table->string('deposit');
            $table->string('mileage');
            $table->string('fuel_type');
            $table->string('seats');
            $table->string('transmission');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('is_active')->default(0);
            $table->text('features')->nullable();
            $table->integer('clicks')->default(0);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
