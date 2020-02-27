<?php
namespace App\Http\Controllers;
use App\Service;
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
class ServiceController extends Controller
{
  #variable que almacena el nombre del formulario
  private $form_name;
  #Constructor
  function __construct(){
    $this->form_name ='services';
  }
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('service-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Service::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('project', function ($query) use ($params) {
          $query->where('project_name', 'LIKE', "%".$params["search"]["value"]."%");
        });
        $records->orWhere('detail', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('currency', 'LIKE', "%".$params["search"]["value"]."%");
      } else {
        switch ($funnel["search"]["column"]) {
          case 'projects':
            # code...
            $records->whereHas('project', function ($query) use ($params) {
              $query->where('project_name', 'LIKE', "%".$params["search"]["value"]."%");
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
      $project = [];
      $projectString = [];
      $projectData = Service::find($record->id);
      if(isset($projectData->project)) {
        $projectString[0] = $projectData['project_name'];
         $project[] =   implode(" ", $projectString);
       } 
      if(strlen($record->detail) > 200){ $record->detail = mb_substr($record->detail, 0, 200, "utf-8").' ...'; }else{ $record->detail = $record->detail; }
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
          'label' => 'Proyecto',
          'model' => 'projects',
          'value' => implode(', ', $project),
          'type' => 'autocomplete',
          'classes' => ''
        ),
        array(
          'label' => 'Descripción',
          'model' => 'detail',
          'value' => $record->detail,
          'type' => 'longText',
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
          'label' => 'Importe',
          'model' => 'amount',
          'value' => $record->amount,
          'type' => 'decimal',
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
          'label' => 'Proyecto',
          'model' => 'projects',
          'value' => 'NULL',
          'type' => 'autocomplete',
          'classes' => ''
        ),
        array(
          'label' => 'Descripción',
          'model' => 'detail',
          'value' => NULL,
          'type' => 'longText',
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
          'label' => 'Importe',
          'model' => 'amount',
          'value' => NULL,
          'type' => 'decimal',
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
    if (!Gate::allows('service-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    #inicialice objet Service
    $objService=new Service();
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
    #funcion que esta en el método services y se encarga de
    #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
    $array_validator=$objService->validationsReturns($arrayFields,'store',false);
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
    
    
    # Autocomplete projects
    $obj = $request->input('projects');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['project_id'] = $body[0]['id'];
      $record['project_name'] = $body[0]['project_name'];
    }
    # End autocomplete projects
    
    
    # detail
    $record['detail'] = $request->input('detail');
    # End detail
    
    
    # currency
    $record['currency'] = $request->input('currency');
    # End currency
    
    
    # amount
    $record['amount'] = $request->input('amount');
    # End amount
    
    
    # collected
    $record['collected'] = $request->input('collected');
    # End collected
    
    
    # invoiced
    $record['invoiced'] = $request->input('invoiced');
    # End invoiced
    
    # dueDate
    $obj = $request->input('service');
    $record['service_due_date'] = $obj['due_date'];
    $record['service_due_days'] = $obj['due_days'];
    # End dueDate
    # dueDate
    $obj = $request->input('collection');
    $record['collection_due_date'] = $obj['due_date'];
    $record['collection_due_days'] = $obj['due_days'];
    # End dueDate
    
    # invoiced_service
    $record['invoiced_service'] = $request->input('invoiced_service');
    # End invoiced_service
    
    
    # collected_service
    $record['collected_service'] = $request->input('collected_service');
    # End collected_service
    
    
    # added
    $record['added'] = $request->input('added');
    # End added
    
    $record['created_by'] = $userId;
    $record['updated_by'] = $userId;
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (null !== $request->input('detail') ? $request->input('detail') : "");
    $params['t2'] = '';
    $params['t3'] = '';
    DB::transaction(function () use ($params) {
      $id = DB::table('services')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F06';
      $record['form_title'] = 'Alta de servicio';
      $record['name'] = 'services';
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
  public function show(Service $service)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'services'],
      ['record_id', '=', $service['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('service-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Service::find($service['id']);
    $project = $record->project;
    $projects = [];
    if(isset($record->project)) {
      $projects['body'][0] = $record->project;
      $projects['body'][0]['project_name'] = $record['project_name'];
    } else {
      $projects['body'] = [];
    }
    $record->projects = $projects;
    $dueDate = [];
    $dueDate['due_date'] = $record->service_due_date;
    $dueDate['due_days'] = $record->service_due_days;
    $record->service = $dueDate;
    $dueDate = [];
    $dueDate['due_date'] = $record->collection_due_date;
    $dueDate['due_days'] = $record->collection_due_days;
    $record->collection = $dueDate;
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, Service $service)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'services'],
      ['record_id', '=', $service['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('service-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('services')->select('blocked')->where('id', $service['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
   #inicialice objet Service
   $objService=new Service();
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
   #funcion que esta en el método services y se encarga de
   #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
   $array_validator=$objService->validationsReturns($arrayFields,'update',$service['id']);
   $validated_data= Validator::make($request->all(),$array_validator);
   if($validated_data->fails()){
     #si hay algun elemento que no es valido, retorna un arreglo de errores
     $response['status'] = 'error';
     $response['success'] = false;
     $response['errors'] = $validated_data->errors();
     return response()->json($response, 412);
   }
   #fin validaciones
    $record['id'] = $service['id'];
    
    # date
    $record['date'] = $request->input('date');
    # End date
    
    
    # Autocomplete projects
    $obj = $request->input('projects');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['project_id'] = $body[0]['id'];
      $record['project_name'] = $body[0]['project_name'];
    }
    # End autocomplete projects
    
    
    # detail
    $record['detail'] = $request->input('detail');
    # End detail
    
    
    # currency
    $record['currency'] = $request->input('currency');
    # End currency
    
    
    # amount
    $record['amount'] = $request->input('amount');
    # End amount
    
    
    # collected
    $record['collected'] = $request->input('collected');
    # End collected
    
    
    # invoiced
    $record['invoiced'] = $request->input('invoiced');
    # End invoiced
    
    # dueDate
    $obj = $request->input('service');
    $record['service_due_date'] = $obj['due_date'];
    $record['service_due_days'] = $obj['due_days'];
    # End dueDate
    # dueDate
    $obj = $request->input('collection');
    $record['collection_due_date'] = $obj['due_date'];
    $record['collection_due_days'] = $obj['due_days'];
    # End dueDate
    
    # invoiced_service
    $record['invoiced_service'] = $request->input('invoiced_service');
    # End invoiced_service
    
    
    # collected_service
    $record['collected_service'] = $request->input('collected_service');
    # End collected_service
    
    
    # added
    $record['added'] = $request->input('added');
    # End added
    
    $record['updated_by'] = $userId;
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (null !== $request->input('detail') ? $request->input('detail') : "");
    $params['t2'] = '';
    $params['t3'] = '';
    DB::transaction(function () use ($params) {
      DB::table('services')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F06';
      $record['form_title'] = 'Alta de servicio';
      $record['name'] = 'services';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['t1'];
      $record['t2'] = $params['t2'];
      $record['t3'] = $params['t3'];
      $record['updated_by'] = $params['user_id'];
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'services']])->update($record);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(Service $service)
  {
    $userId = Auth::id();
    if (!Gate::allows('service-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('services')->select('blocked')->where('id', $service['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $service;
    DB::transaction(function () use ($params) {
      Service::destroy($params['record']['id']);
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
     $records = Service::where($req)->get();
     $response['data'] = $records;
     return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = Service::where($filter)
      ->where('cancelled', '!=', true)
      ->get()
      ->map(function($record) {
        $record->project;
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
      DB::table('services')->where('id', $params['req']['id'])->update(['done' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'services']])->update(['done' => $params['req']['value']]);
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
      DB::table('services')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'services']])->update(['cancelled' => $params['req']['value']]);
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
      DB::table('services')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'services']])->update(['blocked' => $params['req']['value']]);
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
      ['name', '=', 'services'],
      ['record_id', '=', $id]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('service-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Service::find($id);
    $project = $record->project;
    $projects = [];
    if(isset($record->project)) {
      $projects['body'][0] = $record->project;
      $projects['body'][0]['project_name'] = $record['project_name'];
    } else {
      $projects['body'] = [];
    }
    $record->projects = $projects;
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
    if (!Gate::allows('service-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Service::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('project', function ($query) use ($params) {
        });
        $records->orWhere('detail', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('currency', 'LIKE', "%".$params["search"]["value"]."%");
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
      $project = [];
      $projectString = [];
      $projectData = Service::find($record->id);
      if(isset($projectData->project)) {
        $projectString[0] = $projectData['project_name'];
         $project[] =   implode(" ", $projectString);
       } 
      $fields = array
      (
          $record->date,
          implode(', ', $project),
          $record->detail,
          $record->currency,
          $record->amount,
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
