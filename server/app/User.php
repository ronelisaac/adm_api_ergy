<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
class User extends Authenticatable
{
  use SoftDeletes;
  use HasApiTokens, Notifiable;
  // protected $appends = [
  //     'roles'
  // ];
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'name', 'email', 'password',
  ];
  
  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
      'password', 'remember_token',
  ];
  
  public function records()
  {
      return $this->belongsToMany('App\Record')->withPivot('write', 'read');
  }
  public function hasRole($slug, $requireAll = false)
  {
   # Check if array of role slugs have been passed in, if so loop through them.
  # if $requireAll is passed in as true, need to make sure the user has all roles passed in
  # You can also pass an object and perform a check is_object then use $role->slug in loop
  if (is_array($slug)) {
      foreach ($slug as $roleName) {
          $hasRole = $this->hasRole($roleName);
          if ($hasRole && !$requireAll) {
              return true;
          } elseif (!$hasRole && $requireAll) {
              return false;
          }
      }
      return $requireAll;
  } else {
      foreach ($this->roles as $rol) {
          if ($rol->slug == $slug) {
              return true;
          }
      }
  }
  return false;
  }
  public function user_types()
  {
    return $this->hasMany('App\UserType');
  }
  public function can($permission, $requireAll = false)
  {
    # This checks for permissions attached to a role, so it grabs all the roles a user
    # has and loops trough each role to get the permissions accosiacted and loops through
    # the permissions looking for a match.
    if (is_array($permission)) {
        foreach ($permission as $permName) {
            $hasPerm = $this->can($permName);
            if ($hasPerm && !$requireAll) {
                return true;
            } elseif (!$hasPerm && $requireAll) {
                return false;
            }
        }
        return $requireAll;
    } else {
        foreach ($this->roles->load('permissions') as $role) {
            # Validate against the Permission table
            foreach ($role->permissions as $perm) {
                if ($perm->slug == $permission) {
                    return true;
                }
            }
        }
    }
    return false;
  }
  public function roles()
  {
    return $this->belongsToMany('App\Role');
  }
  public function address()
  {
    return $this->belongsTo('App\Address');
  }
  /***
  * Method: dataIndicators
  * Functions: calcular los datos de los indicadores y mantener esos valores por día en la tabla time_series
  * Parameters get: $day -> (Día del registro)   ,    $form_name  (Formulario asociado al indicador)
  * Data return : none
  ***/
  public function dataIndicators($day,$form_name,$indicators)
  {  
    #Rutina: Incremento el valor del indicador en el periodo respectivo
    # Recorrer indicators y buscar los indicadores que tengan el form_name users
    foreach($indicators as $data_indicator){
      # Funcion que verifica que existe el dia en time_series por indicador
      $timeSerie_ = DB::table('time_series')->where([
        ['day', '=', $day],
        ['indicator_id', '=', $data_indicator['id']]
      ])->first();
      # Si no existe creo el dia y le asigno 0 a value
      if(!isset($timeSerie_->id)){
        $id = DB::table('time_series')->insertGetId(   
           array('day' => $day ,'value' => 0,'indicator_id' => $data_indicator['id'])
        ); 
        # Trae el valor para el dia ese
        $timeSerie_ = DB::table('time_series')->where("id","=",$id)->first();
      }
      # si operation 
      # es count(*) incremetar
      # es sum(field) sumar el valor de field a ese valor
      # si es calc(amount*3) sumar el calculo a ese valor
      #recalcula y hace la consulta en vehicle
      $operation=DB::table('users')->select(DB::raw($data_indicator['operation'].' as value'))->whereDate($data_indicator['date_field'],"=",$day)->where("cancelled","=",0)->first();
      # actualiza el valor
      DB::table('time_series')->where('id', $timeSerie_->id)->update( 
        array('day' => $day,'value' => $operation->value,'indicator_id' => $data_indicator['id'])
      );
    }
  }
  /***
   * Method: removeDataIndicators 
   * Functions: reconstruye los valores por día en la tabla time_series, ya que existio cambio en la fecha de algun users 
   * Parameters get: $day -> (Día del registro)   ,    $form_name  (Formulario asociado al indicador)
   * Data return : none
  ***/ 
  public function removeDataIndicators($day,$form_name,$indicators)
  {  
    #Rutina: Incremento el valor del indicador en el periodo respectivo
    # Recorrer indicators y buscar los indicadores que tengan el form_name users
    foreach($indicators as $data_indicator) {
      # Funcion que verifica que existe el dia en time_series por indicador
      $timeSerie_ = DB::table('time_series')->where([
        ['day', '=', $day],
        ['indicator_id', '=', $data_indicator['id']]
      ])->first();
      # Si  existe creo el dia y le asigno 0 a value
      if(isset($timeSerie_->id)){
        # si operation 
        # es count(*) incremetar
        # es sum(field) sumar el valor de field a ese valor
        # si es calc(amount*3) sumar el calculo a ese valor
        #recalcula y hace la consulta en users de las fechas ya que hubo un cambio de fechas en un users
        $operation=DB::table('users')->select(DB::raw($data_indicator['operation'].' as value'))->whereDate($data_indicator['date_field'],"=",$timeSerie_->day)->where("cancelled","=",0)->first();
        # actualiza el valor restando el valor actual del vehicles al  value actual de timeSeria 
        DB::table('time_series')->where('id', $timeSerie_->id)->update(
          array('day' => $day,'value' => $operation->value,'indicator_id' => $data_indicator['id'])
        );
      }
    }
  }
 /***
  * Method: validationsReturns 
   * Functions: genera las validaciones de acuerdo al tipo de dato y la estructura del setting
   * Parameters get: $array_fields -> (Arreglo de datos)   ,    $action  ()
   * Data return : none
 ***/ 
 public function validationsReturns($array_fields,$action,$id)
 {
   $array_validator=[];
   foreach($array_fields as $field){
    switch($field['type']){
      case 'string':
        $i=0;
        if(isset($field['settings']['validations'])) {
          foreach($field['settings']['validations'] as $key =>$validation){
            if($i==0){ $concat=$field['type']."|"; $i++;}  else $concat .="|";     
            //verifica booleano que esta en el settings no tienen valor si no su propio nombre ejm: unique o require
            if($key=='required'){
             if($validation==true) $concat .=$key;
             else $concat .="nullable";
            }else if($key=='unique'){
             if($action=='update'){
		if( $field['model'] != "email" ){
			 $concat .=$key.':users,'.$field['model'].','.$id;
		}else $concat .="nullable";
	     }else if ($action=='store') $concat .=$key.':users';
            }else{
              if($key == 'min_length') $key='min';
              if($key == 'max_length') $key='max';
              $concat .=$key.':'.$validation;
            }
            $array_validator[$field['model']]=$concat;
          }
        }
      break;
     case 'longText':
       $i=0;
       if(isset($field['settings']['validations'])) {
         foreach($field['settings']['validations'] as $key =>$validation){
           if($i==0){ $concat="string|"; $i++;}  else $concat .="|";
           //verifica booleano que esta en el settings no tienen valor si no su propio nombre ejm: unique o require
           if($key=='required'){
             if($validation==true) $concat .=$key;
             else $concat .="nullable";
           }else if($key=='unique'){
             if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
             else if ($action=='store') $concat .=$key.':users';
           }else{
             if($key == 'min_length') $key='min';
           if($key == 'max_length') $key='max';
             $concat .=$key.':'.$validation;
           }
           $array_validator[$field['model']]=$concat;
         }
       }
     break;
     case 'date':
       $i=0;
       if(isset($field['settings']['validations'])) {
         foreach($field['settings']['validations'] as $key =>$validation){
           if($i==0){ $concat=$field['type']."|date_format:Y-m-d|"; $i++;}  else $concat .="|";
           #verifica si es un require el cual es el unico booleano que esta en el settings
            if($key=='required'){
             if($validation==true) $concat .=$key;
             else $concat .="nullable";
            }else if($key=='unique'){
             if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
             else if ($action=='store') $concat .=$key.':users';
           }else{
            if($key == 'min') $key='after_or_equal';
            if($key == 'max') $key='before_or_equal';
            $concat .=$key.':'.$validation;
           }
           $array_validator[$field['model']]=$concat;
         }
       }
      break;
        case 'datetime':
          $i=0;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=$field['type']."|date_format:Y-m-d H:i|"; $i++;}  else $concat .="|";
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
               if($validation==true) $concat .=$key;
               else $concat .="nullable";
              }else if($key=='unique'){
               if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
               else if ($action=='store') $concat .=$key.':users';
              }else{
                if($key == 'min') $key='after_or_equal';
                if($key == 'max') $key='before_or_equal';
                $concat .=$key.':'.$validation;
              }
              $array_validator[$field['model']]=$concat;
            }
          }
        break;
        case 'month':
          $i=0;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=$field['type']."|date_format:Y-m|"; $i++;}  else $concat .="|"; 
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
                if($validation==true) $concat .=$key;
                else $concat .="nullable";
              }else if($key=='unique'){
                if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':users';
              }else{
                if($key == 'min') $key='after_or_equal';
                if($key == 'max') $key='before_or_equal';
                $concat .=$key.':'.$validation;
              }
              $array_validator[$field['model']]=$concat;
            }
          }
        break;
        case 'integer':
          #para validar el max_length y el min_length en un entero laravel es asi interger|digits_between:3,4;
          $i=0;$min="";$max="";$nulo=false;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=$field['type']."|"; $i++;}  else $concat .="|";
              if($key=='required'){
                if($validation==true) $concat .=$key;
                else $concat .="nullable";
                $nulo=true;
                $array_validator[$field['model']]=$concat;
              }else if($key=='unique'){
                if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':users';
                $array_validator[$field['model']]=$concat;
              }else{
                if(!$nulo){$concat .="nullable|";$nulo=true;} 
                if($key=='min_length' || $key=='max_length'){
                  if($key=='min_length') $min =$validation;
                  if($key=='max_length') $max =$validation;
                  if($min!="" AND $max!=""){
                    $concat .= "digits_between:".$min.",".$max;
                    $array_validator[$field['model']]=$concat;
                  }
                }else{
                  $concat .=$key.':'.$validation;
                  $array_validator[$field['model']]=$concat;
                }
              }
            }
          }
        break;
        case 'decimal':
          $i=0;$min=0;$max=0;$nulo=false;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=""; $i++;}  else $concat .="|";
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
                if($validation==true) $concat .=$key;
                else $concat .="nullable";
                $nulo=true;
                $array_validator[$field['model']]=$concat;
              }else if($key=='unique'){
                if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':users';
                $array_validator[$field['model']]=$concat;
              }else{
                // regex:/^d*(.d{1,2})?$/"
                if(!$nulo){$concat .="nullable|";$nulo=true;} 
                if($key=='min' || $key=='max'){
                  if($key=='min') $min =$validation;
                  if($key=='max') $max =$validation;
                  if($min!="" AND $max!=""){
                    $concat .= 'regex:/^d*(.d{'.$min.','.$max.'})?$/"';
                    $array_validator[$field['model']]=$concat;
                  }
                }else{
                  $concat .=$key.':'.$validation;
                  $array_validator[$field['model']]=$concat;
                } 
              }
            }
          }
        break;
        case 'record':
          $i=0;
          if(isset($field['settings']['validations'])) { 
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=""; $i++;}  else $concat .="|";
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
                if($validation==true) $concat .=$key;
                else $concat .="nullable";
              }else if($key=='unique'){
                if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':users';
              }else{
                $concat .=$key.':'.$validation;
              }
              $array_validator[$field['model']]=$concat;
            }
          }
        break;
        case 'checkbox':
        break;
        case 'checkboxs':
        break;
        case 'radio':
        break;
        case 'select':
        break;
        case 'list':
          $i=0;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=""; $i++;}  else $concat .="|";
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
                  if($validation==true)  $concat .="array|min:1|".$key; #se verifica que el body del autcomplete tenga un array con datos
                  else $concat .="nullable";
                 $array_validator[$field['model']]=$concat;
               }
            }
          }
        break;
        case 'fullList':
          $i=0;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=""; $i++;}  else $concat .="|";
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
                  if($validation==true)  $concat .="array|min:1|".$key; #se verifica que el body del autcomplete tenga un array con datos
                  else $concat .="nullable";
                 $array_validator[$field['model']]=$concat;
               }
            }
          }
        break;
        case 'autocomplete':
          $i=0;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=""; $i++;}  else $concat .="|";
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
                  if($validation==true)  $concat .="array|min:1|".$key; #se verifica que el body del autcomplete tenga un array con datos
                  else $concat .="nullable";
                 $array_validator[$field['model'].".body"]=$concat;
               }
            }
          }
        break;
        case 'address':
          $i=0;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=""; $i++;}  else $concat .="|";
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
                if($validation==true) $concat .=$key;
                else $concat .="nullable";
              }else if($key=='unique'){
                if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':users';
              }else{
                $concat .=$key.':'.$validation;
              }
              $array_validator[$field['model']]=$concat;
            }
          }
        break;
        case 'file':
          $i=0;
          if(isset($field['settings']['validations'])) {
            foreach($field['settings']['validations'] as $key =>$validation){
              if($i==0){ $concat=""; $i++;}  else $concat .="|";
              #verifica si es un require el cual es el unico booleano que esta en el settings
              if($key=='required'){
                if($validation==true) $concat .=$key;
                else $concat .="nullable";
              }else if($key=='unique'){
                if($action=='update') $concat .=$key.':users,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':users';
              }else{
                if($key=='accept') $key='mimes';
                $concat .=$key.':'.$validation;
              }
              $array_validator[$field['model']]=$concat;
            }
          }
        break;
      }
    }
    return $array_validator;
  }
}
