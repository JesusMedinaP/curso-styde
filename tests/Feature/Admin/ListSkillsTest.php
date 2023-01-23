<?php

namespace Tests\Feature\Admin;

use App\Skill;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListSkillsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    function it_show_the_list_of_skills()
    {
        factory(Skill::class)->create(['name' => 'CSS']);

        factory(Skill::class)->create(['name' => 'PHP']);

        factory(Skill::class)->create(['name' => 'JS']);

        $this->get('/habilidades')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'CSS',
                'JS',
                'PHP'
            ]);
    }
}
