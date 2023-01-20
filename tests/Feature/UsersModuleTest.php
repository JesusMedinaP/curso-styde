<?php

namespace Tests\Feature;

use App\Profession;
use App\Skill;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;
    protected $profession;

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
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */

    function it_displays_the_users_details(){
        $this->withoutExceptionHandling();
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
        $this->withoutExceptionHandling();
        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear nuevo Usuario')
            ->assertViewHas('professions', function ($professions) use ($profession){
                return $professions->contains($profession);
        })
            ->assertViewHas('skills', function ($skills) use ($skillA, $skillB){
               return $skills->contains($skillA) && $skills->contains($skillB);
            });

    }


    /** @test  */

    function it_creates_a_new_user(){

        $this->withoutExceptionHandling();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->post('usuarios/',$this->getValidData([
            'skills' => [$skillA->id,$skillB->id],
        ]))->assertRedirect('usuarios');


        $this->assertCredentials([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => '123456',
            'role' => 'user',

        ]);
        $user = User::where('email', 'johndoe@example.com')->first();
        $this->assertDatabaseHas('user_profiles',[
            'bio' => 'Programador de Laravel',
            'twitter' => 'https://twitter.com/johndoe',
            'user_id' => User::where('email', 'johndoe@example.com')->first()->id,
            'profession_id' => $this->profession->id,
        ]);
        $this->assertDatabaseHas('user_skill',[
            'user_id' => $user->id,
            'skill_id' => $skillA->id,
         ]);

        $this->assertDatabaseHas('user_skill',[
            'user_id' => $user->id,
            'skill_id' => $skillB->id,
        ]);
    }

    /** @test  */

    function the_twitter_field_is_optional(){


        $this->post('usuarios/', $this->getValidData([
            'twitter' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles',[
            'bio' => 'Programador de Laravel',
            'twitter' => null,
            'user_id' => User::where('email', 'johndoe@example.com')->first()->id,
        ]);
    }

    /** @test  */

    function the_role_field_is_optional(){


        $this->post('usuarios/', $this->getValidData([
            'role' => null
        ]))->assertRedirect('usuarios');

        $this->assertDatabaseHas('users',[
            'email' => 'johndoe@example.com',
            'role' => 'user',
        ]);
    }


    /** @test  */

    function the_role_field_must_be_valid(){

        $this->post('usuarios/', $this->getValidData([
            'role' => 'invalid-role',
        ]))->assertSessionHasErrors('role');

        $this->assertEquals(0, User::count());
    }


    /** @test  */

    function the_profession_id_field_is_optional(){

        $this->post('usuarios/', $this->getValidData([
            'profession_id' => null
        ]))->assertRedirect('usuarios');

        $this->assertCredentials([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles',[
            'bio' => 'Programador de Laravel',
            'user_id' => User::where('email', 'johndoe@example.com')->first()->id,
            'profession_id' => null,
        ]);
    }


    /** @test  */

    function the_name_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'name' => '',
            ]))->assertRedirect('usuarios/nuevo')
          ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_email_is_required()
    {

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email' => ''
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_password_is_required()
    {

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'password' => '',
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password' => 'El campo contraseña es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_email_must_be_valid()
    {

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email' => 'correo-no-valido',
            ]))->assertRedirect('usuarios/nuevo')
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
            ->post('/usuarios/', $this->getValidData([
                'email' => 'johndoe@example.com',
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);

        $this->assertEquals(1, User::count());
    }

    /** @test  */

    function the_profession_id_must_be_valid()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'profession_id' => '999',
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_skills_must_be_an_array()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'skills' => 'PHP, JS',
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['skills']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_skills_must_be_valid()
    {
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'skills' => [$skillA->id,$skillB->id+1],
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['skills']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    public function only_selectable_professions_are_valid()
    {
        $nonSelectableProfession = factory(Profession::class)->create([
            'selectable' => false,
        ]);

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'profession_id' => $nonSelectableProfession->id,
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */
    function it_loads_the_edit_user_page(){

        $user = factory(User::class)->create();

        $this->get("/usuarios/{$user->id}/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Detalles de Usuario')
            ->assertViewHas('user', function ($viewUser) use ($user){
                    return $viewUser->id == $user->id;
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
    function  the_name_is_required_when_updating_the_user()
    {

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
    function  the_email_is_required_when_updating_the_user()
    {

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
    function  the_email_must_be_valid_when_updating_the_user()
    {

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
    function  the_email_must_be_unique_when_updating_the_user()
    {
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
    function the_password_is_optional_when_updating_the_user()
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
    function the_email_can_stay_the_same_when_updating_user()
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

    /** @test  */

    function it_deletes_a_user()
    {
        $user = factory(User::class)->create();

        $this->delete("usuarios/{$user->id}")
            ->assertRedirect('usuarios');


        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }

    public function getValidData(array $custom=[])
    {
        $this->profession = factory(Profession::class)->create();

        return array_filter(array_merge([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => '123456',
            'profession_id' => $this->profession->id,
            'bio' => 'Programador de Laravel',
            'twitter' => 'https://twitter.com/johndoe',
            'role' => 'user',
        ], $custom));
    }
}
