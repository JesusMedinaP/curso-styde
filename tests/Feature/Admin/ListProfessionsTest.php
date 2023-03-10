<?php

namespace Tests\Feature\Admin;

use App\Profession;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListProfessionsTest extends TestCase
{

    use RefreshDatabase;

    /** @test */

    function it_show_the_list_of_professions()
    {
        factory(Profession::class)->create(['title' => 'Diseñador']);

        factory(Profession::class)->create(['title' => 'Programador']);

        factory(Profession::class)->create(['title' => 'Administrador']);

        $this->get('/profesiones')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Administrador',
                'Diseñador',
                'Programador'
            ]);
    }
}
