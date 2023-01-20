<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \App\User;
use \App\Profession;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       //$professions = DB::select('SELECT id FROM professions WHERE title = ? LIMIT 0,1', ['Desarrollador Back-end']);

        $professionId = Profession::where('title', 'Desarrollador back-end')->value('id');

       //DB::table('users')->insert([
      //     'name' => 'John Doe',
      //     'email' => 'johndoe@exameple.com',
      //     'password' => bcrypt('laravel'),
      //     'profession_id' => DB::table('professions')
      //         ->whereTitle('Desarrollador back-end')
      //         ->value('id')
      // ]);

        $user = factory(User::class)->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('laravel'),
            'role' => 'admin',
        ]);
        $user->profile()->create([
            'bio' => 'Programador Web',
            'profession_id' => $professionId,
        ]);

        factory(User::class,29)->create()->each(function ($user){
            $user->profile()->create(
                factory(\App\UserProfiles::class)->raw()
            );
        });

        //User::create([
        //    'name' => 'Geralt de Rivia',
        //    'email' => 'geraltrivia@example.com',
        //    'password' => bcrypt('123'),
        //    'profession_id' => $professionId
        //]);

        //User::create([
        //    'name' => 'Yennefer de Vengerberg',
        //    'email' => 'yenneferv@exameple.com',
        //    'password' => bcrypt('1234'),
        //   'profession_id' => $professionId
        //]);
    }
}
