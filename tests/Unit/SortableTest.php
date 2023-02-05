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


         $this->assertSame('http://localhost:8000/?order=name',
             $this->sortable->url('name')
         );
    }

    /** @test  */
    function appends_query_data_to_the_url()
    {
        $this->sortable->appends(['a' => 'parameter', 'and' => 'another-parameter']);

        $this->assertSame('http://localhost:8000/?a=parameter&and=another-parameter&order=name',
            $this->sortable->url('name')
        );
    }

    /** @test  */

    public function builds_a_url_with_desc_order_if_current_column_matches_given_and_current_direction_is_asc()
    {
        $this->sortable->appends(['order' => 'name']);
        $this->assertSame('http://localhost:8000/?order=name-desc',
            $this->sortable->url('name')
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

        $this->sortable->appends(['order' => 'name']);

        $this->assertSame('link-sortable link-sorted-up',  $this->sortable->classes('name'));
    }

    /** @test  */

    public function returns_a_css_class_if_sortable_in_des_order()
    {

        $this->sortable->appends(['order' => 'name-desc']);

        $this->assertSame('link-sortable link-sorted-down',  $this->sortable->classes('name'));
    }


    /** @test  */

    function gets_the_info_about_the_column_name_and_the_other_order_direction()
    {
        $this->assertSame(['name', 'asc'], Sortable::info('name'));
        $this->assertSame(['name', 'desc'], Sortable::info('name-desc'));
        $this->assertSame(['email', 'desc'], Sortable::info('email-desc'));
        $this->assertSame(['email', 'asc'], Sortable::info('email'));
    }
}





