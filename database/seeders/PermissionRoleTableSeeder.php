<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('permission_role')->delete();
        
        // Root
        $role = Role::where('name', 'admin')->firstOrFail();
        $permissions = Permission::all();
        $role->permissions()->sync($permissions->pluck('id')->all());



        $role = Role::where('name', 'administrador')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or

                                            table_name = "vaults" or
                                            table_name = "cashiers" or
                                            table_name = "sales" or

                                            table_name = "egres_inventories" or

                                            table_name = "categories" or
                                            table_name = "item_sales" or

                                            table_name = "category_inventories" or
                                            table_name = "item_inventories" or
                                            

                                            table_name = "people" or
                                            table_name = "roles" or
                                            table_name = "users" or
                                            table_name = "settings" or

                                            table_name = "report_sales" or
                                            table_name = "report_inventories" or


                                            `key` = "browse_clear-cache"')
                                            ->get();
        $role->permissions()->sync($permissions->pluck('id')->all());


        $role = Role::where('name', 'cashier')->firstOrFail();
        $permissions = Permission::whereRaw('table_name = "admin" or

                                            table_name = "sales" or   
                                            table_name = "egres_inventories" or


                                            `key` = "browse_people" or
                                            `key` = "read_people" or
                                            `key` = "add_people" or
                                            `key` = "edit_people" or



                                            table_name = "report_sales" or
                                            table_name = "report_inventories" or


                                            `key` = "browse_clear-cache"')
                                            ->get();
        $role->permissions()->sync($permissions->pluck('id')->all());
    }
}
