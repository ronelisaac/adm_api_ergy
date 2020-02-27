<html lang="es-ES">
<head>
  <meta charset="utf-8">
    <title>Recuperar contraseña {{$title}}</title>
    <link href="css/main.css" rel="stylesheet">
   <style>

.nu-btn {
    background-color: {{$styles['color']}};
}
</style>
</head>

<body>
    <div class="big-header">
      <div>
        <img src="./assets/imgs/logos/full-logo.svg" width="">
      </div>
      <h1 style="color:{{$styles['color']}}">{{$title}}</h1>
    </div>
    @if($vencida==true)
    <form class="row" action="{{ url('remember-user') }}" method="post">      
      {{ csrf_field() }}
      <div class="colum medium-4" style="text-align:center;margin:0 auto;">
        <h3>Recuperar contraseña</h3>
        <input class="input-text ng-pristine ng-valid ng-touched" name="email" placeholder="Ingresa el correo" type="email" ng-reflect-name="email" ng-reflect-model="" required>
      </div>
      @if(session()->get('message'))
      <div class="colum medium-4"  style="text-align:center;margin:0 auto;" >
          <p style="color:green;text-align:center">{{session()->get('message') }}</p>
      </div>
      @endif    
      @if(session()->get('error'))
      <div class="colum medium-4" style="text-align:center;margin:0 auto;">
          <p style="color:#b10000;">{{session()->get('error') }}</p>
      </div>
      @endif  
      <div class="colum medium-4" style="text-align:center;margin:20px auto;">
        <button class="nu-btn" full="" ion-button="" large="" round="" ng-reflect-large="" ng-reflect-round="" ng-reflect-full=""><span class="button-inner">Recuperar contraseña</span><div class="button-effect"></div></button>
      </div>
    </form>
    @php  session()->put('message',false);  session()->put('error',false); @endphp
    @else
      <h1 style="text-align:center;">Se ha enviado un correo a {{session()->get('email_change')}} para la recuperaci&oacute;n de la contrase&ntilde;a</h1>
    @endif
</body>
</html>