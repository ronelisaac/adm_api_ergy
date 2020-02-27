<?php
namespace App\Http\Controllers;
use App\User;
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
class FullUserController extends Controller
{
  #variable que almacena el nombre del formulario
  private $form_name;
  #Constructor
  function __construct(){
    $this->form_name ='users';
  }
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('user-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = User::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('type', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('identity_id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('tin', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('last_name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('birth_date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('email', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('address', function ($query) use ($params) {
        });
        $records->orWhere('tax_condition', 'LIKE', "%".$params["search"]["value"]."%");
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
      $records = $records->orderBy('users.updated_at', 'desc');
    } else {
      $sort = explode('.', $funnel["sort"]);
      $records = $records->orderBy($sort[0], $sort[1]);
    }
    if(count($funnel["user_types"]) > 0 ) {
      if(is_numeric(array_search(true, array_column($funnel["user_types"], 'filter')))){      
        $records->whereHas('user_types', function ($query) use ($funnel) {
          $i=0;
          foreach($funnel["user_types"] as $value){
            if($value['filter']){
              if($i==0){
                $query->where('user_types.type', '=', $value);
              }else{
                $query->orWhere('user_types.type', '=', $value);
              }
              $i++;
            } 
          }
        })->with('user_types');
      }
    }
    $records = $records->get()
    ->map(function ($record) {
      $array_types=[];
      foreach($record->user_types as $value){
        $array_types[]=$value->type;
      }
      $record->type=implode(", ",$array_types);      
      #comprueba si el password es NULL es un usuario no validado
      $record->has_password = false;
      if($record['password'] != NULL){
        $record->has_password = true;
      }

      // $states = [];
      // if($record->blocked) $states[] = 'blocked';
      // if($record->cancelled) $states[] = 'cancelled';
      // if($record->done) $states[] = 'done';
      //  $roles = $record->roles;
      //  $role = [];
      //  foreach ($roles as $key => $value) {
      //    $role[] =   $value->name;
      //  }
      // $address = [];
      // $addressString = [];
      // $addressData = User::find($record->id);
      // if(isset($addressData->address)) {
      //   $addressString[0] = $addressData['address_line'];
      //    $address[] =   implode(" ", $addressString);
      //  } 
      // $fields = array
      // (
      //   array(
      //     'label' => 'ID',
      //     'model' => 'id',
      //     'value' => $record->id,
      //     'type' => 'integer',
      //     'classes' => 're-id'
      //   ),
      //   array(
      //     'label' => 'Tipo',
      //     'model' => 'type',
      //     'value' => $record->type,
      //     'type' => 'radio',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'DNI',
      //     'model' => 'identity_id',
      //     'value' => $record->identity_id,
      //     'type' => 'integer',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'CUIT',
      //     'model' => 'tin',
      //     'value' => $record->tin,
      //     'type' => 'integer',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Nombre',
      //     'model' => 'name',
      //     'value' => $record->name,
      //     'type' => 'string',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Apellido',
      //     'model' => 'last_name',
      //     'value' => $record->last_name,
      //     'type' => 'string',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Fecha de nacimiento',
      //     'model' => 'birth_date',
      //     'value' => $record->birth_date,
      //     'type' => 'date',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Email',
      //     'model' => 'email',
      //     'value' => $record->email,
      //     'type' => 'string',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Roles',
      //     'model' => 'roles',
      //     'value' => implode(", ",$role),
      //     'type' => 'checkboxs',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Domicilio',
      //     'model' => 'addresses',
      //     'value' => implode(', ', $address),
      //     'type' => 'autocomplete',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Telefonos',
      //     'model' => 'phones',
      //     'value' => json_decode($record->phones),
      //     'type' => 'list',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Condici�n frente al IVA',
      //     'model' => 'tax_condition',
      //     'value' => $record->tax_condition,
      //     'type' => 'select',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Descripci�n',
      //     'model' => 'description',
      //     'value' => $record->description,
      //     'type' => 'longText',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Modificado el',
      //     'model' => 'updated_at',
      //     'value' => $record->updated_at,
      //     'type' => 'datetime',
      //     'classes' => ''
      //   ),
      //   array(
      //     'label' => 'Estados',
      //     'model' => 'states',
      //     'value' => json_encode($states),
      //     'type' => 'states',
      //     'classes' => 'states'
      //   )
      // );
      // return $fields;
      return $record;
    });
  //  if(count($records) == 0){
  //     $records[0] = array
  //     (
  //       array(
  //         'label' => 'ID',
  //         'model' => 'id',
  //         'value' => NULL,
  //         'type' => 'integer',
  //         'classes' => 're-id'
  //       ),
  //       array(
  //         'label' => 'Tipo',
  //         'model' => 'type',
  //         'value' => NULL,
  //         'type' => 'radio',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'DNI',
  //         'model' => 'identity_id',
  //         'value' => NULL,
  //         'type' => 'integer',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'CUIT',
  //         'model' => 'tin',
  //         'value' => NULL,
  //         'type' => 'integer',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Nombre',
  //         'model' => 'name',
  //         'value' => NULL,
  //         'type' => 'string',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Apellido',
  //         'model' => 'last_name',
  //         'value' => NULL,
  //         'type' => 'string',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Fecha de nacimiento',
  //         'model' => 'birth_date',
  //         'value' => NULL,
  //         'type' => 'date',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Email',
  //         'model' => 'email',
  //         'value' => NULL,
  //         'type' => 'string',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Roles',
  //         'model' => 'roles',
  //         'value' => NULL,
  //         'type' => 'checkboxs',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Domicilio',
  //         'model' => 'addresses',
  //         'value' => 'NULL',
  //         'type' => 'autocomplete',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Telefonos',
  //         'model' => 'phones',
  //         'value' => '',
  //         'type' => 'list',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Condici�n frente al IVA',
  //         'model' => 'tax_condition',
  //         'value' => NULL,
  //         'type' => 'select',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Descripci�n',
  //         'model' => 'description',
  //         'value' => NULL,
  //         'type' => 'longText',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Modificado el',
  //         'model' => 'updated_at',
  //         'value' => NULL,
  //         'type' => 'datetime',
  //         'classes' => ''
  //       ),
  //       array(
  //         'label' => 'Estados',
  //         'model' => 'states',
  //         'value' => json_encode([]),
  //         'type' => 'states',
  //         'classes' => 'states'
  //       )
  //     );
     //}
    $response['data'] = $records;
    return response()->json($response, 200);
  }
  public function store(Request $request)
  {
    $response = [];
    if (!Gate::allows('user-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    #inicialice objet User
    $objUser=new User();
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
    #funcion que esta en el método users y se encarga de
    #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
    $array_validator=$objUser->validationsReturns($arrayFields,'store',false);
    $validated_data= Validator::make($request->all(),$array_validator);
    $validated_email= Validator::make($request->all(),['email' => 'required|unique:users']);
    if($validated_data->fails() || $validated_email->fails()){
      #si hay algun elemento que no es valido, retorna un arreglo de errores
      $response['status'] = 'error';
      $response['success'] = false;
      if($validated_data->fails()) $response['errors'] = $validated_data->errors();
      else $response['errors'] = $validated_email->errors();
      return response()->json($response, 412);
    }
    #fin validaciones
    $userId = Auth::id();
    $record = [];
    $params = [];
    
    # type
    $record['type'] = $request->input('type');
    # End type
    
    
    # identity_id
    $record['identity_id'] = $request->input('identity_id');
    # End identity_id
    
    
    # tin
    $record['tin'] = $request->input('tin');
    # End tin
    
    
    # name
    $record['name'] = $request->input('name');
    # End name
    
    
    # last_name
    $record['last_name'] = $request->input('last_name');
    # End last_name
    
    
    # birth_date
    $record['birth_date'] = $request->input('birth_date');
    # End birth_date
    
    
    # email
    $record['email'] = $request->input('email');
    # End email
    
    $record['search_field'] = (null !== $request->input('name') ? $request->input('name') : "").' '.(null !== $request->input('last_name') ? $request->input('last_name') : "").' - DNI: '.(null !== $request->input('identity_id') ? $request->input('identity_id') : "").' - CUIT: '.(null !== $request->input('tin') ? $request->input('tin') : "");
    $record['full_name'] = (null !== $request->input('name') ? $request->input('name') : "").' '.(null !== $request->input('last_name') ? $request->input('last_name') : "");
    
    # Autocomplete addresses
    $obj = $request->input('addresses');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['address_id'] = $body[0]['id'];
      $record['address_line'] = $body[0]['address_line'];
    }
    # End autocomplete addresses
    
    $record['phones'] = json_encode($request->input('phones'));
    
    # tax_condition
    $record['tax_condition'] = $request->input('tax_condition');
    # End tax_condition
    
    
    # description
    $record['description'] = $request->input('description');
    # End description
    
    $record['created_by'] = $userId;
    $record['updated_by'] = $userId;
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (null !== $request->input('full_name') ? $request->input('full_name') : "");
    $params['t2'] = (count($request->input('addresses')['body']) > 0 ? $request->input('addresses')['body'][0]['address_line'] : "");
    $params['t3'] = '';
    $roles = $request->input('roles');
    #roles del usuario 
    $params['roles'] = $roles;
    $roles = $request->input('roles');
    $flag_roles = false;
    foreach ($request->input('roles') as $r) {      
      if ($r!=null || $r!=false) {
        if($flag_roles == false) $flag_roles = true;
      }
    }   
    $params['flag_roles'] = $flag_roles;
    #fin roles
    
    $user_types = $request->input('user_types');
    $flag_user_types = false;
    foreach ($user_types as $ut) {      
      if ($ut!=null || $ut!=false) {
        if($flag_user_types == false) $flag_user_types = true;
      }
    }   
    $params['flag_user_types'] = $flag_user_types;
    $params['user_types'] = $user_types;

    $params['server'] = $server;
    $params['title'] = $title;
    $styles=[];
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];
    $params['styles']=$styles;
    # Para el indicator
    $params['form_name'] = $this->form_name;
    $params['indicators'] = $indicators;
    #fin parametros para indicadores
    DB::transaction(function () use ($params) {
      $id = DB::table('users')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F01';
      $record['form_title'] = 'Alta de persona';
      $record['name'] = 'users';
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
      #verifica si trae un rol en el request, es un usuario
      if( $params['flag_roles'] != false ){
       #enviar mail con el link
       $link=$params['server'].'/user-validation/'.base64_encode($id);
       Mail::to($params['record']['email'])->send(new AlertsEmail(["link"=>$link,"subject"=>"Validación de  usuario","title"=> $params['title'],"server"=> $params['server'],"file"=>"sendmail-register","styles"=>$params['styles']]));
      }
      #verifica si el formulario tiene un indicador
      foreach( $params['indicators'] as $indicator){
        if($indicator['form_name']==$params['form_name']){
         # Rutina: Incremento el valor del indicador en el periodo respectivo
         $objUser=new User();
         $objUser->dataIndicators(date("Y-m-d", strtotime($params['record'][$indicator['date_field']])),$params['form_name'],$params['indicators']);
        }
      }
      #Fin de la busqueda de indicadores
      #si existen roles tildados en el array
      if($params['flag_roles'] != false){
       $roles = [];
       foreach ($params['roles'] as $key => $value) {
         if ($value) {
           $roles[] = [
             'role_id' => $key,
             'user_id' => $id,
             'created_by' => $params['user_id'],
             'updated_by' => $params['user_id'],
             'created_at' => Carbon::now()->toDateTimeString(),
             'updated_at' => Carbon::now()->toDateTimeString()
           ];
         }
       }
       if(count($roles)>0) DB::table('role_user')->insert($roles);
      }
       #si existen user_types tildados en el array
       if($params['user_types'] != false){
        $user_types = [];
        foreach ($params['user_types'] as $key => $value) {
          if ($value) {
            $user_types[] = [
              'user_id' => $id,              
              'type' => $key,
              'created_by' => $params['user_id'],
              'updated_by' => NULL,
              'created_at' => Carbon::now()->toDateTimeString(),
              'updated_at' => NULL
            ];
          }
        }
        if(count($user_types)>0)  DB::table('user_types')->insert($user_types);
       }

    });
    
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function show($user)
  {
    $id = $user;
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'users'],
      ['record_id', '=', $id]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('user-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = User::find($id);
    $address = $record->address;
    $addresses = [];
    if(isset($record->address)) {
      $addresses['body'][0] = $record->address;
      $addresses['body'][0]['address_line'] = $record['address_line'];
    } else {
      $addresses['body'] = [];
    }
    $record->addresses = $addresses;
    if($record->phones == null) {
     $record->phones = [];
    }else{
     $record->phones =json_decode($record->phones);
    }
    $roles = $record->roles;
    $record = $record->toArray();
    $a = [];
    foreach ($roles as $key => $value) {
      $a[] = $value['id'];
    }
    $b = [];
    if(count($roles) > 0) {
      $n = $roles[count($roles) - 1]['id'];
      for ($i=0; $i <= $n; $i++) { 
        if(in_array($i, $a)) {
          $b[] = $i;
        } else {
          $b[] = null;
        }
      }
    }
    $record['roles'] = $b;
    
    $user_types = [];
    $result = DB::table('user_types')->where("user_id","=",$id)->get();
    foreach($result as $value){
      $user_types[$value->type] = true;
      // $users_types[]=[
      //   $value->type => true
      // ];
    }
    if(count($user_types) == 0) {
      $user_types =  (object) [];
    }
    $record['user_types'] = $user_types;
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, User $user)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'users'],
      ['record_id', '=', $request->input('id')]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('user-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('users')->select('blocked')->where('id', "=",$request->input('id'))->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
   #inicialice objet User
   $objUser=new User();
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
   #funcion que esta en el método users y se encarga de
   #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
   $array_validator=$objUser->validationsReturns($arrayFields,'update',$request->input('id'));
   $validated_data= Validator::make($request->all(),$array_validator);
   $validated_email= Validator::make($request->all(),['email' => 'required|unique:users,email,'.$request->input('id')]);
   if($validated_data->fails() || $validated_email->fails()){
     #si hay algun elemento que no es valido, retorna un arreglo de errores
     $response['status'] = 'error';
     $response['success'] = false;
     if($validated_data->fails()) $response['errors'] = $validated_data->errors();
     else $response['errors'] = $validated_email->errors();
     return response()->json($response, 412);
   }
   #fin validaciones
    $record['id'] = $request->input('id');
    
    # type
    $record['type'] = $request->input('type');
    # End type
    
    
    # identity_id
    $record['identity_id'] = $request->input('identity_id');
    # End identity_id
    
    
    # tin
    $record['tin'] = $request->input('tin');
    # End tin
    
    
    # name
    $record['name'] = $request->input('name');
    # End name
    
    
    # last_name
    $record['last_name'] = $request->input('last_name');
    # End last_name
    
    
    # birth_date
    $record['birth_date'] = $request->input('birth_date');
    # End birth_date
    
    
    # email
    $record['email'] = $request->input('email');
    # End email
    $record['search_field'] = (null !== $request->input('name') ? $request->input('name') : "").' '.(null !== $request->input('last_name') ? $request->input('last_name') : "").' - DNI: '.(null !== $request->input('identity_id') ? $request->input('identity_id') : "").' - CUIT: '.(null !== $request->input('tin') ? $request->input('tin') : "");
    $record['full_name'] = (null !== $request->input('name') ? $request->input('name') : "").' '.(null !== $request->input('last_name') ? $request->input('last_name') : "");
    
    # Autocomplete addresses
    $obj = $request->input('addresses');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['address_id'] = $body[0]['id'];
      $record['address_line'] = $body[0]['address_line'];
    }
    # End autocomplete addresses
    
    $record['phones'] = json_encode($request->input('phones'));
    
    # tax_condition
    $record['tax_condition'] = $request->input('tax_condition');
    # End tax_condition
    
    
    # description
    $record['description'] = $request->input('description');
    # End description
    
    $record['updated_by'] = $userId;
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (null !== $request->input('full_name') ? $request->input('full_name') : "");
    $params['t2'] = (count($request->input('addresses')['body']) > 0 ? $request->input('addresses')['body'][0]['address_line'] : "");
    $params['t3'] = '';

    #verifica si el arreglo rol trae valores posibles para el usuario
    $flag_roles=false;      
    $params['roles'] = $request->input('roles');    
    foreach ($request->input('roles') as $r) {      
      if ($r!=null || $r!=false) {
          if($flag_roles == false) $flag_roles = true;
      }
    }
    $params['new_email'] = false;
    $params['old_password'] = "";
    if($flag_roles == false){
      $params['flag_roles'] = false;     
    }else{
     $params['flag_roles'] = true;
     $params['server'] = $server;
     $params['title'] = $title;
     $old_value_pass= DB::table('users')->select("password")->where('id', $user['id'])->first();
     if(isset($old_value_pass->password)) $params['old_password'] = $old_value_pass->password;
     if(isset($old_value_pass->email)){
      if($old_value_pass->email != $record['email']){
        $params['new_email']=true;#quiere decir que el email fue modificado
      }
     } 
    }
    #verifica si trae algun dato en user types
    $user_types = $request->input('user_types');
    $flag_user_types = false;
    foreach ($user_types as $ut) {      
      if ($ut!=null || $ut!=false) {
        if($flag_user_types == false) $flag_user_types = true;
      }
    }   
    $params['flag_user_types'] = $flag_user_types;
    $params['user_types'] = $user_types;
    $styles=[];
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];
    $params['styles']=$styles;
     #indicators vars
     $params['form_name'] = $this->form_name;
     $params['indicators'] = $indicators;
     $date_field = false;
     #verifica si el formulario tiene un indicador
     foreach( $indicators as $indicator){
      if($indicator['form_name']==$params['form_name']){
        # Rutina: trae el campo que se selcciono para que sea el date del indicator 
        #consulta el dia y el valor anterior antes de actualizar
        $params['actual_day'] = $request[$indicator['date_field']]; 
        $old_values= DB::table('users')->select($indicator['date_field']." as date")->where('id', $request->input('id'))->first();
        $params['old_day'][$indicator['id']] = $old_values->date;  
        #fin rutina indicator and old data
       }
     }
     #Fin de la busqueda de indicadores
    DB::transaction(function () use ($params) {
      DB::table('users')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F01';
      $record['form_title'] = 'Alta de persona';
      $record['name'] = 'users';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['t1'];
      $record['t2'] = $params['t2'];
      $record['t3'] = $params['t3'];
      $record['updated_by'] = $params['user_id'];
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'users']])->update($record);
      #verifica si trae un rol el request y si el password diferente de false, en tal caso ejecuta el envio del mail ya que se convirtio en un usuario
      $send_mail=false;
      if( $params['flag_roles'] != false AND  $params['old_password']==""  ){
       #enviar mail con el link
       $link=$params['server'].'/user-validation/'.base64_encode($params['record']['id']);
       Mail::to($params['record']['email'])->send(new AlertsEmail(["link"=>$link,"subject"=>"Validación de  usuario","title"=> $params['title'],"server"=> $params['server'],"file"=>"sendmail-register","styles"=>$params['styles']]));
       $send_mail=true;
      }else if($params['new_email']){
        #quiere decir que el email fue modificado
        if($send_mail==false){
          $link=$params['server'].'/user-validation/'.base64_encode($params['record']['id']);
          Mail::to($params['record']['email'])->send(new AlertsEmail(["link"=>$link,"subject"=>"Validación de  usuario","title"=> $params['title'],"server"=> $params['server'],"file"=>"sendmail-register","styles"=>$params['styles']]));       
        }
        #coloca el password en null para que realice todo el proceso de veririfacion de usuario
        DB::table('users')->where('id', $params['record']['id'])->update(array('password' => NULL));
      }
     foreach($params['indicators'] as $indicator){
       # Rutina: Actualización  e Incremento el valor del indicador en el periodo respectivo
       # Si las fechas son iguales dejo igual en caso el campo fecha no cambie de mes
       if($indicator['form_name']==$params['form_name'])
       {
         $timeSeriesExist = DB::table('time_series')->where('indicator_id', '=', $indicator['id'])->first();
         if($params['actual_day'] != $params['old_day'][$indicator['id']] || !isset($timeSeriesExist))
         {
           # Rutina: trae el campo que se selcciono para que sea el date del indicator 
           # consulta el dia y el valor anterior antes de actualizar
           $objUser=new User();
           # Disminuye el valor del indicador en el periodo respectivo 
           # Usando la fecha vieja del User, como condicion para traer los valores actualizados
           $objUser->removeDataIndicators(date("Y-m-d", strtotime($params['old_day'][$indicator['id']])), $params['form_name'],$params['indicators']);
           # Usando la fecha nueva reconstruye la data de los indicadores que corresponden a la nueva fecha modificada
           $objUser->dataIndicators(date("Y-m-d", strtotime($params['actual_day'])), $params['form_name'],$params['indicators']);
         }
       }
     }
     
      
      if( $params['flag_roles']  != false){
        DB::table('role_user')
        ->where('user_id', $params['record']['id'])
        ->delete();
        $roles = [];
       foreach ($params['roles'] as $key => $value) {
        if ($value) {
          $roles[] = [
            'role_id' => $key,
            'user_id' => $params['record']['id'],
            'created_by' => $params['user_id'],
            'updated_by' => $params['user_id'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
          ];
         }
        }
        if(count($roles)>0) DB::table('role_user')->insert($roles);
      }
     
      #si existen user_types tildados en el array
      if($params['user_types'] != false){
        DB::table('user_types')
        ->where('user_id', $params['record']['id'])
        ->delete();
        $user_types = [];
        foreach ($params['user_types'] as $key => $value) {
          if ($value) {
            $user_types[] = [
                  'user_id' => $params['record']['id'],              
                  'type' => $key,
                  'created_by' => $params['user_id'],
                  'updated_by' => $params['user_id'],
                  'created_at' => Carbon::now()->toDateTimeString(),
                  'updated_at' => Carbon::now()->toDateTimeString()
            ];
          }
        }
        if(count($user_types)>0) DB::table('user_types')->insert($user_types);
      }
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(User $user)
  {
    $userId = Auth::id();
    if (!Gate::allows('user-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('users')->select('blocked')->where('id', $user['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $user;
    DB::transaction(function () use ($params) {
      User::destroy($params['record']['id']);
      Record::where('record_id', $params['record']['id'])->delete();
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function all(Request $request)
  {
      $response = [];
      $req = json_decode($request->getContent(), true);
      $records = User::where($req)->get();
      $response['data'] = $records;
      return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = User::where($filter)
      ->where('cancelled', '!=', true)
      ->get()
      ->map(function($record) {
        $record->address;
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
      DB::table('users')->where('id', $params['req']['id'])->update(['done' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'users']])->update(['done' => $params['req']['value']]);
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
      DB::table('users')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'users']])->update(['cancelled' => $params['req']['value']]);
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
      DB::table('users')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'users']])->update(['blocked' => $params['req']['value']]);
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
      ['name', '=', 'users'],
      ['record_id', '=', $id]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('user-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = User::find($id);
    $address = $record->address;
    $addresses = [];
    if(isset($record->address)) {
      $addresses['body'][0] = $record->address;
      $addresses['body'][0]['address_line'] = $record['address_line'];
    } else {
      $addresses['body'] = [];
    }
    $record->addresses = $addresses;
    $record->phones = json_decode($record->phones);
    $roles = $record->roles;
    $record = $record->toArray();
    $a = [];
    foreach ($roles as $key => $value) {
      $a[] = array("name"=>$value['name']);
    }
    $record['roles'] = $a;
    $response['data'] = $record;
    #lee el json setting con los datos de configuracion de form
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true); 
    $forms=array_get($json, 'forms');
    foreach($forms as $form){
      if($form['name']==$this->form_name){
        $form_code=$form['code'];
        $form_title=$form['title'];
        $data = $form['fields'];
      }
    }
    $pdf = \PDF::loadView('pdf', compact('data','form_name','form_title','form_code','record'));
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
    if (!Gate::allows('user-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = User::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('type', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('identity_id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('tin', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('last_name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('birth_date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('email', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('address', function ($query) use ($params) {
        });
        $records->orWhere('tax_condition', 'LIKE', "%".$params["search"]["value"]."%");
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
       $roles = $record->roles;
       $role = [];
       foreach ($roles as $key => $value) {
         $role[] =   $value->name;
       }
      $address = [];
      $addressString = [];
      $addressData = User::find($record->id);
      if(isset($addressData->address)) {
        $addressString[0] = $addressData['address_line'];
         $address[] =   implode(" ", $addressString);
       } 
       $phone = [];
       if(isset($record->phones) || $record->phones != ""){
         foreach (json_decode($record->phones) as  $value) {   
           $phone[] = $value;
         }
       }
      $fields = array
      (
          $record->type,
          $record->identity_id,
          $record->tin,
          $record->name,
          $record->last_name,
          $record->birth_date,
          $record->email,
          implode(', ', $role),
          implode(', ', $address),
          implode(', ', $phone),
          $record->tax_condition,
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
      $excel->sheet(substr($form_title, 0, 30), function($sheet) use ($data,$labels)
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
    /**
   * Cera y envía un mail con un archivo pdf, con los datos de un formulario.
   * Params: $id data
   * Return :json response con los datos de acceso al formulario
   */
  public function sharePdfMail($id,$mails){
    #verifica si trae mails
    if(isset($mails)){
      if(count(json_decode($mails))==0){
        $response['status'] = 'error';
        $response['success'] = false;
        $response['msg'] = 'Debe ingresra al menos un email';
        return response()->json($response, 200);
      }
    }
    $response['status'] = 'error';

    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'users'],
      ['record_id', '=', $id]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('user-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = User::find($id);
    $address = $record->address;
    $addresses = [];
    if(isset($record->address)) {
      $addresses['body'][0] = $record->address;
      $addresses['body'][0]['address_line'] = $record['address_line'];
    } else {
      $addresses['body'] = [];
    }
    $record->addresses = $addresses;
    $record->phones = json_decode($record->phones);
    $roles = $record->roles;
    $record = $record->toArray();
    $a = [];
    foreach ($roles as $key => $value) {
      $a[] = array("name"=>$value['name']);
    }
    $record['roles'] = $a;
    $response['data'] = $record;
    #lee el json setting con los datos de configuracion de form
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true); 
    $forms=array_get($json, 'forms');
    foreach($forms as $form){
      if($form['name']==$this->form_name){
        $form_code=$form['code'];
        $form_title=$form['title'];
        $data = $form['fields'];
      }
    }
    $pdf = \PDF::loadView('pdf', compact('data','form_name','form_title','form_code','record'));
    $pdf->save(storage_path().'/pdf_files_tmp/'.Auth::user()->id.'.pdf');
    $title= $json['display_name'];
    $server= $json['server_url'];
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];
    $attach_file='/pdf_files_tmp/'.Auth::user()->id.'.pdf';
    #recorriendo la lista y enviado los mails      
    foreach (json_decode($mails) as  $email) {   
      Mail::to($email)->send(new AlertsEmail(["title"=>$title,"server"=>$server,"file"=>"sendmail","from"=>Auth::user()->name.' '.Auth::user()->last_name,"attach_file"=>$attach_file,"styles"=>$styles]));
    }      
    $response['success'] = true;
    $response['req'] = true;
    return response()->json($response, 200);
  }
    /**
     * Reenvía un mail de validacion de usuario
     * Params: $email
     * Return :json response success true
     */
    public function sendVerificationEmail($email){
      $confirm=DB::table('users')->select('id,email')->where("email","=",$email)->whereNotNull('password')->first();  
      if(!isset($confirm->email)){       
        $response['status'] = 'error';
        $response['success'] = false;
        $response['errors'] = "Imposible validar el usuario";    
      }else{      
        #lee el json setting con los datos de configuracion de form
        $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
        $json = json_decode(file_get_contents($path), true); 
        $title= $json['display_name'];
        $server= $json['server_url'];
        $styles['width_logo']=$json['width_logo'];
        $styles['color']=$json['color'];
        $link=$server.'/user-validation/'.base64_encode($confirm->id);
        Mail::to($confirm->email)->send(new AlertsEmail(["link"=>$link,"subject"=>"Validación de  usuario","title"=> $title,"server"=> $server,"file"=>"sendmail-register","styles"=>$styles]));        
        $response['success'] = true;
        $response['req'] = true;
      }        
      return response()->json($response, 200);
    }
  /**
  * Crea y envía un mail de recuperacion de usuario, registrando en la bd un token y una fecha de expedicion.
  * Params: $email
  * Return :json response success true
  */
  public function recoveryPassword($email){
    /*$validated_data= Validator::make($request->all(),[
      'email' => 'required'
    ]);
    if($validated_data->fails()){
      #si hay algun elemento que no es valido, retorna un arreglo de errores
      $response['status'] = 'error';
      $response['success'] = false;
      $response['errors'] = $validated_data->errors();
      return response()->json($response, 412);
    }*/
    #fin validaciones
    $confirm=DB::table('users')->select('id', 'email')->where("email","=",$email)->whereNotNull('password')->first();  
    if(!isset($confirm->email)){       
      $response['status'] = 'error';
      $response['success'] = false;
      $response['errors'] = "Usuario no encontrado";    
    }else{      
      $token= bin2hex(openssl_random_pseudo_bytes((200 - (200 % 2)) / 2));//token cifrado       
      $nuevafecha = strtotime ( '+15 minute' , strtotime ( date("Y-m-d h:i")) ) ;//15 minutos adelantado es el tiempo de expiracion
      $date_time = date ( 'Y-m-d h:i' , $nuevafecha );//fecha y hora
      $records=[];
      $records['link_token']=$token;
      $records['expiration_date']=$date_time;
      $params['records']=$records;
      //agrega el token y la fecha de expiración
      DB::table('users')->where('id', $confirm->id)->update($params['records']);
      #lee el json setting con los datos de configuracion de form
      $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
      $json = json_decode(file_get_contents($path), true); 
      $title= $json['display_name'];
      $server= $json['server_url'];
      $styles['width_logo']=$json['width_logo'];
      $styles['color']=$json['color'];
      $link=$server.'/change-pass/'.$token;
      Mail::to($email)->send(new AlertsEmail(["link"=>$link,"subject"=>"solicitud de cambio de contraseña","title"=>$title,"server"=>$server,"file"=>"sendmail-forget","styles"=>$styles]));
      $response['success'] = true;
      $response['req'] = true;
    }        
    return response()->json($response, 200);
  }

  /**
  * Crea un nuevo suario desde el app de bibliorato y envía un mail de validación.
  * Params: $email,nombres, apellidos, password
  * Return :json response success true
  */
  public function register(Request $request)
  {
    #inicialice objet User
    $objUser=new User();
    #Validations
    #read json setting whit form data in configuration setting.json 
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true);
    $title = $json['display_name'];
    $server = $json['server_url'];
    $indicators=array_get($json, 'indicators');
    
    $validated_data= Validator::make($request->all(),[
      'email' => 'required|unique:users',
      'name' => 'required',
      'last_name' => 'required',
      'password' => 'required',
    ]);
    if($validated_data->fails()){
      #si hay algun elemento que no es valido, retorna un arreglo de errores
      $response['status'] = 'error';
      $response['success'] = false;
      $response['errors'] = $validated_data->errors();
      return response()->json($response, 412);
    }
    #fin validaciones
    $record = [];
    $params = [];
   
    
    
    # name
    $record['name'] = $request->input('name');
    # End name
    
    
    # last_name
    $record['last_name'] = $request->input('last_name');
    # End last_name
    
    # email
    $record['email'] = $request->input('email');
    # End email
    
    
    
    

    
    
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['t1'] = '';
    $params['t2'] = '';
    $params['t3'] = '';
    
    $params['server'] = $server;
    $params['title'] = $title;
    $styles=[];
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];
    $params['styles']=$styles;
    # Para el indicator
    $params['form_name'] = $this->form_name;
    $params['indicators'] = $indicators;
    #fin parametros para indicadores
    DB::transaction(function () use ($params) {
      $id = DB::table('users')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F01';
      $record['form_title'] = 'Alta de persona';
      $record['name'] = 'users';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['t1'];
      $record['t2'] = $params['t2'];
      $record['t3'] = $params['t3'];
      $record["created_at"] = Carbon::now()->toDateTimeString();
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->insert($record);
      
      #enviar mail con el link
      $link=$params['server'].'/user-validation/'.base64_encode($id);
      Mail::to($params['record']['email'])->send(new AlertsEmail(["link"=>$link,"subject"=>"Validación de  usuario","title"=> $params['title'],"server"=> $params['server'],"file"=>"sendmail-register","styles"=>$params['styles']]));
       
      #verifica si el formulario tiene un indicador
      foreach( $params['indicators'] as $indicator){
        if($indicator['form_name']==$params['form_name']){
         # Rutina: Incremento el valor del indicador en el periodo respectivo
         $objUser=new User();
         $objUser->dataIndicators(date("Y-m-d", strtotime($params['record'][$indicator['date_field']])),$params['form_name'],$params['indicators']);
        }
      }
      #Fin de la busqueda de indicadores
    });
    
    $response['success'] = true;
    return response()->json($response, 200);
  }
}
