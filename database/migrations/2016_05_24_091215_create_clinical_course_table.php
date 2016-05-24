<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClinicalCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinical_course', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->nullable()->unsigned();
            $table->integer('review_id')->nullable()->unsigned();
            $table->text('content');
            $table->dateTime('date');
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
