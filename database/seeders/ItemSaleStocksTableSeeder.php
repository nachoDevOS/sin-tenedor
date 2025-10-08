<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ItemSaleStocksTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('item_sale_stocks')->delete();
        
        // \DB::table('item_sale_stocks')->insert(array (
        //     0 => 
        //     array (
        //         'id' => 1,
        //         'itemSale_id' => 7,
        //         'quantity' => '50.00',
        //         'stock' => '50.00',
        //         'type' => 'Ingreso',
        //         'observation' => 'Nuevo Ingreso',
        //         'created_at' => '2025-05-27 12:03:10',
        //         'updated_at' => '2025-08-19 16:04:25',
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
        //         'itemSale_id' => 6,
        //         'quantity' => '25.00',
        //         'stock' => '25.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-27 12:07:56',
        //         'updated_at' => '2025-05-27 12:53:43',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => '2025-05-27 12:53:43',
        //         'deleteUser_id' => 1,
        //         'deleteRole' => 'admin',
        //         'deleteObservation' => 'Eliminacio',
        //     ),
        //     2 => 
        //     array (
        //         'id' => 3,
        //         'itemSale_id' => 5,
        //         'quantity' => '30.00',
        //         'stock' => '30.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-27 12:08:22',
        //         'updated_at' => '2025-05-27 12:08:22',
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
        //         'itemSale_id' => 4,
        //         'quantity' => '30.00',
        //         'stock' => '30.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-27 12:08:36',
        //         'updated_at' => '2025-05-27 12:08:36',
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
        //         'itemSale_id' => 3,
        //         'quantity' => '26.00',
        //         'stock' => '26.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-27 12:08:51',
        //         'updated_at' => '2025-05-27 12:08:51',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     5 => 
        //     array (
        //         'id' => 6,
        //         'itemSale_id' => 2,
        //         'quantity' => '28.00',
        //         'stock' => '28.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-27 12:09:19',
        //         'updated_at' => '2025-05-27 12:09:19',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     6 => 
        //     array (
        //         'id' => 7,
        //         'itemSale_id' => 1,
        //         'quantity' => '35.00',
        //         'stock' => '33.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-27 12:09:34',
        //         'updated_at' => '2025-07-07 17:53:25',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     7 => 
        //     array (
        //         'id' => 8,
        //         'itemSale_id' => 6,
        //         'quantity' => '5.00',
        //         'stock' => '5.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-27 13:12:51',
        //         'updated_at' => '2025-05-27 13:12:51',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     8 => 
        //     array (
        //         'id' => 9,
        //         'itemSale_id' => 7,
        //         'quantity' => '5.00',
        //         'stock' => '5.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-28 04:56:35',
        //         'updated_at' => '2025-05-28 04:56:35',
        //         'registerUser_id' => 1,
        //         'registerRole' => 'admin',
        //         'deleted_at' => NULL,
        //         'deleteUser_id' => NULL,
        //         'deleteRole' => NULL,
        //         'deleteObservation' => NULL,
        //     ),
        //     9 => 
        //     array (
        //         'id' => 10,
        //         'itemSale_id' => 9,
        //         'quantity' => '15.00',
        //         'stock' => '15.00',
        //         'type' => 'Ingreso',
        //         'observation' => NULL,
        //         'created_at' => '2025-05-29 07:08:12',
        //         'updated_at' => '2025-07-03 19:53:48',
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