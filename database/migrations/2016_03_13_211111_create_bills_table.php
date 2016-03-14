<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->nullable()->unsigned();;
            $table->integer('client_id')->nullable()->unsigned();;
            $table->integer('iva')->nullable();
            $table->integer('irpf')->nullable();
            $table->string('concept');
            $table->integer('qty');
            $table->decimal('amount');
            $table->date('creation_date');
            $table->date('expiration_date');
            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('client_id')->references('id')->on('clients');
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
