<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers, Authorization, X-CSRF-Token');
header('Access-Control-Allow-Credentials: true');
use Illuminate\Http\Request;
use App\Api;


Route::get('/', function () {
    return view('index');
});
Route::get('list/', function (Request $request) {
    $objApi=new Api();
    $search['latitud_search']="";
    $search['longitud_search']="";
    if(isset($request['distancia']))  $distancia=htmlspecialchars($request['distancia']); else $distancia =11;
    $search['datos']=[];  
    $search['markers']=[]; 
    $search['infowindows']=[]; 
    $licencia=false;          
    $horarios=[];
    $idiomas=[];
    $otros=[];
    $rt=[];
    $paginas=0;
    if(isset($request['latitud_search']) AND isset($request['longitud_search'])){
        if(isset($request['licencia'])){
            if($request['licencia']!=""){
                $licencia = $request['licencia'];
            }
        }

        if(isset($request['idiomas'])){
            $vidiomas=explode(",",$request['idiomas']);
            if (count($vidiomas)>0) {
                    $idiomas=$vidiomas;
            }
        }
        if(isset($request['horarios'])){
            $vhorarios=explode(",",$request['horarios']);
            if (count($vhorarios)>0) {
                   $horarios=$vhorarios;
           }
       }
       if(isset($request['otros'])){
            $votros=explode(",",$request['otros']);
            if (count($votros)>0) {
                $otros= $votros;
            }
        }
           
        $rt=$objApi->get_schools($request['latitud_search'],$request['longitud_search'],$distancia,$licencia,$idiomas,$horarios,$otros);                
        //print_r($rt);exit;
        if($rt != false ){
            $search['datos']=$rt;            
            $paginas=ceil(count(json_decode($search['datos'])) / 10);
            foreach(json_decode($rt, true) as $item){
                array_push($search['markers'],array($item['nombre'],$item['latitud'],$item['longitud'])); 
                $infowindows='<div class="info_content"><div class="container-fluid"><div class="ft-product"><div class="product-inner"><h3  style="color:#ccc;"><i class="fa fa-heart-o"></i></h3><div class="product-info"><div class="product-category"><a href="'.env('APP_URL').'/detail?auto_school_id='.$item['id'].'&latitud_search='.$item['latitud'].'&longitud_search='.$item['longitud'].'">'.$item['nombre'].'</a></div><h3 class="product-title"><a href="'.env('APP_URL').'/detail?auto_school_id='.$item['id'].'&latitud_search='.$item['latitud'].'&longitud_search='.$item['longitud'].'">'.$item['direccion'].'</a></h3><p><span class="pull-left"><i  class="fa fa-map-marker"></i> '.$item['distancia'].'</span> <button class="btn btn-primary__ pull-right" style="border-radius:25px">preguntar</button> <a href="'.env('APP_URL').'/detail?auto_school_id='.$item['id'].'&latitud_search='.$item['latitud'].'&longitud_search='.$item['longitud'].'" class="btn btn-primary_ pull-right" style="border-radius:25px">más</a> </p></div></div></div></div></div>';
                array_push($search['infowindows'],array($infowindows));
            }
        }
        $search['latitud_search']=$request['latitud_search'];
        $search['longitud_search']=$request['longitud_search'];
    
    }
    $search['licencia']= false;
    if( isset($request['licencia']))  $search['licencia'] = $request['licencia'];
    $search['distancia']= false;
    if(isset($request['distancia'])) $search['distancia'] = $request['distancia'];
    //print_r($request);
    $search['search_string']="";
    if(isset($request['search_string'])){
        $search['search_string'] = $request['search_string'];
    }
    //todos los idiomas
    $search['idiomas']= $objApi->get_lang();
     //otros
     $search['otros']= $objApi->get_otros();
     //horarios checheck
     $search['horarios_checked'] = $horarios;
     //idiomas checheck
     $search['idiomas_checked'] = $idiomas;
     //otros checheck
     $search['otros_checked'] = $otros; 
     $pagina=$request['pagina'];

    return view('list',compact('search','pagina','paginas'));
});
Route::post('api/auto_escuelas', function (Request $request) {
    $objApi=new Api();
    if(isset($request['distancia']))  $distancia=htmlspecialchars($request['distancia']); else $distancia =11;
          
            if(isset($request['latitud_search']) AND isset($request['longitud_search'])){
               if($request['licencia']!=""){
                $rt=$objApi->get_schools_lic($request['latitud_search'],$request['longitud_search'],$distancia,$request['licencia']);
               }else{
                $rt=$objApi->get_schools($request['latitud_search'],$request['longitud_search'],$distancia,false);                
               }
               return json_encode($rt);
    }else return false;  
});
Route::post('api/count_auto_escuelas', function (Request $request) {
    if(isset($request['distancia']))  $distancia=htmlspecialchars($request['distancia']); else $distancia =11;
            
            if(isset($request['latitud_search']) AND isset($request['longitud_search'])){
                $rt=count_schools_lic($request['latitud_search'],$request['longitud_search'],$distancia);
                return json_encode($rt);
            }else return false; 
});

