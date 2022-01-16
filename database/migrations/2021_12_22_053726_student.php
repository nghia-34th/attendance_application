<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Student extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("student", function (Blueprint $table) {
            $table->string("id",36);
            $table->string("name",50);
            $table->string("phone",20);
            $table->string("parent_phone",20)->nullable();
            $table->string("address",1000);
            $table->boolean("gender");
            $table->date("birthdate");
            $table->string("class_id",36);
            $table->string("created_by",50);
            $table->dateTime("created_at")->useCurrent();
            $table->string("full_txt_search",100)->nullable();

            //constraint(s)
            $table->primary("id");
            $table->foreign("class_id")->references("id")->on("class");
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
