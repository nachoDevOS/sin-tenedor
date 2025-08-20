<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PeopleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('people')->delete();
        
        \DB::table('people')->insert(array (
            0 => 
            array (
                'id' => 1,
                'ci' => '5597800',
                'first_name' => 'ARTURO',
                'middle_name' => NULL,
                'paternal_surname' => 'MORENO',
                'maternal_surname' => 'BENAVIDES|',
                'birth_date' => NULL,
                'email' => NULL,
                'phone' => NULL,
                'address' => NULL,
                'gender' => 'masculino',
                'image' => NULL,
                'status' => 1,
                'created_at' => '2025-05-28 06:34:08',
                'updated_at' => '2025-05-28 06:34:08',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'ci' => '7633666',
                'first_name' => 'Ignacio',
                'middle_name' => NULL,
                'paternal_surname' => 'Molina',
                'maternal_surname' => 'Guzman',
                'birth_date' => '1997-03-08',
                'email' => NULL,
                'phone' => '67285914',
                'address' => NULL,
                'gender' => 'masculino',
                'image' => NULL,
                'status' => 1,
                'created_at' => '2025-05-29 06:07:35',
                'updated_at' => '2025-05-29 06:07:35',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
        ));
        
        
    }
}