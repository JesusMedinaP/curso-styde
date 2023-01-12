<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    /** @test */

    function it_shows_the_users_list()
    {
        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('Listado de Usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /** @test */

    function it_shows_a_default_message_if_list_is_empty()
    {
        $this->get('/usuarios?empty')
            ->assertStatus(200)
            ->assertSee('No se ha registrado ningún usuario');
    }

    /** @test */

    function it_loads_the_users_details_page(){
        $this->get('/usuarios/5')
            ->assertStatus(200)
            ->assertSee('Usuario #5');
    }

    /** @test */

    function it_loads_the_new_users_page(){

        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear nuevo usuario');
    }
}
