<?php
namespace App\Http\Controllers;
use App\Purchase;
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
class PurchaseController extends Controller
{
  #variable que almacena el nombre del formulario
  private $form_name;
  #Constructor
  function __construct(){
    $this->form_name ='purchases';
  }
  public function index(Request $request)
  {
    $response = [];
    if (!Gate::allows('purchase-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Purchase::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('currency', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('type', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('point_of_sale', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('number', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('user', function ($query) use ($params) {
          $query->where('user_search_field', 'LIKE', "%".$params["search"]["value"]."%");
        });
        $records->orWhere('detail', 'LIKE', "%".$params["search"]["value"]."%");
      } else {
        switch ($funnel["search"]["column"]) {
          case 'users':
            # code...
            $records->whereHas('user', function ($query) use ($params) {
              $query->where('user_search_field', 'LIKE', "%".$params["search"]["value"]."%");
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
      $user = [];
      $userString = [];
      $userData = Purchase::find($record->id);
      if(isset($userData->user)) {
        $userString[0] = $userData['user_search_field'];
         $user[] =   implode(" ", $userString);
       } 
      if(strlen($record->detail) > 200){ $record->detail = mb_substr($record->detail, 0, 200, "utf-8").' ...'; }else{ $record->detail = $record->detail; }
       $outgoing_checks = $record->outgoing_checks;
       $outgoing_check = [];
       $outgoing_checkString = [];
       foreach ($outgoing_checks as $key => $value) {
        $outgoing_checkString[0] = $value->check_search_field;
        $outgoing_checkString[1] = $value->amount;
         $outgoing_check[] =   implode(" ", $outgoing_checkString);
       }
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
          'label' => 'Fecha del comprobante',
          'model' => 'date',
          'value' => $record->date,
          'type' => 'date',
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
          'label' => 'Tipo de cambio',
          'model' => 'exchange_rate',
          'value' => $record->exchange_rate,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Tipo de comprobante',
          'model' => 'type',
          'value' => $record->type,
          'type' => 'select',
          'classes' => ''
        ),
        array(
          'label' => 'Punto de venta',
          'model' => 'point_of_sale',
          'value' => $record->point_of_sale,
          'type' => 'integer',
          'classes' => ''
        ),
        array(
          'label' => 'Numero de comprobante',
          'model' => 'number',
          'value' => $record->number,
          'type' => 'integer',
          'classes' => ''
        ),
        array(
          'label' => 'Proveedor',
          'model' => 'users',
          'value' => implode(', ', $user),
          'type' => 'autocomplete',
          'classes' => ''
        ),
        array(
          'label' => 'Detalle',
          'model' => 'detail',
          'value' => $record->detail,
          'type' => 'longText',
          'classes' => ''
        ),
        // array(
        //   'label' => 'Importe neto',
        //   'model' => 'net_amount',
        //   'value' => $record->net_amount,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 21%',
        //   'model' => 'tax_1',
        //   'value' => $record->tax_1,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 10,5',
        //   'model' => 'tax_2',
        //   'value' => $record->tax_2,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 2,5%',
        //   'model' => 'tax_4',
        //   'value' => $record->tax_4,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 5%',
        //   'model' => 'tax_3',
        //   'value' => $record->tax_3,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 27%',
        //   'model' => 'tax_5',
        //   'value' => $record->tax_5,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Importe exento',
        //   'model' => 'exempt',
        //   'value' => $record->exempt,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Importe no gravado',
        //   'model' => 'untaxed',
        //   'value' => $record->untaxed,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Impuestos internos',
        //   'model' => 'internal_tax',
        //   'value' => $record->internal_tax,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Percepcion de IIBB',
        //   'model' => 'perception_4',
        //   'value' => $record->perception_4,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Perc. de imp. mun.',
        //   'model' => 'perception_3',
        //   'value' => $record->perception_3,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Percepción de IVA',
        //   'model' => 'perception_1',
        //   'value' => $record->perception_1,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Perc. de otros imp. nac.',
        //   'model' => 'perception_2',
        //   'value' => $record->perception_2,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        array(
          'label' => 'Total',
          'model' => 'total',
          'value' => $record->total,
          'type' => 'decimal',
          'classes' => ''
        ),
        // array(
        //   'label' => 'Contado',
        //   'model' => 'cash',
        //   'value' => $record->cash,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Deposito bancario',
        //   'model' => 'bank_deposit',
        //   'value' => $record->bank_deposit,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Tarjeta de débito',
        //   'model' => 'debit_card',
        //   'value' => $record->debit_card,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Tarjeta de crédito',
        //   'model' => 'credit_card',
        //   'value' => $record->credit_card,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Compra a crédito',
        //   'model' => 'on_credit',
        //   'value' => $record->on_credit,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
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
          'label' => 'Fecha del comprobante',
          'model' => 'date',
          'value' => NULL,
          'type' => 'date',
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
          'label' => 'Tipo de cambio',
          'model' => 'exchange_rate',
          'value' => NULL,
          'type' => 'decimal',
          'classes' => ''
        ),
        array(
          'label' => 'Tipo de comprobante',
          'model' => 'type',
          'value' => NULL,
          'type' => 'select',
          'classes' => ''
        ),
        array(
          'label' => 'Punto de venta',
          'model' => 'point_of_sale',
          'value' => NULL,
          'type' => 'integer',
          'classes' => ''
        ),
        array(
          'label' => 'Numero de comprobante',
          'model' => 'number',
          'value' => NULL,
          'type' => 'integer',
          'classes' => ''
        ),
        array(
          'label' => 'Proveedor',
          'model' => 'users',
          'value' => 'NULL',
          'type' => 'autocomplete',
          'classes' => ''
        ),
        array(
          'label' => 'Detalle',
          'model' => 'detail',
          'value' => NULL,
          'type' => 'longText',
          'classes' => ''
        ),
        // array(
        //   'label' => 'Importe neto',
        //   'model' => 'net_amount',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 21%',
        //   'model' => 'tax_1',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 10,5',
        //   'model' => 'tax_2',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 2,5%',
        //   'model' => 'tax_4',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 5%',
        //   'model' => 'tax_3',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'IVA 27%',
        //   'model' => 'tax_5',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Importe exento',
        //   'model' => 'exempt',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Importe no gravado',
        //   'model' => 'untaxed',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Impuestos internos',
        //   'model' => 'internal_tax',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Percepcion de IIBB',
        //   'model' => 'perception_4',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Perc. de imp. mun.',
        //   'model' => 'perception_3',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Percepción de IVA',
        //   'model' => 'perception_1',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Perc. de otros imp. nac.',
        //   'model' => 'perception_2',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        array(
          'label' => 'Total',
          'model' => 'total',
          'value' => NULL,
          'type' => 'decimal',
          'classes' => ''
        ),
        // array(
        //   'label' => 'Contado',
        //   'model' => 'cash',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Deposito bancario',
        //   'model' => 'bank_deposit',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Tarjeta de débito',
        //   'model' => 'debit_card',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Tarjeta de crédito',
        //   'model' => 'credit_card',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
        // array(
        //   'label' => 'Compra a crédito',
        //   'model' => 'on_credit',
        //   'value' => NULL,
        //   'type' => 'decimal',
        //   'classes' => ''
        // ),
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
    if (!Gate::allows('purchase-create') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    #inicialice objet Purchase
    $objPurchase=new Purchase();
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
    #funcion que esta en el método purchases y se encarga de
    #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
    $array_validator=$objPurchase->validationsReturns($arrayFields,'store',false);
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
    $record['search_field'] = (null !== $request->input('id') ? $request->input('id') : "").' - '.(null !== $request->input('users')['body'][0]['full_name'] ? $request->input('users')['body'][0]['full_name'] : "").' - '.(null !== $request->input('detail') ? $request->input('detail') : "").' - '.(null !== $request->input('total') ? $request->input('total') : "");
    
    # date
    $record['date'] = $request->input('date');
    # End date
    
    $record['month'] = (null !== $request->input('month')) ? Carbon::parse($request->input('month'))->format("Y-m-d") : null;
    
    # currency
    $record['currency'] = $request->input('currency');
    # End currency
    
    
    # exchange_rate
    $record['exchange_rate'] = $request->input('exchange_rate');
    # End exchange_rate
    
    
    # type
    $record['type'] = $request->input('type');
    # End type
    
    
    # point_of_sale
    $record['point_of_sale'] = $request->input('point_of_sale');
    # End point_of_sale
    
    
    # number
    $record['number'] = $request->input('number');
    # End number
    
    
    # Autocomplete users
    $obj = $request->input('users');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['user_id'] = $body[0]['id'];
      $record['user_search_field'] = $body[0]['user_search_field'];
    }
    # End autocomplete users
    $obj = $request->input('accounts');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['account_id'] = $body[0]['id'];
    }
    # User type
    $params['user_type'] = 'Proveedor';
    # End user type
    
    # detail
    $record['detail'] = $request->input('detail');
    # End detail
    
    
    # net_amount
    $record['net_amount_1'] = $request->input('net_amount_1');
    # End net_amount
    
    # net_amount
    $record['net_amount_2'] = $request->input('net_amount_2');
    # End net_amount

    # net_amount
    $record['net_amount_3'] = $request->input('net_amount_3');
    # End net_amount


    # tax_1
    $record['withholding_1'] = $request->input('withholding_1');
    # End tax_1
    # tax_1
    $record['withholding_2'] = $request->input('withholding_2');
    # End tax_1
    # tax_1
    $record['tax_1'] = $request->input('tax_1');
    # End tax_1
    
    
    # tax_2
    $record['tax_2'] = $request->input('tax_2');
    # End tax_2
    
    
    // # tax_4
    // $record['tax_4'] = $request->input('tax_4');
    // # End tax_4
    
    
    # tax_3
    $record['tax_3'] = $request->input('tax_3');
    # End tax_3
    
    
    // # tax_5
    // $record['tax_5'] = $request->input('tax_5');
    // # End tax_5
    
    
    # exempt
    $record['exempt'] = $request->input('exempt');
    # End exempt
    
    
    # untaxed
    $record['untaxed'] = $request->input('untaxed');
    # End untaxed
    
    
    # internal_tax
    $record['internal_tax'] = $request->input('internal_tax');
    # End internal_tax
    
    
    # perception_4
    $record['perception_4'] = $request->input('perception_4');
    # End perception_4
    
    
    # perception_3
    $record['perception_3'] = $request->input('perception_3');
    # End perception_3
    
    
    # perception_1
    $record['perception_1'] = $request->input('perception_1');
    # End perception_1
    
    
    # perception_2
    $record['perception_2'] = $request->input('perception_2');
    # End perception_2
    
    
    # cash
    $record['cash'] = $request->input('cash');
    # End cash
    
    
    # bank_deposit
    $record['bank_deposit'] = $request->input('bank_deposit');
    # End bank_deposit
    
    # bank_deposit
    $record['automatic_debit'] = $request->input('automatic_debit');
    # End bank_deposit
    
    # debit_card
    $record['debit_card'] = $request->input('debit_card');
    # End debit_card
    
    
    # credit_card
    $record['credit_card'] = $request->input('credit_card');

    # Total
    $record['total'] = $request->input('net_amount_1') + 
                       ($request->input('net_amount_1') * 0.21) +
                       $request->input('net_amount_2') + 
                       ($request->input('net_amount_2') * 0.105) +
                       $request->input('net_amount_3') + 
                       ($request->input('net_amount_3') * 0.27) +
                       $request->input('perception_1') + 
                       $request->input('perception_2') + 
                       $request->input('perception_3') + 
                       $request->input('perception_4') + 
                       $request->input('untaxed') +  
                       $request->input('exempt');
    #end total
    
    
    # Autocomplete outgoing_checks
    $obj = $request->input('outgoing_checks');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $record['total_checks'] = $footer['total_checks'];
    $outgoing_checks = isset($obj) ? $obj['body'] : [];
    $params['outgoing_checks'] = $outgoing_checks;
    # End autocomplete outgoing_checks
    
    
    # on_credit
    $record['on_credit'] = $request->input('on_credit');
    # End on_credit
    
    # assignmentAmount
    $obj = $request->input('projects');
    $projects = isset($obj) ? $obj : [];
    $params['projects'] = $projects;
    $record['unassigned_amount_projects'] = $request->input('unassigned_amount_projects');
    $record['unassigned_percentage_projects'] = $request->input('unassigned_percentage_projects');
    # End assignmentAmount
    # assignmentAmount
    $obj = $request->input('expenses_accounts');
    $expenses_accounts = isset($obj) ? $obj : [];
    $params['expenses_accounts'] = $expenses_accounts;
    $record['unassigned_amount_expenses_accounts'] = $request->input('unassigned_amount_expenses_accounts');
    $record['unassigned_percentage_expenses_accounts'] = $request->input('unassigned_percentage_expenses_accounts');
    # End assignmentAmount
    $record['created_by'] = $userId;
    $record['updated_by'] = $userId;
    $record["created_at"] = Carbon::now()->toDateTimeString();
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (count($request->input('users')['body']) > 0 ? $request->input('users')['body'][0]['search_field'] : "");
    $params['t2'] = (null !== $request->input('total') ? $request->input('total') : "");
    $params['t3'] = (null !== $request->input('date') ? $request->input('date') : "");
    DB::transaction(function () use ($params) {
      $id = DB::table('purchases')->insertGetId($params['record']);
      $record = [];
      $record['record_id'] = $id;
      $record['form_code'] = 'F99';
      $record['form_title'] = 'Alta de compra y gasto';
      $record['name'] = 'purchases';
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
      #guarda los datos del usuario en usertype siempre y cuando no este ya registrado
      if(isset($params['user_type'])){
       if($params['user_type'] != false || $params['user_type'] != ""){
         #verifica si el usuario esta en la tabla user_type con el tipo especificado
         $result=DB::table('user_types')->where("user_id","=",$params['record']['user_id'])->where("type","=",$params['user_type'])->first();
         if(!isset($result->id)){
           $user_types = [];
           $user_types['user_id'] = $params['record']['user_id'];
           $user_types['type'] = $params['user_type'];
           $user_types['created_by'] = $params['user_id'];
           $user_types['updated_by'] = $params['user_id'];
           $user_types["created_at"] = Carbon::now()->toDateTimeString();
           $user_types["updated_at"] = Carbon::now()->toDateTimeString();
           DB::table('user_types')->insert($user_types);
         }
       }
      }
      $outgoing_checks = [];
      foreach ($params['outgoing_checks'] as $value) {
        $outgoing_checks[] = [
          'outgoing_check_id' => $value['id'],
          'purchase_id' => $id,
          'check_search_field' => $value['check_search_field'],
          'amount' => $value['amount'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
           DB::table('outgoing_check_purchase')->insert($outgoing_checks);
      $projects = [];
      foreach ($params['projects'] as $value) {
        $projects[] = [
          'project_id' => $value['id'],
          'purchase_id' => $id,
          'percentage' => $value['percentage'],
          'amount' => $value['amount'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('project_purchase')->insert($projects);
      $expenses_accounts = [];
      foreach ($params['expenses_accounts'] as $value) {
        $expenses_accounts[] = [
          'expenses_account_id' => $value['id'],
          'purchase_id' => $id,
          'percentage' => $value['percentage'],
          'amount' => $value['amount'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('expenses_account_purchase')->insert($expenses_accounts);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function show(Purchase $purchase)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'purchases'],
      ['record_id', '=', $purchase['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('purchase-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Purchase::find($purchase['id']);
    $record->month = (null !== $record->month) ? Carbon::parse($record->month)->format("Y-m") : null;
    $user = $record->user;
    $users = [];
    if(isset($record->user)) {
      $users['body'][0] = $record->user;
      $users['body'][0]['user_search_field'] = $record['user_search_field'];
    } else {
      $users['body'] = [];
    }
    $record->users = $users;

    $account = $record->account;
    $accounts = [];
    if(isset($record->account)) {
      $accounts['body'][0] = $record->account;
      $accounts['body'][0]['account_name'] = $record->account->name;
    } else {
      $accounts['body'] = [];
    }
    $record->accounts = $accounts;

    $body = $record->outgoing_checks;
    foreach ($record->outgoing_checks as $outgoing_check) {
      $outgoing_check->check_search_field = $outgoing_check->pivot->check_search_field;
      $outgoing_check->amount = $outgoing_check->pivot->amount;
    }
    $outgoing_checks = [];
    $outgoing_checks['body'] = $body;
    $outgoing_checks['footer'] = [];
    $outgoing_checks['footer']['total_checks'] = $record->total_checks;
    $record->projects;
    foreach ($record->projects as $project) {
      $project->percentage = $project->pivot->percentage;
      $project->amount = $project->pivot->amount;
    }
    $record->expenses_accounts;
    foreach ($record->expenses_accounts as $expenses_account) {
      $expenses_account->percentage = $expenses_account->pivot->percentage;
      $expenses_account->amount = $expenses_account->pivot->amount;
    }
    if(!is_array ($record)) $record = $record->toArray();
    $record['outgoing_checks'] = $outgoing_checks;
    $response['data'] = $record;
    return response()->json($response, 200);
  }
 
  public function update(Request $request, Purchase $purchase)
  {
    $userId = Auth::id();
    $record = DB::table('records')->select('id')->where([
      ['name', '=', 'purchases'],
      ['record_id', '=', $purchase['id']]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('purchase-edit') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('purchases')->select('blocked')->where('id', $purchase['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $record = [];
    $params = [];
   #inicialice objet Purchase
   $objPurchase=new Purchase();
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
   #funcion que esta en el método purchases y se encarga de
   #generar un arreglo compatible con validate, con los campos disponibles en el setting del form el el arreglo validations
   $array_validator=$objPurchase->validationsReturns($arrayFields,'update',$purchase['id']);
   $validated_data= Validator::make($request->all(),$array_validator);
   if($validated_data->fails()){
     #si hay algun elemento que no es valido, retorna un arreglo de errores
     $response['status'] = 'error';
     $response['success'] = false;
     $response['errors'] = $validated_data->errors();
     return response()->json($response, 412);
   }
   #fin validaciones
    $record['id'] = $purchase['id'];
    $record['search_field'] = (null !== $request->input('id') ? $request->input('id') : "").' - '.(null !== $request->input('users')['body'][0]['full_name'] ? $request->input('users')['body'][0]['full_name'] : "").' - '.(null !== $request->input('detail') ? $request->input('detail') : "").' - '.(null !== $request->input('total') ? $request->input('total') : "");
    
    # date
    $record['date'] = $request->input('date');
    # End date
    
    $record['month'] = (null !== $request->input('month')) ? Carbon::parse($request->input('month'))->format("Y-m-d") : null;
    
    # currency
    $record['currency'] = $request->input('currency');
    # End currency
    
    
    # exchange_rate
    $record['exchange_rate'] = $request->input('exchange_rate');
    # End exchange_rate
    
    
    # type
    $record['type'] = $request->input('type');
    # End type
    
    
    # point_of_sale
    $record['point_of_sale'] = $request->input('point_of_sale');
    # End point_of_sale
    
    
    # number
    $record['number'] = $request->input('number');
    # End number
    
    
    # Autocomplete users
    $obj = $request->input('users');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['user_id'] = $body[0]['id'];
      $record['user_search_field'] = $body[0]['user_search_field'];
    }
    # End autocomplete users
    
    $obj = $request->input('accounts');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $body = isset($obj['body']) ? $obj['body'] : [];
    if(count($body) > 0) {
      $record['account_id'] = $body[0]['id'];
    }

    # User type
    $params['user_type'] = 'Proveedor';
    # End user type
    
    # detail
    $record['detail'] = $request->input('detail');
    # End detail
    
    
    // # net_amount
    // $record['net_amount'] = $request->input('net_amount');
    // # End net_amount
    # net_amount
    $record['net_amount_1'] = $request->input('net_amount_1');
    # End net_amount
    
    # net_amount
    $record['net_amount_2'] = $request->input('net_amount_2');
    # End net_amount
    
    # net_amount
    $record['net_amount_3'] = $request->input('net_amount_3');
    # End net_amount
    # tax_1
    $record['withholding_1'] = $request->input('withholding_1');
    # End tax_1
    # tax_1
    $record['withholding_2'] = $request->input('withholding_2');
    # End tax_1
    # tax_1
    $record['tax_1'] = $request->input('tax_1');
    # End tax_1
    
    
    # tax_2
    $record['tax_2'] = $request->input('tax_2');
    # End tax_2
    
    
    # tax_4
    $record['tax_3'] = $request->input('tax_3');
    # End tax_4
    
    
    // # tax_3
    // $record['tax_3'] = $request->input('tax_3');
    // # End tax_3
    
    
    // # tax_5
    // $record['tax_5'] = $request->input('tax_5');
    // # End tax_5
    
    
    # exempt
    $record['exempt'] = $request->input('exempt');
    # End exempt
    
    
    # untaxed
    $record['untaxed'] = $request->input('untaxed');
    # End untaxed
    
    
    # internal_tax
    $record['internal_tax'] = $request->input('internal_tax');
    # End internal_tax
    
    
    # perception_4
    $record['perception_4'] = $request->input('perception_4');
    # End perception_4
    
    
    # perception_3
    $record['perception_3'] = $request->input('perception_3');
    # End perception_3
    
    
    # perception_1
    $record['perception_1'] = $request->input('perception_1');
    # End perception_1
    
    
    # perception_2
    $record['perception_2'] = $request->input('perception_2');
    # End perception_2
    
    
    # cash
    $record['cash'] = $request->input('cash');
    # End cash
    
    
    # bank_deposit
    $record['bank_deposit'] = $request->input('bank_deposit');
    # End bank_deposit
    
    # bank_deposit
    $record['automatic_debit'] = $request->input('automatic_debit');
    # End bank_deposit
    
    # debit_card
    $record['debit_card'] = $request->input('debit_card');
    # End debit_card
    
    
    # credit_card
    $record['credit_card'] = $request->input('credit_card');
    # End credit_card

    #total
    $record['total'] = $request->input('net_amount_1') + 
                       ($request->input('net_amount_1') * 0.21) +
                       $request->input('net_amount_2') + 
                       ($request->input('net_amount_2') * 0.105) +
                       $request->input('net_amount_3') + 
                       ($request->input('net_amount_3') * 0.27) +
                       $request->input('perception_1') + 
                       $request->input('perception_2') + 
                       $request->input('perception_3') + 
                       $request->input('perception_4') + 
                       $request->input('untaxed') +  
                       $request->input('exempt');
    #end total

    # Autocomplete outgoing_checks
    $obj = $request->input('outgoing_checks');
    $footer = isset($obj['footer']) ? $obj['footer'] : [];
    $record['total_checks'] = $footer['total_checks'];
    $outgoing_checks = isset($obj) ? $obj['body'] : [];
    $params['outgoing_checks'] = $outgoing_checks;
    # End autocomplete outgoing_checks
    
    
    # on_credit
    $record['on_credit'] = $request->input('on_credit');
    # End on_credit
    
    # assignmentAmount
    $obj = $request->input('projects');
    $projects = isset($obj) ? $obj : [];
    $params['projects'] = $projects;
    $record['unassigned_amount_projects'] = $request->input('unassigned_amount_projects');
    $record['unassigned_percentage_projects'] = $request->input('unassigned_percentage_projects');
    # End assignmentAmount
    # assignmentAmount
    $obj = $request->input('expenses_accounts');
    $expenses_accounts = isset($obj) ? $obj : [];
    $params['expenses_accounts'] = $expenses_accounts;
    $record['unassigned_amount_expenses_accounts'] = $request->input('unassigned_amount_expenses_accounts');
    $record['unassigned_percentage_expenses_accounts'] = $request->input('unassigned_percentage_expenses_accounts');
    # End assignmentAmount
    $record['updated_by'] = $userId;
    $record["updated_at"] = Carbon::now()->toDateTimeString();
    $params['record'] = $record;
    $params['user_id'] = $userId;
    $params['t1'] = (count($request->input('users')['body']) > 0 ? $request->input('users')['body'][0]['search_field'] : "");
    $params['t2'] = (null !== $request->input('total') ? $request->input('total') : "");
    $params['t3'] = (null !== $request->input('date') ? $request->input('date') : "");
    DB::transaction(function () use ($params) {
      DB::table('purchases')->where('id', $params['record']['id'])->update($params['record']);
      $record = [];
      $record['form_code'] = 'F99';
      $record['form_title'] = 'Alta de compra y gasto';
      $record['name'] = 'purchases';
      $record['period'] = null;
      $record['due_date'] = null;
      $record['t1'] = $params['t1'];
      $record['t2'] = $params['t2'];
      $record['t3'] = $params['t3'];
      $record['updated_by'] = $params['user_id'];
      $record["updated_at"] = Carbon::now()->toDateTimeString();
      DB::table('records')->where([['record_id', '=', $params['record']['id']],['name', '=', 'purchases']])->update($record);
      #guarda los datos del usuario en usertype siempre y cuando no este ya registrado
      if(isset($params['user_type'])){
       if($params['user_type'] != false || $params['user_type'] != ""){
         #verifica si el usuario esta en la tabla user_type con el tipo especificado
         $result=DB::table('user_types')->where("user_id","=",$params['record']['user_id'])->where("type","=",$params['user_type'])->first();
         if(!isset($result->id)){
           $user_types = [];
           $user_types['user_id'] = $params['record']['user_id'];
           $user_types['type'] = $params['user_type'];
           $user_types['created_by'] = $params['user_id'];
           $user_types['updated_by'] = $params['user_id'];
           $user_types["created_at"] = Carbon::now()->toDateTimeString();
           $user_types["updated_at"] = Carbon::now()->toDateTimeString();
           DB::table('user_types')->insert($user_types);
         }
       }
      }
      DB::table('outgoing_check_purchase')->where('purchase_id', $params['record']['id'])->delete();
      $outgoing_checks = [];
      foreach ($params['outgoing_checks'] as $value) {
        $outgoing_checks[] = [
          'outgoing_check_id' => $value['id'],
          'purchase_id' => $params['record']['id'],
          'check_search_field' => $value['check_search_field'],
          'amount' => $value['amount'],
          'updated_by' => $params['user_id'],
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('outgoing_check_purchase')->insert($outgoing_checks);
      DB::table('project_purchase')->where('purchase_id', $params['record']['id'])->delete();
      $projects = [];
      foreach ($params['projects'] as $value) {
        $projects[] = [
          'project_id' => $value['id'],
          'purchase_id' => $params['record']['id'],
          'percentage' => $value['percentage'],
          'amount' => $value['amount'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('project_purchase')->insert($projects);
      DB::table('expenses_account_purchase')->where('purchase_id', $params['record']['id'])->delete();
      $expenses_accounts = [];
      foreach ($params['expenses_accounts'] as $value) {
        $expenses_accounts[] = [
          'expenses_account_id' => $value['id'],
          'purchase_id' => $params['record']['id'],
          'percentage' => $value['percentage'],
          'amount' => $value['amount'],
          'created_by' => $params['user_id'],
          'updated_by' => $params['user_id'],
          'created_at' => Carbon::now()->toDateTimeString(),
          'updated_at' => Carbon::now()->toDateTimeString()
        ];
      }
      DB::table('expenses_account_purchase')->insert($expenses_accounts);
    });
    $response['success'] = true;
    return response()->json($response, 200);
  }
  public function destroy(Purchase $purchase)
  {
    $userId = Auth::id();
    if (!Gate::allows('purchase-delete') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = DB::table('purchases')->select('blocked')->where('id', $purchase['id'])->first();
    if($record->blocked === 1) {
      $response['status'] = 'error';
      $response['msg'] = 'Registro bloqueado';
      return response()->json($response, 403);
    }
    $params = [];
    $params['record'] = $purchase;
    DB::transaction(function () use ($params) {
      Purchase::destroy($params['record']['id']);
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
     $records = Purchase::where($req)->get();
     $response['data'] = $records;
     return response()->json($response, 200);
  }
  public function search(Request $request) {
    $response = [];
    $filter = json_decode($request->input('filter'), true);
    $records = Purchase::where($filter)
      ->where('cancelled', '!=', true)
      ->get()
      ->map(function($record) {
        $record->user;
        $record->outgoing_check;
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
      DB::table('purchases')->where('id', $params['req']['id'])->update(['done' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'purchases']])->update(['done' => $params['req']['value']]);
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
      DB::table('purchases')->where('id', $params['req']['id'])->update(['cancelled' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'purchases']])->update(['cancelled' => $params['req']['value']]);
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
      DB::table('purchases')->where('id', $params['req']['id'])->update(['blocked' => $params['req']['value']]);
      DB::table('records')->where([['record_id', '=', $params['req']['id']],['name', '=', 'purchases']])->update(['blocked' => $params['req']['value']]);
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
      ['name', '=', 'purchases'],
      ['record_id', '=', $id]
    ])->first();
    $count = DB::table('record_user')->where([
      ['user_id', '=', $userId],
      ['record_id', '=', $record->id],
      ['read', '=', true]
    ])->count();
    $response = [];
    if (!Gate::allows('purchase-read') && !Auth::user()->administrator && $count == 0){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $record = Purchase::find($id);
    $record->month = (null !== $record->month) ? Carbon::parse($record->month)->format("Y-m") : null;
    $user = $record->user;
    $users = [];
    if(isset($record->user)) {
      $users['body'][0] = $record->user;
      $users['body'][0]['user_search_field'] = $record['user_search_field'];
    } else {
      $users['body'] = [];
    }
    $record->users = $users;
    $body = $record->outgoing_checks;
    foreach ($record->outgoing_checks as $outgoing_check) {
      $outgoing_check->check_search_field = $outgoing_check->pivot->check_search_field;
      $outgoing_check->amount = $outgoing_check->pivot->amount;
    }
    $outgoing_checks = [];
    $outgoing_checks['body'] = $body;
    $outgoing_checks['footer'] = [];
    $outgoing_checks['footer']['total_checks'] = $record->total_checks;
    if(!is_array ($record)) $record = $record->toArray();
    $record['outgoing_checks'] = $outgoing_checks;
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
    if (!Gate::allows('purchase-read') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $offset = $request->input('offset');
    $limit = $request->input('limit');
    $funnel = json_decode($request->input('funnel'), true);
    $records = Purchase::where($funnel["filters"]);
    $params = [];
    $params['search'] = $funnel["search"];
    if($funnel['search']['value'] != '') {
      if($funnel["search"]["column"] == "all") {
        $records->where('id', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('date', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('currency', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('type', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('point_of_sale', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhere('number', 'LIKE', "%".$params["search"]["value"]."%");
        $records->orWhereHas('user', function ($query) use ($params) {
        });
        $records->orWhere('detail', 'LIKE', "%".$params["search"]["value"]."%");
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
      $user = [];
      $userString = [];
      $userData = Purchase::find($record->id);
      if(isset($userData->user)) {
        $userString[0] = $userData['user_search_field'];
         $user[] =   implode(" ", $userString);
       } 
       $outgoing_checks = $record->outgoing_checks;
       $outgoing_check = [];
       $outgoing_checkString = [];
       foreach ($outgoing_checks as $key => $value) {
        $outgoing_checkString[0] = $value->check_search_field;
        $outgoing_checkString[1] = $value->amount;
         $outgoing_check[] =   implode(" ", $outgoing_checkString);
       }
      $fields = array
      (
          $record->date,
          $record->currency,
          $record->exchange_rate,
          $record->type,
          $record->point_of_sale,
          $record->number,
          implode(', ', $user),
          $record->detail,
          $record->net_amount,
          $record->tax_1,
          $record->tax_2,
          $record->tax_4,
          $record->tax_3,
          $record->tax_5,
          $record->exempt,
          $record->untaxed,
          $record->internal_tax,
          $record->perception_4,
          $record->perception_3,
          $record->perception_1,
          $record->perception_2,
          $record->total,
          $record->cash,
          $record->bank_deposit,
          $record->debit_card,
          $record->credit_card,
          $record->on_credit,
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
