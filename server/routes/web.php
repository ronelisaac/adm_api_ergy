<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\AlertsEmail;
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

Route::get('/', function () {
   // return view('welcome');
   $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
   $json = json_decode(file_get_contents($path), true); 
   return Redirect::to($json['client_url']);
});
Route::get('/forget-pass', function () {
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true); 
    $title= $json['display_name'];
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];
    /*compara el tiempo de duracion de la solicitud de resteblecimiento*/
    $vencida=true;
    
    if(session()->get('token_time')){
        if(strtotime(date("Y-m-d h:i"))<= strtotime(session()->get('token_time')) ){
            $vencida=false;
        }else{
            /*********destruye las variables de sesion ************/
            session()->put('token_url',false);
            session()->put('token_time',false);        
            session()->put('email_change',false);   
            session()->put('id_change',false);
            session()->put('server_url',false);
        }
    }
    return view('mails.forget',compact('title','vencida','styles'));
});


Route::get('/download-file/{user_id}/{url_file}', function () {    
   return explode("digitalicemos",base64_decode($url_file));
    /*return response()->file(storage_path().base64_decode($url_file));*/
});
Route::post('/remember-user', function (Request $request) {
    $confirm=DB::table('users')->select('id','email')->where("email","=",$request->email)->whereNotNull('password')->first();    
    
    session()->put('error',false); 
    if(!isset($confirm->email)){       
        session()->put('error','Por favor verifique, El usuario no se encuentra en el sistema'); 
        session()->put('message',false);    
    } else{      
        $token= bin2hex(openssl_random_pseudo_bytes((200 - (200 % 2)) / 2));//token cifrado       
        $nuevafecha = strtotime ( '+15 minute' , strtotime ( date("Y-m-d h:i")) ) ;//15 minutos adelantado es el tiempo de expiracion
        $date_time = date ( 'Y-m-d h:i' , $nuevafecha );//fecha y hora
        $records=[];
        $records['link_token']=$token;
        $records['expiration_date']=$date_time;
        $params['records']=$records;
        //agrega el token y la fecha de expiración
        DB::table('users')->where('id', $confirm->id)->update($params['records']);
        session()->put('message','Correo enviado a '.$request->email.' , tiene un lapso de 15 minutos para la recuperación de su contraseña');
        /*session()->put('token_url',$token);
        session()->put('token_time',$date_time);        
        session()->put('email_change',$request->email);   
        session()->put('id_change',$confirm->id);*/
        #lee el json setting con los datos de configuracion de form
        $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
        $json = json_decode(file_get_contents($path), true); 
        $title= $json['display_name'];
        $server= $json['server_url'];
        $styles['width_logo']=$json['width_logo'];
        $styles['color']=$json['color'];
        //session()->put('server_url',$server);
        $link=$server.'/change-pass/'.$token;
        Mail::to($request->email)->send(new AlertsEmail(["link"=>$link,"subject"=>"solicitud de cambio de contraseña","title"=>$title,"server"=>$server,"file"=>"sendmail-forget","styles"=>$styles]));
    }   
    return redirect()->back();
   
});
Route::get('/change-pass/{token}', function ($token) {       
    $results=DB::table('users')->select('id','email','expiration_date')->where("link_token","=",$token)->first();
    $vencida=true;  
    #verifica que no esté vencido el tiempo de expiracion del token 
    if(isset($results->expiration_date)){
        if(strtotime(date("Y-m-d h:i"))<= strtotime($results->expiration_date) ){
            $vencida=false;
        }
        session()->put('token_url',$token);
        session()->put('token_time',$results->expiration_date);        
        session()->put('email_change',$results->email);   
        session()->put('id_change',$results->id);
    }
    $path = storage_path() . "/settings.json"; # storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true); 
    $title= $json['display_name'];
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];
    $server= $json['server_url'];
    $client= $json['client_url'];
    return view('mails.change',compact('title','vencida','styles','server','client'));
});
Route::post('/edit-pass', function (Request $request) {
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true); 
    $title= $json['display_name'];
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];
    $server= $json['server_url'];
    $client= $json['client_url'];
    $vencida=false;
    $validado=false ;
    session()->put('finish_change',false);
    session()->put('url_server',$server);
    /*compara el tiempo de duracion de la solicitud de resteblecimiento   */    
    if(session()->get('token_time')){
        if(strtotime(date("Y-m-d h:i"))<= strtotime(session()->get('token_time')) ){
            /*Puede editar*/
            $record['updated_by'] = session()->get('id_change');
            $record['password'] =Hash::make($request->password);
            $record["updated_at"] =  date("Y-m-d h:i");
            $params['record'] = $record;
            DB::table('users')->where('id', session()->get('id_change'))->update($params['record']);           
            session()->put('finish_change','contraseña cambiada con éxito');            
            $vencida=true;
        }
    }
    #se usa para cuando se crea el password por primera vez
    if(isset($request->id)){
        $record['updated_by'] = $request->id;
        $record['password'] =Hash::make($request->password);
        $record["updated_at"] =  date("Y-m-d h:i");
        $params['record'] = $record;
        DB::table('users')->where('id', $request->id)->update($params['record']); 
        $validado=true;
        $valid=true;        
         /*********destruye las variables de sesion */
        return view('mails.create-pass',compact('title','validado','server','valid','styles','client'));
    }
    
    /*********destruye las variables de sesion */
     session()->put('token_url',false);
     session()->put('token_time',false);        
     session()->put('email_change',false);   
     session()->put('id_change',false);
     return view('mails.change',compact('title','vencida','styles','server','client'));
   
});
#validacion de usuario
Route::get('/user-validation/{id_base64}', function ($id_base64) {     
    $validado=true;
    if(isset($id_base64))
    {
        $user_id=base64_decode($id_base64);
        $confirm=DB::table('users')->select('password','id')->where("id","=", $user_id)->first();  
        if(isset($confirm->id)){
            if(!isset($confirm->password) or $confirm->password==NULL ){
                #consulta si tiene un rol de tipo usuario
                $role=DB::table('role_user')->select('id')->where("user_id","=", $user_id)->first(); 
                if(isset($role->id))  $validado=false;
            }
        }
    }
    $path = storage_path() . "/settings.json"; // storage/json/setting_form.json
    $json = json_decode(file_get_contents($path), true); 
    $title= $json['display_name'];
    $server= $json['server_url'];    
    $client= $json['client_url'];   
    $styles['width_logo']=$json['width_logo'];
    $styles['color']=$json['color'];

    return view('mails.create-pass',compact('title','validado','server','user_id','styles','client'));
});

