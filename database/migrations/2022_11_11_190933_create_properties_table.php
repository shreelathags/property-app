<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->uuid('property_id')->unique();
            $table->string('county');
            $table->string('country');
            $table->string('town');
            $table->text('description');
            $table->tinyText('details_url')->nullable();
            $table->string('address', 1000);
            $table->tinyText('image_url');
            $table->tinyText('thumbnail_url');
            $table->string('latitude');
            $table->string('longitude');
            $table->integer('no_of_bedrooms');
            $table->integer('no_of_bathrooms');
            $table->bigInteger('price');
            $table->boolean('for_sale')->nullable();
            $table->boolean('for_rent')->nullable();
            $table->unsignedBigInteger('property_type_id');
            $table->dateTime('property_created_at');
            $table->dateTime('property_updated_at');
            $table->timestamps();

            $table->foreign('property_type_id')->references('id')->on('property_types');

            $table->index(['for_sale']);
            $table->index(['for_rent']);
            $table->fullText(['address']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property');
    }
}
