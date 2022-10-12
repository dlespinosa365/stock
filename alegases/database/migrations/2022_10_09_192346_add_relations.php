<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('product_type_id');
            $table->foreign('product_type_id')->references('id')->on('product_types');

            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers');
        });

        Schema::table('movements', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('location_from_id');
            $table->foreign('location_from_id')->references('id')->on('locations');

            $table->unsignedBigInteger('location_to_id');
            $table->foreign('location_to_id')->references('id')->on('locations');

            $table->unsignedBigInteger('movement_type_id');
            $table->foreign('movement_type_id')->references('id')->on('movement_types');
        });

        Schema::table('product_maintenances', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');
        });


        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
