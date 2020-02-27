<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Auth;
use App\Service;
use App\SaleFee;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CustomServiceController extends Controller
{
  //
  public function list(Request $request)
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
    $records = Service::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('project', function ($query) use ($params) {
        });
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('due_date', 'LIKE', "%".$params["search"]["value"]."%");
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
      $project = $record->project;
      $project->users;
      $people = "";
      for ($i = 0; $i < count($project->users); $i++) {
        $people = $people.''.$project->users[$i]['name']." ".$project->users[$i]['last_name']." CUIT: ".$project->users[$i]['tin'];
        if($i < count($project->users) - 1) {
          $people = $people.', ';
        }
      }
      $record->people = $people;
      if(strlen($record->detail) > 200){ $record->detail = mb_substr($record->detail, 0, 200).' ...'; }else{ $record->detail = $record->detail; }
      return $record;
    });
    $response['data'] = $records;
    return response()->json($response, 200);
  }
  /*******************
   * Lista servicios Pendientes, cuando done es null o 0 (cero)
   * Retorna: array json con [{total}]
   *******************/
  public function getPending() {
    $response = [];
    $response['total'] = Service::whereNull("done")->orWhere("done","=",0)->count();  
    return response()->json($response, 200);    
  }


}
