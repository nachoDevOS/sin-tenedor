<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UnitTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('unit_types')->delete();
        
        \DB::table('unit_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Metro',
                'abbreviation' => 'm',
                'status' => 1,
                'created_at' => '2025-05-29 20:45:46',
                'updated_at' => '2025-05-29 20:45:46',
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
                'name' => 'Pieza',
                'abbreviation' => 'pz',
                'status' => 1,
                'created_at' => '2025-05-29 20:45:54',
                'updated_at' => '2025-05-29 20:49:10',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Kilogramo',
                'abbreviation' => 'kg',
                'status' => 1,
                'created_at' => '2025-05-29 20:46:47',
                'updated_at' => '2025-05-29 20:46:47',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Libra',
                'abbreviation' => 'lb',
                'status' => 1,
                'created_at' => '2025-05-29 20:47:09',
                'updated_at' => '2025-05-29 20:47:09',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Litro',
                'abbreviation' => 'L',
                'status' => 1,
                'created_at' => '2025-05-29 20:47:42',
                'updated_at' => '2025-05-29 20:47:42',
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