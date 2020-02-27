<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 
        //DB::table('permission_role')->truncate();     
        $roles_permissions = [];     
        # Cambiar i por la cantidad de permisos que tengamos     
        for ($i=1; $i <= 108; $i++) {
          $roles_permissions[] = [ 'role_id' => '1', 'permission_id' => $i];     
        }     
        DB::table('permission_role')->insert($roles_permissions);
    }
}
