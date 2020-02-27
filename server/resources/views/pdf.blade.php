<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style type="text/css">
      @font-face {
        font-family: 'Roboto';
        src: url('./public/fonts/Roboto-Regular.tff');
      }
      body{
          font-family: 'Arial, Helvetica, sans-serif';
          width:700px;
          color: #555;
          font-size: 13px;
      }
      table{
        width: 100%;
        font-family: 'Arial, Helvetica, sans-serif';
      }
      div{
        font-family: 'Arial, Helvetica, sans-serif';
      }
      label {
        font-family: 'Arial, Helvetica, sans-serif';
        line-height: normal;
        color: #000000;
      }
      .row {
        width:100%;
      }


      .ce-1 {
        width: 8.3333333333%;
      }
      .ce-2 {
        width: 16.6666666667%;
      }
      .ce-3 {
        width: 25%;
      }
      .ce-4 {
        width: 33.3333333333%;
      }
      .ce-5 {
        width: 41.6666666667%;
      }
      .ce-6 {
        width: 50% !important;
      }
      .ce-7 {
        width: 58.3333333333%;
      }
      .ce-8 {
        width: 66.6666666667%;
      }
      .ce-9 {
        width: 75%;
      }
      .ce-10 {
        width: 83.3333333333%;
      }
      .ce-11 {
        width: 91.6666666667%;
      }
      .ce-12 {
        width: 100%;
      }
      .string-input {
        width: 100%;
        font-size: 16px;
        border: none;
        color: #333;
        background-image: none;
        font-family: 'Arial, Helvetica, sans-serif';
        display: block;
        padding: 0px 10px;
        font-weight: 300;
        color: #666;
        padding:10px;
        display: block;
        background-color: #e6e6e6;;
        margin-bottom:10px;
        margin-top:5px; 
      }
      span {
        font-weight: normal;
      }

      p {
        margin:0;
      }
      h2 {
        padding: 0px 10px;
        margin: 0px;
        display: block;
        width: 240px;
        text-align: right;
        font-size: 30px;
        color: #555;
        font-weight: bold;
      }
      
    </style>
  </head>
  <body>
    <table style="margin-bottom: 30px;">
      <thead>
        <tr>
          <th class="ce-5">
            <img src="assets/imgs/logos/logo-form.svg" class="img-responsive" {{$styles["width_logo"]}} />
          </th>
          <th class="ce-2" style="text-align: center;"><div style=" background-color: #555;color:#fff;padding:10px !important;font-size: 20px;">{{$form_code}}</div></th>
          <th class="ce-5" style="text-align: right;"><h2>{{$form_title}}</h2></th>
        </tr>
      </thead>
    </table>
        
      @php $fila_nueva=0; $tam_fila=0;$col_full=0@endphp
      @foreach($data as $field)
        <?php
          $hidden = false;
        ?>
        @if(isset($field['settings']['visible']))
          @if($field['settings']['visible'] == false)
            <?php
              $hidden = true;
            ?>
          @endif
        @endif
        @if($hidden != true)
          <?php #asigna el tamaño actual de las columnas ?>
          @php $tam_fila=$tam_fila+(int)$field['size'];@endphp
          <?php #si el tamaño actual de las columnas supera a 12 que es 100%, completa la tabla con un td del tamaño faltante?>
          @if($tam_fila>12)
            @php $col_full=1;@endphp 
            <td class="ce-{{($tam_fila-(int)$field['size'])}}" style="text-align: left;" valign="top"></td><tr></table>
            @php $fila_nueva=0; @endphp
          @else @php$col_full=0;@endphp @endif  

          @if($fila_nueva==0)                     
          <table>
            <tr>
          @endif
          
          @php $fila_nueva=$field['size']+$fila_nueva;
          @endphp
        
          @if($field['type']=='string' || $field['type']=='longText' || $field['type']=='select')   
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label>                
              @if($field['type']=='string' || $field['type']=='select') 
                @php $min_height='auto'; $line_height='20px'; @endphp
              @else($field['type']=='longText') 
                @php $min_height='200px'; $line_height='1.2em !important'; @endphp
              @endif
            
              <div class="string-input" style="height: <?php echo $min_height?>;line-height:<?php echo $line_height?> !important;">
                @if(isset($record[$field['model']]) || $record[$field['model']]=!"") {{$record[$field['model']]}}
                @else
                  &nbsp;
                @endif
              </div>
            </td>
          @elseif($field['type']=='list' || $field['type']=='fullList')
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label> 
              @if(isset($record[$field['model']]) && count($record[$field['model']])>0)
                @foreach($record[$field['model']] as $data_list)  
                <div class="string-input" style="height:auto; line-height:20px !important;">
                  @if(isset($data_list) || $data_list!="" )
                    {{$data_list}}
                  @endif
                </div>
                @endforeach
              @else
              <div class="string-input" style="height:auto;line-height:20px !important;"></div>  
              @endif
            </td>
          @elseif($field['type']=='autocomplete')
            <?php #estructura de código que muestra los datos de un autocomplete ?>
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label>
              @if(count($record[$field['model']]['body'])>0 ) 
                <table width="100%">
                  @foreach($field['settings']['cols'] as $item)     
                  <tr>
                    @php $n=0; @endphp
                    @if(count($record[$field['model']]['body'])>0 )
                      @foreach($record[$field['model']]['body'] as $data_auto)   
                        @php 
                          $n=$item['size']+$n; 
                        @endphp                           
                        <td class="ce-{{$item['size']}}" style="text-align: left;" valign="top">
                          <div class="string-input" style="height:auto;line-height:20px !important;">
                            @if(isset($data_auto[$item['model']]) || $data_auto[$item['model']]!="" )
                              {{$data_auto[$item['model']]}}
                            @else 
                              &nbsp;
                            @endif 
                          </div>
                        </td>                                 
                      @endforeach
                      <?php #completa las celdas que faltan para completar la fila ?>
                      @if($n > 0 AND $n < 12)
                        <td class="ce-{{(12-$n)}}" style="text-align: left;" valign="top"></td>  
                      @endif
                    @endif
                    </tr>
                  @endforeach
                </table>
              @else
                <div class="string-input" style="height:auto;line-height:20px !important;"></div>  
              @endif
            </td>
          @elseif($field['type']=='radio')
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label> <br>
              <table class='radios'>
              <?php 
                                          
                #si los datos de busqueda estan en el settings
                if(isset($field['settings']['options'])) {
                  foreach($field['settings']['options'] as $item){
                    $checked="";
                    if(isset($record[$field['model']]) &&  $record[$field['model']] != ""){
                      if($item==$record[$field['model']]){
                        $checked="checked";
                      }
                    }
                    echo  '<tr>
                            <td>
                              <input type="radio" '.$checked.'>
                            </td>
                            <td>
                              <span>'.$item.'</span>
                            </td>
                          </tr>'; 
                  }
                } 
                // else{ 
                //   #sino los datos de busqueda estan en la base de datos    
                //   $dt=DB::table($field['settings']['search_in'])->pluck($field['settings']['search'])->all();
                //   foreach($dt as $data_list) {
                //       $chk="";
                //     if($data_list==$record[$field['model']])  $chk="checked";
                                                
                //     echo '<p style="text-align:left;width:100%;">'.$data_list.' <input style="margin-top:10px;float:right;" type="radio" '.$ck.' /> </p>';
                //   }
                // }
              ?>
              </table>
            </td>
          @elseif($field['type']=='checkboxs')
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label>  <br>      
              @php $dt=DB::table($field['settings']['search_in'])->pluck($field['settings']['search'])->all();@endphp                             
              @foreach($dt as $data_list)  
                @php $chk="";@endphp
                @foreach($record[$field['model']] as $data_list_)  
                  @if($data_list == $data_list_[$field['settings']['search']])
                    @php $chk="checked";@endphp   
                  @endif
                @endforeach
                <span style="text-align:left"><input style="margin-top:10px;" type="checkbox" {{$chk}} /> {{$data_list}}</span>
              @endforeach                             
              
                
            </td>
          @elseif($field['type']=='checkbox')
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              @if(isset($record[$field['model']]))
                @php $check=''; @endphp
                @if($record[$field['model']] != 0 ) @php $check='checked'; @endphp @endif
                <span style="text-align:left"> <input style="margin-top:10px;" type="checkbox" {{$check}} />  <label class="string">{{$field['label']}}</label> </span>
              @endif

            </td>
          @elseif($field['type']=='date')
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label> 
              <div class="string-input" style="height:auto;line-height:20px !important;">
                @if(isset($record[$field['model']]) && $record[$field['model']]!="") {{date('d/m/Y',strtotime($record[$field['model']]))}}
                @else
                &nbsp;
                @endif
              </div>
            </td>
          @elseif($field['type']=='datetime')
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label> 
              <div class="string-input" style="height:auto;line-height:20px !important;">
                @if(isset($record[$field['model']]) && $record[$field['model']]!="") {{date('d/m/Y h:i',strtotime($record[$field['model']]))}} 
                @else
                &nbsp;
                @endif
              </div>
            </td>
          @elseif($field['type']=='month')      
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label>                 
              <div class="string-input" style="height:auto;line-height:20px !important;">
              @if(isset($record[$field['model']])) 
                  @php 
                    $vm=array("01"=>"Enero","02"=>"Febrero","03"=>"Marzo","04"=>"Abril","05"=>" Mayo","06"=>"Junio","07"=>"Julio","08"=>"Agosto","09"=>"Septiembre","10"=>"Octubre","11"=>"Noviembre","12"=>"Diciembre");
                    $mv=explode("-",$record[$field['model']]);
                  @endphp
                  {{$vm[$mv[1]]}}-{{$mv[0]}}
                @endif
              </div>
            </td>
          @elseif($field['type']=='decimal')
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label> 
              <div class="string-input" style="height:auto;line-height:20px !important;">
                @if(isset($record[$field['model']]) && $record[$field['model']] != "") {{number_format($record[$field['model']], 2, ',', '.')}}
                @else
                &nbsp;
                @endif
              </div>
            </td>
          @else
            <td class="ce-{{$field['size']}}" style="text-align: left;" valign="top">
              <label class="string">{{$field['label']}}</label>                
              @if($field['type']=='string' || $field['type']=='select' || $field['type']=='integer') @php $min_height='auto'; $line_height='20px'; @endphp
              @else($field['type']=='longText') @php $min_height='200px'; $line_height='1.2em !important'; @endphp
              @endif
              <div class="string-input" style="height: <?php echo $min_height?>;line-height:<?php echo $line_height?> !important;">
                @if(isset($record[$field['model']]) || $record[$field['model']]=!"") {{$record[$field['model']]}} 
                @else
                &nbsp;
                @endif
              </div> 
            </td>
          @endif
          <?php //indica que el ancho supera el 100% entonces reinicia el tamaño de la fila ?>
          @if($col_full==1)
            $tam_fila=0;
            @endphp
          @endif
          @if($fila_nueva==12) 
            </tr>
            </table>
            @php 
              $fila_nueva=0;$tam_fila=0; 
            @endphp
          @endif            
        @endif            
      @endforeach
  </body>
</html>