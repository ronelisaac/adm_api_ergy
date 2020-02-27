<?php
namespace App\Http\Controllers;
use App\TimeSerie;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Gate;
use Validator;
use Illuminate\Support\Facades\Storage;
class TimeSerieController extends Controller
{
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('time-serie-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = TimeSerie::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('priod', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('description', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('indicator', function ($query) use ($params) {
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
      $indicator = $record->indicator;
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
          'label' => 'Periodo',
          'model' => 'priod',
          'value' => $record->priod,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Valor',
          'model' => 'value',
          'value' => $record->value,
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
    $response['data'] = $records;
    return response()->json($response, 200);
  }
  public function store(Request $request)
  {
    $response = [];
    if (!Gate::allows('time-serie-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $userId = Auth::id();
    $record = [];
    $params = [];
    $record['priod'] = $request->input('priod');
    $record['value'] = $request->input('value');
    $record['description'] = $request->input('description');
    $indicators = $request->input('indicators');
    $record['indicator_id'] = isset($indicators['body'][0]['id']) ? $indicators['body'][0]['id'] : null;
    $record['created_by'] = $userId;
    $record['updated_by'] = $userId;
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    DB::transaction(function () use ($params) {
      $id = DB::table('time_series')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F0';
      $record['form_title'] = 'Alta de';
      $record['name'] = 'time-series';
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
  public function show(TimeSerie $timeSerie)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'time-series'],
      ['record_id', '=', $timeSerie['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('time-serie-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = TimeSerie::find($timeSerie['id']);
    $indicator = $record->indicator;
    $indicators = [];
    if(isset($record->indicator)) {
      $indicators['body'][0] = $record->indicator;
    } else {
      $indicators['body'] = [];
    }
    $record->indicators = $indicators;
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, TimeSerie $timeSerie)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'time-series'],
      ['record_id', '=', $timeSerie['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('time-serie-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('time_series')->select('blocked')->where('id', $timeSerie['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
    $record['id'] = $timeSerie['id'];
    $record['priod'] = $request->input('priod');
    $record['value'] = $request->input('value');
    $record['description'] = $request->input('description');
    $indicators = $request->input('indicators');
    $record['indicator_id'] = isset($indicators['body'][0]['id']) ? $indicators['body'][0]['id'] : null;
    $params['record'] = $record;
    $params['user_id'] = $userId;
    DB::transaction(function () use ($params) {
      DB::table('time_series')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F0';
      $record['form_title'] = 'Alta de';
      $record['name'] = 'time-series';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['record']['name'];
      $record['t2'] = null;
      $record['t3'] = null;
      $record['created_by'] = $params['user_id'];
      $record['updated_by'] = $params['user_id'];
      $record["created_at"] = Carbon::now()->toDateTimeString();
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'time-series']])->update($record);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(TimeSerie $timeSerie)
  {
    $userId = Auth::id();
    if (!Gate::allows('time-serie-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('time_series')->select('blocked')->where('id', $timeSerie['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $timeSerie;
    DB::transaction(function () use ($params) {
      TimeSerie::destroy($params['record']['id']);
      Record::where('record_id', $params['record']['id'])->delete();
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function all(Request $request)
  {
      $response = [];
      $req = json_decode($request->getContent(), true);
      $records = TimeSerie::where($req)->get();
      $response['data'] = $records;
      return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = TimeSerie::where($filter)
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
      DB::table('time_series')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'time-series']])->update(['cancelled' => $params['req']['value']]);
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
      DB::table('time_series')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'time-series']])->update(['blocked' => $params['req']['value']]);
    });
    $response = [];
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
}
