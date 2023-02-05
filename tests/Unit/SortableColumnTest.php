<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Rules\SortableColumn;

class SortableColumnTest extends TestCase
{
    /** @test  */
    function validates_sortable_values()
    {
        $rule = new SortableColumn(['name', 'email']);

        $this->assertTrue($rule->passes('order', 'name'));
        $this->assertTrue($rule->passes('order', 'email'));
        //$this->assertTrue($rule->passes('order', 'date'));
        $this->assertTrue($rule->passes('order', 'name-desc'));
        $this->assertTrue($rule->passes('order', 'email-desc'));
        //$this->assertTrue($rule->passes('order', 'date-desc'));

        $this->assertFalse($rule->passes('order', 'name-descendent'));
        $this->assertFalse($rule->passes('order', 'asc-name'));
        $this->assertFalse($rule->passes('order', '-desc'));
        $this->assertFalse($rule->passes('order', 'email-'));
        $this->assertFalse($rule->passes('order', 'desc-name'));



    }
}
