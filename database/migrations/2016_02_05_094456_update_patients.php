<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdatePatients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('surname')->nullable()->change();
            $table->string('lastname')->nullable()->change();
            $table->date('birth_date')->nullable()->default("0000-00-00")->change();
            $table->integer('age')->nullable()->change();
            $table->string('gender')->nullable();
            $table->string('profession')->nullable()->change();
            $table->string('address')->nullable()->change();
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
