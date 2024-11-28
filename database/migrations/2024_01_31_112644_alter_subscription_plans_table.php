<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // id
        // duration
        // is_featured
        // featured_duration
        // featured

        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->dropColumn('stripe_prod_id');
            $table->dropColumn('stripe_price_id');
            $table->dropColumn('amount');
            $table->dropColumn('title');
            $table->string('packages');
            $table->renameColumn('type', 'featured_packages')->nullable()->change();
            $table->integer('featured_duration')->default(0);
            $table->tinyInteger('is_featured')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
