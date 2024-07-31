<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('NameUser')->unique();
            $table->string('email')->unique();  
            $table->string('password');
            $table->boolean('must_change_password')->default(true);
            $table->string('password_recovery_token')->nullable();
            $table->enum('role', ['user', 'secretary', 'admin'])->default('user'); 
            $table->unsignedBigInteger('StatusSesion')->default('6');
            $table->timestamps();


            $table->foreign('StatusSesion')->references('id')->on('status')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
