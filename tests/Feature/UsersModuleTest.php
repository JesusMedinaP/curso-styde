<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'name' => 'Joel'
        ]);

        factory(User::class)->create([
            'name' => 'Ellie'
        ]);

        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('Listado de Usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }


    /** @test */

    function it_shows_a_default_message_if_list_is_empty()
    {
        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('Listado de Usuarios vacio');
    }

    /** @test */

    function it_displays_the_users_details(){

        $user = factory(User::class)->create([
            'name' => 'Geralt de Rivia',
        ]);

        $this->get('/usuarios/'.$user->id)
            ->assertStatus(200)
            ->assertSee('Geralt de Rivia');
    }

    /** @test */

    function it_displays_a_404_error_if_the_user_is_not_found()
    {
        $this->get('usuarios/999')
            ->assertStatus(404)
            ->assertSee('Página no encontrada');
    }

    /** @test */

    function it_loads_the_new_users_page(){

        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear nuevo Usuario');
    }


    /** @test  */

    function it_creates_a_new_user(){

        $this->post('usuarios/',[
           'name' => 'John Doe',
           'email' => 'johndoe@example.com',
           'password' => '123456'
        ])->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => '123456'
        ]);
    }

    /** @test  */

    function the_name_is_required()
    {

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
            'name' => '',
            'email' => 'johndoe@example.com',
            'password' => '123456'
        ])->assertRedirect('usuarios/nuevo')
          ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_email_is_required()
    {

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'John',
                'email' => '',
                'password' => '123456'
            ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_password_is_required()
    {

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'John',
                'email' => 'johndoe@example.com',
                'password' => ''
            ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password' => 'El campo contraseña es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_email_must_be_valid()
    {

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'John',
                'email' => 'correo-no-valido',
                'password' => '123456'
            ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_email_must_be_unique()
    {
        factory(User::class)->create([
            'email' => 'johndoe@example.com'
            ]);
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'John',
                'email' => 'correo-no-valido',
                'password' => '123456'
            ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);

        $this->assertEquals(1, User::count());
    }
}
