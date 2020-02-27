<?php

namespace App;

class Api  
{
    public function get_schools($lat,$lng,$distancia,$cursos,$idiomas,$horarios,$otros){
        //API URL http://pre.elportaldelalumno.com/buscadorae/autoescuelas?latitud=40.4167754&longitud=-3.7037901999999576&distancia=20&idiomas=53&horario=1&otros=1
        $strSearch="";
        if($cursos){
            //foreach($cursos as $licencia){
                $strSearch .="&cursos=".$cursos;
           // }
        }
        if(count($idiomas)>0){
            foreach ($idiomas as $value) {
                if($value!="")$strSearch .="&idiomas=".$value;
            }
        }
        if(count($horarios)>0){
            foreach($horarios  as  $value){
                if($value!="")$strSearch .="&horario=".$value;
            }
        }
        if(count($otros)>0){
            foreach($otros as  $value){
                if($value!="")$strSearch .="&otros=".$value;
            }
        }

        $url = 'http://pre.elportaldelalumno.com/buscadorae/autoescuelas?latitud='.$lat.'&longitud='.$lng.'&distancia='.$distancia.$strSearch;
        //  Initiate curl
        $ch = curl_init();
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url Accept: application/json
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept:application/json'));
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);
        if(is_array(json_decode($result))){
            if(count(json_decode($result)) > 0){ return $result;}else{ return false; }
        }else return false;
   }
   
   public function get_school($id,$lat,$lng){
       //API URL
       $url = 'http://pre.elportaldelalumno.com/buscadorae/autoescuela?id='.$id.'&latitud='.$lat.'&longitud='.$lng;
       //  Initiate curl
       $ch = curl_init();
       // Will return the response, if false it print the response
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       // Set the url Accept: application/json
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept:application/json'));
       // Execute
       $result=curl_exec($ch);
       // Closing
       curl_close($ch);
       return $result;
   }
   public function count_schools_lic($lat,$lng,$distancia){
       //API URL
       $url = 'http://pre.elportaldelalumno.com/buscadorae/autoescuelas?latitud='.$lat.'&longitud='.$lng.'&distancia='.$distancia;
       //  Initiate curl
       $ch = curl_init();
       // Will return the response, if false it print the response
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       // Set the url Accept: application/json
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept:application/json'));
       // Execute
       $result=curl_exec($ch);
       // Closing
       curl_close($ch);
       $a=0;$b=0;$c=0;$d=0;$e=0;
       foreach(json_decode($result, true) as $item){
           $sc=json_decode($this->get_school($item['id'],$lat,$lng),true);
           foreach($sc['cursos'] as $curso){
               if($curso=='A')$a++;
               if($curso=='B')$b++;
               if($curso=='C')$c++;
               if($curso=='D')$d++;
               if($curso=='E')$e++;
           }
       }
       $cursos=[];
       $cursos['A']=$a;$cursos['B']=$b;$cursos['C']=$c;$cursos['D']=$d;$cursos['E']=$e;
       return json_encode($cursos);
   }
   
   public function get_schools_lic($lat,$lng,$distancia,$licencia){
       //API URL
       $url = 'http://pre.elportaldelalumno.com/buscadorae/autoescuelas?latitud='.$lat.'&longitud='.$lng.'&distancia='.$distancia;
       //  Initiate curl
       $ch = curl_init();
       // Will return the response, if false it print the response
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       // Set the url Accept: application/json
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept:application/json'));
       // Execute
       $result=curl_exec($ch);
       // Closing
       curl_close($ch);
       $a=0;
       $data=[];
       foreach(json_decode($result, true) as $item){
           $sc=json_decode($this->get_school($item['id'],$lat,$lng),true);
           $flag=0;
           foreach($sc['cursos'] as $curso){
               if($curso==$licencia){              
                  $flag++;            
               }
           }
           #si existe la licencia en una escuela la guarda
           if($flag>0){
               $data[$a]=$item;
               $a++;
           }
           
       }   
       return json_encode($data);
   }
   public function get_lang(){
       //API URL
       $url = 'http://pre.elportaldelalumno.com/buscadorae/idiomas';
       //  Initiate curl
       $ch = curl_init();
       // Will return the response, if false it print the response
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       // Set the url Accept: application/json
       curl_setopt($ch, CURLOPT_URL,$url);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept:application/json'));
       // Execute
       $result=curl_exec($ch);
       // Closing
       curl_close($ch);
       return $result;
   }
   public function get_otros(){
    //API URL
    $url = 'http://pre.elportaldelalumno.com/buscadorae/otros';
    //  Initiate curl
    $ch = curl_init();
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the url Accept: application/json
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('accept:application/json'));
    // Execute
    $result=curl_exec($ch);
    // Closing
    curl_close($ch);
    return $result;
}

   
}
