<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class classes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("class", function (Blueprint $table) {
            $table->string("id",36);
            $table->string("name",50);
            $table->integer("quantity")->unsigned();
            $table->string("major_id",36);
            $table->string("school_year_id",36);
            $table->string("created_by",50);
            $table->dateTime("created_at")->useCurrent();

            //constraint(s)
            $table->primary("id");
            $table->foreign("major_id")->references("id")->on("major");
            $table->foreign("school_year_id")->references("id")->on("school_year");
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
