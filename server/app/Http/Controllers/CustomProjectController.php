<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Project;
use App\Service;
class CustomProjectController extends Controller
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
    $records = Project::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('user', function ($query) use ($params) {
        });
        $records->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%");
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
      $people = "";
      for ($i = 0; $i < count($record->users); $i++) {
        $people = $people.''.$record->users[$i]['name']." ".$record->users[$i]['last_name']." CUIT: ".$record->users[$i]['tin'];
        if($i < count($record->users) - 1) {
          $people = $people.', ';
        }
      }
      $record->people = $people;
      // $record->person = $record->user->name." ".$record->user->last_name." - CUIT: ".$record->user->tin;
      $services = $record->services;
      $pesos = 0;
      $dollars = 0;
      $pesos_collected = 0;
      $dollars_collected = 0;
      $pesos_invoiced = 0;
      $dollars_invoiced = 0;
      $n = 0;
      $done = 0;
      $descriptions = "";
      for ($i = 0; $i < count($services); $i++) {
        if($services[$i]['currency'] == 'Peso') {
          $pesos = $pesos + $services[$i]["amount"];
          $pesos_collected = $pesos_collected + $services[$i]["collected"];
        } else if($services[$i]['currency'] == 'Dolar') {
          $dollars = $dollars + $services[$i]["amount"];
          $dollars_collected = $dollars_collected + $services[$i]["collected"];
        }
        // $pesos = $pesos + $services[$i]["pesos"];
        // $dollars = $dollars + $services[$i]["dollars"];
        // $pesos_collected = $pesos_collected + $services[$i]["pesos_collected"];
        // $dollars_collected = $dollars_collected + $services[$i]["dollars_collected"];


        if($services[$i]["done"]) {
          $done++;
        }
        $n++;

        $descriptions = $descriptions.''.$services[$i]["detail"];
        if($i < count($services) - 1) {
          $descriptions = $descriptions.' - ';
        }
      }
      if(strlen($record->description) > 200){ $record->description = mb_substr($record->description, 0, 200).' ...'; }else{ $record->description = $record->description; }
      if(strlen($record->observations) > 200){ $record->observations = mb_substr($record->observations, 0, 200,'utf-8').' ...'; }else{ $record->observations = $record->observations; }
      //$record->descriptions = $descriptions;

      $record->pesos = $pesos;
      $record->dollars = $dollars;
      $record->pesos_collected = $pesos_collected;
      $record->dollars_collected = $dollars_collected;
      $record->pesos_invoiced = $pesos_invoiced;
      $record->dollars_invoiced = $dollars_invoiced;
      $record->project_services = $done.' de '.$n;
      
      return $record;
    });
    $response['data'] = $records;
    return response()->json($response, 200);
  }
  /*********
   * Genera una lista de proyectos 
   * Retorna: array json con [{id,name}]
   *********/
  public function all()
  {
    if (!Gate::allows('project-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $records = [];
    $response = [];
    $records = Project::orderBy("created_at","DESC")->get();
    foreach ($records as $index => $p) {
      $response[$index]['id'] = $p->id;
      $response[$index]['name'] = $p->name;
    }
    return response()->json($response, 200);
   
  }
  public function getAmounts($project_id)
  {
    if (!Gate::allows('project-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
   // $project_id=1;
    $response = [];
    if(is_integer($project_id)) {
      #suma todos los importes del proyecto
      $pesos_total=Service::where("project_id","=",$project_id)->where("currency","=",'Peso')->sum("amount");  
      $dollars_total=Service::where("project_id","=",$project_id)->where("currency","=",'Dolar')->sum("amount");
      #suma todos los importes cobrados 
      $collected_pesos=Service::where("project_id","=",$project_id)->where("currency","=",'Peso')->sum("collected"); 
      $collected_dollars=Service::where("project_id","=",$project_id)->where("currency","=",'Dolar')->sum("collected");
      #importes a cobrar
      $receivable_pesos = $pesos_total-$collected_pesos;
      $receivable_dollars = $dollars_total -$collected_dollars;
      //$receivable_pesos=Service::where("project_id","=",$project_id)->where("currency","=",'Peso')->whereNull("collected_service")->orWhere("collected_service","=",0)->sum("amount");  
      //$receivable_dollars=Service::where("project_id","=",$project_id)->where("currency","=",'Dolar')->whereNull("collected_service")->orWhere("collected_service","=",0)->sum("amount");

      $response['pesos_total'] = $pesos_total;
      $response['dollars_total'] = $dollars_total;
      $response['collected_dollars'] = $collected_dollars;
      $response['collected_pesos'] = $collected_pesos;
      $response['receivable_dollars'] = $receivable_dollars;
      $response['receivable_pesos'] = $receivable_pesos;
    } else {
      //receivable = 'a cobrar'
      #suma todos los importes del proyecto
      $pesos_total=Service::where("currency","=",'Peso')->sum("amount");  
      $dollars_total=Service::where("currency","=",'Dolar')->sum("amount");
      #suma todos los importes cobrados 
      $collected_pesos=Service::where("currency","=",'Peso')->sum("collected"); 
      $collected_dollars=Service::where("currency","=",'Dolar')->sum("collected");
      #importes a cobrar
      $receivable_pesos = $pesos_total-$collected_pesos;
      $receivable_dollars = $dollars_total -$collected_dollars;
      $response['receivable_dollars'] =$receivable_dollars;
      $response['receivable_pesos'] = $receivable_pesos;
    }
    return response()->json($response, 200);
  }
  public function downloadPurchaseOrderPdf($id) {
    $userId = Auth::id();
    $record = project::find($id);
    $record->services;
    $record->users;
    $response = [];
    $response['data'] = $record;
    //print_r($record);
    $pdf = \PDF::loadView('own/purchase-order-pdf', compact('record'));
    $pdf->save(storage_path().'/files/pdf/purchase-order-'.$record->id.'.pdf');
    $b64Doc = base64_encode(file_get_contents(storage_path('/files/pdf/purchase-order-'.$record->id.'.pdf')));
    $response['route']=$b64Doc;
    $response['success'] = true;
    $response['response'] = $response;
    return response()->json($response, 200);
  }
 
  /*********
  * Genera una lista de clientes asociados a los proyectos 
  * Retorna: array json con [{id,name}]
  *********/
  public function listCustomers()
  {
    if (!Gate::allows('project-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $records = [];
    $response = [];
    $records = DB::table('users')->select("users.id","users.search_field as name")->join('project_user', 'users.id', '=', 'project_user.user_id')->groupBy('users.id')->get();
    $response['data'] = $records;
    return response()->json($response, 200);
  }
}
