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
      html {
       
      }
      span {
        font-weight: 300;
      }
      table {
        border-collapse: separate;
        border-spacing: 10px;
        width: 100%;
      }
      body{
        font-family: 'Arial, Helvetica, sans-serif';
        width:700px;
        color: #555;
        font-size: 13px;
      }
      header {
       /* position: fixed;
        top: 0cm;
        left: 0cm;
        right: 0cm;
        height: 3cm;
        line-height: 30px;*/
      }
      .content {
        margin-bottom:4cm;
        /*height: 1000px;
        height:18cm;*/
        position: relative;
       /* page-break-after: always ;*/
      }
      
      
      #date {
        font-size: 12px;
        position: absolute;
        top: 25px;
        right: 5px;
      }
      #order-number {
        font-size: 12px;
        position: absolute;
        top: 5px;
        right: 5px;
      }
      #header {
        margin-top: 10px;
      }
      #header th {
        width: 50%;
      }
      .section {
        margin-top: 15px;
      }
      .section > div {
        border-bottom: 1px solid #666;
      }
      .section > div > span {
        display: inline-block;
        padding: 5px;
        background: #666;
        color: #fff;
        font-weight: bold;
        font-size: 16px;
      }
      .section > div.full > span {
        width: 100%;
        text-align: center;
      }
      .section > table {
        
      }
      #description > span {
      }
      #observations > span {
        
      }
      #payment th {
        width: 30%;
        text-align: center;
      }
      #payment th {
        
        padding: 5px;
        background: #666;
        color: #fff;
        font-weight: bold;
        font-size: 16px;
        
      }
      #payment td span {
        display: block;
        padding: 10px 0px;
        font-size: 13px;
        text-align: center;
      }
      #payment-form table td {
        width: 25%;
      }
      #payment-form table td {
        position: relative;
        padding-left: 25px;
        height: 20px;
      }
      #payment-form table td div {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 20px;
        height: 20px;
        border: 1px solid #666;
        text-align: center;
        line-height: 20px;
        font-weight: bold; 
      }
      #payment-form table td span {
        display: inline-block;
        position: relative;
        top: -13px;
        
      }
      #footer {
        margin-top: 30px;
        border-top:solid 0.5px #ccc;
      }
      #footer td {
        width: 50%;
        height: 35px;
      }
      #footer b {
        display: block;
        text-align: left;
      }
      footer {
        position: fixed;
        bottom: 1cm;
        left: 0cm;
        right: 0cm;
        height: 4cm;
      }
      .page-break{        
        page-break-after:always;
      }
      
    </style>
  </head>
  <body>
    <footer>
      <table id='footer'>
        <tr>
          <td>
            <b>Firma responsable ERGY SOLAR:</b>
          </td>
          <td>
            <b>Firma del Solicitante:</b>
          </td>
        </tr>
        <tr>
          <td>
            <b>Aclaraci&oacute;n:</b>
          </td>
            
          <td>
            <b>Aclaraci&oacute;n:</b>
          </td>
        </tr>
        <tr>
          <td>
              
          </td>
          <td>
            <b>Tipo y n&uacute;mero de documento:</b>
          </td>
        </tr>
      </table>
    </footer>
    <main>
      <div class="content">
        <div id="order-number">
            <b>N° de orden:</b>
            <span>{{ $record['id'] }}</span>
        </div>
        <div id="date">
          <b>Fecha:</b>
          <span>{{ $record['date'] }}</span>
        </div>
          
        <table id='header'>
          <tr>
            <th>
              <img src="assets/imgs/logos/logo-form.svg" class="logo" />
            </th>
            <th style="text-align: right;">
              <h2>Orden de Compra</h2>
            </th>
          </tr>
        </table>
        <div class='section'>
          <div>
            <span>Solicitante</span>
          </div>
          <table>
            <tr>
              <td>
                <b>Nombre: </b>
                <span>{{ $record['users'][0]['name'] }} {{ $record['users'][0]['last_name'] }}</span>
              </td>
              <td>
                @if(isset($record['users'][0]['tin']))
                  <b>CUIT: </b>
                  <span>{{ $record['users'][0]['tin'] }}</span>
                @else
                  <b>DNI: </b>
                  <span>{{ $record['users'][0]['identity_id'] }}</span>
                @endif
              </td>
              <td style="text-align: right;">
                <b>N° de cliente: </b>
                <span>{{ $record['users'][0]['id'] }}</span>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <b>Domicilio: </b>
                <span>{{ $record['users'][0]['address_line'] }}</span>
              </td>
              <!-- <td>
                <b>Ciudad: </b>
                <span></span>
              </td>
              <td>
                <b>Provincia: </b>
                <span></span>
              </td> -->
            </tr>
          </table>
        </div>
        <div class='section'>
          <div>
            <span>Responsable</span>
          </div>
          <table>
            <tr>
              <td>
                <b>Nombre y apellido: </b>
                <span>{{ $record['liaison_full_name'] }}</span>
              </td>
              <td>
                <b>Tel&eacute;fono: </b>
                <span>{{ $record['liaison_phone'] }}</span>
              </td>
              <td>
                <b>Email</b>
                <span>{{ $record['liaison_email'] }}</span>
              </td>
            </tr>
          
          </table>
        </div>
        <div id="description" class='section'>
          <div>
            <span>Descipci&oacute;n de las tareas</span>
          </div>
          <span>
            {!! nl2br(e($record['description']))  !!}
          </span>
        </div>
        <div id="observations" class='section'>
          <div>
            <span>Observaciones</span>
          </div>
          <span>
          {!! nl2br(e($record['observations'])) !!}
          </span>
        </div>
        <table id="payment">
          
          <tr>
            <th>Total a pagar</th>
            <th>Anticipo</th>
            <th>Saldo</th>
          </tr>
          <tr>
            <td>
              <span>{{ $record['amount_budgeted'] }}</span>
            </td>
            <td>
              <span>{{ $record['advance'] }}</span>
            </td>
            <td>
              <span>{{ $record['balance'] }}</span>
            </td>
          
          </tr>
            
          
        </table>

        <div id="payment-form" class='section'>
          <div class='full'>
            <span>Forma de pago</span>
          </div>
          <span>
            <table>
              <tr>
                <td>
                  @if($record['check'] == true)
                  <div>X</div>
                  @else
                  <div></div>
                  @endif
                  <span>Cheque</span>
                  
                </td>
                <td>
                  @if($record['bank_transfer'] == true)
                  <div>X</div>
                  @else
                  <div></div>
                  @endif
                  <span>Transferencia</span>
                  
                </td>
                <td>
                  @if($record['bank_deposit'] == true)
                  <div>X</div>
                  @else
                  <div></div>
                  @endif
                  <span>Dep&oacute;sito</span>
                  
                </td>
                <td>
                  @if($record['cash'] == true)
                  <div>X</div>
                  @else
                  <div></div>
                  @endif
                  <span>Efectivo</span>
                  
                </td>
              <tr>
            </table>
          </span>
        </div>
      </div>
    </main>

      <!-- {{ $record['name'] }} -->

      <!-- @foreach($record['services'] as $key => $item)
      <p>
        {{ $item->detail }} {{  $item->amount }}
      </p>
      @endforeach -->
      
      
      <!-- <table class='footer'>
        <tr>
          <th>
            1
          </th>
          <th style="text-align: right;">
            2
          </th>
        </tr>
      </table> -->
   
    
  </body>
</html>