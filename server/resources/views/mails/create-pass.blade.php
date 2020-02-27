<html lang="es-ES">
<head>
  <meta charset="utf-8">
    <title>Recuperar contraseña {{$title}}</title>
    <link href="{{$server}}/css/main.css" rel="stylesheet">
   <style>

.nu-btn {
    background-color: {{$styles['color']}};
}
</style>
</head>

<body>
    <div class="big-header">
      <div>
      <img src="../assets/imgs/logos/full-logo.svg">
      </div>
      <h1 style="color:{{$styles['color']}}">{{$title}}</h1>
      <h2 style="text-align:center">Generar contrase&ntilde;a</h2>
    </div>
    @if($validado==false)
    <form class="row" action="{{ url('edit-pass') }}" method="post">      
      {{ csrf_field() }}
      <div class="colum medium-4" style="text-align:center;margin:0 auto;">
        
        <p style="text-align:left;">Escriba la contraseña</p>
        <input class="input-text ng-pristine ng-valid ng-touched" name="password" id="password" placeholder="******" type="password" minlength="8" maxlength="20" ng-reflect-name="Escriba la contrase&ntilde;a" ng-reflect-model="" required onkeyup='check();'>        
      </div>
      <div class="colum medium-4" style="text-align:center;margin:20px auto;">      
        <p style="text-align:left;">Repita la contraseña</p>
        <input class="input-text ng-pristine ng-valid ng-touched" name="confirm_password" id="confirm_password" placeholder="******" type="password" minlength="8"  maxlength="20" ng-reflect-name="Repita la contrase&ntilde;a" ng-reflect-model="" required onkeyup='check();'>
        <p  style="text-align:left;padding : 10px;" id="message"></p>
      </div>
     
      <div class="colum medium-4" style="text-align:center;margin:20px auto;">
        <button class="nu-btn" id="enviar" full="" ion-button="" large="" round="" ng-reflect-large="" ng-reflect-round="" ng-reflect-full=""><span class="button-inner">Recuperar contraseña</span><div class="button-effect"></div></button>
      </div>
      <input type="hidden" name="id" value="{{$user_id}}">
    </form>
 
    <script>
      var check = function() {
        
          if (document.getElementById('password').value ==
            document.getElementById('confirm_password').value) {
            document.getElementById('message').style.color = 'green';
            document.getElementById('message').innerHTML = 'Las contrase&ntilde;as coinciden';
            document.getElementById("enviar").disabled = false;
          } else {
            document.getElementById('message').style.color = 'red';
            document.getElementById('message').innerHTML = 'Las contrase&ntilde;as no coinciden';
            document.getElementById("enviar").disabled = true;

          }
          if(document.getElementById('password').value.length==0 && document.getElementById('confirm_password').value.length==0){
            document.getElementById('message').innerHTML = '';
          }  
      }
    </script>
    @else 
      @if(isset($valid))
        <h1 style="text-align:center">El usuario ha sido validado con &Eacute;xito</h1>
        <div class="row">
          <div class="colum medium-4" style="text-align:center;margin:20px auto;">
            <a class="nu-btn" href="{{$client}}"><span class="button-inner">finalizar</span><div class="button-effect"></div></a>
          </div>
        </div>
      @else
        <h1 style="text-align:center">El usuario ya se encuentra validado o no tiene los priviegios</h1>
        <div class="row">
          <div class="colum medium-4" style="text-align:center;margin:20px auto;">
            <a class="nu-btn" href="{{$client}}"><span class="button-inner">ingresar</span><div class="button-effect"></div></a>
          </div>
        </div>
      @endif

    @endif


</body>
</html>