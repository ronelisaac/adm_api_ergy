<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //
      //DB::table('roles')->truncate();
      DB::transaction(function () {  
        DB::table('roles')->insert([       
          [       
            'slug' => 'administrador',       
            'name' => 'Administrador',       
            'description'=> '...',
            'created_by'=> 1,
            'updated_by'=> 1
          ],
          [       
            'slug' => 'operador',       
            'name' => 'Operador',       
            'description'=> '...',
            'created_by'=> 1,
            'updated_by'=> 1
          ],
        ]);
        DB::table('records')->insert([       
          [       
            'record_id' => '1',  
            'form_code' => 'F99',       
            'form_title' => 'Alta de rol',       
            'name'=> 'roles',
            'period'=> null,
            'due_date'=> null,
            't1'=> 'Administrador',
            't2'=> null,
            't3'=> null,
            'created_by'=> 1,
            'updated_by'=> 1
          ],
          [       
            'record_id' => '2',  
            'form_code' => 'F99',       
            'form_title' => 'Alta de rol',       
            'name'=> 'roles',
            'period'=> null,
            'due_date'=> null,
            't1'=> 'Operador',
            't2'=> null,
            't3'=> null,
            'created_by'=> 1,
            'updated_by'=> 1
          ],
        ]);
      });
    }
}
