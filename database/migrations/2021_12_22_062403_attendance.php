<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Attendance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("attendance", function (Blueprint $table) {
            $table->string("id",36);
            $table->enum("status",["with reason", "without reason", "late"]);
            $table->string("absent_reason",1000)->nullable();
            $table->string("student_id",36);
            $table->string("lesson_id",36);
            $table->string("created_by",50);
            $table->dateTime("created_at")->useCurrent();

            //constraint(s)
            $table->primary("id");
            $table->foreign("student_id")->references("id")->on("student");
            $table->foreign("lesson_id")->references("id")->on("lesson");
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
