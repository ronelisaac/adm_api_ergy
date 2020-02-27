<?php

namespace App\Http\Controllers;

use App\Record;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Gate;
class RecordController extends Controller
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
        if (!Gate::allows('record-read') && !Auth::user()->administrator){
                
          $response['status'] = 'error';
          $response['msg'] = 'No estas autorizado';
        
          return response()->json($response, 403);
          
        }
        $offset = $request->input('offset');
        $limit = $request->input('limit');
        $funnel = json_decode($request->input('funnel'), true);
       
        
        $records = Record::where($funnel["filters"]);

        $params = [];
        $params['search'] = $funnel["search"];
        if($funnel['search']['value'] != '') {
          if($funnel["search"]["column"] == "all") {
            
            $records->where('id', 'LIKE', "%".$params["search"]["value"]."%")
            
                    ->orWhere('record_id', 'LIKE', "%".$params["search"]["value"]."%")
                    ->orWhere('form_code', 'LIKE', "%".$params["search"]["value"]."%")
                    ->orWhere('form_title', 'LIKE', "%".$params["search"]["value"]."%")
                    ->orWhere('name', 'LIKE', "%".$params["search"]["value"]."%")
                    ->orWhere('period', 'LIKE', "%".$params["search"]["value"]."%")
                    ->orWhere('due_date', 'LIKE', "%".$params["search"]["value"]."%")
                    ->orWhere('t1', 'LIKE', "%".$params["search"]["value"]."%")
                    ->orWhere('t2', 'LIKE', "%".$params["search"]["value"]."%")
                    ->orWhere('t3', 'LIKE', "%".$params["search"]["value"]."%");
            //${searchOnes}
          } else {
            
            $records->where($params["search"]["column"], 'LIKE', "%".$params["search"]["value"]."%");
                      
          }
        }
        
        if($limit != 0) {
          $records = $records->offset($offset)
          ->limit($limit);
        }
        if($funnel["sort"] == '') {
          $records = $records->orderBy('updated_at', 'desc')->orderBy('id', 'desc');
        } else {
          $sort = explode('.', $funnel["sort"]);
          $records = $records->orderBy($sort[0], $sort[1]);
        }
                  
        $records = $records->get()
          ->map(function ($record) { 
            $record->users;
            return $record;
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
        $response = [];
        if (!Gate::allows('record-create') && !Auth::user()->administrator){
                
          $response['status'] = 'error';
          $response['msg'] = 'No estas autorizado';
        
          return response()->json($response, 403);
          
        }
        $userId = Auth::id();
        $response = [];
        $record = [];

        $record['record_id'] = $request->input('record_id');
        $record['form_code'] = $request->input('form_code');
        $record['form_title'] = $request->input('form_title');
        $record['name'] = $request->input('name');
        $record['period'] = $request->input('period');
        $record['due_date'] = $request->input('due_date');
        $record['t1'] = $request->input('t1');
        $record['t2'] = $request->input('t2');
        $record['t3'] = $request->input('t3');
        $record['created_by'] = $userId;
        $record['updated_by'] = $userId;
        $record["created_at"] = Carbon::now()->toDateTimeString();
        $record["updated_at"] = Carbon::now()->toDateTimeString();
        $params = [];
        $params['record'] = $record;
        $params['user_id'] = $userId;
        DB::transaction(function () use ($params) {
          $id = DB::table('records')->insertGetId($params['record']);
        });


        $response['success'] = true;
        
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function show(Record $record)
    {
        //
        $response = [];
        if (!Gate::allows('record-read') && !Auth::user()->administrator){
                
          $response['status'] = 'error';
          $response['msg'] = 'No estas autorizado';
        
          return response()->json($response, 403);
          
        }
        $record = Record::find($record['id']);


        
        
        $response['data'] = $record;
       
        
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(Record $record)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $record)
    {
        //
        $response = [];
        if (!Gate::allows('record-edit') && !Auth::user()->administrator){
                
          $response['status'] = 'error';
          $response['msg'] = 'No estas autorizado';
        
          return response()->json($response, 403);
          
        }
        $userId = Auth::id();
        

        $record['id'] = $record['id'];
        $record['record_id'] = $request->input('record_id');
        $record['form_code'] = $request->input('form_code');
        $record['form_title'] = $request->input('form_title');
        $record['name'] = $request->input('name');
        $record['period'] = $request->input('period');
        $record['due_date'] = $request->input('due_date');
        $record['t1'] = $request->input('t1');
        $record['t2'] = $request->input('t2');
        $record['t3'] = $request->input('t3');
        $record['created_by'] = $userId;
        $record['updated_by'] = $userId;
        $record["created_at"] = Carbon::now()->toDateTimeString();
        $record["updated_at"] = Carbon::now()->toDateTimeString();
        $params = [];
        $params['record'] = $record;
        $params['user_id'] = $userId;
        DB::transaction(function () use ($params) {
          DB::table('records')->where('id', $params['record']['id'])->update($params['record']);
        });

        
        $response['success'] = true;
        
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TaskGroup  $taskGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $record)
    {
        //
        $response = [];
        if (!Gate::allows('record-delete') && !Auth::user()->administrator){
                
          $response['status'] = 'error';
          $response['msg'] = 'No estas autorizado';
        
          return response()->json($response, 403);
          
        }
        
        
        Record::destroy($record['id']);
        $response['success'] = true;
        return response()->json($response, 200);
    }
    public function listAll(Request $request)
    {
        

      
        $response = [];
        $req = json_decode($request->getContent(), true);
        $records = Record::where($req)->get()
        ->map(function($record) {
          $record->search_field = $record->name.' - '.$record->last_name;
          return $record;
        });
        $response['data'] = $records;
        
        return response()->json($response, 200);
        
       
    }
    public function dataCheckboxs($checkboxs) {
        $a = [];
        foreach ($checkboxs as $key => $value) {
          //if($i == )
          $a[] = $value['id'];
          //$p[] = $key;
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
    public function shared(Request $request)
    {
      $response = [];
        
      $name = $request->input('name');
      $id = $request->input('id');

      $response['name'] = $name;
      $response['id'] = $id;
      $record = Record::where('record_id', $id)
                ->where('name', $name)
                ->with('users')->first();
      $response['data'] = $record;
      return response()->json($response, 200);
    }
   
    public function share(Request $request)
    {
      $userId = Auth::id();
      $response = [];
      $req = json_decode($request->getContent(), true);
      $params = [];
      $params['req'] = $req;
      $params['user_id'] = $userId;
      DB::transaction(function () use ($params) {
        $record = Record::where('record_id', $params["req"]["id"])
          ->where('name', $params["req"]["name"])
          ->first();
        $recordId = $record->id;
         
        DB::table('record_user')->where('record_id', $recordId)->delete();
        $users = [];
        foreach ($params['req']['users'] as $value) {
          $users[] = [
            'record_id' => $recordId,
            'user_id' => $value['id'],
            'read' => $value['read'],
            'write' => $value['write'],
            'created_by' => $params['user_id'],
            'updated_by' => $params['user_id'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
          ];
        };
        DB::table('record_user')->insert($users);

        
      });
      $response['success'] = true;
      return response()->json($response, 200);
    }

    public function listApprovals(Request $request)
    {
      $response = [];
        
      $name = $request->input('name');
      $id = $request->input('id');

      $response['name'] = $name;
      $response['id'] = $id;
      $record = Record::where('record_id', $id)
                ->where('name', $name)
                ->with('approvals')->first();
      $response['data'] = $record;
      return response()->json($response, 200);
    }
   
    public function updateApprovals(Request $request)
    {
      $userId = Auth::id();
      $response = [];
      $req = json_decode($request->getContent(), true);
      $params = [];
      $params['req'] = $req;
      $params['user_id'] = $userId;
      DB::transaction(function () use ($params) {
        $record = Record::where('record_id', $params["req"]["id"])
          ->where('name', $params["req"]["name"])
          ->first();
        $recordId = $record->id;
         
        DB::table('approvals')->where('record_id', $recordId)->delete();
        $users = [];
        foreach ($params['req']['users'] as $value) {
          $users[] = [
            'record_id' => $recordId,
            'user_id' => $value['id'],
            'created_by' => $params['user_id'],
            'updated_by' => $params['user_id'],
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
          ];
        };
        DB::table('approvals')->insert($users);

        
      });
      $response['success'] = true;
      return response()->json($response, 200);
    }
    
    public function listUserRecords(Request $request)
    {
        

      
        $response = [];
        // $req = json_decode($request->getContent(), true);
        // $records = User::where($req)->get()
        // ->map(function($record) {

        //   return $record;
        // });
        // $response['data'] = $records;
        
        
        //$req = json_decode($request->getContent(), true);
        $offset = $request->input('offset');
        $limit = $request->input('limit');
        $funnel = json_decode($request->input('funnel'), true);
        $params = [];
        //$params['req'] = $req;
        $params['offset'] = $offset;
        $params['limit'] = $limit;
        $params['funnel'] = $funnel;
        $userId = Auth::id();
        // $user = User::where('id', $userId)->whereDoesntHave('records', function ($query) use ($params) {
        //     $query->where($params['req']['filters']);
        //     //$query->where(["done", "=", false]);
        // })->get()
        // //})->first();
        // //$user->records;
        // ->map(function ($record) { 
        //   $record->records;
        //   return $record;
        // });


        $user = User::where('id', $userId)->with(['records' => function ($query) use ($params) {
          $query->where($params['funnel']['filters']);
          if($params['limit'] != 0) {
            $query = $query->offset($params['offset'])
            ->limit($params['limit']);
            $query = $query->orderBy('updated_at', 'desc');
          }
        }])->first();
        $response['data'] = $user;
        
        $response['offset'] = $offset;
        $response['limit'] = $limit;
        $response['funnel'] = $funnel;

        return response()->json($response, 200);
        
       
    }
}

