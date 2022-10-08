<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->unsignedBigInteger('product_type_id');  
            $table->foreign('product_type_id')->references('id')->on('product_type');

            $table->unsignedBigInteger('provider_id');  
            $table->foreign('provider_id')->references('id')->on('provider');
        });

        Schema::table('movement', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');  
            $table->foreign('product_id')->references('id')->on('product');

            $table->unsignedBigInteger('location_from_id');  
            $table->foreign('location_from_id')->references('id')->on('location');

            $table->unsignedBigInteger('location_to_id');  
            $table->foreign('location_to_id')->references('id')->on('location');

            $table->unsignedBigInteger('movement_type_id');  
            $table->foreign('movement_type_id')->references('id')->on('movement_type');
        });

        Schema::table('product_maintenance', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');  
            $table->foreign('product_id')->references('id')->on('product');

            $table->unsignedBigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('location');
        });

        
        Schema::table('customer', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id');  
            $table->foreign('location_id')->references('id')->on('location');
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
}
