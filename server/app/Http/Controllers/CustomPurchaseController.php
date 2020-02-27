<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Purchase;
use App\User;
use App\Account;
use Excel;
class CustomPurchaseController extends Controller
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
    $records = Purchase::where($funnel["filters"]);
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
      $record->projects;
      $record->expenses_accounts;
      $record->user;
      #calcula lo pagado
      $paid = $record->cash + $record->bank_deposit + $record->automatic_debit + $record->debit_card + $record->credit_card + $record->total_checks;
      if($record->withholding_1){
        $paid = $paid + $record->withholding_1;
      }
      if($record->withholding_2){
        $paid = $paid + $record->withholding_2;
      }
      #fin calculo de lo pagado
      $record->paid = $paid;
      return $record;
    });
    $response['data'] = $records;
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
    $records->where('type', '!=', "RECIBO X");
    
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
    $records->with("account");
    $records->with("user");
    $fecha_array=explode("-",$funnel["filters"][0][1]);
    $records = $records->get()
    ->map(function ($record) {
      $user = [];
      $userString = [];
      $user_data = User::find($record->user_id);
      $account_data = Account::find($record->account_id);
      $name_acount = $account_data['name'];
      $tax_condition="";
      $user_name="";
      $tin="";
      /*if(isset($userData->user_id)) {
        $userString[0] = $userData['user_search_field'];
         $user[] =   implode("-", $userString);
         $user_id= ""
       }*/ 
       if(isset($user_data->id)) {
        $user_name=$user_data->name;
        $array_tin  = array_map('intval', str_split($user_data->tin));
        $tin=$array_tin[0].$array_tin[1].'-'.$array_tin[2].$array_tin[3].$array_tin[4].$array_tin[5].$array_tin[6].$array_tin[7].$array_tin[8].$array_tin[9].'-'.$array_tin[10];
        switch($user_data->tax_condition){
          case "Monotributista":
            $tax_condition ="MT";
          break;
          case "Responsable inscripto":
            $tax_condition ="RI";
          break;
          case "Excento":
            $tax_condition ="EX";
          break;
        }
       }
       $outgoing_checks = $record->outgoing_checks;
       $outgoing_check = [];
       $outgoing_checkString = [];
       foreach ($outgoing_checks as $key => $value) {
        $outgoing_checkString[0] = $value->check_search_field;
        $outgoing_checkString[1] = $value->amount;
         $outgoing_check[] =   implode(" ", $outgoing_checkString);
       }
      $type=$record->type;
      $vect_type=explode(" ",$type);
      switch($type){
        case 'FACTURAS A':
          $codigo="FC";
          $comprobante="Factura";
          $tipo="A";

        break;
        case 'FACTURAS B':
          $codigo="FC";
          $comprobante="Factura";
          $tipo="B";

        break;
        case 'FACTURAS C':
          $codigo="FC";
          $comprobante="Factura";
          $tipo="C";

        break;
        case 'TICKET A':
          $codigo="TF";
          $comprobante="Ticket factura";
          $tipo="A";

        break;
        case 'TICKET B':
          $codigo="TF";
          $comprobante="Ticket factura";
          $tipo="B";

        break;
        case 'NOTAS DE DÉBITO A':
          $codigo="ND";
          $comprobante="Notas de débito";
          $tipo="A";
        break;        
        case 'NOTAS DE DÉBITO B':
          $codigo="ND";
          $comprobante="Notas de débito";
          $tipo="B";
        break;               
        case 'NOTAS DE DÉBITO C':
          $codigo="ND";
          $comprobante="Notas de débito";
          $tipo="C";
        break;
        case 'NOTAS DE CRÉDITO A':
          $codigo="NC";
          $comprobante="Notas de crédito";
          $tipo="A";
        break;        
        case 'NOTAS DE CRÉDITO B':
          $codigo="NC";
          $comprobante="Notas de crédito";
          $tipo="B";
        break;               
        case 'NOTAS DE CRÉDITO C':
          $codigo="NC";
          $comprobante="Notas de crédito";
          $tipo="C";
        break;
        case 'RECIBOS A':
          $codigo="RB";
          $comprobante="Recibo";
          $tipo="A";
        break;        
        case 'RECIBOS B':
          $codigo="RB";
          $comprobante="Recibo";
          $tipo="B";
        break;               
        case 'RECIBOS C':
          $codigo="RB";
          $comprobante="Recibo";
          $tipo="C";
        break;

      }
      $fields = array
      (
          date("d/m/Y",strtotime($record->date)),
          $codigo,
          $comprobante,
          $tipo,
          str_pad($record->point_of_sale, 4, "0", STR_PAD_LEFT),
          str_pad($record->number, 10, "0", STR_PAD_LEFT),
          $record->user_id,
          $user_name,
          $tax_condition,
          $tin,
          $record->net_amount_2,
          $record->net_amount_1,
          $record->net_amount_3,
          ($record->tax_1 + $record->tax_2 + $record->tax_3),
          ($record->exempt + $record->untaxed),
          $record->internal_tax,
          $record->perception_1,
          $record->perception_4,
          $record->perception_3,          
          $record->net_amount_2 + $record->net_amount_1+
          $record->net_amount_3 + ($record->tax_1 + $record->tax_2 + $record->tax_3) +
          ($record->exempt + $record->untaxed) + $record->internal_tax +
          $record->perception_1 + $record->perception_4 + $record->perception_3,
          $name_acount ,
      );
      return $fields;
    });
    $labels=[];
    #lee el json setting con los datos de configuracion de form
    $labels['fecha']='Fecha';
    $labels['cod']='Cod. Compra';
    $labels['comprobante']='Comprobante';
    $labels['tipo']='Tipo';
    $labels['punto_venta']='Punto de venta';
    $labels['nrocomp']='N° comprobante';
    $labels['cod_prov']='Cod. Prov.';
    $labels['cliente']='Apellido y Nombre o Razon Social';
    $labels['tipo_iva']='Tipo IVA';
    $labels['tin']='Cuit';
    $labels['net1']='NG. (10.50%)';
    $labels['net2']='NG. (21.00%)';
    $labels['net3']='NG. (27.00%)';
    $labels['ivacf']='IVA Cred. Fiscal';
    $labels['nograv_exen']='NoGrav/Exent';    
    $labels['otros_impuesto']='Otros Impuestos';    
    $labels['pi']='Percepción Iva';    
    $labels['piibb']='Percepción IIBB';    
    $labels['tissh']='TISSH';
    $labels['tot_cpte']='Total Cpte.';
    $labels['cuenta']='Cuenta';

    $data = $records->toArray();
    $name_file="IVA COMPRAS - ".$fecha_array[1]."-".$fecha_array[0];
    $form_title ="IVA COMPRAS (".$fecha_array[1]."-".$fecha_array[0].")";
    Excel::create($name_file, function($excel) use ($data,$labels,$form_title) {
      $excel->sheet(mb_substr($form_title, 0, 30,'utf-8'), function($sheet) use ($data,$labels)
      {
        //$sheet->row(1, $labels);
        $sheet->fromArray($data, null, 'A1', false, false);
        $sheet->prependRow($labels);
      });
    })->store("xls", storage_path('xls_files_tmp'));
    $response = [];
    $response['records'] = $records;
    $b64Doc = base64_encode(file_get_contents(storage_path('/xls_files_tmp/'.$name_file.'.xls')));
    $response['form']=$name_file;
    $response['route']=$b64Doc;
    $response['success'] = true;
    $response['request'] = true;
    return response()->json($response, 200);
   }
}
