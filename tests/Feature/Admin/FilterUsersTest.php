<?php

namespace Tests\Feature\Admin;

use App\Skill;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FilterUsersTest extends TestCase
{

   use RefreshDatabase;

   /** @test  */

    function filter_users_by_state_active()
    {

        $activeUser = factory(User::class)->create();

        $inactiveUser = factory(User::class)->state('inactive')->create();

        $response = $this->get('/usuarios?state=active');

        $response->assertViewCollection('users')
            ->contains($activeUser)
            ->notContains($inactiveUser);
    }

    /** @test  */

    function filter_users_by_state_inactive()
    {

        $activeUser = factory(User::class)->create();

        $inactiveUser = factory(User::class)->state('inactive')->create();

        $response = $this->get('/usuarios?state=inactive');

        $response->assertViewCollection('users')
            ->notcontains($activeUser)
            ->contains($inactiveUser);
    }

    /** @test  */

    function filter_users_by_role_admin()
    {

        $admin = factory(User::class)->create([
            'role' => 'admin'
        ]);

        $user = factory(User::class)->create([
            'role' => 'user'
        ]);

        $response = $this->get('/usuarios?role=admin');

        $response->assertViewCollection('users')
            ->notcontains($user)
            ->contains($admin);
    }

    /** @test  */

    function filter_users_by_role_user()
    {

        $admin = factory(User::class)->create([
            'role' => 'admin'
        ]);

        $user = factory(User::class)->create([
            'role' => 'user'
        ]);

        $response = $this->get('/usuarios?role=user');

        $response->assertViewCollection('users')
            ->notcontains($admin)
            ->contains($user);
    }

    /** @test  */

    function filter_users_by_skill()
    {

        $php = factory(Skill::class)->create([
            'name' => 'PHP'
        ]);

        $css = factory(Skill::class)->create([
            'name' => 'CSS'
        ]);

        $backendDev = factory(User::class)->create();
        $backendDev->skills()->attach($php);

        $frontendDev = factory(User::class)->create();
        $frontendDev->skills()->attach($css);

        $fullStackDev = factory(User::class)->create();
        $fullStackDev->skills()->attach([$php->id, $css->id]);

        $response = $this->get("/usuarios?skills[0]={$php->id}&skills[1]={$css->id}");

        $response->assertStatus(200);

        $response->assertViewCollection('users')
            ->contains($fullStackDev)
            ->notcontains($backendDev)
            ->notcontains($frontendDev);
    }

    /** @test  */

    function filter_users_created_from_date()
    {
        $newestUser = factory(User::class)->create([
            'created_at' => '2018-10-02 12:00:00',
        ]);

        $oldestUser = factory(User::class)->create([
            'created_at' => '2018-09-29 12:00:00',
        ]);

        $newUser = factory(User::class)->create([
            'created_at' => '2018-10-01 12:00:00',
        ]);

        $oldUser = factory(User::class)->create([
            'created_at' => '2018-09-30 23:59:59',
        ]);

        $response = $this->get('usuarios?from=01/10/2018');

        $response->assertOk();

        $response->assertViewCollection('users')
            ->contains($newUser)
            ->contains($newestUser)
            ->notContains($oldUser)
            ->notContains($oldestUser);
    }

    /** @test  */

    function filter_users_created_to_date()
    {
        $newestUser = factory(User::class)->create([
            'created_at' => '2018-10-02 12:00:00',
        ]);

        $oldestUser = factory(User::class)->create([
            'created_at' => '2018-09-29 12:00:00',
        ]);

        $newUser = factory(User::class)->create([
            'created_at' => '2018-10-01 12:00:00',
        ]);

        $oldUser = factory(User::class)->create([
            'created_at' => '2018-09-30 23:59:59',
        ]);

        $response = $this->get('usuarios?to=30/09/2018');

        $response->assertOk();

        $response->assertViewCollection('users')
            ->notContains($newUser)
            ->notContains($newestUser)
            ->contains($oldUser)
            ->contains($oldestUser);
    }
}

























