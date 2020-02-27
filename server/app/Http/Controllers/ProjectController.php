<?php
namespace App\Http\Controllers;
use App\Project;
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
class ProjectController extends Controller
{
  #variable que almacena el nombre del formulario
  private $form_name;
  #Constructor
  function __construct(){
    $this->form_name ='projects';
  }
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('project-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Project::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('description', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('observations', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('liaison_full_name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('liaison_phone', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('liaison_email', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('amount_budgeted', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('advance', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('balance', 'LIKE', "%".$params["search"]["value"]."%");
      } else {
        switch ($funnel["search"]["column"]) {
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
      if(strlen($record->description) > 200){ $record->description = mb_substr($record->description, 0, 200, "utf-8").' ...'; }else{ $record->description = $record->description; }
      if(strlen($record->observations) > 200){ $record->observations = mb_substr($record->observations, 0, 200, "utf-8").' ...'; }else{ $record->observations = $record->observations; }
      if(strlen($record->balance) > 200){ $record->balance = mb_substr($record->balance, 0, 200, "utf-8").' ...'; }else{ $record->balance = $record->balance; }
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
          'label' => 'Nombre del proyecto',
          'model' => 'name',
          'value' => $record->name,
          'type' => 'string',
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
          'label' => 'Observaciones',
          'model' => 'observations',
          'value' => $record->observations,
          'type' => 'longText',
          'classes' => ''
        ),
        array(
          'label' => 'Nombre y apellido',
          'model' => 'liaison_full_name',
          'value' => $record->liaison_full_name,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Teléfono',
          'model' => 'liaison_phone',
          'value' => $record->liaison_phone,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Email',
          'model' => 'liaison_email',
          'value' => $record->liaison_email,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Importe presupuestado',
          'model' => 'amount_budgeted',
          'value' => $record->amount_budgeted,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Anticipo',
          'model' => 'advance',
          'value' => $record->advance,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Saldo',
          'model' => 'balance',
          'value' => $record->balance,
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
          'label' => 'Nombre del proyecto',
          'model' => 'name',
          'value' => NULL,
          'type' => 'string',
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
          'label' => 'Observaciones',
          'model' => 'observations',
          'value' => NULL,
          'type' => 'longText',
          'classes' => ''
        ),
        array(
          'label' => 'Nombre y apellido',
          'model' => 'liaison_full_name',
          'value' => NULL,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Teléfono',
          'model' => 'liaison_phone',
          'value' => NULL,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Email',
          'model' => 'liaison_email',
          'value' => NULL,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Importe presupuestado',
          'model' => 'amount_budgeted',
          'value' => NULL,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Anticipo',
          'model' => 'advance',
          'value' => NULL,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Saldo',
          'model' => 'balance',
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
    if (!Gate::allows('project-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    #inicialice objet Project
    $objProject=new Project();
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
    #funcion que esta en el método projects y se encarga de
    #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
    $array_validator=$objProject->validationsReturns($arrayFields,'store',false);
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
    
    $record['search_field'] = (null !== $request->input('name') ? $request->input('name') : "");
    
    # name
    $record['name'] = $request->input('name');
    # End name
    
    # assignment
    $obj = $request->input('users');
    $users = isset($obj) ? $obj : [];
    $params['users'] = $users;
    # End assignment
    # User type
    $params['user_type'] = 'Cliente';
    # End user type
    
    # description
    $record['description'] = $request->input('description');
    # End description
    
    
    # observations
    $record['observations'] = $request->input('observations');
    # End observations
    
    
    # liaison_full_name
    $record['liaison_full_name'] = $request->input('liaison_full_name');
    # End liaison_full_name
    
    
    # liaison_phone
    $record['liaison_phone'] = $request->input('liaison_phone');
    # End liaison_phone
    
    
    # liaison_email
    $record['liaison_email'] = $request->input('liaison_email');
    # End liaison_email
    
    
    # amount_budgeted
    $record['amount_budgeted'] = $request->input('amount_budgeted');
    # End amount_budgeted
    
    
    # advance
    $record['advance'] = $request->input('advance');
    # End advance
    
    
    # balance
    $record['balance'] = $request->input('balance');
    # End balance
    
    $record['bank_transfer'] = $request->input('bank_transfer');
    $record['bank_deposit'] = $request->input('bank_deposit');
    $record['cash'] = $request->input('cash');
    $record['check'] = $request->input('check');

    $record['created_by'] = $userId;
    $record['updated_by'] = $userId;
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (null !== $request->input('name') ? $request->input('name') : "");
    $params['t2'] = '';
    $params['t3'] = '';
    DB::transaction(function () use ($params) {
      $id = DB::table('projects')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F05';
      $record['form_title'] = 'Alta de proyecto';
      $record['name'] = 'projects';
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
      $users = [];
      foreach ($params['users'] as $value) {
        $users[] = [
          'user_id' => $value['id'],
          'project_id' => $id,
          'percentage' => $value['percentage'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('project_user')->insert($users);
      #guarda los datos del usuario en usertype siempre y cuando no este ya registrado
      if(isset($params['user_type'])){
        if($params['user_type'] != false || $params['user_type'] != ""){
          foreach ($params['users'] as $value) {
            #verifica si el usuario esta en la tabla user_type con el tipo especificado
            $result=DB::table('user_types')->where("user_id","=",$value['id'])->where("type","=",$params['user_type'])->first();
            if(!isset($result->id)){
              $user_types = [];
              $user_types['user_id'] = $value['id'];
              $user_types['type'] = $params['user_type'];
              $user_types['created_by'] = $params['user_id'];
              $user_types['updated_by'] = $params['user_id'];
              $user_types["created_at"] = Carbon::now()->toDateTimeString();
              $user_types["updated_at"] = Carbon::now()->toDateTimeString();
              DB::table('user_types')->insert($user_types);
            }
          }
        }
      }
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function show(Project $project)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'projects'],
      ['record_id', '=', $project['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('project-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Project::find($project['id']);
    $record->users;
    foreach ($record->users as $user) {
      $user->percentage = $user->pivot->percentage;
    }
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, Project $project)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'projects'],
      ['record_id', '=', $project['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('project-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('projects')->select('blocked')->where('id', $project['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
   #inicialice objet Project
   $objProject=new Project();
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
   #funcion que esta en el método projects y se encarga de
   #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
   $array_validator=$objProject->validationsReturns($arrayFields,'update',$project['id']);
   $validated_data= Validator::make($request->all(),$array_validator);
   if($validated_data->fails()){
     #si hay algun elemento que no es valido, retorna un arreglo de errores
     $response['status'] = 'error';
     $response['success'] = false;
     $response['errors'] = $validated_data->errors();
     return response()->json($response, 412);
   }
   #fin validaciones
    $record['id'] = $project['id'];
    
    # date
    $record['date'] = $request->input('date');
    # End date
    
    $record['search_field'] = (null !== $request->input('name') ? $request->input('name') : "");
    
    # name
    $record['name'] = $request->input('name');
    # End name
    
    # assignment
    $obj = $request->input('users');
    $users = isset($obj) ? $obj : [];
    $params['users'] = $users;
    # End assignment
    # User type
    $params['user_type'] = 'Cliente';
    # End user type
    
    # description
    $record['description'] = $request->input('description');
    # End description
    
    
    # observations
    $record['observations'] = $request->input('observations');
    # End observations
    
    
    # liaison_full_name
    $record['liaison_full_name'] = $request->input('liaison_full_name');
    # End liaison_full_name
    
    
    # liaison_phone
    $record['liaison_phone'] = $request->input('liaison_phone');
    # End liaison_phone
    
    
    # liaison_email
    $record['liaison_email'] = $request->input('liaison_email');
    # End liaison_email
    
    
    # amount_budgeted
    $record['amount_budgeted'] = $request->input('amount_budgeted');
    # End amount_budgeted
    
    
    # advance
    $record['advance'] = $request->input('advance');
    # End advance
    
    
    # balance
    $record['balance'] = $request->input('balance');
    # End balance

    $record['bank_transfer'] = $request->input('bank_transfer');
    $record['bank_deposit'] = $request->input('bank_deposit');
    $record['cash'] = $request->input('cash');
    $record['check'] = $request->input('check');
    
    $record['updated_by'] = $userId;
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (null !== $request->input('name') ? $request->input('name') : "");
    $params['t2'] = '';
    $params['t3'] = '';
    DB::transaction(function () use ($params) {
      DB::table('projects')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F05';
      $record['form_title'] = 'Alta de proyecto';
      $record['name'] = 'projects';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['t1'];
      $record['t2'] = $params['t2'];
      $record['t3'] = $params['t3'];
      $record['updated_by'] = $params['user_id'];
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'projects']])->update($record);
      DB::table('project_user')->where('project_id', $params['record']['id'])->delete();
      $users = [];
      foreach ($params['users'] as $value) {
        $users[] = [
          'user_id' => $value['id'],
          'project_id' => $params['record']['id'],
          'percentage' => $value['percentage'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('project_user')->insert($users);
      #guarda los datos del usuario en usertype siempre y cuando no este ya registrado
      if(isset($params['user_type'])){
        if($params['user_type'] != false || $params['user_type'] != ""){
          foreach ($params['users'] as $value) {
            #verifica si el usuario esta en la tabla user_type con el tipo especificado
            $result=DB::table('user_types')->where("user_id","=",$value['id'])->where("type","=",$params['user_type'])->first();
            if(!isset($result->id)){
              $user_types = [];
              $user_types['user_id'] = $value['id'];
              $user_types['type'] = $params['user_type'];
              $user_types['created_by'] = $params['user_id'];
              $user_types['updated_by'] = $params['user_id'];
              $user_types["created_at"] = Carbon::now()->toDateTimeString();
              $user_types["updated_at"] = Carbon::now()->toDateTimeString();
              DB::table('user_types')->insert($user_types);
            }
          }
        }
      }
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(Project $project)
  {
    $userId = Auth::id();
    if (!Gate::allows('project-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('projects')->select('blocked')->where('id', $project['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $project;
    DB::transaction(function () use ($params) {
      Project::destroy($params['record']['id']);
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
     $records = Project::where($req)->get();
     $response['data'] = $records;
     return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = Project::where($filter)
      ->where('cancelled', '!=', true)
      ->get()
      ->map(function($record) {
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
      DB::table('projects')->where('id', $params['req']['id'])->update(['done' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'projects']])->update(['done' => $params['req']['value']]);
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
      DB::table('projects')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'projects']])->update(['cancelled' => $params['req']['value']]);
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
      DB::table('projects')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'projects']])->update(['blocked' => $params['req']['value']]);
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
      ['name', '=', 'projects'],
      ['record_id', '=', $id]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('project-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Project::find($id);
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
    if (!Gate::allows('project-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Project::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('description', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('observations', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('liaison_full_name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('liaison_phone', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('liaison_email', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('amount_budgeted', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('advance', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('balance', 'LIKE', "%".$params["search"]["value"]."%");
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
      $fields = array
      (
          $record->date,
          $record->name,
          $record->description,
          $record->observations,
          $record->liaison_full_name,
          $record->liaison_phone,
          $record->liaison_email,
          $record->amount_budgeted,
          $record->advance,
          $record->balance,
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
