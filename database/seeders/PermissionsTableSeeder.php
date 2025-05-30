<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        Permission::firstOrCreate([
            'key'        => 'browse_admin',
            'keyDescription'=>'vista de acceso al sistema',
            'table_name' => 'admin',
            'tableDescription'=>'Panel del Sistema'
        ]);

        $keys = [
            // 'browse_admin',
            'browse_bread',
            'browse_database',
            'browse_media',
            'browse_compass',
            'browse_clear-cache',
        ];

        foreach ($keys as $key) {
            Permission::firstOrCreate([
                'key'        => $key,
                'table_name' => null,
            ]);
        }

        Permission::generateFor('menus');

        Permission::generateFor('roles');
        Permission::generateFor('permissions');
        Permission::generateFor('settings');

        Permission::generateFor('users');

        Permission::generateFor('posts');
        Permission::generateFor('categories');
        Permission::generateFor('pages');


        // Ventas

        $permissions = [
            'browse_sales' => 'Ver lista de ventas',
            'read_sales' => 'Ver detalles de una venta',
            'edit_sales' => 'Editar información de ventas',
            'add_sales' => 'Agregar nuevas ventas',
            'delete_sales' => 'Eliminar ventas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'sales',
                'tableDescription'=>'Ventas'
            ]);
        }

        

        // Administracion
        $permissions = [
            'browse_people' => 'Ver lista de personas',
            'read_people' => 'Ver detalles de una persona',
            'edit_people' => 'Editar información de personas',
            'add_people' => 'Agregar nuevas personas',
            'delete_people' => 'Eliminar personas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'people',
                'tableDescription'=>'Personas'
            ]);
        }


        // Parametros
        $permissions = [
            'browse_item_sales' => 'Ver lista de productos en ventas',
            'read_item_sales' => 'Ver detalles de productos en ventas',
            'edit_item_sales' => 'Editar información de productos en ventas',
            'add_item_sales' => 'Agregar nuevos productos en ventas',
            'delete_item_sales' => 'Eliminar productos en ventas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'item_sales',
                'tableDescription'=>'Productos / Items en Venta'
            ]);
        }

     


        // ######################################           PARAMETROS DEL ALMACEN             ###################

        $permissions = [
            'browse_category_inventories' => 'Ver lista de categorías del almacén',
            'read_category_inventories' => 'Ver detalles de categorías del almacén',
            'edit_category_inventories' => 'Editar información de categorías del almacén',
            'add_category_inventories' => 'Agregar nuevas categorías del almacén',
            'delete_category_inventories' => 'Eliminar categorías del almacén',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'category_inventories',
                'tableDescription'=>'Categorías del Almacén'
            ]);
        }
        // Item del Almacen
        $permissions = [
            'browse_item_inventories' => 'Ver lista de productos del almacén',
            'read_item_inventories' => 'Ver detalles de productos del almacén',
            'edit_item_inventories' => 'Editar información de productos del almacén',
            'add_item_inventories' => 'Agregar nuevas productos del almacén',
            'delete_item_inventories' => 'Eliminar productos del almacén',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'item_inventories',
                'tableDescription'=>'Producto / Items del Almacén'
            ]);
        }


        
        
    }
}