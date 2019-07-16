<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testShowDefault()
    {
        $response = $this->get("/api");
        $response->assertOk();
    }

    public function testShowOneParameter()
    {
        $randomString = substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            ceil(3 / strlen($x)))), 1, 3);
        $response     = $this->get("/api/{$randomString}");
        $response->assertOk();
    }

    public function testShowAllParameters()
    {
        $randomString = substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
            ceil(3 / strlen($x)))), 1, 3);
        $randomNumber = rand(5, 60);
        $response     = $this->get("/api/{$randomString}/{$randomNumber}");
        $response->assertOk();
    }
}
