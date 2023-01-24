<?php

use App\Profession;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    //    DB::insert('INSERT INTO professions (title) VALUES (:title)', [
    //        'title' => "Desarollador back-end"
    //    ]);

      //  DB::table('professions')->insert([
      //      'title' => 'Desarrollador Back-end',
      //  ]);

        Profession::create([
            'title' => 'Desarrollador Back-end',
        ]);

        Profession::create([
            'title' => 'Desarrollador Front-end',
        ]);

        Profession::create([
            'title' => 'Diseñador Web',
        ]);

        //DB::table('professions')->insert([
        //    'title' => 'Desarrollador Front-end',
       // ]);

        //DB::table('professions')->insert([
        //    'title' => 'Diseñador Web',
        //]);

    }
}
