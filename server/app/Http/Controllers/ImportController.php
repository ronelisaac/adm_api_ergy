<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Record;
use App\Area;
use App\Position;
use App\Designation;
use App\Feature;
use Illuminate\Support\Facades\Hash;
class ImportController extends Controller
{
    //
  public function persons(Request $request)
  {
    //
    $response = [];
    
    $req = json_decode($request->getContent(), true);
    $userId = Auth::id();
    
    //People
    // foreach ($req as $value) {
    //   $record = [
    //     'name' => $value['name'],
    //     'last_name' => $value['last_name'],
    //     'birth_date' => $value['birth_date'],
    //     'identity_id' => $value['identity_id'],
    //     'emails' => json_encode(array($value['email'])),
    //     'phones' => json_encode(array($value['phone'])),
    //     'address_line' => $value['address_line'],
    //     'locality' => $value['locality'],
    //     'district' => $value['district'],
    //     'country' => $value['country'],
    //     'postal_code' => $value['postal_code'],
    //     'created_by' => $userId,
    //     'updated_by' => $userId
    //   ];
    //   $params = [];
    //   $params['record'] = $record;
    //   $params['value'] = $value;
    //   $params['user_id'] = $userId;
    //   DB::transaction(function () use ($params) {
    //     $id = DB::table('people')->insertGetId($params['record']);
    //     $record = [];
    //     $record['record_id'] = $id;
    //     $record['form_code'] = 'F01';
    //     $record['form_title'] = 'Alta de persona';
    //     $record['name'] = 'people';
    //     $record['period'] = null;
    //     $record['due_date'] = null;
    //     $record['t1'] = null;
    //     $record['t2'] = $params['value']['name']." ".$params['value']['last_name'];
    //     $record['t3'] = $params['value']['identity_id'];
    //     $record['created_by'] = $params['user_id'];
    //     $record['updated_by'] = $params['user_id'];
    //     $record["created_at"] = Carbon::now()->toDateTimeString();
    //     $record["updated_at"] = Carbon::now()->toDateTimeString();
    //     DB::table('records')->insert($record);
    //   });
    // }
    
    // //Users
    // foreach ($req as $value) {
    //   $record = [
    //     'name' => $value['full_name'],
    //     'identity_id' => $value['identity_id'],
    //     'email' => $value['email'],
    //     'password' => Hash::make(substr($value['last_name'],0,4).'1234'),
    //     'administrator' => false,
    //     'created_by' => $userId,
    //     'updated_by' => $userId
    //   ];
    //   $params = [];
    //   $params['record'] = $record;
    //   $params['value'] = $value;
    //   $params['user_id'] = $userId;
    //   DB::transaction(function () use ($params) {
    //     $id = DB::table('users')->insertGetId($params['record']);
    //     $record = [];
    //     $record['record_id'] = $id;
    //     $record['form_code'] = 'F100';
    //     $record['form_title'] = 'Alta de usuario';
    //     $record['name'] = 'users';
    //     $record['period'] = null;
    //     $record['due_date'] = null;
    //     $record['t1'] = $params['value']['email'];
    //     $record['t2'] = $params['value']['name']." ".$params['value']['last_name'];
    //     $record['t3'] = null;
    //     $record['created_by'] = $params['user_id'];
    //     $record['updated_by'] = $params['user_id'];
    //     $record["created_at"] = Carbon::now()->toDateTimeString();
    //     $record["updated_at"] = Carbon::now()->toDateTimeString();
    //     DB::table('records')->insert($record);
    //   });
    // }
    // //Areas
    // foreach ($req as $value) {
    //   if($value['boss'] == "x") {
    //     $person = DB::table('people')->where('identity_id', $value['identity_id'])->first();
    //     $personId = $person->id;
    //     $params = [];
    //     $params['value'] = $value;
    //     $params['user_id'] = $userId;
    //     $params['person_id'] = $personId;
    //     DB::transaction(function () use ($params) {
    //       //$id = DB::table('areas')->insertGetId($params['record']);
    //       $area = Area::firstOrNew(
    //         ['name' => $params['value']['area']]
    //       );
    //       $area->person_id = $params['person_id'];
    //       $area->created_by = $params['user_id'];
    //       $area->updated_by = $params['user_id'];
    //       $area->save();

    //       $record = Record::firstOrNew(
    //         [
    //           'record_id' => $area->id,
    //           'name' => 'areas'
    //         ]
    //       );
    //       $record->form_code = 'F06';
    //       $record->form_title = 'Alta de area';
    //       $record->period = null;
    //       $record->due_date = null;
    //       $record->t1 = $params['value']['area'];
    //       $record->t2 = null;
    //       $record->t3 = null;
    //       $record->created_by = $params['user_id'];
    //       $record->updated_by = $params['user_id'];
    //       $record->save();

          
    //     });
    //   }
    // }

    // //Positions
    // foreach ($req as $value) {
    //   $area = DB::table('areas')->where('name', $value['area'])->first();
    //   $areaId = $area->id;
      
    //   $params = [];
      
    //   $params['value'] = $value;
    //   $params['user_id'] = $userId;
    //   $params['area_id'] = $areaId;
    //   DB::transaction(function () use ($params) {
    //     //$id = DB::table('positions')->insertGetId($params['record']);
    //     $position = Position::firstOrNew(
    //       [
    //         'name' => $params['value']['position'],
    //         'area_id' => $params['area_id']
    //       ]
    //     );
    //     $position->created_by = $params['user_id'];
    //     $position->updated_by = $params['user_id'];
    //     $position->save();

    //     $record = Record::firstOrNew(
    //       [
    //         'record_id' => $position->id,
    //         'name' => 'positions'
    //       ]
    //     );
    //     $record->form_code = 'F07';
    //     $record->form_title = 'Alta de cargo';
    //     $record->period = null;
    //     $record->due_date = null;
    //     $record->t1 = $params['value']['position'];
    //     $record->t2 = $params['value']['area'];
    //     $record->t3 = null;
    //     $record->created_by = $params['user_id'];
    //     $record->updated_by = $params['user_id'];
    //     $record->save();
        
        
    //   });
      
    // }

    // //Designation
    // foreach ($req as $value) {
      
      
    //   $params = [];
    //   $params['value'] = $value;
    //   $params['user_id'] = $userId;
      
    //   DB::transaction(function () use ($params) {
    //     $area = DB::table('areas')->where('name', $params['value']['area'])->first();
    //     $areaId = $area->id;

    //     $position = DB::table('positions')->where([
    //       ['name', '=', $params['value']['position']], 
    //       ['area_id', '=', $areaId]
    //     ])->first();
    //     $positionId = $position->id;

    //     $person = DB::table('people')->where('identity_id', $params['value']['identity_id'])->first();
    //     $personId = $person->id;
        
        
    //     switch ($params['value']['c1']) {
    //       case '1':
    //         $c1 = "1 - Cumplimiento mínimo";
    //         break;
    //       case '2':
    //         $c1 = "2 - Quiere hacer bien su trabajo";
    //         break;
    //       case '3':
    //         $c1 = "3 - Mejora el rendimiento";
    //         break;
    //       case '4':
    //         $c1 = "4 - Asume riesgos calculados";
    //         break;
    //       default:
    //         $c1 = null;
    //         break;
    //     }
    //     switch ($params['value']['c2']) {
    //       case '1':
    //         $c2 = "1 - Presta servicio mínimo";
    //         break;
    //       case '2':
    //         $c2 = "2 - Mantiene clara comunicación con el cliente";
    //         break;
    //       case '3':
    //         $c2 = "3 - Disponibilidad para el cliente";
    //         break;
    //       case '4':
    //         $c2 = "4 - Actúa como un consejero de confianza";
    //         break;
    //       default:
    //         $c2 = null;
    //         break;
    //     }
    //     switch ($params['value']['c3']) {
    //       case '1':
    //         $c3 = "1 - Siempre sigue los procedimientos";
    //         break;
    //       case '2':
    //         $c3 = "2 - Tiene flexibilidad para aplicar reglamentos";
    //         break;
    //       case '3':
    //         $c3 = "3 - Adapta sus propias estrategias, metas o proyectos a las situaciones";
    //         break;
    //       case '4':
    //         $c3 = "4 - Adapta estrategias";
    //         break;
    //       default:
    //         $c3 = null;
    //         break;
    //     }
    //     switch ($params['value']['c4']) {
    //       case '1':
    //         $c4 = "1 - Esfuerzo mínimo";
    //         break;
    //       case '2':
    //         $c4 = "2 - Esfuerzo activo";
    //         break;
    //       case '3':
    //         $c4 = "3 - Sentido del propósito";
    //         break;
    //       case '4':
    //         $c4 = "4 - Prioriza las metas de la organización";
    //         break;
    //       default:
    //         $c4 = null;
    //         break;
    //     }
    //     switch ($params['value']['c5']) {
    //       case '1':
    //         $c5 = "1 - Es neutro";
    //         break;
    //       case '2':
    //         $c5 = "2 - Su conducta es consistente con sus valores";
    //         break;
    //       case '3':
    //         $c5 = "3 - Es honesto ante los demás";
    //         break;
    //       case '4':
    //         $c5 = "4 - Demanda honestidad en los demás";
    //         break;
    //       default:
    //         $c5 = null;
    //         break;
    //     }
    //     switch ($params['value']['c6']) {
    //       case '1':
    //         $c6 = "1 - Requiere supervisión";
    //         break;
    //       case '2':
    //         $c6 = "2 - Trabaja en forma independiente";
    //         break;
    //       case '3':
    //         $c6 = "3 - Es decidido frente a una crisis";
    //         break;
    //       case '4':
    //         $c6 = "4 - Se anticipa";
    //         break;
    //       default:
    //         $c6 = null;
    //         break;
    //     }
    //     switch ($params['value']['c7']) {
    //       case '1':
    //         $c7 = "1 - Neutra";
    //         break;
    //       case '2':
    //         $c7 = "2 - Coopera";
    //         break;
    //       case '3':
    //         $c7 = "3 - Comparte información";
    //         break;
    //       case '4':
    //         $c7 = "4 - Reconoce y demuestra confianza";
    //         break;
    //       default:
    //         $c7 = null;
    //         break;
    //     }
    //     switch ($params['value']['c8']) {
    //       case '1':
    //         $c8 = "1 - Se resiste a aprender";
    //         break;
    //       case '2':
    //         $c8 = "2 - Se interesa por aprender";
    //         break;
    //       case '3':
    //         $c8 = "3 - Aplica los conocimientos adquiridos";
    //         break;
    //       case '4':
    //         $c8 = "4 - Transfiere los conocimientos a su entorno";
    //         break;
    //       default:
    //         $c8 = null;
    //         break;
    //     }

    //     $designation = Designation::firstOrNew(
    //       [
    //         'person_id' => $personId,
    //         'position_id' => $positionId
    //       ]
    //     );
    //     $designation->competence_1 = $c1;
    //     $designation->competence_2 = $c2;
    //     $designation->competence_3 = $c3;
    //     $designation->competence_4 = $c4;
    //     $designation->competence_5 = $c5;
    //     $designation->competence_6 = $c6;
    //     $designation->competence_7 = $c7;
    //     $designation->competence_8 = $c8;
    //     $designation->objetive = $params['value']['objetive'];
        
    //     if($params['value']['evaluator'] != '') {
    //       $designation->user_id = intval($params['value']['evaluator']) + 1;
    //     }
        
    //     $designation->created_by = $params['user_id'];
    //     $designation->updated_by = $params['user_id'];
    //     $designation->save();

    //     $record = Record::firstOrNew(
    //       [
    //         'record_id' => $designation->id,
    //         'name' => 'designations'
    //       ]
    //     );
    //     $record->form_code = 'F02';
    //     $record->form_title = 'Alta de designación';
    //     $record->period = null;
    //     $record->due_date = null;
    //     $record->t1 = $params['value']['position']." - ".$params['value']['area'];
    //     $record->t2 = $params['value']['name']." ".$params['value']['last_name'];
    //     $record->t3 = null;
    //     $record->created_by = $params['user_id'];
    //     $record->updated_by = $params['user_id'];
    //     $record->save();

    //     if(trim($params['value']['features']) !== '') {
          
    //       $features = explode("*", $params['value']['features']);
    //       $f = [];
    //       foreach ($features as $item) {
    //         $item = trim($item);
    //         if($item !== '') {
    //           $feature = Feature::firstOrNew(
    //             ['name' => $item]
    //           );
    //           $feature->created_by = $params['user_id'];
    //           $feature->updated_by = $params['user_id'];
    //           $feature->save();

    //           $f[] = [
    //             'feature_id' => $feature->id,
    //             'designation_id' => $designation->id,
    //             'created_by' => $params['user_id'],
    //             'updated_by' => $params['user_id']
    //           ];

    //           $record = Record::firstOrNew(
    //             [
    //               'record_id' => $feature->id,
    //               'name' => 'features'
    //             ]
    //           );

    //           $record->form_code = 'F04';
    //           $record->form_title = 'Alta de función';
    //           $record->period = null;
    //           $record->due_date = null;
    //           //$record->t1 = substr($item,0,20)."...";
    //           $record->t1 = $item;
    //           $record->t2 = null;
    //           $record->t3 = null;
    //           $record->created_by = $params['user_id'];
    //           $record->updated_by = $params['user_id'];
    //           $record->save();
    //         }
    //       }
    //       DB::table('designation_feature')->insert($f);
    //     }
        
    //   });
      
    // }
    
    
    
    
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function bankStatement(Request $request)
  {
    //
    $response = [];
    
    $req = json_decode($request->getContent(), true);
    $userId = Auth::id();
    
    //People
    // foreach ($req as $value) {
    //   $record = [
    //     'name' => $value['name'],
    //     'last_name' => $value['last_name'],
    //     'birth_date' => $value['birth_date'],
    //     'identity_id' => $value['identity_id'],
    //     'emails' => json_encode(array($value['email'])),
    //     'phones' => json_encode(array($value['phone'])),
    //     'address_line' => $value['address_line'],
    //     'locality' => $value['locality'],
    //     'district' => $value['district'],
    //     'country' => $value['country'],
    //     'postal_code' => $value['postal_code'],
    //     'created_by' => $userId,
    //     'updated_by' => $userId
    //   ];
    //   $params = [];
    //   $params['record'] = $record;
    //   $params['value'] = $value;
    //   $params['user_id'] = $userId;
    //   DB::transaction(function () use ($params) {
    //     $id = DB::table('people')->insertGetId($params['record']);
    //     $record = [];
    //     $record['record_id'] = $id;
    //     $record['form_code'] = 'F01';
    //     $record['form_title'] = 'Alta de persona';
    //     $record['name'] = 'people';
    //     $record['period'] = null;
    //     $record['due_date'] = null;
    //     $record['t1'] = null;
    //     $record['t2'] = $params['value']['name']." ".$params['value']['last_name'];
    //     $record['t3'] = $params['value']['identity_id'];
    //     $record['created_by'] = $params['user_id'];
    //     $record['updated_by'] = $params['user_id'];
    //     $record["created_at"] = Carbon::now()->toDateTimeString();
    //     $record["updated_at"] = Carbon::now()->toDateTimeString();
    //     DB::table('records')->insert($record);
    //   });
    // }
    
    // //Users
    // foreach ($req as $value) {
    //   $record = [
    //     'name' => $value['full_name'],
    //     'identity_id' => $value['identity_id'],
    //     'email' => $value['email'],
    //     'password' => Hash::make(substr($value['last_name'],0,4).'1234'),
    //     'administrator' => false,
    //     'created_by' => $userId,
    //     'updated_by' => $userId
    //   ];
    //   $params = [];
    //   $params['record'] = $record;
    //   $params['value'] = $value;
    //   $params['user_id'] = $userId;
    //   DB::transaction(function () use ($params) {
    //     $id = DB::table('users')->insertGetId($params['record']);
    //     $record = [];
    //     $record['record_id'] = $id;
    //     $record['form_code'] = 'F100';
    //     $record['form_title'] = 'Alta de usuario';
    //     $record['name'] = 'users';
    //     $record['period'] = null;
    //     $record['due_date'] = null;
    //     $record['t1'] = $params['value']['email'];
    //     $record['t2'] = $params['value']['name']." ".$params['value']['last_name'];
    //     $record['t3'] = null;
    //     $record['created_by'] = $params['user_id'];
    //     $record['updated_by'] = $params['user_id'];
    //     $record["created_at"] = Carbon::now()->toDateTimeString();
    //     $record["updated_at"] = Carbon::now()->toDateTimeString();
    //     DB::table('records')->insert($record);
    //   });
    // }
    // //Areas
    // foreach ($req as $value) {
    //   if($value['boss'] == "x") {
    //     $person = DB::table('people')->where('identity_id', $value['identity_id'])->first();
    //     $personId = $person->id;
    //     $params = [];
    //     $params['value'] = $value;
    //     $params['user_id'] = $userId;
    //     $params['person_id'] = $personId;
    //     DB::transaction(function () use ($params) {
    //       //$id = DB::table('areas')->insertGetId($params['record']);
    //       $area = Area::firstOrNew(
    //         ['name' => $params['value']['area']]
    //       );
    //       $area->person_id = $params['person_id'];
    //       $area->created_by = $params['user_id'];
    //       $area->updated_by = $params['user_id'];
    //       $area->save();

    //       $record = Record::firstOrNew(
    //         [
    //           'record_id' => $area->id,
    //           'name' => 'areas'
    //         ]
    //       );
    //       $record->form_code = 'F06';
    //       $record->form_title = 'Alta de area';
    //       $record->period = null;
    //       $record->due_date = null;
    //       $record->t1 = $params['value']['area'];
    //       $record->t2 = null;
    //       $record->t3 = null;
    //       $record->created_by = $params['user_id'];
    //       $record->updated_by = $params['user_id'];
    //       $record->save();

          
    //     });
    //   }
    // }

    // //Positions
    // foreach ($req as $value) {
    //   $area = DB::table('areas')->where('name', $value['area'])->first();
    //   $areaId = $area->id;
      
    //   $params = [];
      
    //   $params['value'] = $value;
    //   $params['user_id'] = $userId;
    //   $params['area_id'] = $areaId;
    //   DB::transaction(function () use ($params) {
    //     //$id = DB::table('positions')->insertGetId($params['record']);
    //     $position = Position::firstOrNew(
    //       [
    //         'name' => $params['value']['position'],
    //         'area_id' => $params['area_id']
    //       ]
    //     );
    //     $position->created_by = $params['user_id'];
    //     $position->updated_by = $params['user_id'];
    //     $position->save();

    //     $record = Record::firstOrNew(
    //       [
    //         'record_id' => $position->id,
    //         'name' => 'positions'
    //       ]
    //     );
    //     $record->form_code = 'F07';
    //     $record->form_title = 'Alta de cargo';
    //     $record->period = null;
    //     $record->due_date = null;
    //     $record->t1 = $params['value']['position'];
    //     $record->t2 = $params['value']['area'];
    //     $record->t3 = null;
    //     $record->created_by = $params['user_id'];
    //     $record->updated_by = $params['user_id'];
    //     $record->save();
        
        
    //   });
      
    // }

    // //Designation
    // foreach ($req as $value) {
      
      
    //   $params = [];
    //   $params['value'] = $value;
    //   $params['user_id'] = $userId;
      
    //   DB::transaction(function () use ($params) {
    //     $area = DB::table('areas')->where('name', $params['value']['area'])->first();
    //     $areaId = $area->id;

    //     $position = DB::table('positions')->where([
    //       ['name', '=', $params['value']['position']], 
    //       ['area_id', '=', $areaId]
    //     ])->first();
    //     $positionId = $position->id;

    //     $person = DB::table('people')->where('identity_id', $params['value']['identity_id'])->first();
    //     $personId = $person->id;
        
        
    //     switch ($params['value']['c1']) {
    //       case '1':
    //         $c1 = "1 - Cumplimiento mínimo";
    //         break;
    //       case '2':
    //         $c1 = "2 - Quiere hacer bien su trabajo";
    //         break;
    //       case '3':
    //         $c1 = "3 - Mejora el rendimiento";
    //         break;
    //       case '4':
    //         $c1 = "4 - Asume riesgos calculados";
    //         break;
    //       default:
    //         $c1 = null;
    //         break;
    //     }
    //     switch ($params['value']['c2']) {
    //       case '1':
    //         $c2 = "1 - Presta servicio mínimo";
    //         break;
    //       case '2':
    //         $c2 = "2 - Mantiene clara comunicación con el cliente";
    //         break;
    //       case '3':
    //         $c2 = "3 - Disponibilidad para el cliente";
    //         break;
    //       case '4':
    //         $c2 = "4 - Actúa como un consejero de confianza";
    //         break;
    //       default:
    //         $c2 = null;
    //         break;
    //     }
    //     switch ($params['value']['c3']) {
    //       case '1':
    //         $c3 = "1 - Siempre sigue los procedimientos";
    //         break;
    //       case '2':
    //         $c3 = "2 - Tiene flexibilidad para aplicar reglamentos";
    //         break;
    //       case '3':
    //         $c3 = "3 - Adapta sus propias estrategias, metas o proyectos a las situaciones";
    //         break;
    //       case '4':
    //         $c3 = "4 - Adapta estrategias";
    //         break;
    //       default:
    //         $c3 = null;
    //         break;
    //     }
    //     switch ($params['value']['c4']) {
    //       case '1':
    //         $c4 = "1 - Esfuerzo mínimo";
    //         break;
    //       case '2':
    //         $c4 = "2 - Esfuerzo activo";
    //         break;
    //       case '3':
    //         $c4 = "3 - Sentido del propósito";
    //         break;
    //       case '4':
    //         $c4 = "4 - Prioriza las metas de la organización";
    //         break;
    //       default:
    //         $c4 = null;
    //         break;
    //     }
    //     switch ($params['value']['c5']) {
    //       case '1':
    //         $c5 = "1 - Es neutro";
    //         break;
    //       case '2':
    //         $c5 = "2 - Su conducta es consistente con sus valores";
    //         break;
    //       case '3':
    //         $c5 = "3 - Es honesto ante los demás";
    //         break;
    //       case '4':
    //         $c5 = "4 - Demanda honestidad en los demás";
    //         break;
    //       default:
    //         $c5 = null;
    //         break;
    //     }
    //     switch ($params['value']['c6']) {
    //       case '1':
    //         $c6 = "1 - Requiere supervisión";
    //         break;
    //       case '2':
    //         $c6 = "2 - Trabaja en forma independiente";
    //         break;
    //       case '3':
    //         $c6 = "3 - Es decidido frente a una crisis";
    //         break;
    //       case '4':
    //         $c6 = "4 - Se anticipa";
    //         break;
    //       default:
    //         $c6 = null;
    //         break;
    //     }
    //     switch ($params['value']['c7']) {
    //       case '1':
    //         $c7 = "1 - Neutra";
    //         break;
    //       case '2':
    //         $c7 = "2 - Coopera";
    //         break;
    //       case '3':
    //         $c7 = "3 - Comparte información";
    //         break;
    //       case '4':
    //         $c7 = "4 - Reconoce y demuestra confianza";
    //         break;
    //       default:
    //         $c7 = null;
    //         break;
    //     }
    //     switch ($params['value']['c8']) {
    //       case '1':
    //         $c8 = "1 - Se resiste a aprender";
    //         break;
    //       case '2':
    //         $c8 = "2 - Se interesa por aprender";
    //         break;
    //       case '3':
    //         $c8 = "3 - Aplica los conocimientos adquiridos";
    //         break;
    //       case '4':
    //         $c8 = "4 - Transfiere los conocimientos a su entorno";
    //         break;
    //       default:
    //         $c8 = null;
    //         break;
    //     }

    //     $designation = Designation::firstOrNew(
    //       [
    //         'person_id' => $personId,
    //         'position_id' => $positionId
    //       ]
    //     );
    //     $designation->competence_1 = $c1;
    //     $designation->competence_2 = $c2;
    //     $designation->competence_3 = $c3;
    //     $designation->competence_4 = $c4;
    //     $designation->competence_5 = $c5;
    //     $designation->competence_6 = $c6;
    //     $designation->competence_7 = $c7;
    //     $designation->competence_8 = $c8;
    //     $designation->objetive = $params['value']['objetive'];
        
    //     if($params['value']['evaluator'] != '') {
    //       $designation->user_id = intval($params['value']['evaluator']) + 1;
    //     }
        
    //     $designation->created_by = $params['user_id'];
    //     $designation->updated_by = $params['user_id'];
    //     $designation->save();

    //     $record = Record::firstOrNew(
    //       [
    //         'record_id' => $designation->id,
    //         'name' => 'designations'
    //       ]
    //     );
    //     $record->form_code = 'F02';
    //     $record->form_title = 'Alta de designación';
    //     $record->period = null;
    //     $record->due_date = null;
    //     $record->t1 = $params['value']['position']." - ".$params['value']['area'];
    //     $record->t2 = $params['value']['name']." ".$params['value']['last_name'];
    //     $record->t3 = null;
    //     $record->created_by = $params['user_id'];
    //     $record->updated_by = $params['user_id'];
    //     $record->save();

    //     if(trim($params['value']['features']) !== '') {
          
    //       $features = explode("*", $params['value']['features']);
    //       $f = [];
    //       foreach ($features as $item) {
    //         $item = trim($item);
    //         if($item !== '') {
    //           $feature = Feature::firstOrNew(
    //             ['name' => $item]
    //           );
    //           $feature->created_by = $params['user_id'];
    //           $feature->updated_by = $params['user_id'];
    //           $feature->save();

    //           $f[] = [
    //             'feature_id' => $feature->id,
    //             'designation_id' => $designation->id,
    //             'created_by' => $params['user_id'],
    //             'updated_by' => $params['user_id']
    //           ];

    //           $record = Record::firstOrNew(
    //             [
    //               'record_id' => $feature->id,
    //               'name' => 'features'
    //             ]
    //           );

    //           $record->form_code = 'F04';
    //           $record->form_title = 'Alta de función';
    //           $record->period = null;
    //           $record->due_date = null;
    //           //$record->t1 = substr($item,0,20)."...";
    //           $record->t1 = $item;
    //           $record->t2 = null;
    //           $record->t3 = null;
    //           $record->created_by = $params['user_id'];
    //           $record->updated_by = $params['user_id'];
    //           $record->save();
    //         }
    //       }
    //       DB::table('designation_feature')->insert($f);
    //     }
        
    //   });
      
    // }
    
    
    
    
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
}