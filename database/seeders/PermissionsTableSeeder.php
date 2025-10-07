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

        // Permission::generateFor('posts');
        // Permission::generateFor('pages');

        $permissions = [
            'browse_vaults'=>'Ver bovedas',
            'add_vaults'=>'Agregar bovedas',
            'open_vaults'=> 'Abrir bovedas',
            'movements_vaults' => 'Movimientos en bovedas',
            'close_vaults' => 'Cerrar bovedas',
            'print_vaults' => 'Imprimir reporte de bovedas',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'vaults',
                'tableDescription'=>'Bovedas'
            ]);
        }

        $permissions = [
            'browse_cashiers'=> 'Ver cajeros',
            'add_cashiers'=> 'Agregar cajeros',
            'read_cashiers'=> 'Ver detalle de cajeros',
            // 'open_cashiers',
            // 'movements_cashiers',
            // 'close_vaults',
            // 'print_vaults',
            
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'cashiers',
                'tableDescription'=>'Cajeros'
            ]);
        }


        //##################### Ventas  ##########################

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



        //############################## Egresos del almacen ############################
        $permissions = [
            'browse_egres_inventories' => 'Ver lista de egreso del almacén',
            'read_egres_inventories' => 'Ver detalles de egreso del almacén',
            'edit_egres_inventories' => 'Editar información del egreso del almacén',
            'add_egres_inventories' => 'Agregar nuevo egreso del almacén',
            'delete_egres_inventories' => 'Eliminar egreso del almacén',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'egres_inventories',
                'tableDescription'=>'Egreso de Almacén'
            ]);
        }
        

        //#################################### Administracion #################################
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


        //############################## Parametros  de ventas ###########################

        $permissions = [
            'browse_categories' => 'Ver lista de categories de items de venta',
            'read_categories' => 'Ver detalles de categories de items de venta',
            'edit_categories' => 'Editar información de categories de items de venta',
            'add_categories' => 'Agregar nuevos categories de items de venta',
            'delete_categories' => 'Eliminar categories de items de venta',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'categories',
                'tableDescription'=>'Categorias Items de Ventas'
            ]);
        }

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

        $permissions = [
            'browse_item_inventories' => 'Ver lista de items del almacén',
            'read_item_inventories' => 'Ver detalles de items del almacén',
            'edit_item_inventories' => 'Editar información de items del almacén',
            'add_item_inventories' => 'Agregar nuevas items del almacén',
            'delete_item_inventories' => 'Eliminar items del almacén',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'item_inventories',
                'tableDescription'=>'Items del almacén'
            ]);
        }


        //############################# REPORT SALES #####################################
        $permissions = [
            'browse_reportsales' => 'Reportes de ventas',
            'browse_reportsales-stock' => 'Reportes de stock disponible',
            'browse_reportsales-income' => 'Reportes de ingresos de items',
        ];
        

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'report_sales',
                'tableDescription'=>'Reportes de Ventas'
            ]);
        }



        //############################# REPORT INVENTARIO ALMACEN #####################################
        $permissions = [
            'browse_reportinventories-egres' => 'Reportes de salidas de items',
            'browse_reportinventories-stock' => 'Reportes de stock disponible',
            'browse_reportinventories-income' => 'Reportes de ingresos de items',
        ];

        foreach ($permissions as $key => $description) {
            Permission::firstOrCreate([
                'key'        => $key,
                'keyDescription'=> $description,
                'table_name' => 'report_inventories',
                'tableDescription'=>'Reportes de Almacen'
            ]);
        }



        


        
        
    }
}