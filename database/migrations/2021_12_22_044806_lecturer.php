<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Lecturer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("lecturer", function (Blueprint $table) {
            $table->string("id",36);
            $table->string("name",50);
            $table->string("title",100);
            $table->string("phone",20);
            $table->string("address",1000);
            $table->boolean("gender");
            $table->string("username",100)->unique();
            $table->string("password",256);
            $table->boolean("role");
            $table->rememberToken();
            $table->string("created_by",50);
            $table->dateTime("created_at")->useCurrent();

            //constraint(s)
            $table->primary("id");
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
