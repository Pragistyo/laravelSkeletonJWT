<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_profile', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned(); // unassign() because it cant have negative value
            $table->index('user_id'); 
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            //$table->foreign('user_id')->references('id')->on('users'); // foreign key dari user
            $table->string('nama');
            $table->string('avatar');
            $table->string('alamat1');
            $table->string('alamat2');
            $table->string('latlang1');
            $table->string('latlang2');
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
        Schema::dropIfExists('customer_profile');
    }
}
