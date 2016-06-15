<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{

    protected $baseUrl = 'http://awesome.dev/historia-clinica/public';

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Email')
             ->see('Contrasenya');
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample2()
    {
        $this->visit('/')
            ->type('suport@hcabosantos.cat', 'email')
            ->type('juninho01', 'password')
            ->press('Inicia sessió')
            ->seePageIs('/');
    }
}
