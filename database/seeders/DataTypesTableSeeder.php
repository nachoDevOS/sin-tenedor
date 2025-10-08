<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'users',
                'slug' => 'users',
                'display_name_singular' => 'User',
                'display_name_plural' => 'Users',
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController',
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"desc","default_search_key":null,"scope":null}',
                'created_at' => '2024-10-18 06:28:26',
                'updated_at' => '2025-04-07 08:18:35',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'menus',
                'slug' => 'menus',
                'display_name_singular' => 'Menu',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2024-10-18 06:28:26',
                'updated_at' => '2024-10-18 06:28:26',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'roles',
                'slug' => 'roles',
                'display_name_singular' => 'Role',
                'display_name_plural' => 'Roles',
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'policy_name' => NULL,
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2024-10-18 06:28:26',
                'updated_at' => '2024-10-18 06:28:26',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'posts',
                'slug' => 'posts',
                'display_name_singular' => 'Post',
                'display_name_plural' => 'Posts',
                'icon' => 'voyager-news',
                'model_name' => 'TCG\\Voyager\\Models\\Post',
                'policy_name' => 'TCG\\Voyager\\Policies\\PostPolicy',
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2024-10-18 06:28:45',
                'updated_at' => '2024-10-18 06:28:45',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'pages',
                'slug' => 'pages',
                'display_name_singular' => 'Page',
                'display_name_plural' => 'Pages',
                'icon' => 'voyager-file-text',
                'model_name' => 'TCG\\Voyager\\Models\\Page',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2024-10-18 06:28:45',
                'updated_at' => '2024-10-18 06:28:45',
            ),
            5 => 
            array (
                'id' => 8,
                'name' => 'people',
                'slug' => 'people',
                'display_name_singular' => 'Persona',
                'display_name_plural' => 'Personas',
                'icon' => 'fa-solid fa-person',
                'model_name' => 'App\\Models\\Person',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-04-07 01:43:00',
                'updated_at' => '2025-09-09 04:55:15',
            ),
            6 => 
            array (
                'id' => 10,
                'name' => 'categories',
                'slug' => 'categories',
                'display_name_singular' => 'Categoría',
                'display_name_plural' => 'Categorías',
                'icon' => 'fa-solid fa-layer-group',
                'model_name' => 'App\\Models\\Category',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-05-26 10:01:28',
                'updated_at' => '2025-05-26 10:07:36',
            ),
            7 => 
            array (
                'id' => 11,
                'name' => 'item_sales',
                'slug' => 'item-sales',
                'display_name_singular' => 'Productos en Venta',
                'display_name_plural' => 'Productos en Ventas',
                'icon' => 'voyager-bag',
                'model_name' => 'App\\Models\\ItemSale',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-05-26 10:20:09',
                'updated_at' => '2025-05-29 13:21:22',
            ),
            8 => 
            array (
                'id' => 12,
                'name' => 'category_inventories',
                'slug' => 'category-inventories',
                'display_name_singular' => 'Categorías del Almacén',
                'display_name_plural' => 'Categorías del Almacén',
                'icon' => 'fa-solid fa-layer-group',
                'model_name' => 'App\\Models\\CategoryInventory',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-05-29 09:34:21',
                'updated_at' => '2025-05-29 09:35:28',
            ),
            9 => 
            array (
                'id' => 13,
                'name' => 'unit_types',
                'slug' => 'unit-types',
                'display_name_singular' => 'Unidad de Medida',
                'display_name_plural' => 'Unidades de Medidas',
                'icon' => 'fa-solid fa-scale-balanced',
                'model_name' => 'App\\Models\\UnitType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2025-05-29 12:38:51',
                'updated_at' => '2025-05-29 12:38:51',
            ),
            10 => 
            array (
                'id' => 14,
                'name' => 'item_inventories',
                'slug' => 'item-inventories',
                'display_name_singular' => 'Producto del Almacén',
                'display_name_plural' => 'Productos del Almacén',
                'icon' => 'voyager-bag',
                'model_name' => 'App\\Models\\ItemInventory',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2025-05-29 13:21:40',
                'updated_at' => '2025-05-29 14:46:05',
            ),
        ));
        
        
    }
}