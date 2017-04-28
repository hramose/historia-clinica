<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillConceptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_concepts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->integer('parent_id', false, true)->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('bill_concepts');
        });

        $concepts = [
            [
                'id' => 1,
                'name' => 'Fisioteràpia individual',
                'parent_id' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 2,
                'name' => 'Teràpia respiratòria',
                'parent_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 3,
                'name' => 'Teràpia manual',
                'parent_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 4,
                'name' => 'Exercicis actius / pasius / dirigits',
                'parent_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 5,
                'name' => 'Reeducació de moviments / Transferències / Marxa / Escales',
                'parent_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 6,
                'name' => 'Punció seca',
                'parent_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 7,
                'name' => 'Estiraments musculars / postures mantingudes',
                'parent_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 8,
                'name' => 'Teràpia antialgica (TENS, IR, Cold-Hotpack)',
                'parent_id' => 1,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 9,
                'name' => 'Fisioteràpia grupal',
                'parent_id' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ],
            [
                'id' => 10,
                'name' => 'Ergonomia / assessorament productes de suport',
                'parent_id' => null,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now()
            ]
        ];

        foreach ($concepts as $concept) {
            DB::table('bill_concepts')->insert(
                $concept
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bill_concepts');
    }
}