<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \App\{User, Profession, Skill, Team, UserProfiles};

class UserSeeder extends Seeder
{

    protected $professions;
    protected $skills;
    protected $teams;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       //$professions = DB::select('SELECT id FROM professions WHERE title = ? LIMIT 0,1', ['Desarrollador Back-end']);

       //DB::table('users')->insert([
      //     'name' => 'John Doe',
      //     'email' => 'johndoe@exameple.com',
      //     'password' => bcrypt('laravel'),
      //     'profession_id' => DB::table('professions')
      //         ->whereTitle('Desarrollador back-end')
      //         ->value('id')
      // ]);

        $this->fetchRelations();

        $this->createAdmin();

        foreach (range(1,999) as $i){
            $this->createRandomUser();
        }

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
    protected function fetchRelations() {
        $this->professions = Profession::all();
        $this->skills = Skill::all();
        $this->teams = Team::all();
    }

    public function createAdmin()
    {
        $admin = factory(User::class)->create([
            'team_id' => $this->teams->firstWhere('name', 'Styde'),
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => bcrypt('laravel'),
            'role' => 'admin',
            'created_at' => now()->addDay(),
        ]);
        $admin->skills()->attach($this->skills);

        $admin->profile()->create([
            'bio' => 'Programador Web',
            'profession_id' => $this->professions->where('title', 'Desarrollador Back-end')->first()->id
        ]);
    }

    public function createRandomUser(): void
    {
        $user = factory(User::class)->create([
            'team_id' => $this->teams->random()->id,
        ]);

        $user->skills()->attach($this->skills->random(rand(0, 6)));

        factory(UserProfiles::class)->create([
            'user_id' => $user->id,
            'profession_id' => rand(0, 2) ? $this->professions->random()->id : null,
        ]);
    }
}
