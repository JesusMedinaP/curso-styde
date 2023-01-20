<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Skill;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUsersTest extends TestCase
{
    use RefreshDatabase;
    protected $profession;
    protected $defaultData = [
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'password' => '123456',
        'profession_id' => '',
        'bio' => 'Programador de Laravel',
        'twitter' => 'https://twitter.com/johndoe',
        'role' => 'user',
    ];

    /** @test */

    function it_loads_the_new_users_page(){

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

        $profession = factory(Profession::class)->create();

        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();

        $this->post('usuarios/',$this->withData([
            'skills' => [$skillA->id,$skillB->id],
            'profession_id' => $profession->id,
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
            'profession_id' => $profession->id,
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

    function the_user_is_redirected_to_the_previous_page_when_the_validation_fails()
    {
        $this->handleValidationExceptions();
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [])
            ->assertRedirect('usuarios/nuevo');

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_twitter_field_is_optional(){


        $this->post('usuarios/', $this->withData([
            'twitter' => null
        ]));

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
        $this->handleValidationExceptions();

        $this->post('usuarios/', $this->withData([
            'role' => null
        ]));

        $this->assertDatabaseHas('users',[
            'email' => 'johndoe@example.com',
            'role' => 'user',
        ]);
    }


    /** @test  */

    function the_role_field_must_be_valid(){
        $this->handleValidationExceptions();
        $this->post('usuarios/', $this->withData([
            'role' => 'invalid-role',
        ]));

        $this->assertEquals(0, User::count());
    }


    /** @test  */

    function the_profession_id_field_is_optional(){

        $this->post('usuarios/', $this->withData([
            'profession_id' => null
        ]));

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
        $this->handleValidationExceptions();
        $this->post('/usuarios/', $this->withData([
                'name' => '',
            ]))->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_email_is_required()
    {
        $this->handleValidationExceptions();
        $this->post('/usuarios/', $this->withData([
                'email' => ''
            ]))->assertSessionHasErrors(['email' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_password_is_required()
    {
        $this->handleValidationExceptions();
        $this->post('/usuarios/', $this->withData([
                'password' => '',
            ]))->assertSessionHasErrors(['password' => 'El campo contraseña es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_email_must_be_valid()
    {
        $this->handleValidationExceptions();
        $this->post('/usuarios/', $this->withData([
                'email' => 'correo-no-valido',
            ]))->assertSessionHasErrors(['email']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_email_must_be_unique()
    {
        $this->handleValidationExceptions();
        factory(User::class)->create([
            'email' => 'johndoe@example.com'
        ]);
        $this->post('/usuarios/', $this->withData([
                'email' => 'johndoe@example.com',
            ]))->assertSessionHasErrors(['email']);

        $this->assertEquals(1, User::count());
    }

    /** @test  */

    function the_profession_id_must_be_valid()
    {
        $this->handleValidationExceptions();
        $this->post('/usuarios/', $this->withData([
                'profession_id' => '999',
            ]))->assertSessionHasErrors(['profession_id']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_skills_must_be_an_array()
    {
        $this->handleValidationExceptions();
        $this->post('/usuarios/', $this->withData([
                'skills' => 'PHP, JS',
            ]))->assertSessionHasErrors(['skills']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    function the_skills_must_be_valid()
    {
        $this->handleValidationExceptions();
        $skillA = factory(Skill::class)->create();
        $skillB = factory(Skill::class)->create();
        $this->post('/usuarios/', $this->withData([
                'skills' => [$skillA->id,$skillB->id+1],
            ]))->assertSessionHasErrors(['skills']);

        $this->assertEquals(0, User::count());
    }

    /** @test  */

    public function only_selectable_professions_are_valid()
    {
        $this->handleValidationExceptions();
        $nonSelectableProfession = factory(Profession::class)->create([
            'selectable' => false,
        ]);

        $this->post('/usuarios/', $this->withData([
                'profession_id' => $nonSelectableProfession->id,
            ]))->assertSessionHasErrors(['profession_id']);

        $this->assertEquals(0, User::count());
    }

}
