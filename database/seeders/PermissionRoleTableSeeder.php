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



        // $role = Role::where('name', 'sedeges_admin')->firstOrFail();
        // $permissions = Permission::whereRaw('table_name = "admin" or
        //                                     `key` = "browse_centro_categorias" or
        //                                     `key` = "read_centro_categorias" or

        //                                     `key` = "browse_egressdonor" or
        //                                     `key` = "read_egressdonor" or
        //                                     `key` = "edit_egressdonor" or
        //                                     `key` = "add_egressdonor" or

        //                                     table_name = "view_stock_donacion" or
        //                                     `key` = "browse_clear-cache"')->get();
        // $role->permissions()->sync($permissions->pluck('id')->all());
    }
}
