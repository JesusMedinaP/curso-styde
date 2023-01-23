<?php

namespace Admin;

use App\Profession;
use App\User;
use App\UserProfiles;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    protected $defaultData =[
        'name' => 'John Doe',
        'email' => 'johndoe@example.com',
        'bio' => 'Programador de Laravel',
        'twitter' => 'https://twitter.com/johndoe',
    ];

    /** @test */

    function a_user_can_edit_its_profile()
    {
        $user = factory(User::class)->create();
        $user->profile()->save(factory(UserProfiles::class)->make());

        $newProfession = factory(Profession::class)->create();

        //$this->actingAs($user);

        $response = $this->get('/editar-perfil/');

        $response->assertStatus(200);

        $response = $this->put('/editar-perfil/',[
            'name' => 'Duilio',
            'email' => 'duilio@gmail.com',
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/silencee',
            'profession_id' => $newProfession->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users',[
            'name' => 'Duilio',
            'email' => 'duilio@gmail.com',
        ]);

        $this->assertDatabaseHas('user_profiles',[
            'bio' => 'Programador de Laravel y Vue.js',
            'twitter' => 'https://twitter.com/silencee',
            'profession_id' => $newProfession->id,
        ]);
    }
    /** @test */

    function a_user_cannot_change_its_role()
    {
        $user = factory(User::class)->create([
            'role' => 'user'
        ]);

        $response = $this->put('/editar-perfil/',$this->withData([
            'role' => 'admin'
        ]));

        $response->assertRedirect();

        $this->assertDatabaseHas('users',[
            'id' => $user->id,
            'role' => 'user'
        ]);
    }

    /** @test */

    function the_user_cannot_change_its_password()
    {
        factory(User::class)->create([
            'password' => bcrypt('old123')
        ]);

        $response = $this->put('/editar-perfil/',$this->withData([
            'email' => 'duilio@styde.net',
            'password' => 'new456'
        ]));

        $response->assertRedirect();

        $this->assertCredentials([
            'email' => 'duilio@styde.net',
            'password' => 'old123',
        ]);
    }

}
