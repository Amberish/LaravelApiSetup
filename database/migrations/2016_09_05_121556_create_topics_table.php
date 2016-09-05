<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      // Create table for storing roles
      Schema::create('topics', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('business_id')->unsigned();
          $table->string('name');
          $table->string('slug')->unique();
          $table->string('description')->nullable();
          $table->timestamps();
      });

      Schema::table('topics', function($table) {
        $table->foreign('business_id')->references('id')->on('businesses')
            ->onUpdate('cascade')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('topics');
    }
}
