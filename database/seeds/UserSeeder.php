<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \App\User;
use \App\Profession;
use App\Skill;
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

        $professions = Profession::all();

        $skills = Skill::all();

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
            'created_at' => now()->addDay(),
        ]);
        $user->profile()->create([
            'bio' => 'Programador Web',
            'profession_id' => $professions->where('title', 'Desarrollador Back-end')->first()->id
        ]);

        factory(User::class,100)->create()->each(function ($user) use ($professions, $skills){
            $randomSkills = $skills->random(rand(0,6));

            $user->skills()->attach($randomSkills);

                factory(\App\UserProfiles::class)->create([
                    'user_id' => $user->id,
                    'profession_id' => rand(0,2) ? $professions->random()->id : null,
                ]);

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
