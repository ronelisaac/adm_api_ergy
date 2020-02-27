<?php
namespace App\Http\Controllers;
use App\Indicator;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Gate;
use Validator;
use Illuminate\Support\Facades\Storage;
class IndicatorController extends Controller
{
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('indicator-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Indicator::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('measurement_unit', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('operation', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('form', function ($query) use ($params) {
        });
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
      if($record->blocked) $states[] = 'blocked';
      if($record->cancelled) $states[] = 'cancelled';
      if($record->done) $states[] = 'done';
      $form = $record->form;
      $fields = array
      (
        array(
          'label' => 'ID',
          'model' => 'id',
          'value' => $record->id,
          'type' => 'number',
          'classes' => 're-id'
        ),
        array(
          'label' => 'Nombre',
          'model' => 'name',
          'value' => $record->name,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Unidad de medida',
          'model' => 'measurement_unit',
          'value' => $record->measurement_unit,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Campo fecha',
          'model' => 'date',
          'value' => $record->date,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Campo de operacion',
          'model' => 'operation',
          'value' => $record->operation,
          'type' => 'string',
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
    $response['data'] = $records;
    return response()->json($response, 200);
  }
  public function store(Request $request)
  {
    $response = [];
    if (!Gate::allows('indicator-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $userId = Auth::id();
    $record = [];
    $params = [];
    $record['name'] = $request->input('name');
    $record['measurement_unit'] = $request->input('measurement_unit');
    $record['date'] = $request->input('date');
    $record['operation'] = $request->input('operation');
    $forms = $request->input('forms');
    $record['form_id'] = isset($forms['body'][0]['id']) ? $forms['body'][0]['id'] : null;
    $record['created_by'] = $userId;
    $record['updated_by'] = $userId;
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    DB::transaction(function () use ($params) {
      $id = DB::table('indicators')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F02';
      $record['form_title'] = 'Alta de indicadores';
      $record['name'] = 'indicators';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['record']['name'];
      $record['t1'] = null;
      $record['t1'] = null;
      $record['created_by'] = $params['user_id'];
      $record['updated_by'] = $params['user_id'];
      $record["created_at"] = Carbon::now()->toDateTimeString();
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->insert($record);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function show(Indicator $indicator)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'indicators'],
      ['record_id', '=', $indicator['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('indicator-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Indicator::find($indicator['id']);
    $form = $record->form;
    $forms = [];
    if(isset($record->form)) {
      $forms['body'][0] = $record->form;
    } else {
      $forms['body'] = [];
    }
    $record->forms = $forms;
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, Indicator $indicator)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'indicators'],
      ['record_id', '=', $indicator['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('indicator-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('indicators')->select('blocked')->where('id', $indicator['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
    $record['id'] = $indicator['id'];
    $record['name'] = $request->input('name');
    $record['measurement_unit'] = $request->input('measurement_unit');
    $record['date'] = $request->input('date');
    $record['operation'] = $request->input('operation');
    $forms = $request->input('forms');
    $record['form_id'] = isset($forms['body'][0]['id']) ? $forms['body'][0]['id'] : null;
    $params['record'] = $record;
    $params['user_id'] = $userId;
    DB::transaction(function () use ($params) {
      DB::table('indicators')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F02';
      $record['form_title'] = 'Alta de indicadores';
      $record['name'] = 'indicators';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['record']['name'];
      $record['t2'] = null;
      $record['t3'] = null;
      $record['created_by'] = $params['user_id'];
      $record['updated_by'] = $params['user_id'];
      $record["created_at"] = Carbon::now()->toDateTimeString();
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'indicators']])->update($record);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(Indicator $indicator)
  {
    $userId = Auth::id();
    if (!Gate::allows('indicator-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('indicators')->select('blocked')->where('id', $indicator['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $indicator;
    DB::transaction(function () use ($params) {
      Indicator::destroy($params['record']['id']);
      Record::where('record_id', $params['record']['id'])->delete();
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function all(Request $request)
  {
      $response = [];
      $req = json_decode($request->getContent(), true);
      $records = Indicator::where($req)->get();
      $response['data'] = $records;
      return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = Indicator::where($filter)
      ->where('cancelled', '!=', true)
      ->get()
      ->map(function($record) {
        $record->person;
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
      
  public function cancel(Request $request)
  {
    $req = json_decode($request->getContent(), true);
    $params = [];
    $params['req'] = $req;
    DB::transaction(function () use ($params) {
      DB::table('indicators')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'indicators']])->update(['cancelled' => $params['req']['value']]);
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
      DB::table('indicators')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'indicators']])->update(['blocked' => $params['req']['value']]);
    });
    $response = [];
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
  /***
   * Method: returnIndicartorsValues
   * Functions: retorna la serie de datos de las ultimas 12 fechas en  los indicadores
   * Parameters 
   * Data return : array json con la información del indicador mas la data timeSeries 
  ***/ 
  public function returnIndicartorsValues(Request $request){
    setlocale(LC_ALL,"es_ES"); 
    Carbon::setLocale('es');
    $datev=array("Mon"=>array('Lun.','Lunes'),"Tue"=>array('Mar.','Martes'),"Wed"=>array('Mie.','Miércoles'),"Thu"=>array('Jue.','Jueves'),"Fri"=>array('Vie.','Viernes'),"Sat"=>array('Sáb.','Sábado'),"Sun"=>array('Dom.','domingo'));
    $records = [];   
    $indicator_ =DB::table('indicators')->get();
    foreach($indicator_ as $data_indicator) { 
      $array['name']=$data_indicator->name;
      $array['measurement_unit']=$data_indicator->measurement_unit;
      $array['date_field']=$data_indicator->date_field;
      $array['operation']=$data_indicator->operation;
      $array['form_name']=$data_indicator->form_name;
      $array['time_serie']=[];
      for($i=0;$i<10;$i++){        
        if($i==0) $fecha= Carbon::parse($request->input('day'));
        else  $fecha=Carbon::parse($fecha)->subDay(); 
        $values['day'] = $fecha->toDateString();  
        # Funcion que verifica que existe el dia en time_series por indicador     
        $timeSerie_ = DB::table('time_series')->where([
          ['day', '=',  $fecha->toDateString()],
          ['indicator_id', '=', $data_indicator->id]
        ])->first();
        # Si  existe creo el dia y le asigno 0 a value
        if(isset($timeSerie_->id)){
          $values['value']=$timeSerie_->value;
        }else{
          $values['value']=0;
        }
        $fecha_name= $fecha->format("D");
        $values['name_day']=array("short" => $datev[$fecha_name][0] , "large" => $datev[$fecha_name][1] );
        array_push($array['time_serie'],$values);
      }
      
      usort($array['time_serie'], function ($a, $b){
          return strtotime($a['day']) - strtotime($b['day']);
      });
      array_push($records,$array);
    }
    $response = [];
    $response['data']=$records;
    return response()->json($response, 200); 
  }
}
