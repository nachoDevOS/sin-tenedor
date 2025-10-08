<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        // \DB::table('categories')->insert(array (
        //     0 => 
        //     array (
        //         'id' => 1,
        //         'name' => 'Bebidas',
        //         'observation' => NULL,
        //         'status' => 1,
        //         'created_at' => '2025-05-26 14:07:45',
        //         'updated_at' => '2025-05-26 14:07:45',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     1 => 
        //     array (
        //         'id' => 2,
        //         'name' => 'Platos Principales',
        //         'observation' => NULL,
        //         'status' => 1,
        //         'created_at' => '2025-05-26 14:08:05',
        //         'updated_at' => '2025-05-26 14:08:05',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     2 => 
        //     array (
        //         'id' => 3,
        //         'name' => 'Guarniciones',
        //         'observation' => NULL,
        //         'status' => 1,
        //         'created_at' => '2025-05-26 14:08:29',
        //         'updated_at' => '2025-05-26 14:08:29',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     3 => 
        //     array (
        //         'id' => 4,
        //         'name' => 'Postres',
        //         'observation' => NULL,
        //         'status' => 1,
        //         'created_at' => '2025-05-26 14:08:46',
        //         'updated_at' => '2025-05-26 14:08:46',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     4 => 
        //     array (
        //         'id' => 5,
        //         'name' => 'Porciones',
        //         'observation' => NULL,
        //         'status' => 1,
        //         'created_at' => '2025-05-27 11:42:37',
        //         'updated_at' => '2025-05-27 11:42:37',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        // ));
        
        
    }
}