<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateUsersTest extends TestCase
{

    use RefreshDatabase;
    protected $defaultData = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => '123456',
        'profession_id' => '',
        'bio' => 'Programador de Laravel',
        'twitter' => 'https://twitter.com/johndoe',
        'role' => 'user',
    ];

    /** @test  */
    function it_loads_the_edit_user_page(){

        $user = factory(User::class)->create();

        $this->get("/usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Detalles de Usuario')
            ->assertViewHas('user', function ($viewUser) use ($user){
                return $viewUser->id === $user->id;
            });
    }

    /** @test  */
    function it_updates_a_user(){

        $user = factory(User::class)->create();
        $this->put("usuarios/{$user->id}",[
            'name' => 'Duilio',
            'email' => 'duilio@gmail.com',
            'password' => '1234567'
        ])->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Duilio',
            'email' => 'duilio@gmail.com',
            'password' => '1234567'
        ]);
    }

    /** @test  */
    function  the_name_is_required()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", [
                'name' => '',
                'email' => 'johndoe@example.com',
                'password' => '123456'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('users', ['email' => 'johndoe@example.com']);
    }

    /** @test  */
    function  the_email_is_required()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", [
                'name' => 'John Doe',
                'email' => '',
                'password' => '123456'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['email' => 'johndoe@example.com']);
    }


    /** @test  */
    function  the_email_must_be_valid()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", [
                'name' => 'John Doe',
                'email' => 'correo-no-valido',
                'password' => '123456'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', ['email' => 'johndoe@example.com']);
    }


    /** @test  */
    function  the_email_must_be_unique()
    {
        $this->withExceptionHandling();

        $randomUser = factory(User::class)->create([
            'email' => 'existing-email@example.com'
        ]);
        $user = factory(User::class)->create([
            'email' => 'johndoe@example.com'
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", [
                'name' => 'John Doe',
                'email' => 'existing-email@example.com',
                'password' => '123456'
            ])
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['email']);

    }


    /** @test  */
    function the_password_is_optional()
    {
        $oldPassword = 'clave_anterior';
        $user = factory(User::class)->create([
            'password' => bcrypt($oldPassword)
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => ''
            ])
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => $oldPassword
        ]);
    }

    /** @test  */
    function the_email_can_stay_the_same()
    {

        $user = factory(User::class)->create([
            'email' => 'johndoe@example.com'
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => '1234567'
            ])
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseHas('users',[
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
    }

}
