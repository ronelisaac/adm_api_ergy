@extends('layout.layout')
  @section('content')
  <main class="main-content-wrapper">
            <!-- Slider area Start -->
            <section class="homepage-slider mb--75 mb-md--55" style="overflow-x: hidden;">
                <div class="container-fluid animation">
                @include('layout.animation')
                    
                </div>
          
            </section>
            <div class="container-fluid mb--100" style="position:absolute;width:100%;top:380px;">
                 <div  class="center-block form-animation">
                       <div class="col-12 text-center"> <h1 class="text-center">TuPermiso <br>Autoescuelas!</h1>
                        </div>
                        <form action="{{ URL::asset('/list')}}" method="get">
                            <div class="row mt--30">
                                <div class="col-10 offset-1 col-md-4 offset-md-4 form-rounded">
                                  <div class="input-group">
                                    <span class="input-group-addon" style="display:none">
                                        <label style="top:0px;position:relative;padding-left:5px;font-weight:bold;color:#fff;">Zip Code</label> 
                                        <input type="radio" id="zip_code" name="zip_code" onchange="capturarCiudad()" >
                                    </span>
                                    <input class="form-control" style="background-color:#2619c5;border:0px;font-size:20px;font-weight:bold;color:#fff;" type="text" name="search_string" id="search_string" placeholder="Ciudad o Cod. Zip">                                    
                                    <span class="input-group-addon" style="">
                                        <button class="botonsub" style="">IR</button>
                                    </span>
                                </div>
                            </div>
                            <input type="hidden" name="latitud_search" id="latitud_search" />
                            <input type="hidden" name="longitud_search" id="longitud_search" />
                            <!-- /row -->
                        </form>
                </div>
            </div>
            </div>
            <!-- Slider area End -->
            <div class="container">
                <div class="row">
                    <div class="col-12 col-xl-10 offset-xl-1 mb--75">
                        <div class="benefits__content">
                                 <h2 class="mt--20 mb--20 ptb--20 text-center" style="text-align:center" ><font style="vertical-align: inherit;"><font style="vertical-align: inherit;color:#001d31">ASÍ DE FÁCIL</font></font></h2>
                            
                            <div class="row">
                                        <div class="col-4 col-md-1 text-center" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                   <img src="assets/img/icons/number-1.svg" class="image-responsive" />
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-8 col-md-3" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                    Ingrese su dirección o ubicación actual.
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-4 col-md-1 text-center" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                   <img src="assets/img/icons/number-2.svg" class="image-responsive" />
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-8 col-md-3" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                Encuentra la escuela de manejo que más te convenga.
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-4 col-md-1 text-center" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                   <img src="assets/img/icons/number-3.svg" class="image-responsive" />
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-8 col-md-3" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                Realice su consulta sin compromiso con un solo clic.
                                                </font>
                                            </font>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Top Sale Area Start -->
            <section class="top-sale-area  licencias-div">
                <div class="container">
                    <div class="mt--40 row mb--35 mb-md--23" style="padding-top:80px">
                        <div class="col-12 text-center">
                            <h2 class="">TU INICIO RÁPIDO A LA CAPACITACIÓN</h2>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Single Item of popular category starts -->
                        <div class="col-sm-3 col-lg-1 col-md-1 "></div>
                         <div class="col-sm-4 col-lg-2 col-md-3  ptb-10">
                            <a href="" class="grid_item">
                                <figure>
                                    <img src="images/a624aaa2450dec05753798003536fcf7.jpg" alt="" style="heigth:100px;width:100px;">
                                    <div class="info text-center">
                                        <h3><button class="btn btn-primary">A - Motos</button></h3>
                                    </div>
                                </figure>
                            </a>
                        </div>
                        <div class="col-sm-4 col-lg-2 col-md-3 ">
                            <a href="" class="grid_item">
                                <figure>
                                    <img src="images/auto.jpg" alt="" style="heigth:100px;width:100px;">
                                    <div class="info text-center">
                                        <h3><button class="btn btn-primary">B - Coches</button></h3>
                                    </div>
                                </figure>
                            </a>
                        </div>
                                <div class="col-sm-4 col-lg-2 col-md-3  ptb-10">
                            <a href="" class="grid_item">
                                <figure>
                                    <img src="images/camion.jpg" alt="" style="heigth:100px;width:100px;">
                                    <div class="info text-center">
                                    
                                        <h3><button class="btn btn-primary">C - Camión</button></h3>
                                    </div>
                                </figure>
                            </a>
                        </div>
                                <div class="col-sm-4 col-lg-2 col-md-3  ptb-10">
                            <a href="" class="grid_item">
                                <figure>
                                    <img src="images/bus.jpg" alt="" style="heigth:100px;width:100px;">
                                    <div class="info text-center">
                                        <h3><button class="btn btn-primary">D - Bus</button></h3>
                                    </div>
                                </figure>
                            </a>
                        </div>
                        <div class="col-sm-4 col-lg-2 col-md-3 ptb-10 ">
                            <a href="" class="grid_item">
                                <figure>
                                    <img src="images/vans.jpg" alt="" style="heigth:100px;width:100px;">
                                    <div class="info text-center">
                                        <h3><button class="btn btn-primary">E - Vans</button></h3>
                                    </div>
                                </figure>
                            </a>
                        </div>
                        <!-- Single Item of popular category ends -->
                    </div>
                    </div>
                </section>

                <div class="container-fluid cont-div" >
                <div class="row">
                    <div class="col-12 col-xl-10 offset-xl-1 mb--75">
                        <div class="benefits__content">
                            <div class="row">
                                        <div class="col-4 col-md-1 ptb-10" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                   <img src="assets/img/icons/checkmark-colored.svg" class="image-responsive" />
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-8 col-md-3 ptb-10" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                Probado y de alta calidad: su búsqueda de autoescuela con nivel
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-4 col-md-1 ptb-10" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                   <img src="assets/img/icons/checkmark-colored.svg" class="image-responsive" />
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-8 col-md-3 ptb-10" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                Encuentre simple y rápidamente la escuela de manejo adecuada cerca de usted.
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-4 col-md-1 ptb-10" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                   <img src="assets/img/icons/checkmark-colored.svg" class="image-responsive" />
                                                </font>
                                            </font>
                                        </div>
                                        <div class="col-8 col-md-3 ptb-10" style="padding-top:30px">
                                            <font style="vertical-align: inherit;">
                                                <font style="vertical-align: inherit;">
                                                Directo y gratuito para enviar su solicitud
                                                </font>
                                            </font>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </main>
                
  @endsection
  @section('scripts')
  <script>
    $(document).ready(function() {
        $('select').niceSelect();
        $(".nice-select").removeClass("form__input");        
        $(".header__inner").css({'background-color':'rgb(0, 128, 234)','transition': 'fill 1000ms linear'});
        
    });
    function initMap (){
        return false;
    }
    </script>

    <script>
        $(document).ready(function() {
        var svg = document.getElementsByTagName("svg")[0];
            var bbox = svg.getBBox();

            svg.setAttribute("viewBox", (bbox.x-10)+" "+(bbox.y-10)+" "+(bbox.width+20)+" "+(bbox.height+20));
            svg.setAttribute("width", (bbox.width+20)  + "px");
            svg.setAttribute("height",(bbox.height+20) + "px");
            repeat(1);
            $(".mask").css("width",$(".header__inner fixed-header").width()+'px');
        

            if ("geolocation" in navigator){
                    navigator.geolocation.getCurrentPosition(function(position){ 
                            var pos = {lat: position.coords.latitude, lng: position.coords.longitude};
                            localStorage.setItem("tupermiso_pos",JSON.stringify(pos));
                            countSchools(localStorage.getItem("tupermiso_pos"));
                            console.log( localStorage.getItem("tupermiso_pos"))
                        });
                }else{
                    console.log("Browser doesn't support geolocation!");
                }
                /********************   Input de busqueda    ***************************/
                var latitud= 40.4165000;
                var longitud =  -3.7025600;
                //countSchools(latitud,longitud);
                //listAEIndex (latitud,longitud);
                $("#licencias_filter").change(function(){ listAEIndex (latitud,longitud);})
                $("#distancia").change(function(){ listAEIndex (latitud,longitud); $("#distance_span").html($(this).val());})
                capturarCiudad();
            
            });

                
                
                

      
        function listAEIndex (latitud,longitud){       
                var licencia="";
                if( $("#licencias_filter").val()!="") licencia= $("#licencias_filter").val();
                
                    $.getJSON( URL_BASE+"/api", { "request":"auto_escuelas","latitud_search" : latitud, "longitud_search" :  longitud, "licencia" :  licencia,"distancia":  $("#distancia").val() } )
                    .done(function( data, textStatus, jqXHR ) {
                        if ( console && console.log ) {
                            if(data.length==0)  $(".list-schools").html('<div class="col-12"><h3 class="text-center" style="color:#666"><i class="la la-map-marker" aria-hidden="true"></i> No hay escuelas disponibles en '+$("#distancia").val()+' km de distancia</h3></div>');
                            else construirListado(data);
                        }
                    })
                    .fail(function( jqXHR, textStatus, errorThrown ) {
                        if ( console && console.log ) {
                            console.log( "Algo ha fallado: " +  textStatus );
                        }
                    });
                
                return false;
        }
        function initMap(){
            
        }

    </script>
  @endsection
  @section('styles')
     <style>
    .header__inner{background-color:rgb(0, 128, 234);}
    .sticky-header{background-color:rgb(255, 255, 255);}
    .form-rounded {      
        background-color: #2619c5;
        border: 2px solid transparent;
        height: 5.2rem;
        border-radius: 2.6rem;
        padding:10px;
    }
    .botonsub{
            width: 5.2rem;
        height: 5.2rem;
        border-radius: 50%;
        margin-bottom: 0;
        top: -12px;
        right: -11px;
        position:absolute;
        padding: 0;
        background-color:#2619c5;border:0px;color:#fff;font-size:18px;font-weight:bold;
        z-index:150;
    }
    .botonsub:hover{ background-color:#230448;}
    h1{
        text-align:center;
    }
    input:focus {
         border:0px;
    }
    @media (max-width: 768px){
    
        .form-rounded {      
            background-color: #2619c5;
            border: 2px solid transparent;
            height: 7.2rem;
            border-radius: 3.3rem;
            padding:10px;
            }
            .botonsub{
                width: 7.2rem;
            height: 7.2rem;
            border-radius: 50%;
            margin-bottom: 0;
            top: -12px;
            right: -11px;
            position:absolute;
            padding: 0;
            background-color:#2619c5;border:0px;color:#fff;font-size:18px;font-weight:bold;
            z-index:20;
        }
    }

    /********new */
    @media (min-width: 768px)
    .benefits__content {
        padding: 4rem 0 5rem;
    }
    .benefits__content {
        padding: 2.5rem 0 3rem;
    }
    @media (min-width: 768px)
    .benefits__content {
        padding: 4rem 0 5rem;
    }
    .benefits__content {
        padding: 2.5rem 0 3rem;
    }
    .image-responsive{
        max-width:50px;
        max-height:70px;
    }

    a.grid_item .info {
        
        background: none;
    }
    .licencias-div{
        padding-top:40px;
        padding-bottom:40px;
        background-color: rgba(183,226,240,.5);
        color:#001d31;
    }
    .licencias-div h2{
        color:#001d31;
        text-align:center;
    }
    .cont-div{
        padding-top:40px;
        padding-bottom:40px;
        background-color: #001d31;
        color:#ffff;
    }
    .cont-div font{
        color:#fff;
        font-size:bold;
    }
    .btn {
        padding: 8px 8px;
        border:solid 2.5px #ffffff;
    }
    .btn, .btn--primary {
        color: #fff;
        background-color: #1a41d8;
        font-size:14px;
        font-weight:bold;
    }
    .btn:hover {
        color: #1a41d8;
        background-color: #fff;
        font-size:14px;
        font-weight:bold;
        border-color:#1a41d8;
    }
    .btn {
        padding: 8px 8px;
        border:solid 2.5px #ffffff;
    }
     ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
        color: #ffffff;
    }
      ::-moz-placeholder { /* Firefox 19+ */
        color: #ffffff;
    }
     ::-ms-input-placeholder { /* IE 10+ */
       
        color: #ffffff;
    }
    ::-moz-placeholder { /* Firefox 18- */
         color: #ffffff;
    }
    @media (max-width: 74.9375em){
        h2, .h2 {
            font-size: 50px;
        }
    }    




    a.grid_item {
        display: block;
        margin-bottom: 30px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        border-radius: 3px;
        overflow: hidden;
        height: 200px;
        border: none;
        }
        @media (max-width: 991px) {
        a.grid_item {
            height: 180px;
        }
        }
        @media (max-width: 767px) {
        a.grid_item {
            height: 150px;
        }
        }
        @media (max-width: 575px) {
        a.grid_item {
            height: 180px;
        }
        }
        a.grid_item .info {
        position: absolute;
        width: 100%;
        z-index: 9;
        display: block;
        padding: 25px 25px 10px 25px;
        color: #fff;
        left: 0;
        bottom: 0;
        background: transparent;
        background: -webkit-linear-gradient(top, transparent, #2B74C8);
        background: linear-gradient(to bottom, transparent, #2B74C8);
        box-sizing: border-box;
        }
        @media (max-width: 767px) {
        a.grid_item .info {
            padding: 15px 15px 0 15px;
        }
        }
        a.grid_item .info small {
        display: inline-block;
        margin-bottom: 5px;
        font-weight: 600;
        font-size: 11px;
        background-color: #045498;
        line-height: 1;
        padding: 3px 5px 2px 5px;
        }
        a.grid_item .info h3 {
        color: #111111;
        font-size: 21px;
        }
        a.grid_item .info p {
        color: #fff;
        margin-bottom: 0;
        font-size: 15px;
        }
        a.grid_item figure {
        position: relative;
        overflow: hidden;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        border-radius: 3px;
        width: 100%;
        height: 100%;
        }
        @media (max-width: 767px) {
        a.grid_item figure {
            height: 150px;
        }
        }
        @media (max-width: 575px) {
        a.grid_item figure {
            height: 180px;
        }
        }
        a.grid_item figure img {
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        position: absolute;
        left: 50%;
        top: 50%;
        -webkit-transform: translate(-50%, -50%) scale(1);
        -moz-transform: translate(-50%, -50%) scale(1);
        -ms-transform: translate(-50%, -50%) scale(1);
        -o-transform: translate(-50%, -50%) scale(1);
        transform: translate(-50%, -50%) scale(1);
        }
        @media (max-width: 767px) {
        a.grid_item figure img {
            width: 100%;
        }
        }
        a.grid_item:hover figure img {
        -webkit-transform: translate(-50%, -50%) scale(1.1);
        -moz-transform: translate(-50%, -50%) scale(1.1);
        -ms-transform: translate(-50%, -50%) scale(1.1);
        -o-transform: translate(-50%, -50%) scale(1.1);
        transform: translate(-50%, -50%) scale(1.1);
        }
        a.grid_item.small {
        height: 170px;
        }
        @media (max-width: 1199px) {
        a.grid_item.small {
            height: 130px;
        }
        }
        @media (max-width: 991px) {
        a.grid_item.small {
            height: 180px;
        }
        }
        @media (max-width: 767px) {
        a.grid_item.small {
            height: 150px;
        }
        }
        @media (max-width: 575px) {
        a.grid_item.small {
            height: 170px;
        }
        }
        a.grid_item.small .info {
        padding: 35px 15px 0 15px;
        }
        a.grid_item.small .info h3 {
        font-size: 16px;
        }
        @media (max-width: 767px) {
        a.grid_item.small figure {
            height: 150px !important;
        }
        }
        @media (max-width: 575px) {
        a.grid_item.small figure {
            height: 170px !important;
        }
        }
        a.grid_item.small figure img {
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        width: 100%;
        }
    </style>

  @endsection