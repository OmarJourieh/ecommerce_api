<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('market_id');
            $table->double('total');                //----------new------
            $table->boolean('is_paid');             //------new-------
            $table->integer('deliverer_id')->nullable();
            $table->dateTime('deliver_scheduel')->nullable();
            $table->text('address');
            $table->integer('is_accepted')->nullable();
            $table->integer('is_delivered')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
