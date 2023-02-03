<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Sortable;

class SortableTest extends TestCase
{

    /** @test  */

    public function returns_a_css_class_if_sortable()
    {
        $sortable = new Sortable;
        $this->assertSame('link-sortable', $sortable->classes('name'));
    }

    /** @test  */

    public function returns_a_css_class_if_sortable_in_asc_order()
    {
        $sortable = new Sortable;

        $sortable->setCurrentOrder('name');

        $this->assertSame('link-sortable link-sorted-up', $sortable->classes('name'));
    }

    /** @test  */

    public function returns_a_css_class_if_sortable_in_des_order()
    {
        $sortable = new Sortable;

        $sortable->setCurrentOrder('name', 'desc');

        $this->assertSame('link-sortable link-sorted-down', $sortable->classes('name'));
    }
}
