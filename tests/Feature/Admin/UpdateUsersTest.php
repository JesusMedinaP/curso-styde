<?php

namespace Tests\Feature\Admin;

use App\Profession;
use App\Skill;
use App\User;
use App\UserProfiles;
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
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        $oldProfession = factory(Profession::class)->create();
        $user->profile()->save(factory(UserProfiles::class)->make([
            'user_id' => $user->id,
            'profession_id' => $oldProfession->id,
        ]));

        $oldSkill1 = factory(Skill::class)->create();
        $oldSkill2 = factory(Skill::class)->create();
        $user->skills()->attach([$oldSkill1->id, $oldSkill2->id,]);

        $newProfession = factory(Profession::class)->create();
        $newSkill1 = factory(Skill::class)->create();
        $newSkill2 = factory(Skill::class)->create();

        $this->put("usuarios/{$user->id}",[
            'name' => 'Duilio',
            'email' => 'duilio@gmail.com',
            'password' => '1234567',
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/silencee',
            'role' => 'admin',
            'profession_id' => $newProfession->id,
            'skills' => [$newSkill1->id, $newSkill2->id],
        ])->assertRedirect("usuarios/{$user->id}");

        $this->assertCredentials([
            'name' => 'Duilio',
            'email' => 'duilio@gmail.com',
            'password' => '1234567',
            'role' => 'admin',
        ]);
        $this->assertDatabaseHas('user_profiles', [
           'user_id' => $user->id,
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/silencee',
            'profession_id' => $newProfession->id,
        ]);
        $this->assertDatabaseCount('user_skill', 2);

        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $newSkill1->id,
        ]);
        $this->assertDatabaseHas('user_skill', [
            'user_id' => $user->id,
            'skill_id' => $newSkill2->id,
        ]);
    }

    /** @test  */
    function it_detaches_all_the_skills_if_none_are_selected(){

        $user = factory(User::class)->create();

        $oldSkill1 = factory(Skill::class)->create();
        $oldSkill2 = factory(Skill::class)->create();
        $user->skills()->attach([$oldSkill1->id, $oldSkill2->id]);

        $this->put("usuarios/{$user->id}",$this->withData([]))
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseEmpty('user_skill');

    }

    /** @test  */
    function  the_name_is_required()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", $this->withData([
                'name' => ''
            ]))
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
            ->put("/usuarios/{$user->id}", $this->withData([
                'email' => ''
            ]))
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
            ->put("/usuarios/{$user->id}", $this->withData([
                'email' => 'correo-no-valido'
            ]))
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
            ->put("/usuarios/{$user->id}",$this->withData([
                'email' => 'existing-email@example.com',
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['email']);

    }


    /** @test  */
    function the_password_is_optional()
    {
        $this->withExceptionHandling();
        $oldPassword = 'clave_anterior';
        $user = factory(User::class)->create([
            'password' => bcrypt($oldPassword)
        ]);

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'password' => '',
                'role' => 'user'
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
            ->put("/usuarios/{$user->id}", $this->withData([
                'email' => 'johndoe@example.com'
            ]))
            ->assertRedirect("usuarios/{$user->id}");

        $this->assertDatabaseHas('users',[
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
    }

    /** @test  */
    function  the_role_is_required()
    {
        $this->withExceptionHandling();
        $user = factory(User::class)->create();

        $this->from("usuarios/{$user->id}/editar")
            ->put("/usuarios/{$user->id}", $this->withData([
                'role' => ''
            ]))
            ->assertRedirect("usuarios/{$user->id}/editar")
            ->assertSessionHasErrors(['role']);

        $this->assertDatabaseMissing('users', ['email' => 'johndoe@example.com']);
    }

}
