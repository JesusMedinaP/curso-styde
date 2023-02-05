<?php

namespace Tests\Feature\Admin;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ListUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'first_name' => 'Joel'
        ]);

        factory(User::class)->create([
            'first_name' => 'Ellie'
        ]);

        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee(trans('users.titles.index'))
            ->assertSee('Joel')
            ->assertSee('Ellie');

        //$this->assertNotRepeatedQueries();
    }

    /** @test */

    function it_shows_a_default_message_if_list_is_empty()
    {
        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('No hay usuarios registrados');
    }

    /** @test */

    function it_shows_the_deleted_users_list()
    {

        factory(User::class)->create([
            'first_name' => 'Joel',
            'deleted_at' => now(),
        ]);

        factory(User::class)->create([
            'first_name' => 'Ellie'
        ]);

        $this->get('/usuarios/papelera')
            ->assertStatus(200)
            ->assertSee(trans('users.titles.trashed'))
            ->assertSee('Joel')
            ->assertDontSee('Ellie');
    }


    /** @test */

    function it_paginates_the_users_list()
    {

        factory(User::class)->create([
            'first_name' => 'Tercer Usuario',
            'created_at' => now()->subDays(5)
        ]);

        factory(User::class)->times(12)->create([
            'created_at' => now()->subDays(4)
        ]);

        factory(User::class)->create([
            'first_name' => 'Primer Usuario',
            'created_at' => now()->subWeek(),
        ]);

        factory(User::class)->create([
            'first_name' => 'Segundo Usuario',
            'created_at' => now()->subDays(6)
        ]);

        factory(User::class)->create([
            'first_name' => 'Decimoséptimo Usuario',
            'created_at' => now()->subDays(2)
        ]);

        factory(User::class)->create([
            'first_name' => 'Decimosexto Usuario',
            'created_at' => now()->subDays(3)
        ]);

        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSeeInOrder([
                'Decimoséptimo Usuario',
                'Decimosexto Usuario',
                'Tercer Usuario'
            ])
            ->assertDontSee('Segundo Usuario')
            ->assertDontSee('Primer Usuario');

        $this->get('usuarios?page=2')
            ->assertSeeInOrder(['Segundo Usuario', 'Primer Usuario'])
            ->assertDontSee('Tercer Usuario');
    }


    /** @test  */

    function users_are_sorted_by_name()
    {

        factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        factory(User::class)->create([
            'first_name' => 'Richard',
            'last_name' => 'Roe',
        ]);

        factory(User::class)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
        ]);


        $this->get('/usuarios?order=name')
            ->assertSeeInOrder([
                'Jane Doe',
                'John Doe',
                'Richard Roe'
            ]);

        $this->get('/usuarios?order=name-desc')
            ->assertSeeInOrder([
                'Richard Roe',
                'John Doe',
                'Jane Doe',
            ]);
    }

    /** @test  */

    function users_are_sorted_by_email()
    {

        factory(User::class)->create([
            'email' => 'johndoe@example.com'
        ]);

        factory(User::class)->create([
            'email' => 'richardroe@example.com'
        ]);

        factory(User::class)->create([
            'email' => 'janedoe@example.com'
        ]);


        $this->get('/usuarios?order=email')
            ->assertSeeInOrder([
                'janedoe@example.com',
                'johndoe@example.com',
                'richardroe@example.com'
            ]);

        $this->get('/usuarios?order=email-desc')
            ->assertSeeInOrder([
                'richardroe@example.com',
                'johndoe@example.com',
                'janedoe@example.com',
            ]);
    }

    /** @test  */

    function users_are_sorted_by_register()
    {

        factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'created_at' => now()->subDays(2)
        ]);

        factory(User::class)->create([
            'first_name' => 'Richard',
            'last_name' => 'Roe',
            'created_at' => now()->subDays(3)
        ]);

        factory(User::class)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'created_at' => now()->subDays(5)
        ]);


        $this->get('/usuarios?order=date')
            ->assertSeeInOrder([
                'Jane Doe',
                'Richard Roe',
                'John Doe',
            ]);

        $this->get('/usuarios?order=date-desc')
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);
    }

    /** @test */

    function invalid_order_query_data_is_ignored_and_the_default_order_is_used_instead()
    {
        factory(User::class)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'created_at' => now()->subDays(2)
        ]);

        factory(User::class)->create([
            'first_name' => 'Richard',
            'last_name' => 'Roe',
            'created_at' => now()->subDays(3)
        ]);

        factory(User::class)->create([
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'created_at' => now()->subDays(5)
        ]);


        $this->get('/usuarios?order=id')
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',

            ]);

        $this->get('/usuarios?order=invalid_column-desc')
            ->assertOk()
            ->assertSeeInOrder([
                'John Doe',
                'Richard Roe',
                'Jane Doe',
            ]);


    }
}








