<?php
namespace App\Http\Controllers;
use App\Permission;
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
class PermissionController extends Controller
{
  #variable que almacena el nombre del formulario
  private $form_name;
  #Constructor
  function __construct(){
    $this->form_name ='permissions';
  }
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('permission-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Permission::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('permissions_group', function ($query) use ($params) {
          $query->where('name', 'LIKE', "%".$params["search"]["value"]."%");
        });
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('description', 'LIKE', "%".$params["search"]["value"]."%");
      } else {
        switch ($funnel["search"]["column"]) {
          case 'permissions_groups':
            # code...
            $records->whereHas('permissions_group', function ($query) use ($params) {
              $query->where('name', 'LIKE', "%".$params["search"]["value"]."%");
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
      $permissions_group = [];
      $permissions_groupString = [];
      $permissions_groupData = Permission::find($record->id);
      if(isset($permissions_groupData->permissions_group)) {
         $permissions_group[] =   implode(" ", $permissions_groupString);
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
          'label' => 'Gupo de permisos',
          'model' => 'permissions_groups',
          'value' => implode(', ', $permissions_group),
          'type' => 'autocomplete',
          'classes' => ''
        ),
        array(
          'label' => 'Nombre',
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
          'label' => 'Gupo de permisos',
          'model' => 'permissions_groups',
          'value' => 'NULL',
          'type' => 'autocomplete',
          'classes' => ''
        ),
        array(
          'label' => 'Nombre',
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
    if (!Gate::allows('permission-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    #inicialice objet Permission
    $objPermission=new Permission();
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
    #funcion que esta en el método permissions y se encarga de
    #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
    $array_validator=$objPermission->validationsReturns($arrayFields,'store',false);
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
    $record['slug'] = str_slug($request->input('name'));
    
    # Autocomplete permissions_groups
    $obj = $request->input('permissions_groups');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['permissions_group_id'] = $body[0]['id'];
    }
    # End autocomplete permissions_groups
    
    
    # name
    $record['name'] = $request->input('name');
    # End name
    
    
    # description
    $record['description'] = $request->input('description');
    # End description
    
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
      $id = DB::table('permissions')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F03';
      $record['form_title'] = 'Alta de permiso';
      $record['name'] = 'permissions';
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
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function show(Permission $permission)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'permissions'],
      ['record_id', '=', $permission['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('permission-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Permission::find($permission['id']);
    $permissionsGroup = $record->permissions_group;
    $permissionsGroups = [];
    if(isset($record->permissions_group)) {
      $permissionsGroups['body'][0] = $record->permissions_group;
    } else {
      $permissionsGroups['body'] = [];
    }
    $record->permissions_groups = $permissionsGroups;
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, Permission $permission)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'permissions'],
      ['record_id', '=', $permission['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('permission-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('permissions')->select('blocked')->where('id', $permission['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
   #inicialice objet Permission
   $objPermission=new Permission();
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
   #funcion que esta en el método permissions y se encarga de
   #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
   $array_validator=$objPermission->validationsReturns($arrayFields,'update',$permission['id']);
   $validated_data= Validator::make($request->all(),$array_validator);
   if($validated_data->fails()){
     #si hay algun elemento que no es valido, retorna un arreglo de errores
     $response['status'] = 'error';
     $response['success'] = false;
     $response['errors'] = $validated_data->errors();
     return response()->json($response, 412);
   }
   #fin validaciones
    $record['id'] = $permission['id'];
    $record['slug'] = str_slug($request->input('name'));
    
    # Autocomplete permissions_groups
    $obj = $request->input('permissions_groups');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['permissions_group_id'] = $body[0]['id'];
    }
    # End autocomplete permissions_groups
    
    
    # name
    $record['name'] = $request->input('name');
    # End name
    
    
    # description
    $record['description'] = $request->input('description');
    # End description
    
    $record['updated_by'] = $userId;
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (null !== $request->input('name') ? $request->input('name') : "");
    $params['t2'] = '';
    $params['t3'] = '';
    DB::transaction(function () use ($params) {
      DB::table('permissions')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F03';
      $record['form_title'] = 'Alta de permiso';
      $record['name'] = 'permissions';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['t1'];
      $record['t2'] = $params['t2'];
      $record['t3'] = $params['t3'];
      $record['updated_by'] = $params['user_id'];
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'permissions']])->update($record);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(Permission $permission)
  {
    $userId = Auth::id();
    if (!Gate::allows('permission-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('permissions')->select('blocked')->where('id', $permission['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $permission;
    DB::transaction(function () use ($params) {
      Permission::destroy($params['record']['id']);
      Record::where('record_id', $params['record']['id'])->delete();
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function all(Request $request)
  {
     $response = [];
     $records = [];
     $result = DB::table('permissions_groups')->get();
     foreach($result as $value){
      $records[$value->name] = Permission::where("permissions_group_id","=",$value->id)->get();
     }
     if(count($records) == 0) {
      $records =  (object) [];
     }
     $response['groups'] = $result;
     $response['data'] = $records;
     return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = Permission::where($filter)
      ->where('cancelled', '!=', true)
      ->get()
      ->map(function($record) {
        $record->permissions_group;
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
      DB::table('permissions')->where('id', $params['req']['id'])->update(['done' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'permissions']])->update(['done' => $params['req']['value']]);
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
      DB::table('permissions')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'permissions']])->update(['cancelled' => $params['req']['value']]);
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
      DB::table('permissions')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'permissions']])->update(['blocked' => $params['req']['value']]);
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
      ['name', '=', 'permissions'],
      ['record_id', '=', $id]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('permission-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Permission::find($id);
    $permissionsGroup = $record->permissions_group;
    $permissionsGroups = [];
    if(isset($record->permissions_group)) {
      $permissionsGroups['body'][0] = $record->permissions_group;
    } else {
      $permissionsGroups['body'] = [];
    }
    $record->permissions_groups = $permissionsGroups;
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
    if (!Gate::allows('permission-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Permission::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('permissions_group', function ($query) use ($params) {
        });
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
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
      $permissions_group = [];
      $permissions_groupString = [];
      $permissions_groupData = Permission::find($record->id);
      if(isset($permissions_groupData->permissions_group)) {
         $permissions_group[] =   implode(" ", $permissions_groupString);
       } 
      $fields = array
      (
          implode(', ', $permissions_group),
          $record->name,
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
