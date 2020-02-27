<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Project extends Model
{
  use SoftDeletes;
  public function users()
  {
    return $this->belongsToMany('App\User')->withPivot(['percentage']);
  }
  public function services()
  {
    return $this->hasMany('App\Service');
  }
/***
 * Method: dataIndicators
 * Functions: calcular los datos de los indicadores y mantener esos valores por día en la tabla time_series
 * Parameters get: $day -> (Día del registro)   ,    $form_name  (Formulario asociado al indicador)
 * Data return : none
***/
public function dataIndicators($day,$form_name)
{  
  #Rutina: Incremento el valor del indicador en el periodo respectivo
  # Recorrer indicators y buscar los indicadores que tengan el form_name vehicles
  $indicator_ =DB::table('indicators')->where([
    ['form_name', '=', $form_name]
  ])->get();
  foreach($indicator_ as $data_indicator) {
    # Funcion que verifica que existe el dia en time_series por indicador
    $timeSerie_ = DB::table('time_series')->where([
      ['day', '=', $day],
      ['indicator_id', '=', $data_indicator->id]
    ])->first();
    # Si no existe creo el dia y le asigno 0 a value
    if(!isset($timeSerie_->id)){
      $id = DB::table('time_series')->insertGetId(   
        array('day' => $day ,'value' => 0,'indicator_id' => $data_indicator->id)       
      ); 
      # Trae el valor para el dia ese
      $timeSerie_ = DB::table('time_series')->where("id","=",$id)->first();
    }
    # si operation 
    # es count(*) incremetar
    # es sum(field) sumar el valor de field a ese valor
    # si es calc(amount*3) sumar el calculo a ese valor
    #recalcula y hace la consulta en vehicle
    $operation=DB::table('projects')->select(DB::raw($data_indicator->operation.' as value'))->where("date","=",$day)->where("cancelled","=",0)->first();
    # actualiza el valor
    DB::table('time_series')->where('id', $timeSerie_->id)->update( 
      array('day' => $day,'value' => $operation->value,'indicator_id' => $data_indicator->id)
    );
  }
}
/***
 * Method: removeDataIndicators 
 * Functions: reconstruye los valores por día en la tabla time_series, ya que existio cambio en la fecha de algun projects 
 * Parameters get: $day -> (Día del registro)   ,    $form_name  (Formulario asociado al indicador)
 * Data return : none
***/ 
public function removeDataIndicators($day,$form_name)
{  
  #Rutina: Incremento el valor del indicador en el periodo respectivo
  # Recorrer indicators y buscar los indicadores que tengan el form_name vehicles
  $indicator_ =DB::table('indicators')->where([
    ['form_name', '=', $form_name]
  ])->get();
  foreach($indicator_ as $data_indicator) {
    # Funcion que verifica que existe el dia en time_series por indicador     
    $timeSerie_ = DB::table('time_series')->where([
      ['day', '=', $day],
      ['indicator_id', '=', $data_indicator->id]
    ])->first();
    # Si  existe creo el dia y le asigno 0 a value
    if(isset($timeSerie_->id)){
      # si operation 
      # es count(*) incremetar
      # es sum(field) sumar el valor de field a ese valor
      # si es calc(amount*3) sumar el calculo a ese valor
      #recalcula y hace la consulta en projects de las fechas ya que hubo un cambio de fechas en un projects
      $operation=DB::table('projects')->select(DB::raw($data_indicator->operation.' as value'))->where("date","=",$timeSerie_->day)->where("cancelled","=",0)->first();
      # actualiza el valor restando el valor actual del vehicles al  value actual de timeSeria 
      DB::table('time_series')->where('id', $timeSerie_->id)->update(
        array('day' => $day,'value' => $operation->value,'indicator_id' => $data_indicator->id)
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
             if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
             else if ($action=='store') $concat .=$key.':projects';
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
             if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
             else if ($action=='store') $concat .=$key.':projects';
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
             if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
             else if ($action=='store') $concat .=$key.':projects';
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
               if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
               else if ($action=='store') $concat .=$key.':projects';
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
                if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':projects';
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
                if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':projects';
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
                if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':projects';
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
                if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':projects';
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
                if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':projects';
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
                if($action=='update') $concat .=$key.':projects,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':projects';
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
