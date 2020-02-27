<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
  use SoftDeletes;
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
             if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
             else if ($action=='store') $concat .=$key.':products';
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
             if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
             else if ($action=='store') $concat .=$key.':products';
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
             if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
             else if ($action=='store') $concat .=$key.':products';
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
               if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
               else if ($action=='store') $concat .=$key.':products';
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
                if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':products';
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
                if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':products';
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
                if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':products';
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
                if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':products';
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
                if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':products';
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
                if($action=='update') $concat .=$key.':products,'.$field['model'].','.$id;
                else if ($action=='store') $concat .=$key.':products';
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
