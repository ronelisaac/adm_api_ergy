<?php
namespace App\Http\Controllers;
use App\Collection;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Gate;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\PDF;
use Excel;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertsEmail;
class CollectionController extends Controller
{
  #variable que almacena el nombre del formulario
  private $form_name;
  #Constructor
  function __construct(){
    $this->form_name ='collections';
  }
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('collection-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Collection::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('user', function ($query) use ($params) {
          $query->where('user_search_field', 'LIKE', "%".$params["search"]["value"]."%");
        });
        $records->orWhere('currency', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('description', 'LIKE', "%".$params["search"]["value"]."%");
      } else {
        switch ($funnel["search"]["column"]) {
          case 'users':
            # code...
            $records->whereHas('user', function ($query) use ($params) {
              $query->where('user_search_field', 'LIKE', "%".$params["search"]["value"]."%");
            });
            break;
          default:
            # code...
            $records->where($params["search"]["column"], 'LIKE', "%".$params["search"]["value"]."%");
            break;
        }
      }
    }
    if($limit != 0) {
      $records = $records->offset($offset)
      ->limit($limit);
    }
    if($funnel["sort"] == '') {
      $records = $records->orderBy('updated_at', 'desc');
    } else {
      $sort = explode('.', $funnel["sort"]);
      $records = $records->orderBy($sort[0], $sort[1]);
    }
    $records = $records->get()
    ->map(function ($record) {
      $states = [];
      if($record->blocked) $states[] = 'blocked';
      if($record->cancelled) $states[] = 'cancelled';
      if($record->done) $states[] = 'done';
      $user = [];
      $userString = [];
      $userData = Collection::find($record->id);
      if(isset($userData->user)) {
        $userString[0] = $userData['user_search_field'];
         $user[] =   implode(" ", $userString);
       } 
       $services = $record->services;
       $service = [];
       $serviceString = [];
       foreach ($services as $key => $value) {
        $serviceString[0] = $value->name;
         $service[] =   implode(" ", $serviceString);
       }
       $incoming_checks = $record->incoming_checks;
       $incoming_check = [];
       $incoming_checkString = [];
       foreach ($incoming_checks as $key => $value) {
        $incoming_checkString[0] = $value->check_search_field;
        $incoming_checkString[1] = $value->amount;
         $incoming_check[] =   implode(" ", $incoming_checkString);
       }
      if(strlen($record->description) > 200){ $record->description = mb_substr($record->description, 0, 200, "utf-8").' ...'; }else{ $record->description = $record->description; }
      $fields = array
      (
        array(
          'label' => 'ID',
          'model' => 'id',
          'value' => $record->id,
          'type' => 'integer',
          'classes' => 're-id'
        ),
        array(
          'label' => 'Fecha',
          'model' => 'date',
          'value' => $record->date,
          'type' => 'date',
          'classes' => ''
        ),
        array(
          'label' => 'Cliente',
          'model' => 'users',
          'value' => implode(', ', $user),
          'type' => 'autocomplete',
          'classes' => ''
        ),
        array(
          'label' => 'Moneda',
          'model' => 'currency',
          'value' => $record->currency,
          'type' => 'radio',
          'classes' => ''
        ),
        array(
          'label' => 'Contado',
          'model' => 'cash',
          'value' => $record->cash,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Tarjeta de débito',
          'model' => 'debit_card',
          'value' => $record->debit_card,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Tarjeta de crédito',
          'model' => 'credit_card',
          'value' => $record->credit_card,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Deposito bancario',
          'model' => 'bank_deposit',
          'value' => $record->bank_deposit,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Descripción',
          'model' => 'description',
          'value' => $record->description,
          'type' => 'longText',
          'classes' => ''
        ),
        array(
          'label' => 'Modificado el',
          'model' => 'updated_at',
          'value' => $record->updated_at,
          'type' => 'datetime',
          'classes' => ''
        ),
        array(
          'label' => 'Estados',
          'model' => 'states',
          'value' => json_encode($states),
          'type' => 'states',
          'classes' => 'states'
        )
      );
      return $fields;
    });
   if(count($records) == 0){
      $records[0] = array
      (
        array(
          'label' => 'ID',
          'model' => 'id',
          'value' => NULL,
          'type' => 'integer',
          'classes' => 're-id'
        ),
        array(
          'label' => 'Fecha',
          'model' => 'date',
          'value' => NULL,
          'type' => 'date',
          'classes' => ''
        ),
        array(
          'label' => 'Cliente',
          'model' => 'users',
          'value' => 'NULL',
          'type' => 'autocomplete',
          'classes' => ''
        ),
        array(
          'label' => 'Moneda',
          'model' => 'currency',
          'value' => NULL,
          'type' => 'radio',
          'classes' => ''
        ),
        array(
          'label' => 'Contado',
          'model' => 'cash',
          'value' => NULL,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Tarjeta de débito',
          'model' => 'debit_card',
          'value' => NULL,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Tarjeta de crédito',
          'model' => 'credit_card',
          'value' => NULL,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Deposito bancario',
          'model' => 'bank_deposit',
          'value' => NULL,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Descripción',
          'model' => 'description',
          'value' => NULL,
          'type' => 'longText',
          'classes' => ''
        ),
        array(
          'label' => 'Modificado el',
          'model' => 'updated_at',
          'value' => NULL,
          'type' => 'datetime',
          'classes' => ''
        ),
        array(
          'label' => 'Estados',
          'model' => 'states',
          'value' => json_encode([]),
          'type' => 'states',
          'classes' => 'states'
        )
      );
     }
    $response['data'] = $records;
    return response()->json($response, 200);
  }
  public function store(Request $request)
  {
    $response = [];
    if (!Gate::allows('collection-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    #inicialice objet Collection
    $objCollection=new Collection();
    #Validations
    #read json setting whit form data in configuration setting.json 
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true);
    $title = $json['display_name'];
    $server = $json['server_url'];
    $indicators=array_get($json, 'indicators');
    $forms=array_get($json, 'forms');
    foreach($forms as $form){
      if($form['name']==$this->form_name){
        $arrayFields = $form['fields'];
      }
    }
    #funcion que esta en el método collections y se encarga de
    #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
    $array_validator=$objCollection->validationsReturns($arrayFields,'store',false);
    $validated_data= Validator::make($request->all(),$array_validator);
    if($validated_data->fails()){
      #si hay algun elemento que no es valido, retorna un arreglo de errores
      $response['status'] = 'error';
      $response['success'] = false;
      $response['errors'] = $validated_data->errors();
      return response()->json($response, 412);
    }
    #fin validaciones
    $userId = Auth::id();
    $record = [];
    $params = [];
    
    # date
    $record['date'] = $request->input('date');
    # End date
    
    
    # Autocomplete users
    $obj = $request->input('users');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['user_id'] = $body[0]['id'];
      $record['user_search_field'] = $body[0]['user_search_field'];
    }
    # End autocomplete users
    
    
    # Autocomplete services
    $obj = $request->input('services');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $services = isset($obj) ? $obj['body'] : [];
    $params['services'] = $services;
    # End autocomplete services
    
    
    # currency
    $record['currency'] = $request->input('currency');
    # End currency
    
    
    # cash
    $record['cash'] = $request->input('cash');
    # End cash
    
    
    # Autocomplete incoming_checks
    $obj = $request->input('incoming_checks');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $record['total_checks'] = $footer['total_checks'];
    $incoming_checks = isset($obj) ? $obj['body'] : [];
    $params['incoming_checks'] = $incoming_checks;
    # End autocomplete incoming_checks
    
    
    # debit_card
    $record['debit_card'] = $request->input('debit_card');
    # End debit_card
    
    
    # credit_card
    $record['credit_card'] = $request->input('credit_card');
    # End credit_card
    
    
    # bank_deposit
    $record['bank_deposit'] = $request->input('bank_deposit');
    # End bank_deposit
    
    
    # description
    $record['description'] = $request->input('description');
    # End description
    
    $record['created_by'] = $userId;
    $record['updated_by'] = $userId;
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (count($request->input('users')['body']) > 0 ? $request->input('users')['body'][0]['search_field'] : "");
    $params['t2'] = (null !== $request->input('date') ? $request->input('date') : "");
    $params['t3'] = '';
    DB::transaction(function () use ($params) {
      $id = DB::table('collections')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F08';
      $record['form_title'] = 'Alta de cobranza';
      $record['name'] = 'collections';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['t1'];
      $record['t2'] = $params['t2'];
      $record['t3'] = $params['t3'];
      $record['created_by'] = $params['user_id'];
      $record['updated_by'] = $params['user_id'];
      $record["created_at"] = Carbon::now()->toDateTimeString();
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->insert($record);
      $services = [];
      foreach ($params['services'] as $value) {
        $services[] = [
          'service_id' => $value['id'],
          'collection_id' => $id,
          'name' => $value['name'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
           DB::table('collection_service')->insert($services);
      $incoming_checks = [];
      foreach ($params['incoming_checks'] as $value) {
        $incoming_checks[] = [
          'incoming_check_id' => $value['id'],
          'collection_id' => $id,
          'check_search_field' => $value['check_search_field'],
          'amount' => $value['amount'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
           DB::table('collection_incoming_check')->insert($incoming_checks);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function show(Collection $collection)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'collections'],
      ['record_id', '=', $collection['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('collection-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Collection::find($collection['id']);
    $user = $record->user;
    $users = [];
    if(isset($record->user)) {
      $users['body'][0] = $record->user;
      $users['body'][0]['user_search_field'] = $record['user_search_field'];
    } else {
      $users['body'] = [];
    }
    $record->users = $users;
    $body = $record->services;
    foreach ($record->services as $service) {
      $service->name = $service->pivot->name;
    }
    $services = [];
    $services['body'] = $body;
    $services['footer'] = [];
    $body = $record->incoming_checks;
    foreach ($record->incoming_checks as $incoming_check) {
      $incoming_check->check_search_field = $incoming_check->pivot->check_search_field;
      $incoming_check->amount = $incoming_check->pivot->amount;
    }
    $incoming_checks = [];
    $incoming_checks['body'] = $body;
    $incoming_checks['footer'] = [];
    $incoming_checks['footer']['total_checks'] = $record->total_checks;
    if(!is_array ($record)) $record = $record->toArray();
    $record['services'] = $services;
    if(!is_array ($record)) $record = $record->toArray();
    $record['incoming_checks'] = $incoming_checks;
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, Collection $collection)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'collections'],
      ['record_id', '=', $collection['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('collection-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('collections')->select('blocked')->where('id', $collection['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
   #inicialice objet Collection
   $objCollection=new Collection();
   #Validations
   #read json setting whit form data in configuration setting.json 
   $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
   $json = json_decode(file_get_contents($path), true);
   $title = $json['display_name'];
   $server = $json['server_url'];
   $indicators=array_get($json, 'indicators');
   $forms=array_get($json, 'forms');
   foreach($forms as $form){
     if($form['name']==$this->form_name){
       $arrayFields = $form['fields'];
     }
   }
   #funcion que esta en el método collections y se encarga de
   #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
   $array_validator=$objCollection->validationsReturns($arrayFields,'update',$collection['id']);
   $validated_data= Validator::make($request->all(),$array_validator);
   if($validated_data->fails()){
     #si hay algun elemento que no es valido, retorna un arreglo de errores
     $response['status'] = 'error';
     $response['success'] = false;
     $response['errors'] = $validated_data->errors();
     return response()->json($response, 412);
   }
   #fin validaciones
    $record['id'] = $collection['id'];
    
    # date
    $record['date'] = $request->input('date');
    # End date
    
    
    # Autocomplete users
    $obj = $request->input('users');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['user_id'] = $body[0]['id'];
      $record['user_search_field'] = $body[0]['user_search_field'];
    }
    # End autocomplete users
    
    
    # Autocomplete services
    $obj = $request->input('services');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $services = isset($obj) ? $obj['body'] : [];
    $params['services'] = $services;
    # End autocomplete services
    
    
    # currency
    $record['currency'] = $request->input('currency');
    # End currency
    
    
    # cash
    $record['cash'] = $request->input('cash');
    # End cash
    
    
    # Autocomplete incoming_checks
    $obj = $request->input('incoming_checks');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $record['total_checks'] = $footer['total_checks'];
    $incoming_checks = isset($obj) ? $obj['body'] : [];
    $params['incoming_checks'] = $incoming_checks;
    # End autocomplete incoming_checks
    
    
    # debit_card
    $record['debit_card'] = $request->input('debit_card');
    # End debit_card
    
    
    # credit_card
    $record['credit_card'] = $request->input('credit_card');
    # End credit_card
    
    
    # bank_deposit
    $record['bank_deposit'] = $request->input('bank_deposit');
    # End bank_deposit
    
    
    # description
    $record['description'] = $request->input('description');
    # End description
    
    $record['updated_by'] = $userId;
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (count($request->input('users')['body']) > 0 ? $request->input('users')['body'][0]['search_field'] : "");
    $params['t2'] = (null !== $request->input('date') ? $request->input('date') : "");
    $params['t3'] = '';
    DB::transaction(function () use ($params) {
      DB::table('collections')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F08';
      $record['form_title'] = 'Alta de cobranza';
      $record['name'] = 'collections';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['t1'];
      $record['t2'] = $params['t2'];
      $record['t3'] = $params['t3'];
      $record['updated_by'] = $params['user_id'];
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'collections']])->update($record);
      DB::table('collection_service')->where('collection_id', $params['record']['id'])->delete();
      $services = [];
      foreach ($params['services'] as $value) {
        $services[] = [
          'service_id' => $value['id'],
          'collection_id' => $params['record']['id'],
          'name' => $value['name'],
          'updated_by' => $params['user_id'],
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('collection_service')->insert($services);
      DB::table('collection_incoming_check')->where('collection_id', $params['record']['id'])->delete();
      $incoming_checks = [];
      foreach ($params['incoming_checks'] as $value) {
        $incoming_checks[] = [
          'incoming_check_id' => $value['id'],
          'collection_id' => $params['record']['id'],
          'check_search_field' => $value['check_search_field'],
          'amount' => $value['amount'],
          'updated_by' => $params['user_id'],
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('collection_incoming_check')->insert($incoming_checks);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(Collection $collection)
  {
    $userId = Auth::id();
    if (!Gate::allows('collection-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('collections')->select('blocked')->where('id', $collection['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $collection;
    DB::transaction(function () use ($params) {
      Collection::destroy($params['record']['id']);
      Record::where('record_id', $params['record']['id'])->delete();
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function all(Request $request)
  {
     $response = [];
     $records = [];
     $req = json_decode($request->getContent(), true);
     $records = Collection::where($req)->get();
     $response['data'] = $records;
     return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = Collection::where($filter)
      ->where('cancelled', '!=', true)
      ->get()
      ->map(function($record) {
        $record->user;
        $record->service;
        $record->incoming_check;
        return $record;
      });
    return response()->json($records, 200);
  }
  public function dataCheckboxs($checkboxs) {
    $a = [];
    foreach ($checkboxs as $key => $value) {
      $a[] = $value['id'];
    }
    $b = [];
    if(count($checkboxs) > 0) {
      $n = $checkboxs[count($checkboxs) - 1]['id'];
      for ($i=0; $i <= $n; $i++) {
        if(in_array($i, $a)) {
          $b[] = true;
        } else {
          $b[] = null;
        }
      }
    }
    return $b;
  }
      
  public function do(Request $request)
  {
    $req = json_decode($request->getContent(), true);
    $params = [];
    $params['req'] = $req;
    DB::transaction(function () use ($params) {
      DB::table('collections')->where('id', $params['req']['id'])->update(['done' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'collections']])->update(['done' => $params['req']['value']]);
    });
    $response = [];
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function cancel(Request $request)
  {
    $req = json_decode($request->getContent(), true);
    $params = [];
    $params['req'] = $req;
    DB::transaction(function () use ($params) {
      DB::table('collections')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'collections']])->update(['cancelled' => $params['req']['value']]);
    });
    $response = [];
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function block(Request $request)
  {
    $req = json_decode($request->getContent(), true);
    $params = [];
    $params['req'] = $req;
    DB::transaction(function () use ($params) {
      DB::table('collections')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'collections']])->update(['blocked' => $params['req']['value']]);
    });
    $response = [];
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
  /**
   * Crea y descarga un archivo pdf, con los datos de un formulario.
   * Params: $id data
   * Return :json response con los datos de acceso al formulario
   */
  public function downloadPdf($id)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'collections'],
      ['record_id', '=', $id]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('collection-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Collection::find($id);
    $user = $record->user;
    $users = [];
    if(isset($record->user)) {
      $users['body'][0] = $record->user;
      $users['body'][0]['user_search_field'] = $record['user_search_field'];
    } else {
      $users['body'] = [];
    }
    $record->users = $users;
    $body = $record->services;
    foreach ($record->services as $service) {
      $service->name = $service->pivot->name;
    }
    $services = [];
    $services['body'] = $body;
    $services['footer'] = [];
    $body = $record->incoming_checks;
    foreach ($record->incoming_checks as $incoming_check) {
      $incoming_check->check_search_field = $incoming_check->pivot->check_search_field;
      $incoming_check->amount = $incoming_check->pivot->amount;
    }
    $incoming_checks = [];
    $incoming_checks['body'] = $body;
    $incoming_checks['footer'] = [];
    $incoming_checks['footer']['total_checks'] = $record->total_checks;
    if(!is_array ($record)) $record = $record->toArray();
    $record['services'] = $services;
    if(!is_array ($record)) $record = $record->toArray();
    $record['incoming_checks'] = $incoming_checks;
    $response['data'] = $record;
    #lee el json setting con los datos de configuracion de form
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true); 
    $styles=[];
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];
    $forms=array_get($json, 'forms');
    foreach($forms as $form){
      if($form['name']==$this->form_name){
        $form_code=$form['code'];
        $form_title=$form['title'];
        $data = $form['fields'];
      }
    }
    $pdf = \PDF::loadView('pdf', compact('data','form_name','form_title','form_code','record','styles'));
    $pdf->save(storage_path().'/pdf_files_tmp/'.Auth::user()->id.'.pdf');
    $b64Doc = base64_encode(file_get_contents(storage_path('/pdf_files_tmp/'.Auth::user()->id.'.pdf')));
    $response['form']=$id;
    $response['route']=$b64Doc;
    $response['success'] = true;
    $response['req'] = true;
    return response()->json($response, 200);
  }
  /**
   * Display a listing of the resource.
   * possible values for type:  xls, xlsx, csv
   * @return IlluminateHttpResponse
   */
  public function downloadExcel(Request $request)
  {
    $response = [];
    if (!Gate::allows('collection-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Collection::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('user', function ($query) use ($params) {
        });
        $records->orWhere('currency', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('description', 'LIKE', "%".$params["search"]["value"]."%");
      } else {
        $records->where($params["search"]["column"], 'LIKE', "%".$params["search"]["value"]."%");
      }
    }
    if($limit != 0) {
      $records = $records->offset($offset)
      ->limit($limit);
    }
    if($funnel["sort"] == '') {
      $records = $records->orderBy('updated_at', 'desc');
    } else {
      $sort = explode('.', $funnel["sort"]);
      $records = $records->orderBy($sort[0], $sort[1]);
    }
    $records = $records->get()
    ->map(function ($record) {
      $states = [];
      if($record->blocked) $states = 'Bloqueado';
      if($record->cancelled) $states = 'Cancelado';
      if($record->done) $states = 'Hecho';
      else $states = '';
      $user = [];
      $userString = [];
      $userData = Collection::find($record->id);
      if(isset($userData->user)) {
        $userString[0] = $userData['user_search_field'];
         $user[] =   implode(" ", $userString);
       } 
       $services = $record->services;
       $service = [];
       $serviceString = [];
       foreach ($services as $key => $value) {
        $serviceString[0] = $value->name;
         $service[] =   implode(" ", $serviceString);
       }
       $incoming_checks = $record->incoming_checks;
       $incoming_check = [];
       $incoming_checkString = [];
       foreach ($incoming_checks as $key => $value) {
        $incoming_checkString[0] = $value->check_search_field;
        $incoming_checkString[1] = $value->amount;
         $incoming_check[] =   implode(" ", $incoming_checkString);
       }
      $fields = array
      (
          $record->date,
          implode(', ', $user),
          $record->currency,
          $record->cash,
          $record->debit_card,
          $record->credit_card,
          $record->bank_deposit,
          $record->description,
          $record->created_at,
          $record->updated_at,
          $states
      );
      return $fields;
    });
    #lee el json setting con los datos de configuracion de form
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true);
    $forms=array_get($json, 'forms');
    $labels=[];
    foreach($forms as $form){
      if($form['name']==$this->form_name){
        $form_code=$form['code'];
        $form_title=$form['title'];
        foreach($form['fields']  as $field){
         if(isset($field['settings']['visible']) && $field['settings']['visible'] == false )  $visible=false;
         else $visible=true;
         if($visible != false)
          $labels[$field['label']]= $field['label'];
        }
      }
    }
    $labels['created_at']='Creado';
    $labels['update_at']='Actualizado';
    $data = $records->toArray();
    Excel::create(Auth::user()->id, function($excel) use ($data,$labels,$form_title) {
      $excel->sheet(mb_substr($form_title, 0, 30,'utf-8'), function($sheet) use ($data,$labels)
      {
        //$sheet->row(1, $labels);
        $sheet->fromArray($data, null, 'A1', false, false);
        $sheet->prependRow($labels);
      });
    })->store("xls", storage_path('xls_files_tmp'));
    $response = [];
    $response['records'] = $records;
    $b64Doc = base64_encode(file_get_contents(storage_path('/xls_files_tmp/'.Auth::user()->id.'.xls')));
    $response['form']=$this->form_name;
    $response['route']=$b64Doc;
    $response['success'] = true;
    $response['request'] = true;
    return response()->json($response, 200);
   }
  }
