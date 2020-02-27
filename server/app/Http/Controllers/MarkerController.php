<?php
namespace App\Http\Controllers;
use App\Marker;
use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Gate;
use Validator;
class MarkerController extends Controller
{
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('marker-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Marker::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
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
      if($record->blocked) $states[] = 'blocked';
      if($record->cancelled) $states[] = 'cancelled';
      if($record->done) $states[] = 'done';
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
    if (!Gate::allows('marker-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $userId = Auth::id();
    $record = [];
    $params = [];
    $obj = $request->input('records');
    $records = isset($obj) ? $obj['body'] : [];
    $params['records'] = $records;
    $footer = isset($obj) ? $obj['footer'] : [];
    $record['name'] = $request->input('name');
    $record['description'] = $request->input('description');
    $record['created_by'] = $userId;
    $record['updated_by'] = $userId;
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    DB::transaction(function () use ($params) {
      $id = DB::table('markers')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F0';
      $record['form_title'] = 'Alta de';
      $record['name'] = 'markers';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t3'] = null;
      $record['created_by'] = $params['user_id'];
      $record['updated_by'] = $params['user_id'];
      $record["created_at"] = Carbon::now()->toDateTimeString();
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->insert($record);
      $records = [];
      foreach ($params['records'] as $value) {
        $records[] = [
          'record_id' => $value['id'],
          'marker_id' => $id,
          'name' => $value['name'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('marker_record')->insert($records);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function show(Marker $marker)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'markers'],
      ['record_id', '=', $marker['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('marker-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Marker::find($marker['id']);
    $body = $record->records;
    foreach ($record->records as $record) {
    }
    $records = [];
    $records['body'] = $body;
    $records['footer'] = [];
    if(!is_array ($record)) $record = $record->toArray();
    $record['records'] = $records;
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, Marker $marker)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'markers'],
      ['record_id', '=', $marker['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('marker-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('markers')->select('blocked')->where('id', $marker['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
    $record['id'] = $marker['id'];
    $obj = $request->input('records');
    $records = $obj['body'];
    $params['records'] = $records;
    $footer = isset($obj) ? $obj['footer'] : [];
    $record['name'] = $request->input('name');
    $record['description'] = $request->input('description');
    $params['record'] = $record;
    $params['user_id'] = $userId;
    DB::transaction(function () use ($params) {
      DB::table('markers')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F0';
      $record['form_title'] = 'Alta de';
      $record['name'] = 'markers';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t3'] = null;
      $record['created_by'] = $params['user_id'];
      $record['updated_by'] = $params['user_id'];
      $record["created_at"] = Carbon::now()->toDateTimeString();
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'markers']])->update($record);
      DB::table('marker_record')->where('marker_id', $params['record']['id'])->delete();
      $records = [];
      foreach ($params['records'] as $value) {
        $records[] = [
          'record_id' => $value['id'],
          'marker_id' => $params['record']['id'],
          'name' => $value['name'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('marker_record')->insert($records);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(Marker $marker)
  {
    $userId = Auth::id();
    if (!Gate::allows('marker-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('markers')->select('blocked')->where('id', $marker['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $marker;
    DB::transaction(function () use ($params) {
      Marker::destroy($params['record']['id']);
      Record::where('record_id', $params['record']['id'])->delete();
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = Marker::where($filter)
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
      DB::table('markers')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'markers']])->update(['cancelled' => $params['req']['value']]);
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
      DB::table('markers')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'markers']])->update(['blocked' => $params['req']['value']]);
    });
    $response = [];
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function refresh(Request $request)
  {
    $req = json_decode($request->getContent(), true);
    $params = [];
    $params['req'] = $req;
    
    $response = [];
    $response['req'] = $req;
    $response['success'] = true;
    return response()->json($response, 200);
  }
}
