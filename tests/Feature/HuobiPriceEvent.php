<?php

namespace Tests\Feature;

use App\Model\Huobi;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HuobiPriceEvent extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        event(new \App\Events\HuobiPrice(Huobi::findOrFail(128)));
        $this->assertTrue(true);
    }
}
