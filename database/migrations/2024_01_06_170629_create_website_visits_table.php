<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebsiteVisitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('website_visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address');
            $table->timestamp('visit_timestamp')->useCurrent();
            $table->string('page_visited');
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->unsignedInteger('visit_count')->default(1);
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
        Schema::dropIfExists('website_visits');
    }
}
