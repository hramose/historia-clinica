<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    public $domain;

    public function __construct()
    {
        $this->domain = '{domain}';
    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit($this->domain)
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
        $this->visit($this->domain)
            ->type('suport@hcabosantos.cat', 'email')
            ->type('Juninho01', 'password')
            ->press('Inicia sessió')
            ->seePageIs($this->domain);
    }

    public function testBillConcepts()
    {
        return true;
    }
}
