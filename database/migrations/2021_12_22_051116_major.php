<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Major extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("major", function (Blueprint $table) {
            $table->string("id",36);
            $table->string("name",50);
            $table->string("codename",20);
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
