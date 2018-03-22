<?php

namespace Tests\Feature;

use App\Inspections\SpamManger;
use Tests\TestCase;

class SpamTest extends TestCase
{

    /** @test */
    public function it_checks_for_invalid_keywords ()
    {
        $spam = new SpamManger();

        $this->assertFalse($spam->detect('Innocent Reply Here'));

        $this->expectException(\Exception::class);

        $spam->detect('Yahoo Customer Support');
    }

    /** @test */
    public function it_checks_for_any_key_being_held_down ()
    {
        $spam = new SpamManger();

        $this->expectException(\Exception::class);

        $spam->detect('Hallo World aaaaaaaaa');


    }

}
