<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ItemSalesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('item_sales')->delete();
        
        \DB::table('item_sales')->insert(array (
            0 => 
            array (
                'id' => 1,
                'category_id' => 1,
                'image' => 'item-sales/October2025/OfrW0eQ83FMSXMHZJsFIday08am.avif',
                'name' => 'Ala Broaster Economico',
                'price' => '13.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-07 20:14:21',
                'updated_at' => '2025-10-07 20:44:57',
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
                'category_id' => 1,
                'image' => 'item-sales/October2025/trP6QVftX3EJL8ZHaxNYday08am.avif',
                'name' => 'Pechuga Broaster Economico',
                'price' => '13.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-07 20:27:56',
                'updated_at' => '2025-10-07 20:45:32',
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
                'category_id' => 1,
                'image' => 'item-sales/October2025/tZbMCJ72lgVJ42T3NZ1Eday08am.avif',
                'name' => 'Pierna Broaster Econ.',
                'price' => '12.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-07 20:29:20',
                'updated_at' => '2025-10-09 13:54:19',
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
                'category_id' => 1,
                'image' => 'item-sales/October2025/sOX1T6VcWzUc4g7kf8kfday08am.avif',
                'name' => 'Entrepierna Broaster Econ.',
                'price' => '12.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-07 20:30:54',
                'updated_at' => '2025-10-09 13:54:34',
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
                'category_id' => 1,
                'image' => 'item-sales/October2025/Dfln0ov1xsUVI4YeS02Rday08am.avif',
                'name' => 'Pierna Spiedo Econ.',
                'price' => '12.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-07 20:32:18',
                'updated_at' => '2025-10-09 13:55:58',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'category_id' => 1,
                'image' => 'item-sales/October2025/G9BJEWxa4vPcUfR1VA5zday08am.avif',
                'name' => 'Pechuga Spiedo Econ.',
                'price' => '13.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-07 20:32:43',
                'updated_at' => '2025-10-09 13:56:16',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'category_id' => 1,
                'image' => 'item-sales/October2025/kvs2bS9g7j6o9bHrrTswday08am.avif',
                'name' => 'Ala Spiedo Econ.',
                'price' => '13.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-07 20:33:29',
                'updated_at' => '2025-10-09 13:56:49',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'category_id' => 1,
                'image' => 'item-sales/October2025/ciKTfqAWhRlkQi1IeeB6day08am.avif',
                'name' => 'Entrepierna Spiedo Econ.',
                'price' => '12.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-07 20:35:04',
                'updated_at' => '2025-10-09 13:54:06',
                'registerUser_id' => 1,
                'registerRole' => 'admin',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'category_id' => 2,
                'image' => 'item-sales/October2025/HCnrCaVrn6YfVF9VKizTday08pm.avif',
                'name' => 'Cola-Cola 3 Ltro',
                'price' => '17.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-08 12:50:09',
                'updated_at' => '2025-10-08 12:51:37',
                'registerUser_id' => 2,
                'registerRole' => 'administrador',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'category_id' => 2,
                'image' => 'item-sales/October2025/TBTFU0Tp2WPawtb9YzYEday08pm.avif',
                'name' => 'Coca-Cola 2 Ltro',
                'price' => '15.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-08 12:51:14',
                'updated_at' => '2025-10-08 12:51:24',
                'registerUser_id' => 2,
                'registerRole' => 'administrador',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'category_id' => 2,
                'image' => 'item-sales/October2025/6kNpQN0LtjoeJ4uF8W8Aday08pm.avif',
                'name' => 'Coca-Cola Personal',
                'price' => '3.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-08 12:53:38',
                'updated_at' => '2025-10-08 12:53:38',
                'registerUser_id' => 2,
                'registerRole' => 'administrador',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'category_id' => 2,
                'image' => 'item-sales/October2025/AndehlgGPu1TFeofmywaday08pm.avif',
                'name' => 'Coca-cola 1 Ltro',
                'price' => '7.00',
                'observation' => NULL,
                'typeSale' => 'Venta Sin Stock',
                'status' => 1,
                'created_at' => '2025-10-08 12:55:13',
                'updated_at' => '2025-10-08 12:55:13',
                'registerUser_id' => 2,
                'registerRole' => 'administrador',
                'deleted_at' => NULL,
                'deleteUser_id' => NULL,
                'deleteRole' => NULL,
                'deleteObservation' => NULL,
            ),
        ));
        
        
    }
}