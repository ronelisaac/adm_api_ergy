<?php

namespace App\Http\Controllers;
use App\Broadcast;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Gate;
use Validator;
class BroadcastController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
      //
    $response = [];
    if (!Gate::allows('area-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Broadcast::where($funnel["filters"]);
    
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
     
      $fields = array
      (
        array(
          'label' => 'ID',
          'model' => 'id',
          'value' => $record->id,
          'type' => 'number',
          'classes' => ''
        ),
        array(
          'label' => 'Periodo',
          'model' => 'period',
          'value' => $record->period,
          'type' => 'string',
          'classes' => ''
        ),
        array(
          'label' => 'Fecha de vencimiento',
          'model' => 'due_date',
          'value' => $record->due_date,
          'type' => 'date',
          'classes' => ''
        ),
        array(
          'label' => 'Fecha desde',
          'model' => 'from',
          'value' => $record->from,
          'type' => 'date',
          'classes' => ''
        ),
        array(
          'label' => '',
          'model' => 'done',
          'value' => $record->done,
          'type' => 'integer',
          'classes' => ''
        ),
        array(
          'label' => 'Cerrados/as',
          'model' => 'closed',
          'value' => $record->closed,
          'type' => 'integer',
          'classes' => ''
        ),
        array(
          'label' => 'Total',
          'model' => 'total',
          'value' => $record->total,
          'type' => 'integer',
          'classes' => ''
        ),
        array(
          'label' => 'Modificado el',
          'model' => 'updated_at',
          'value' => $record->updated_at,
          'type' => 'datetime',
          'classes' => ''
        )
        
      );
      return $fields;
    });
    $response['data'] = $records;
    return response()->json($response, 200);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      //
      $req = json_decode($request->getContent(), true);
      $response = [];
      if (!Gate::allows('broadcast-create') && !Auth::user()->administrator){
        $response['status'] = 'error';
        $response['msg'] = 'No estas autorizado';
        return response()->json($response, 403);
      }
      $messages = [
        'required' => 'El campo :attribute es requerido.',
        'due_date.after' => 'El campo fecha de vencimiento tiene que ser posterior a hoy'
      ];
      $validator = Validator::make($req, [
        'period' => 'required|unique:broadcasts',
        'due_date' => 'required|date|after:today',
        'from' => 'required|date',
      ], $messages);
      if ($validator->fails()) {
        $response['error'] = "Faltan campos";
        return response()->json($validator->errors(), 422);
      }
      $userId = Auth::id();
      $record = [];
      $record['period'] = $request->input('period');
      $record['due_date'] = $request->input('due_date');
      $record['from'] = $request->input('from');
      $record['form_name'] = $request->input('form_name');
      //$record['schema'] = $request->input('schema');
      $record['created_by'] = $userId;
      $record['updated_by'] = $userId;
      $record["created_at"] = Carbon::now()->toDateTimeString();
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('broadcasts')->insert($record);
      $response['success'] = true;
      return response()->json($response, 200);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
      //
    $response = [];
    if (!Gate::allows('broadcast-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Broadcast::find($id);
    $response['data'] = $record;
    return response()->json($response, 200);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
      //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */

  
  // public function update(Request $request, $id)
  // {
  //     //
  //     $req = json_decode($request->getContent(), true);
  //     $response = [];
  //     if (!Gate::allows('broadcast-edit') && !Auth::user()->administrator){
  //       $response['status'] = 'error';
  //       $response['msg'] = 'No estas autorizado';
  //       return response()->json($response, 403);
  //     }
  //     $messages = [
  //       'required' => 'El campo :attribute es requerido.',
  //       'due_date.after' => 'El campo fecha de vencimiento tiene que ser posterior a hoy'
  //     ];
  //     $validator = Validator::make($req, [
  //       'period' => 'required|unique:broadcasts,period,' . $id,
  //       'due_date' => 'required|date',
  //       'from' => 'required|date',
  //     ], $messages);
  //     if ($validator->fails()) {
  //       $response['error'] = "Faltan campos";
  //       return response()->json($validator->errors(), 422);
  //     }
  //     $userId = Auth::id();
  //     $record = [];
  //     $record['id'] = $id;
  //     $record['period'] = $request->input('period');
  //     $record['due_date'] = $request->input('due_date');
  //     $record['from'] = $request->input('from');
  //     $record['created_by'] = $userId;
  //     $record['updated_by'] = $userId;
  //     $record["created_at"] = Carbon::now()->toDateTimeString();
  //     $record["updated_at"] = Carbon::now()->toDateTimeString();
  //     DB::table('broadcasts')->where('id', $id)->update($record);
  //     $response['success'] = true;
  //     return response()->json($response, 200);
  // }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
      //
  }
}
