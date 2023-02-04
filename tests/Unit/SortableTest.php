<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Sortable;

class SortableTest extends TestCase
{

    protected $sortable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sortable = new Sortable('http://localhost:8000/');
    }

    /** @test  */

    public function builds_a_url_with_sortable_data()
    {


         $this->assertSame('http://localhost:8000/?order=name&direction=asc',
             $this->sortable->url('name')
         );
    }

    /** @test  */
    function appends_query_data_to_the_url()
    {
        $this->sortable->appends(['a' => 'parameter', 'and' => 'another-parameter']);

        $this->assertSame('http://localhost:8000/?a=parameter&and=another-parameter&order=name&direction=asc',
            $this->sortable->url('name')
        );
    }

    /** @test  */

    public function builds_a_url_with_desc_order_if_current_column_matches_given_and_current_direction_is_asc()
    {
        $this->sortable->appends(['order' => 'first_name', 'direction' => 'asc']);
        $this->assertSame('http://localhost:8000/?order=first_name&direction=desc',
            $this->sortable->url('first_name')
        );
    }

    /** @test  */

    public function returns_a_css_class_if_sortable()
    {

        $this->assertSame('link-sortable',  $this->sortable->classes('name'));
    }

    /** @test  */

    public function returns_a_css_class_if_sortable_in_asc_order()
    {

        $this->sortable->appends(['order' => 'first_name']);

        $this->assertSame('link-sortable link-sorted-up',  $this->sortable->classes('first_name'));
    }

    /** @test  */

    public function returns_a_css_class_if_sortable_in_des_order()
    {

        $this->sortable->appends(['order' => 'first_name','direction' => 'desc']);

        $this->assertSame('link-sortable link-sorted-down',  $this->sortable->classes('first_name'));
    }

}





