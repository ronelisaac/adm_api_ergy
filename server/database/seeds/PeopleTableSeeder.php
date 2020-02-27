<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = array(
          array(
            'name' => 'Roberto',
            'last_name' => 'Carraro',
            'identity_id' => '30637070',
            'address_line' => 'Leguizamon 1106', 
            'locality' => 'Salta',
            'district' => 'Salta', 
            'country' => 'Argentina',
            'postal_code' => '4400',
            'created_by' => '1',
            'updated_by' => '1'
          ),
          array(
            'name' => 'Mercedes',
            'last_name' => 'Rodriguez',
            'identity_id' => '31556779',
            'address_line' => 'Una calle 1106', 
            'locality' => 'Salta',
            'district' => 'Salta', 
            'country' => 'Argentina',
            'postal_code' => '4400',
            'created_by' => '1',
            'updated_by' => '1'
          )
        );
        DB::table('people')->insert($data);
    }
}
