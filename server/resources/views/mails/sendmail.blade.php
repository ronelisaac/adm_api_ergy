<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Correo enviado desde {{$data['title']}}</title>
</head>
<body>
    <p style="text-align:center;"><img src="{{$data['server']}}/assets/imgs/logos/full-logo.svg"  width="{{$data['styles']['width_logo']}}"></p><br>   
    <h2 style="font-family: sans-serif;color: {{$data['styles']['color']}}; text-align:center;">{{$data['title']}}</h2> 
    <h3 style="font-family: sans-serif;color: #333333; text-align:center;">{{$data['from']}} le ha compartido un archivo adjunto.</h3>    
</body>
</html>