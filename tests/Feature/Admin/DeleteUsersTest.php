<?php

namespace Tests\Feature\Admin;

use App\User;
use App\UserProfiles;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteUsersTest extends TestCase
{

    use RefreshDatabase;


    /** @test  */

    function it_send_a_user_to_the_trash()
    {
        $user = factory(User::class)->create();
        $user->profile()->save(factory(UserProfiles::class)->make());

        $this->patch("usuarios/{$user->id}/papelera")
            ->assertRedirect('usuarios');

        $this->assertSoftDeleted('users',[
            'id' => $user->id
        ]);

        $this->assertSoftDeleted('user_profiles',[
            'user_id' => $user->id
        ]);
    }


    /** @test  */

    function it_completely_deletes_a_user()
    {
        $user = factory(User::class)->create([
            'deleted_at' => now()
        ]);

        factory(UserProfiles::class)->create([
            'user_id' => $user->id,
        ]);

        $this->delete("usuarios/{$user->id}")
            ->assertRedirect('usuarios/papelera');

        $this->assertDatabaseEmpty('users');
    }

    /** @test  */

    function it_cannot_delete_a_user_if_not_trashed()
    {
        $this->withExceptionHandling();

        $user = factory(User::class)->create([
            'deleted_at' => null
        ]);

        factory(UserProfiles::class)->create([
            'user_id' => $user->id,
        ]);

        $this->delete("usuarios/{$user->id}")
            ->assertStatus(404);

        $this->assertDatabaseHas('users',[
           'id' => $user->id,
           'deleted_at' => null,
        ]);
    }
}
