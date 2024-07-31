<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourcesBorrowedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources_borrowed', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('resource_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('statusLoan')->nullable();
            $table->text('Observation');
            $table->text('observationSec')->nullable();
            $table->dateTime('loan_date');
            $table->dateTime('return_date');
            $table->timestamps();

            $table->foreign('resource_id')->references('id')->on('resources')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('statusLoan')->references('id')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources_borrowed');
    }
};