Route::post('api/comprobar_imagen', function (Request $request) {
    if(!isset($request['logo'])) $rs= env('APP_URL')."/assets/img/others/volante.png?v=005";
    else{                
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$request['logo']);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($ch);
        curl_close($ch);
        if($result !== FALSE){
            $rs = $request['logo'];                    
        }else{
            $rs=  env('APP_URL')."/assets/img/others/volantes.png";
        }
    }
    return response()->json(["logo"=>$rs]);
})->name("comprobar_imagen.post");
Route::post('api/abierto_hoy', function (Request $request) {
    $dias = array(1 => "Monday",2 => "Tuesday",3 => "Wednesday",4 => "Thursday",5 => "Friday",6 => "Saturday",7 => "Sunday");
            $num_dia_actual = array_search(date("l"), $dias);
            $h_actual= strtotime(date("d-m-Y 08:00",time()));
            $flag="";
            foreach($request['horarios'] as $horario){
                if($horario['dia']==$num_dia_actual){
                    if($horario['desde']!=""){
                        if($h_actual >= strtotime(date("d-m-Y ".$horario['desde'],time()))  AND $h_actual <= strtotime(date("d-m-Y ".$horario['hasta'],time())) ){
                            $flag=$horario['hasta'];
                        }
                    }
                   
                }
            }
    return response()->json(["flag"=>$flag]);
})->name('abierto_hoy.post');

Route::get('detail/', function (Request $request) {
    $objApi=new Api();
    $search=[];  
    $autoescuela =[];
    $flag=false;   
    
    if(isset($request['auto_school_id']) AND isset($request['latitud_search']) AND isset($request['longitud_search'])){
    
        $rt= $objApi->get_school($request['auto_school_id'],$request['latitud_search'],$request['longitud_search']);
        $flag=true;
       /* foreach(json_decode($rt, true) as $item){
            $search=$item;
        }*/
        $search=json_decode($rt, true);
       
    }
    $search['markers']=[]; 
    $search['infowindows']=[];
    $autoescuela['longitud_search']=$request['longitud_search'];
    $autoescuela['latitud_search']=$request['latitud_search'];
    $autoescuela['auto_school_id'] = $request['auto_school_id'];
    $lang= $objApi->get_lang();
     //otros
    $otros= $objApi->get_otros();
    //horarios checheck
 
    /********MARCADORES E INFOWINDOWS DE SUCURSALES********* */
    if(isset($search['secciones'])){
        foreach($search['secciones'] as $item){
            array_push($search['markers'],array($item['nombre'],$item['latitud'],$item['longitud'])); 
            $infowindows='<div class="info_content"><div class="container-fluid" style="padding: 10px;"><div class="ft-product" style="padding:10px;width: 280px;"><div class="product-inner"><h3  style="color:#ccc;" ><i class="fa fa-heart-o"></i></h3><div class="product-info"><div class="product-category" style="text-align:left"><a href="'.env('APP_URL').'/detail?auto_school_id='.$item['id'].'&latitud_search='.$item['latitud'].'&longitud_search='.$item['longitud'].'">'.$item['nombre'].'</a></div><h3 class="product-title" style="text-align:left"><a href="'.env('APP_URL').'/detail?auto_school_id='.$item['id'].'&latitud_search='.$item['latitud'].'&longitud_search='.$item['longitud'].'">'.$item['direccion'].'</a></h3><p><span class="pull-left" style="padding-right:10pxM"><i  class="fa fa-map-marker"></i> a '.$item['distancia'].'Km. </span> <button class="btn btn-primary__ pull-right" style="border-radius:25px;position: relative;top: auto;padding: 5px; left: auto;">preguntar</button> <a href="'.env('APP_URL').'/detail?auto_school_id='.$item['id'].'&latitud_search='.$item['latitud'].'&longitud_search='.$item['longitud'].'" class="btn btn-primary_ pull-right" style="border-radius:25px">más</a> </p></div></div></div></div></div>';
            array_push($search['infowindows'],array($infowindows));
        }
    }
    return view('detail',compact('search','autoescuela','flag','lang','otros'));
});
Route::post('api/lang_otros', function (Request $request) {
    $objApi=new Api();
    $search=[];  
    $autoescuela =[];
    $flag=false;     
    if(isset($request['auto_school_id']) AND isset($request['latitud_search']) AND isset($request['longitud_search'])){
    
        $rt= $objApi->get_school($request['auto_school_id'],$request['latitud_search'],$request['longitud_search']);
        $flag=true;
        $search=json_decode($rt, true);
       
    }
    $autoescuela['auto_school_id'] = $request['auto_school_id'];
    $lang= $objApi->get_lang();
     //otros
    $otros= $objApi->get_otros();
     //horarios checheck
    $otrs=[];
    if(isset($search['otros'])){
                                                
        $otros=json_decode($otros, true);
        foreach($search['otros'] as $data_otros){
            foreach($otros as $otro){
                if($data_otros==$otro['id']){
                array_push($otrs, $otro['nombre']);
                }
            }
        }
    }
    $langs=json_decode($lang, true);
    $lang_=[];
        foreach($search['idiomas'] as $idioma){
            foreach($langs as $lang){
                if($idioma==$lang['id']){
                array_push($lang_, $lang['nombre']);
            }
        }
    }
    return response()->json(["lenguajes"=>implode(";", $lang_),"otros"=>implode(";", $otrs)]);
})->name("lang_otros.post");
