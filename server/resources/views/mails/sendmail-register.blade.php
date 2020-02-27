<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Email registro usuario</title>
</head>
<body>
    <p style="text-align:center;"><img src="{{$data['server']}}/assets/imgs/logos/full-logo.svg"  width="{{$data['styles']['width_logo']}}"></p><br>   
    <h2 style="font-family: sans-serif;color: {{$data['styles']['color']}}; text-align:center;">{{$data['title']}}</h2> 
    <h3 style="font-family: sans-serif;color: #333333; text-align:center;">Ha sido registrado con &eacute;xito, pulsa el bot&oacute;n de abajo para validar tu cuenta.</h3>  
    <p style="text-align:center"><a href="{{$data['link']}}"
        style=" margin: 0px;
        padding: 0px;
        background-color: {{$data['styles']['color']}};
        border: none;
        outline: 0px;
        text-decoration: none;
        position: relative;
        overflow: hidden;
        display: inline-block;
        float: none;
        vertical-align: middle;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border-radius:20px;
        text-align: center;
        font-size: 17px;
        color: #fff;
        font-family: sans-serif;
        height: 40px;
        width: 300px;
        line-height: 44px;
        text-transform: uppercase;
        -moz-box-shadow: 1px 1px 4px rgba(0, 0, 0,0.5);
        -webkit-box-shadow: 1px 1px 4px rgba(0, 0, 0, .5);
        box-shadow: 1px 1px 4px rgba(0, 0, 0, .5);"
    ><span class="button-inner">VALIDAR</a></p>
</body>
</html>