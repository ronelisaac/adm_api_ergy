<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gate;
use Illuminate\Support\Facades\Auth;
use App\Service;
use App\SaleFee;
use App\Purshase;
use App\PurchaseFee;
use App\OutgoingCheck;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class MainReportController extends Controller
{
    //
  public function getReceivableDollars() {
  }
   /*******************
   * Retorna el json con las cuentas por cobrar
   * Estos gastos pueden ser de tipo+
   * Servicios O Ventas
   * El nodo data lleva un arreglo de fechas ordenados con cada deuda discriminada por type, (service) or (sale)|
   *******************/
	public function getReceivable($currency) {
		if (!Gate::allows('dashboard') && !Auth::user()->administrator){
			$response['status'] = 'error';
			$response['msg'] = 'No estas autorizado';
			return response()->json($response, 403);
    }
 
		$datev=array("Mon"=>array('Lun.','Lunes'),"Tue"=>array('Mar.','Martes'),"Wed"=>array('Mie.','Miércoles'),"Thu"=>array('Jue.','Jueves'),"Fri"=>array('Vie.','Viernes'),"Sat"=>array('Sáb.','Sábado'),"Sun"=>array('Dom.','domingo'));
		$datem=array("Jan"=>array('Ene.','Enero'),"Feb"=>array('Feb.','Febrero'),"Mar"=>array('Mar.','Marzo'),"Apr"=>array('Abr.','Abril'),"May"=>array('May.','Mayo'),"Jun"=>array('Jun.','Junio'),"Jul"=>array('Jul.','Julio'),"Aug"=>array('Ago.','Agosto'),"Sep"=>array('Sep.','Septiembre'),"Oct"=>array('Oct.','Octubre'),"Nov"=>array('Nov.','Noviembre'),"Dec"=>array('Dic.','Diciembre'));
		$response = [];
		$data = [];
		$dataServices = [];
		$TotalService = 0; #total en pesos de los servicios
		$collectedPesosService = 0; #total cobrado en pesos de los servicios
		$receivableTotalService = 0; #total a cobrar en pesos de los servicios
		$toPaidExpired = 0; #total a expedir en pesos de los servicios
		$toPaidNoExpired = 0; #total a expedir en pesos de los servicios
		#calculo de importes en los servicios
		#trae agrupado por fecha de vencimiento los servicios no cobrados en su totalidad
    $result = Service::select('id','collection_due_date',DB::raw("SUM(amount) as total_amount"),DB::raw('SUM(collected) as total_collected'))->where('currency','=',$currency)->where(
    function ($query) {
      $query->whereColumn('amount',">",'collected')->orWhere('collected','=',NULL);
    })->groupBy('collection_due_date')->get();
		foreach ($result as $item){ 
		if(!is_numeric($item->total_collected)) $item->total_collected=0;    
      $deuda = $item->total_amount - $item->total_collected;
      $TotalService = $TotalService + $item->total_amount; #incrementa el monto total
      $collectedPesosService = $collectedPesosService + $item->total_collected; #incrementa el total cobrado 
      $receivableTotalService =  $receivableTotalService + $deuda; #incrementa el total a cobrar 
      if( strtotime(date("d-m-Y",time())) >  strtotime($item->collection_due_date)){
        $expired=true;
        $toPaidExpired = $toPaidExpired + $deuda;
      }else{
        $expired=false;
        $toPaidNoExpired= $toPaidNoExpired + $deuda;
      }
      $fecha=Carbon::parse($item->collection_due_date);
      $dia_name= $fecha->format("D");
      $mes_name= $fecha->format("M");
      
      /*array_push($dataServices,array(
								  'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
								  'value'=> $deuda,
								  'expired'=> $expired,
								  'date'=> $item->collection_due_date,
								  'type' => 'service',
								  'id' => $item->id
								  )
                );*/
                $dataServices[]=array(
								  'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
								  'value'=> $deuda,
								  'expired'=> $expired,
								  'date'=> $item->collection_due_date,
								  'type' => 'service',
								  'id' => $item->id
								  );
		   
		}
		#calculo de importes en las ventas que no son a creditos
		$result= SaleFee::whereHas('sale', function ($query)  use ($currency) {
					$query->where('currency','=',$currency)->where('on_credit','>',0);
		})->with('sale')->get();
		#las cuotas tienen una fecha de vencimiento due_date
		$totalSales = 0;
		$totalSale = 0;
		$paidOut = 0; #pagado
		$toPaidAllSale = 0; #por Pagar todos
		$toPaid = 0; #por Pagar
		$saleIdOld = 0;
		$dataSales = [];
		$filas=count($result);
		$i = 0;
		foreach ($result as $item){ 
			$i++;
		   /* #si saleIdOld es diferente de cero , quiere decir que dio una vuelta
			if($saleIdOld != $item->sale_id){
			  #si la suma de las cuotas es menor a el monto total de la venta, es una cuenta por cobrar (va al array)
			  if( $totalSale > $paidOut ){            
				#incrementa el monto total de las ventas (este se suma al monto total de los servicios, y se retorna como el total)
				$totalSales = $totalSales + ($totalSale - $paidOut);
				#agrega el elemento al array
				$dataSales=['fecha'=>$dueDateSale,'deuda'=>$toPaid,'total ventas con deuda'=>$totalSales ];
			  }
			}
	  
			if($saleIdOld==0 || $saleIdOld != $item->sale_id){               
			  #reinicializa los valores
			  $saleIdOld = $item->sale_id; #nuevo id de venta
			  $totalSale = $item->sale->pesos_total; #asigna el monto total de la venta
			  $toPaid = 0; #reinicializa a pagar en cero
			  $paidOut = 0; #reinicializa lo pagado en cero
			  $dueDateSale = false;#fecha
			}
			#recorremos las cuotas que no estan en done
			if($item->done != 1){
			  $toPaid = $toPaid + $item->amount;
			  if($dueDateSale == false);
			  $dueDateSale = $item->due_date;
			}else{
			  $paidOut = $paidOut + $item->amount;
			}
	  
			
			 #si saleIdOld es diferente de cero , quiere decir que dio una vuelta
			 if($saleIdOld != $item->sale_id or  $filas == $i){
			  #si la suma de las cuotas es menor a el monto total de la venta, es una cuenta por cobrar (va al array)
			  if( $totalSale > $paidOut ){            
				#incrementa el monto total de las ventas (este se suma al monto total de los servicios, y se retorna como el total)
				$totalSales = $totalSales + ($totalSale - $paidOut);
				#agrega el elemento al array
				$dataSales=['fecha'=>$dueDateSale,'deuda'=>$toPaid,'total ventas con deuda'=>$totalSales ];
				array_push( $dataSales,array(
							'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
							'value'=> $deuda,
							'expired'=> $expired,
							'date'=> $item->due_date
							)
						  );
			   
			  }
			}*/
	  
		  if($saleIdOld==0 || $saleIdOld != $item->sale_id){               
			  #reinicializa los valores
			  $saleIdOld = $item->sale_id; #nuevo id de venta
			  $totalSale = $totalSale+$item->sale->pesos_total; #asigna el monto total de la venta
			 
			}
			if($item->done != 1){
			  $toPaid =  $item->amount;
			  $toPaidAllSale = $toPaidAllSale + $toPaid;
			  $fecha=Carbon::parse($item->due_date);
			  $dia_name= $fecha->format("D");
			  $mes_name= $fecha->format("M");
			  if( strtotime(date("d-m-Y",time())) >  strtotime($item->due_date)){
				$expired=true;
				$toPaidExpired = $toPaidExpired + $item->amount;
			  }else{
				$expired=false;
				$toPaidNoExpired= $toPaidNoExpired + $item->amount;
			  }
			  /*array_push( $dataSales,array(
				  'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
				  'value'=> $toPaid,
				  'expired'=> $expired,
				  'date'=> $item->due_date,
				  'type' => 'sale',
				  'id' => $item->sale_id
				  )
        );*/
        $dataSales[]=array(
				  'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
				  'value'=> $toPaid,
				  'expired'=> $expired,
				  'date'=> $item->due_date,
				  'type' => 'sale',
				  'id' => $item->sale_id
        );
			}else{
			  $paidOut = $item->amount;
			}
			  
      }
      $siglas="$";
		  if($currency=="Dolar") $siglas="US$";
       $data = array_merge($dataServices,$dataSales);
     /* foreach($dataServices as $dt){
        foreach($dataSales as $dts){
          if( strtotime($dt['date'])) ==  strtotime($dts['date'])){
           $new_array= array(
              'label'=>$dt['label'],
              'value'=> $toPaid,
              'expired'=> $expired,
              'date'=> $item->due_date,
              'type' => 'sale',
              'id' => $item->sale_id
            )
          }
        }
        
      } */
		  $reference_array=[];
		  $reference_array=collect($data)->sortBy('date')->toArray();#ordena el array ascendente mente por fecha
		  $data =  $reference_array;
		  $response['data']=[
			"color" => '#00b5dc',
			"width" =>  130,
			"currency" =>  $siglas,
			"total" => $toPaidAllSale + $receivableTotalService,
			"expired" => $toPaidExpired,
			"no_expired" => $toPaidNoExpired ,
			"detail_page" => 'ServicesPage',
			"data" => $data
		  ];
		 // print_r(json_encode($response));
		  return response()->json($response, 200);
    }
    /*************
    * Retorna el json con los servicios  sin finalizar
    * Verifica que los servicios que no esten en done puedan contarse
    * El nodo data lleva un arreglo de fechas ordenados con cada deuda discriminada por type, (service) or (sale)| 
     **************/
    public function getExpiredServices() {
      if (!Gate::allows('dashboard') && !Auth::user()->administrator){
        $response['status'] = 'error';
        $response['msg'] = 'No estas autorizado';
        return response()->json($response, 403);
      }
      $datev=array("Mon"=>array('Lun.','Lunes'),"Tue"=>array('Mar.','Martes'),"Wed"=>array('Mie.','Miércoles'),"Thu"=>array('Jue.','Jueves'),"Fri"=>array('Vie.','Viernes'),"Sat"=>array('Sáb.','Sábado'),"Sun"=>array('Dom.','domingo'));
      $datem=array("Jan"=>array('Ene.','Enero'),"Feb"=>array('Feb.','Febrero'),"Mar"=>array('Mar.','Marzo'),"Apr"=>array('Abr.','Abril'),"May"=>array('May.','Mayo'),"Jun"=>array('Jun.','Junio'),"Jul"=>array('Jul.','Julio'),"Aug"=>array('Ago.','Agosto'),"Sep"=>array('Sep.','Septiembre'),"Oct"=>array('Oct.','Octubre'),"Nov"=>array('Nov.','Noviembre'),"Dec"=>array('Dic.','Diciembre'));
      $response = [];
      $data = [];      
      $expiredVal = 0; #cantidad de expirados
      $noExpiredVal = 0; #cantidad sin expirar
      #calculo de importes en los servicios
      #trae agrupado por fecha de vencimiento los servicios no cobrados en su totalidad
      $result = Service::select('id','collection_due_date',DB::raw("COUNT(id) as total_collected"))->where(function($query){
        $query->whereNull("done")->orWhere("done","=",0);
      })->groupBy('collection_due_date')->orderBy('collection_due_date', 'ASC')->get();
      foreach ($result as $item){       
        if( strtotime(date("d-m-Y",time())) >  strtotime($item->collection_due_date)){
          $expired=true;
          $expiredVal = $expiredVal + $item->total_collected;
        }else{
          $expired=false;
          $noExpiredVal = $noExpiredVal + $item->total_collected;
        }
        $fecha=Carbon::parse($item->collection_due_date);
        $dia_name= $fecha->format("D");
        $mes_name= $fecha->format("M");
        array_push($data,array(
          'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
          'value'=> $item->total_collected,
          'expired'=> $expired,
          'date'=> $item->collection_due_date,
          )
        );
  
        
      }
      $response['data']=[
        "color" => '#8a8a8a',
        "width" =>  130,
        "currency" =>  '',
        "total" =>  $expiredVal + $noExpiredVal,
        "expired" => $expiredVal,
        "no_expired" => $noExpiredVal ,
        "data" => $data	  
        ];
      return response()->json($response, 200);
	}
	/*******************
   * Retorna el json con las cuentas por pagar
   * Estos gastos pueden ser de tipo
   * Purchase
   * Fecha : 07/11/2019 15:29
   * El nodo data lleva un arreglo de fechas ordenados con cada cobranza
   *******************/
  public function getPayable($currency) {
    if (!Gate::allows('dashboard') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }

    #calculo de importes en las compras que son a creditos
    $result= PurchaseFee::whereHas('purchase', function ($query) use ($currency){
          $query->where('currency','=',$currency)->where('on_credit','>',0);
    })->with('purchase')->get();
    #las cuotas tienen una fecha de vencimiento due_date
    $totalSales = 0;
    $totalSale = 0;
    $paidOut = 0; #pagado
    $toPaidAllPurchase = 0; #por Pagar todos
    $toPaid = 0; #por Pagar
    $saleIdOld = 0;
    $toPaidExpired = 0; #total a expedir en pesos de los servicios
		$toPaidNoExpired = 0; #total a expedir en pesos de los servicios
    $dataPurchase = [];
    $filas=count($result);
    $i = 0;
    foreach ($result as $item){ 
      $i++;
      if($purchaseIdOld==0 || $purchaseIdOld != $item->purchase_id){               
        #reinicializa los valores
        $purchaseIdOld = $item->purchase_id; #nuevo id de venta
        $totalPurchase = $totalPurchase+$item->purchase->total; #asigna el monto total de la venta
      }
      if($item->done != 1){
        $toPaid =  $item->amount;
        $toPaidAllPurchase = $toPaidAllPurchase + $toPaid;
        $fecha = Carbon::parse($item->due_date);
        $dia_name = $fecha->format("D");
        $mes_name = $fecha->format("M");
        if( strtotime(date("d-m-Y",time())) >  strtotime($item->due_date)){
          $expired=true;
          $toPaidExpired = $toPaidExpired + $item->amount;
        }else{
          $expired=false;
          $toPaidNoExpired= $toPaidNoExpired + $item->amount;
        }
        array_push( $dataPurchase,array(
          'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
          'value'=> $toPaid,
          'expired'=> $expired,
          'date'=> $item->due_date,
          'type' => 'sale',
          'id' => $item->purchase_id
          )
        );
      }else{
        $paidOut = $item->amount;
      }
        
    }
    $siglas="$";$color="#e9afdd";
    if($currency=="Dolar"){ $siglas="US$"; $color="#0ce897";}
    $data = $dataPurchase;
    $reference_array=[];
    $reference_array=collect($data)->sortBy('date')->toArray();#ordena el array ascendente mente por fecha
    $data =  $reference_array;
    $response['data']=[
      "color" => $color,
      "width" =>  130,
      "currency" =>  $siglas,
      "total" => $toPaidAllPurchase,
      "expired" => $toPaidExpired,
      "no_expired" => $toPaidNoExpired ,
      "detail_page" => 'ServicesPage',
      "data" => $data	  
    ];
    return response()->json($response, 200);
  }
  	/*******************
   * Retorna el json con el saldo en banco
   * Recibe el tipo de moneda ($currency)
   * getOutgoingChecks
   * Fecha : 07/11/2019 15:29
   * El nodo data lleva un arreglo de fechas ordenados con cada cheque
   *******************/
  public function getOutgoingCheck() {
    if (!Gate::allows('dashboard') && !Auth::user()->administrator){
      $response['status'] = 'error';
      $response['msg'] = 'No estas autorizado';
      return response()->json($response, 403);
    }
    $datev=array("Mon"=>array('Lun.','Lunes'),"Tue"=>array('Mar.','Martes'),"Wed"=>array('Mie.','Miércoles'),"Thu"=>array('Jue.','Jueves'),"Fri"=>array('Vie.','Viernes'),"Sat"=>array('Sáb.','Sábado'),"Sun"=>array('Dom.','domingo'));
    $datem=array("Jan"=>array('Ene.','Enero'),"Feb"=>array('Feb.','Febrero'),"Mar"=>array('Mar.','Marzo'),"Apr"=>array('Abr.','Abril'),"May"=>array('May.','Mayo'),"Jun"=>array('Jun.','Junio'),"Jul"=>array('Jul.','Julio'),"Aug"=>array('Ago.','Agosto'),"Sep"=>array('Sep.','Septiembre'),"Oct"=>array('Oct.','Octubre'),"Nov"=>array('Nov.','Noviembre'),"Dec"=>array('Dic.','Diciembre'));
    $response = [];
    $data = [];
    $checkExpired = 0; #total a expedir  
    $checkNoExpired = 0; #total que no expide

    #calculo de importes en las ventas que no son a creditos
    $result= OutgoingCheck::select("*",DB::raw("SUM(amount) as amount"))->where("done","!=",1)->groupBy("due_date")->orderBy("due_date","ASC")->get();
    //echo $result;
    #las cuotas tienen una fecha de vencimiento due_date
    $totalchecks = 0;
    $totalcheck = 0;
    $paidOut = 0; #pagado
    $toPaidAllCheck = 0; #por Pagar todos
    $toPaidExpired=0;
    $toPaidNoExpired=0;
    $toPaid = 0; #por Pagar
    $saleIdOld = 0;
    $filas=count($result);
    $i = 0;
    foreach ($result as $item){ 
      $i++;
      $totalcheck = $totalcheck+$item->amount; #asigna el monto total de la venta
      
      $toPaid =  $item->amount;
      $toPaidAllCheck = $toPaidAllCheck + $toPaid;
      $fecha = Carbon::parse($item->due_date);
      $dia_name = $fecha->format("D");
      $mes_name = $fecha->format("M");
      if( strtotime(date("d-m-Y",time())) >  strtotime($item->due_date)){
        $expired=true;
        $toPaidExpired = $toPaidExpired + $item->amount;
      }else{
        $expired=false;
        $toPaidNoExpired= $toPaidNoExpired + $item->amount;
      }
      array_push( $data,array(
          'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
          'value'=> $toPaid,
          'expired'=> $expired,
          'due_date'=> $item->due_date
          
          )
      );
        
    }
    
      #$reference_array=[];
      #$reference_array=collect($data)->sortBy('due_date')->toArray();#ordena el array ascendente mente por fecha de vencimiento
      #$data =  $reference_array;
      $response['data']=[
      "color" => '#b3b3b3',
      "width" =>  130,
      "currency" =>  '$',
      "total" => $toPaidAllCheck,
      "expired" => $toPaidExpired,
      "no_expired" => $toPaidNoExpired ,
      "data" => $data	  
      ];
    // print_r(json_encode($response));
      return response()->json($response, 200);
  }
    /*************
    * Retorna el json con los los servicios sin facturar
    * Verifica que los servicios que no esten facturados o que el monto sea menor al facturado
    * El nodo data lleva un arreglo de fechas ordenados con cada deuda discriminada por type, (service) and (invoice)| 
     **************/
    public function getInvoicesService() {
      if (!Gate::allows('dashboard') && !Auth::user()->administrator){
        $response['status'] = 'error';
        $response['msg'] = 'No estas autorizado';
        return response()->json($response, 403);
      }
      $datev=array("Mon"=>array('Lun.','Lunes'),"Tue"=>array('Mar.','Martes'),"Wed"=>array('Mie.','Miércoles'),"Thu"=>array('Jue.','Jueves'),"Fri"=>array('Vie.','Viernes'),"Sat"=>array('Sáb.','Sábado'),"Sun"=>array('Dom.','domingo'));
      $datem=array("Jan"=>array('Ene.','Enero'),"Feb"=>array('Feb.','Febrero'),"Mar"=>array('Mar.','Marzo'),"Apr"=>array('Abr.','Abril'),"May"=>array('May.','Mayo'),"Jun"=>array('Jun.','Junio'),"Jul"=>array('Jul.','Julio'),"Aug"=>array('Ago.','Agosto'),"Sep"=>array('Sep.','Septiembre'),"Oct"=>array('Oct.','Octubre'),"Nov"=>array('Nov.','Noviembre'),"Dec"=>array('Dic.','Diciembre'));
      $response = [];
      $data = [];      
      $expiredVal = 0; #cantidad de expirados
      $noExpiredVal = 0; #cantidad sin expirar
      $toPaid=0;
      #calculo de importes en los servicios
      #trae agrupado por fecha de vencimiento los servicios no cobrados en su totalidad
     $result = Service::where(function ($query) {
        $query->whereColumn('services.amount',">",'invoiced')->orWhere('invoiced','=',NULL);
      })->groupBy('collection_due_date')->orderBy('collection_due_date', 'ASC')->get();
          
      foreach ($result as $item){      
        $toPaid=$item->amount-$item->invoiced; 
        if( strtotime(date("d-m-Y",time())) >  strtotime($item->collection_due_date)){
          $expired=true;
          $expiredVal = $expiredVal + $toPaid;
        }else{
          $expired=false;
          $noExpiredVal = $noExpiredVal + $toPaid;
        }
        $fecha=Carbon::parse($item->collection_due_date);
        $dia_name= $fecha->format("D");
        $mes_name= $fecha->format("M");
        array_push($data,array(
          'label'=> $datev[$dia_name][0].' '.$fecha->format("d").' '.$datem[$mes_name][0].' '.$fecha->format("Y"),
          'value'=> $toPaid,
          'expired'=> $expired,
          'date'=> $item->collection_due_date,
          )
        );
      }
      $response['data']=[
        "color" => '#8a8a8a',
        "width" =>  130,
        "currency" =>  '',
        "total" =>  $expiredVal + $noExpiredVal,
        "expired" => $expiredVal,
        "no_expired" => $noExpiredVal ,
        "data" => $data	  
        ];
      return response()->json($response, 200);
	}

      /*settings5: any = {
    color: '#8a8a8a',
    width: 130,
    currency: '',
    total: 10,
    expired: 2,
    no_expired: 8,
    data: [
      {
        label: 'Sab. 30 Ago. 2019',
        value: 3,
        expired: true
        
      },
      {
        label: 'Lun. 02 Sep. 2019',
        value: 3,
        expired: true
      },
      {
        label: 'Mie. 04 Sep. 2019',
        value: 6,
        expired: false
      },
      {
        label: 'Sab. 8 Sep. 2019',
        value: 1,
        expired: false
      },
      {
        label: 'Sab. 30 Sep. 2019',
        value: 2,
        expired: false
      }
    ]
  }*/
	  
}
