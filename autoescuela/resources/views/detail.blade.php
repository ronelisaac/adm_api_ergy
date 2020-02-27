@extends('layout.layout')
    @section('content')
        <div class="main-content-wrapper" style="margin-top:0px;">
            <div class="google-map-area">
                <div id="google-map"></div>
            </div>
            
            <div class="page-content-inner pt--20 pt-md--20 header-detail"  style="background-color:#fff;padding-botoom:30px;">
                <div class="container">
                    <?php if($flag){?>
                    <div class="row no-gutters mb--77 mb-md--57 ">                        
                        <div class="col-4 col-md-3  product-main-image">
                            <div style="background-image:url(<?php  
                                 if(!isset($search['logo'])) echo env('APP_URL')."/assets/img/others/volante.png";
                                 else{
                                    $ch = curl_init();
                                    curl_setopt($ch, CURLOPT_URL,$search['logo']);
                                    // don't download content
                                    curl_setopt($ch, CURLOPT_NOBODY, 1);
                                    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                    $result = curl_exec($ch);
                                    curl_close($ch);
                                    if($result !== FALSE){
                                        echo $search['logo'];                    
                                    }else{
                                        echo  env('APP_URL')."/assets/img/others/volante.png";
                                    }
                            }?>);background-size:contain;background-position:center;background-repeat:no-repeat;background-color:#ecf7fb;" class="logo-responsive" alt="<?php echo $search['nombre'];?>"></div>
                        </div>
                        <div class="col-lg-6 product-main-details mt-md--50" style="padding-bottom:50px;">
                            <div class="product-summary pl--30 pl-md--0">
                                <h3 class="product-title mb--20" style="color:#001d31;"><?php echo $search['nombre'];?></h3>
                                <p class="product-short-description mb--20" style="color:#ccc;">
                                <i class="fa fa-map-marker" aria-hidden="true"></i>   
                                <?php  if($search['direccion']!="") echo $search['direccion'];?><br> 
                                <span style="color: #001d31;font-weight:bold;"><?php echo $search['distancia']. " km Lejos de Tí";?></span><br>
                                <a class="btn btn-primary_" onclick="verTelefonos();" style="cursor:pointer"><i class="fa fa-phone" style="color:#44f1bf"></i> Ver teléfono </a>
                                <span class="telefonos" style="display:none">
                                <?php
                                if(isset($search['telefonos'])){
                                    foreach($search['telefonos'] as $telefono){
                                        echo ' <span style="color: #001d31;font-weight:bold;"> '.$telefono.'</span> ';
                                    }
                                }else{
                                    echo ' <span style="color: #001d31;font-weight:bold;"> No hay teléfonos disponibles</span> ';
                                }
                                
                                ?>
                                </span> 
                                <br>

                                </p>
                                
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <a style="cursor:pointer" class="action-btn" id="favorito-<?php echo $search['id']?>" onclick="addFavoritos(<?php echo $search['id']?>)"><i class="fa fa-heart-o"></i></a>
                            <button class="btn btn-primary__" >Solicita ahora gratis</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="page-content-inner secciones"  style="background-color:#f6f7fe;margin-top:-80px;">
                <nav class="navbar navbar-default navbar-fixed-top navbar-light search-bar" id="search-bar">
                <div class="container">
                    <div class="row">
                        <div class="driving-school__anchor-nav d-flex" role="menubar" data-anchor-nav="">
                            <a id="btn_general-info" href="#general-info" class="driving-school__anchor font--bold flex-fill d-flex justify-content-center align-items-center text-center anchor-navigation__anchor-link--active" data-scroll="" role="menuitem"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Sobre nosotros </font></font></a>
                            <a id="btn_services" href="#services" class="driving-school__anchor font--bold flex-fill d-flex justify-content-center align-items-center text-center" data-scroll="" role="menuitem"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Servicios </font></font></a>
                            <a id="btn_extras" href="#extras" class="driving-school__anchor font--bold flex-fill d-flex justify-content-center align-items-center text-center" data-scroll="" role="menuitem"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Extras </font></font></a>
                            <a id="btn_locations" href="#locations" class="driving-school__anchor font--bold flex-fill d-flex justify-content-center align-items-center text-center" data-scroll="" role="menuitem"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Secciones</font></font></a>
                        </div>           
                    </div>

                
                </div>
                </nav>
                <div class="container general-info">
                    <div class="row justify-content-center" style="margin-bottom:50px;">
                        <div class="col-12 " style="margin-bottom:50px;padding-bottom:25px;padding-top:40px;">
                            
                            <h1 id="general-info" style="padding-top:60px; color:#001d31;text-align:left;font-weight:bold;">Acerca de nosotros
                                <br><small style="font-size:14px; text-align:justify"><?php echo $search['descripcion']?></small>
                            </h1>
                            <div class="row">
                                    <div class="col-12" style="padding-top:30px">
                                        <div class="product-footer-meta">
                                             <h3 style="text-align:left;">
                                                Horarios de atenci&oacute;n 
                                             </h3>
                                             <div class="row">
                                                <?php 
                                                    if(isset($search['horarios'])){
                                                        // $dias = array(1 => "Monday",2 => "Tuesday",3 => "Wednesday",4 => "Thursday",5 => "Friday",6 => "Saturday",7 => "Sunday");
                                                        $dias = array(1 => "Lunes",2 => "Martes",3 => "Miércoles",4 => "Jueves",5 => "Viernes",6 => "Sábado",7 => "Domingo");
                                                            $horas=["","","","","","","","","","","","",""];
                                                        $num_dia_actual = array_search(date("l"), $dias);
                                                        $i=0;$lunes="";
                                                    
                                                        foreach($search['horarios'] as $horario){
                                                            if($horario['dia']!=$num_dia_actual){
                                                                $num_dia_actual =$horario['dia'];
                                                                $i=0;
                                                            }
                                                            
                                                            if($horario['dia']==1){ 
                                                                                                              
                                                                if(strlen ($horario['desde']>4))  $horas[1]=$horas[1].' <a href="#" style="font-size:13px;font-weight:500;padding-left:60px;">De '.$horario['desde'].' a '. $horario['hasta'].' </a> ';                                                 
                                                            }
                                                            if($horario['dia']==2){                                                 
                                                                if(strlen ($horario['desde']>4)) $horas[2]=$horas[2].' <a href="#" style="font-size:13px;font-weight:500;padding-left:60px;">De '.$horario['desde'].' a '. $horario['hasta'].' </a> ';                                                 
                                                            }
                                                            if($horario['dia']==3){                                                 
                                                                if(strlen ($horario['desde']>4))  $horas[3]=$horas[3].' <a href="#" style="font-size:13px;font-weight:500;padding-left:60px;">De '.$horario['desde'].' a '. $horario['hasta'].' </a> ';                                                 
                                                            }
                                                            if($horario['dia']==4){                                                 
                                                                if(strlen ($horario['desde']>4))  $horas[4]=$horas[4].' <a href="#" style="font-size:13px;font-weight:500;padding-left:60px;">De '.$horario['desde'].' a '. $horario['hasta'].' </a> ';                                                 
                                                            }
                                                            if($horario['dia']==5){                                                 
                                                                if(strlen ($horario['desde']>4))  $horas[5]=$horas[5].' <a href="#" style="font-size:13px;font-weight:500;padding-left:60px;">De '.$horario['desde'].' a '. $horario['hasta'].' </a> ';                                                 
                                                            }
                                                            if($horario['dia']==6){                                                 
                                                                if(strlen ($horario['desde']>4))  $horas[6]=$horas[6].' <a href="#" style="font-size:13px;font-weight:500;padding-left:60px;">De '.$horario['desde'].' a '. $horario['hasta'].' </a> ';                                                 
                                                            }
                                                            if($horario['dia']==7){                                                 
                                                                if(strlen ($horario['desde']>4))  $horas[7]=$horas[7].' <a href="#" style="font-size:13px;font-weight:500;padding-left:60px;">De '.$horario['desde'].' a '. $horario['hasta'].' </a> ';                                                 
                                                            }
                                                        }
                                                        if(trim($horas[1])!="") echo '<div class="col-12 col-md-4"><p style="font-size:16px;font-weight:bold;text-align:left">'.$dias['1'].': <br> '.$horas[1].'</p></div>';
                                                        if(trim($horas[2])!="") echo '<div class="col-12 col-md-4"><p style="font-size:16px;font-weight:bold;text-align:left">'.$dias['2'].': <br> '.$horas[2].'</p></div>';
                                                        if(trim($horas[3])!="") echo '<div class="col-12 col-md-4"><p style="font-size:16px;font-weight:bold;text-align:left">'.$dias['3'].': <br> '.$horas[3].'</p></div>';
                                                        if(trim($horas[4])!="") echo '<div class="col-12 col-md-4"><p style="font-size:16px;font-weight:bold;text-align:left">'.$dias['4'].': <br> '.$horas[4].'</p></div>';
                                                        if(trim($horas[5])!="") echo '<div class="col-12 col-md-4"><p style="font-size:16px;font-weight:bold;text-align:left">'.$dias['5'].': <br> '.$horas[5].'</p></div>';
                                                        if(trim($horas[6])!="") echo '<div class="col-12 col-md-4"><p style="font-size:16px;font-weight:bold;text-align:left">'.$dias['6'].': <br> '.$horas[6].'</p></div>';
                                                        if(trim($horas[7])!="") echo '<div class="col-12 col-md-4"><p style="font-size:16px;font-weight:bold;text-align:left">'.$dias['7'].': <br> '.$horas[7].'</p></div>';
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 services" style="padding-top:40px" id="services">   
                                        <h2 style="padding-bottom:30px;"> Servicios</h2>     
                                    </div>
                                    <div class="col-12 col-md-6 services" style="padding-top:40px"> 
                                     <div class="product-footer-meta">
                                        <h3 style=""> Cursos</h3>
                                        <p style="text-align:left">
                                            <?php   if(isset($search['cursos'])) {
                                                         foreach($search['cursos'] as $curso){
                                                             echo '<div class="row">';
                                                             echo '<div class="col-2">';
                                                                if(trim($curso) == "A") echo '<img src="'.env('APP_URL').'images/a624aaa2450dec05753798003536fcf7.jpg" alt="" style="height:40px;width:40px;">';
                                                                if(trim($curso) == "B") echo '<img src="'.env('APP_URL').'images/auto.jpg" alt="" style="height:30px;width:30px;">';
                                                                if(trim($curso) == "C") echo '<img src="'.env('APP_URL').'images/camion.jpg" alt="" style="height:30px;width:30px;">';
                                                                if(trim($curso) == "D") echo '<img src="'.env('APP_URL').'images/bus.jpg" alt="" style="height:30px;width:30px;">';
                                                                if(trim($curso) == "E") echo '<img src="'.env('APP_URL').'images/vans.jpg" alt="" style="height:30px;width:30px;">';
                                                            echo '</div>';
                                                            echo '<div class="col-10" style="padding-top:8px">';
                                                                echo '<a href="#">Licencia tipo <b>'.trim($curso).'</b></a><br>';
                                                            echo '</div>';
                                                            echo '</div>';
                                                        }
                                                    }
                                            ?>
                                        </p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 services" style="padding-top:40px">  
                                        <div class="product-footer-meta">
                                        <h3 style=""> Idiomas</h3> 
                                        <p style="text-align:left">
                                                <?php    
                                                        $langs=json_decode($lang, true);
                                                        foreach($search['idiomas'] as $idioma){
                                                            foreach($langs as $lang){
                                                                if($idioma==$lang['id']){
                                                                echo '<a href="#"><i class="fa fa-flag" style="color:#44f1bf"></i> <b>'.$lang['nombre'].'</b></a> &nbsp; ';
                                                                }
                                                            }
                                                        }
                                        
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
            <div class="page-content-inner seccion2 secciones"  style="">
                <div class="container">
                    <div class="row justify-content-center extras" style="margin-bottom:50px;">
                            <div class="col-12 " id="extras" style="padding-bottom:40px;">
                                    <div class="product-footer-meta">
                                        <h3 style=""> Otros servicios</h3> 
                                        <p style="text-align:left">
                                       
                                            <?php  
                                                if(isset($search['otros'])){
                                                        $otros=json_decode($otros, true);
                                                        foreach($search['otros'] as $data_otros){
                                                            foreach($otros as $otro){
                                                                if($data_otros==$otro['id']){
                                                                echo '<a href="#"><b><i class="fa fa-check" style="color:#44f1bf"></i> '.$otro['nombre'].'</b></a> &nbsp; ';
                                                                }
                                                            }
                                                        }
                                                }else{
                                                    echo '<a href="#">Sin servicios extras</a>';
                                                }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row justify-content-center locations" style="margin-bottom:50px;">
                                <div class="col-12 " id="locations">        
                                        <div class="product-footer-meta secciones1">
                                            <h3 style="">Secciones </h3>
                                            <?php 
                                                /*if(isset($search['secciones'])){
                                                foreach($search['secciones'] as $seccion){
                                                        echo '
                                                            <div class="product-footer-meta" style="border-bottom:solid 0.5px #ccc;padding-top:20px;">
                                                                <p style="color:#001d31;text-align:left;;">
                                                                    <a style="font-weight:bold;" href="'.env('APP_URL').'/detail?auto_school_id='.$seccion['id'].'&latitud_search='.$seccion['latitud'].'&longitud_search='.$seccion['longitud'].'" style="font-weight:bold;">
                                                                        <i class="fa fa-check" style="color:#44f1bf"></i> '.$seccion['nombre'].' 
                                                                    </a> 
                                                                    <br> 
                                                                    <a style="padding-left:30px;" href="#"> <i class="fa fa-map-marker" style="color:#001d31"></i> a '.$seccion['distancia'].'Km  de tí</a><br>
                                                                    <a style="padding-left:30px;" href="'.env('APP_URL').'/detail?auto_school_id='.$seccion['id'].'&latitud_search='.$seccion['latitud'].'&longitud_search='.$seccion['longitud'].'">
                                                                        <i class="fa fa-external-link-square" style="color:#001d31"></i> '.$seccion['direccion'].'
                                                                    </a>
                                                                </p>
                                                            </div>
                                                        ';
                                                    }
                                                }else{
                                                    echo '<h4>No hay secciones disponibles</h4>';
                                                }*/
                                                ?>    
                                            <div style="text-align:center" id="map">
                                                
                                            </div>
                                        </div>
                                </div>
                    </div>
                </div>
            </div> 
    <?php }else{?>
                    <div class="row">
                        <div class="col-12">
                            <!-- User Action Start -->
                            <div class="user-actions user-actions__coupon">
                                <div class="message-box mb--30">
                                    <p><i class="fa fa-exclamation-circle"></i> No existen datos asociados a su solicitud! <a class="expand-btn" href="#coupon_info">Intenta buscar otra Auto Escuela.</a></p>
                                </div>
                                <div id="coupon_info" class="user-actions__form hide-in-default">
                                        <p>Intente buscando una nueva Auto Escuela.</p>
                                        <form action="list/" method="get">
                                            <div class="row no-gutters custom-search-input-2">
                                                <div class="col-lg-8 col-5">
                                                    <div class="form-group">
                                                        <input class="form-control" type="text" name="search_string" id="search_string" placeholder="Busca la ciudad de la autoescuela?...">
                                                        <i class="icon_search"></i>
                                                    </div>
                                                </div>
                                                <div class="col-lg-3 col-5">
                                                    
                                                    <select id="licencias" name="licencias" class="form__input nice-select">
                                                        <option value="">Todas Las Licencias</option>
                                                        <option value="A">A - Motos</option>
                                                        <option value="B">B - Coches</option>
                                                        <option value="c">C - Camión</option>
                                                        <option value="D">D - Bus</option>
                                                        <option value="E">E - Vans</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-1  col-2">
                                                    <input type="submit"  value="Search">
                                                </div>
                                            </div>
                                            <input type="hidden" name="latitud_search" id="latitud_search" />
                                            <input type="hidden" name="longitud_search" id="longitud_search" />
                                            <!-- /row -->
                                        </form>
                                </div>
                            </div>
                            <!-- User Action End -->
                        </div>
                    </div>
                <?php }?>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
    <script>
        $(document).ready(function() {
            document.title ='Autoescuela <?php echo $search['nombre'];?>';
            /********************   Input de busqueda    ***************************/
        var input = document.getElementById('search_string');
        var options = {
        types: ['(cities)'],
        componentRestrictions: {country: 'es'}
        };
        autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.addListener('place_changed', onPlaceChanged);

        function onPlaceChanged() {
          var place = autocomplete.getPlace();
          if (place.geometry) {
          // map.panTo(place.geometry.location);
          // map.setZoom(15);
          // search();
          $("#latitud_search").val(place.geometry.location.lat());
          $("#longitud_search").val(place.geometry.location.lng());
          } else {
            document.getElementById('autocomplete').placeholder = 'Ingrese una ciudad a buscar';
          }
        }
        /***********************************************/
        $body = $('body'),
		$document = $(document),
		$window = $(window),
		$pageUrl = window.location.href.substr(window.location.href.lastIndexOf("/") + 1),
		$header = $('.header'),
		$overlay = $('.global-overlay'),
        $headerPosition = ( $header.elExists() ) ? $header.offset().top : '',
		$mainHeaderHeight = ( $header.elExists() ) ? $header[0].getBoundingClientRect().height : 0,
		$headerTotalHeight = $headerPosition + $mainHeaderHeight,
		$fixedHeader = $('.header--fixed'),
		$fixedHeaderPosition = ( $fixedHeader.elExists() ) ? $fixedHeader.offset().top : '',
           
        $(window).on('load', function(){
                state.window_width = $(window).width();
                if ($(window).scrollTop() >= $headerTotalHeight) {
                        
                    } else {
                    }

                    //verifica los favoritos
                    findFavoritos(<?php echo $search['id']?>)
            });
            
            state.stickyHeader = function(){
               
                $(window).on('scroll', function(){
                    if ($(window).scrollTop() >= $headerTotalHeight) {
                       
                        
                    } else {
                    }
                });	
            }
            window.onscroll = function() {scrollBarMenu()};

            var navbar = document.getElementById("search-bar");
            var sticky = navbar.offsetTop;

            function scrollBarMenu() {
                if (window.pageYOffset >= sticky) {
                    navbar.classList.add("sticky")
                } else {
                    //navbar.classList.remove("sticky");
                    $("#search-bar").removeClass("sticky")
                }
            }
            $(window).on('scroll', function(){

                $(".general-info").mouseover(function(){
                    $(".driving-school__anchor-nav a").each(function(){
                        $(this).removeClass("anchor-navigation__anchor-link--active");
                    }); 
                    $("#btn_general-info").addClass("anchor-navigation__anchor-link--active");             
                });
                $(".locations").mouseover(function(){
                    $(".driving-school__anchor-nav a").each(function(){
                        $(this).removeClass("anchor-navigation__anchor-link--active");
                    }); 
                    $("#btn_locations").addClass("anchor-navigation__anchor-link--active");             
                });
                $(".services").mouseover(function(){
                    $(".driving-school__anchor-nav a").each(function(){
                        $(this).removeClass("anchor-navigation__anchor-link--active");
                    }); 
                    $("#btn_services").addClass("anchor-navigation__anchor-link--active");             
                });
                $(".extras").mouseover(function(){
                    $(".driving-school__anchor-nav a").each(function(){
                        $(this).removeClass("anchor-navigation__anchor-link--active");
                    }); 
                    $("#btn_extras").addClass("anchor-navigation__anchor-link--active");             
                });
            });
            initMapSucursales();
      });
     
      function verTelefonos(){
          $(".telefonos").show();
      }
      function initMap(){
          return false;
      }
    </script>
     <script>
        // When the window has finished loading create our google map below
        google.maps.event.addDomListener(window, 'load', init);

        function init() {
            // Basic options for a simple Google Map
            // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
            var latitud_search=0;var longitud_search=0;
            <?php
                if(isset($autoescuela['longitud_search'])){
                    if($autoescuela['longitud_search']!="") echo 'longitud_search='.$autoescuela['longitud_search'].'; ';
                }
                if(isset($autoescuela['latitud_search'])){
                    if($autoescuela['latitud_search']!="") echo 'latitud_search='.$autoescuela['latitud_search'].'; ';
                }
            ?>
           
            var mapOptions = {
                // How zoomed in you want the map to start at (always required)
                zoom: 12,
                // The latitude and longitude to center the map (always required)
                center: new google.maps.LatLng(latitud_search, longitud_search), 

                // How you would like to style the map. 
                // This is where you would paste any style found on

                styles: [{
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#e9e9e9"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 29
                            },
                            {
                                "weight": 0.2
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 18
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#dedede"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [{
                                "saturation": 36
                            },
                            {
                                "color": "#333333"
                            },
                            {
                                "lightness": 40
                            }
                        ]
                    },
                    {
                        "elementType": "labels.icon",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f2f2f2"
                            },
                            {
                                "lightness": 19
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [{
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 17
                            },
                            {
                                "weight": 1.2
                            }
                        ]
                    }
                ],
                draggable: true,
            };

            // Get the HTML DOM element that will contain your map 
            // We are using a div with id="map" seen below in the <body>
            var mapElement = document.getElementById('google-map');

            // Create the Google Map using our element and options defined above
            var map = new google.maps.Map(mapElement, mapOptions);
            // Let's also add a marker while we're at it
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(latitud_search, longitud_search),
                map: map,
                title: 'Contixs',
                icon:'<?php echo env('APP_URL') ?>/assets/img/icons/marker.png',
                animation: google.maps.Animation.BOUNCE
            });
        }
        /*************MAPA DE SUCURSALES**************** */
        var map;
        function initMapSucursales() {
        var markers = <?php echo json_encode($search['markers'])?>;
        var infowindows = <?php echo json_encode($search['infowindows'])?>;
        var infoWindowContent = infowindows;
        <?php
                if(isset($autoescuela['longitud_search'])){
                    if($autoescuela['longitud_search']!="") echo 'longitud_search='.$autoescuela['longitud_search'].'; ';
                }
                if(isset($autoescuela['latitud_search'])){
                    if($autoescuela['latitud_search']!="") echo 'latitud_search='.$autoescuela['latitud_search'].'; ';
                }
            ?>
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions2 = {
            center: new google.maps.LatLng(markers[0][1], markers[0][1]),
            mapTypeId: 'roadmap',
            zoom: 8,
            styles: [{
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#e9e9e9"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 29
                            },
                            {
                                "weight": 0.2
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 18
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#dedede"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [{
                                "visibility": "on"
                            },
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [{
                                "saturation": 36
                            },
                            {
                                "color": "#333333"
                            },
                            {
                                "lightness": 40
                            }
                        ]
                    },
                    {
                        "elementType": "labels.icon",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [{
                                "color": "#f2f2f2"
                            },
                            {
                                "lightness": 19
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [{
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [{
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 17
                            },
                            {
                                "weight": 1.2
                            }
                        ]
                    }
                ]
        };
                    
        // Display a map on the web page
        map = new google.maps.Map(document.getElementById("map"), mapOptions2);
        map.setTilt(50);
            
    

        // Add multiple markers to map
        var infoWindow = new google.maps.InfoWindow({ maxHeight: 320 }), marker, i;
        var marcadores=[];
        // Place each marker on the map  
        for( i = 0; i < markers.length; i++ ) {
            var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
            bounds.extend(position);
            marker = new google.maps.Marker({
                icon:  '<?php echo env('APP_URL') ?>/assets/img/icons/marker.png',
                position: position,
                map: map,
                title: markers[i][0]
            });
            
            // Add info window to marker    
            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infoWindow.setContent(infoWindowContent[i][0]);
                    infoWindow.open(map, marker);
                }
            })(marker, i));

            marcadores.push(marker);//usado para el clusters

            // Center the map to fit all markers on the screen
            map.fitBounds(bounds);
        }
        var markerCluster = new MarkerClusterer(map, marcadores,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
        // Set zoom level
        var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
            this.setZoom(14);
            google.maps.event.removeListener(boundsListener);
        });
        
    }
    </script>
      <script src="{{ URL::asset('assets/js/markerclusterer.js')}}"></script>

    @endsection

    @section('styles')
    <style>
    .nice-select:after { background-color:#ffffff;height:40px;}
    .custom-search-input-2 input[type='submit'] {
        position: absolute;
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        right: -1px;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        font-size: 0.875rem;
        top: 0;
        border: 0;
        height: 40px;
        cursor: pointer;
        outline: none;
        -webkit-border-radius: 0 3px 3px 0;
        -moz-border-radius: 0 3px 3px 0;
        -ms-border-radius: 0 3px 3px 0;
        border-radius: 0 3px 3px 0;
        text-indent: -999px;
        background: #FFC107 url(<?php echo env('APP_URL') ?>/assets/img/icons/search.svg) no-repeat center center;
    }
    .form__input nice-select{height:40px;font-weight:100;font-size:15px;}
    .custom-search-input-2 input{height:40px;font-weight:100;font-size:15px;}
    .custom-search-input-2 i {height:30px;color:#cccccc;}
    .nice-select {height:40px;line-height:40px;font-weight:100;}
    .custom-search-input-2 .nice-select .current{font-weight:100;font-size:15px;}
    /*.search-bar h4{padding-top:15px;font-weight:200;font-size:19px;color:#ffffff;}*/
    .main-content-wrapper{
        margin-top:30px;background-color:#f3f3f3;
    }
    .side-bar{background-color:#ffffff;}
    .shop-toolbar, .schools-grid{
        padding:20px;
    }
    .ft-product{
    background-color:#ffffff;
    padding:20px;
    }
    #map {
            height: 500px;  /* The height is 400 pixels */
            width: 100%;  /* The width is the width of the web page */
            
        }
        @media (max-width: 991px) {
            #map  {
                height: 400px;
    }
    }
    @media (max-width: 991px){
    .custom-search-input-2 input[type='submit'] {
        margin: 0px 0 0 0;
    }
    }
    .gm-style-iw-d{max-height:350px !important;}.gm-style-iw-d figure{text-align:center;}
    .gm-style-iw-d img{height:60% !important;width:60% !important;margin:0 auto;}
    .product-category a{
    font-size:14px;
    font-weight:bold;
    color:#004dda;

    }
    .product-title{
    font-size:12px;
    font-weight:500;
    color:#333333;
    }
    .shape{    
        
        border-style: solid; border-width: 0 70px 40px 0; float:right; height: 0px; width: 0px;
        -ms-transform:rotate(360deg); /* IE 9 */
        -o-transform: rotate(360deg);  /* Opera 10.5 */
        -webkit-transform:rotate(360deg); /* Safari and Chrome */
        transform:rotate(360deg);
    }
    .offer{
        background:#fff; border:1px solid #ddd; box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2); margin: 15px 0; overflow:hidden;
    }
    .offer:hover {
        -webkit-transform: scale(1.1); 
        -moz-transform: scale(1.1); 
        -ms-transform: scale(1.1); 
        -o-transform: scale(1.1); 
        transform:rotate scale(1.1); 
        -webkit-transition: all 0.4s ease-in-out; 
    -moz-transition: all 0.4s ease-in-out; 
    -o-transition: all 0.4s ease-in-out;
    transition: all 0.4s ease-in-out;
        }
    .shape {
        border-color: rgba(255,255,255,0) #d9534f rgba(255,255,255,0) rgba(255,255,255,0);
    }
    .offer-radius{
        border-radius:7px;
    }
    .offer-danger {	border-color: #d9534f; }
    .offer-danger .shape{
        border-color: transparent #d9534f transparent transparent;
    }
    .offer-success {	border-color: #5cb85c; }
    .offer-success .shape{
        border-color: transparent #5cb85c transparent transparent;
    }
    .offer-default {	border-color: #999999; }
    .offer-default .shape{
        border-color: transparent #999999 transparent transparent;
    }
    .offer-primary {	border-color: #428bca; }
    .offer-primary .shape{
        border-color: transparent #428bca transparent transparent;
    }
    .offer-info {	border-color: #5bc0de; }
    .offer-info .shape{
        border-color: transparent #5bc0de transparent transparent;
    }
    .offer-warning {	border-color: #f0ad4e; }
    .offer-warning .shape{
        border-color: transparent #f0ad4e transparent transparent;
    }

    .shape-text{
        color:#fff; font-size:12px; font-weight:bold; position:relative; right:-25px; top:-4px; white-space: nowrap;
        -ms-transform:rotate(30deg); /* IE 9 */
        -o-transform: rotate(360deg);  /* Opera 10.5 */
        -webkit-transform:rotate(30deg); /* Safari and Chrome */
        transform:rotate(30deg);
    }	
    .offer-content{
        padding:0 20px 10px;
    }
    @media (min-width: 768px){
        .lead {
            font-size: 21px;
        }
    }
    .lead {
        margin-bottom: 20px;
        font-size: 16px;
        font-weight: 300;
        line-height: 1.4;
    }
    .logo-responsive{
        width:200px;
        height:200px;
        top:-80px;position:absolute;border:solid 1px #e2e2e2;
    }
    @media (max-width: 768px){
        .logo-responsive{
        width:150px;
        height:150px;
        top:-40px;
        }
    }
    @media (max-width: 484px){
        .logo-responsive{
            width:100px;
            height:100px;
            top:-30px;
        }
    }
    .btn-primary_:hover {
        color: #fff !important;
        background-color: #1a41d8 !important;
        border-color: #1a41d8 !important;
        transition: all .4s ease-in-out;
    }
    .btn-primary_{
        margin-left:5px;
        color: #1a41d8 !important;
        background-color: #fff !important;
        border-color: #1a41d8 !important;
        font-size:14px;
        font-weight:bold;
    }
    .btn-primary__:hover {
        color: #fff !important;
        transition: all .4s ease-in-out;
        background: linear-gradient(130deg,#2957cb,#8400f2,#22daf6,#01a5e1 90%,#c500b1) !important;
        border-color: #1a41d8 !important;
        font-size:20px;
    }
    .btn-primary__{
        margin-left:5px;
        color: #fff !important;
        transition: all .4s ease-in-out;
        background: linear-gradient(130deg,#22daf6,#01a5e1,#2957cb,#8400f2 90%,#c500b1) !important;
        border-color: transparent !important;
        font-size:15px;
        font-weight:bold;
        position:absolute;
        top:-45px;
        left:25px;
        border-radius:25px;
        border: 0px;
        padding: 10px;
    }
    .product-footer-meta h3{
        color:#333;text-align:left;font-weight:bold;
    }
    .seccion2{
        background-color:#fff;margin-top:-50px;padding-top:80px;
    }
    .secciones1 .product-footer-meta{
        margin-top:5px;
    }
    .action-btn{        
        position: absolute;
        top: -45px;
        left: -40px;
    }
    @media (max-width: 768px){
        .product-summary .product-title { margin-top:100px;}
        .product-footer-meta h3{
            padding-top:40px;
            text-align:center;
            padding-bottom:20px;
            font-size : 25px;
        }
        .seccion2{
            background-color:#fff;margin-top:-50px;padding-top:20px;
        }
        .product-footer-meta p{
            text-align:center !important;        
        }
        .product-footer-meta a{
            padding-left: 5px !important;        
        }
        .secciones1 .product-footer-meta{
            margin-top:15px;
            padding-top:30px;
            padding-bottom:30px;
        }
        .secciones1 .product-footer-meta p{
            text-align:left !important;
        }
        .secciones1 .product-footer-meta h3{
            padding-top:60px;
            text-align:center !important;
            font-size : 25px;
        }
        .action-btn{        
        top: -45px;
        left:5px;   
        }
        .btn-primary__{left:55px;}
    }

    /******menu del medio******* */
    .d-flex {
    display: -webkit-box!important;
    display: -ms-flexbox!important;
    display: flex!important;
    }
    .d-flex {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important;
    }
    *, :after, :before {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    *, :after, :before {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .driving-school__anchor.anchor-navigation__anchor-link--active, .driving-school__anchor:hover {
    font-weight: 700;
    color: #1a41d8;
    border-bottom: 4px solid #1a41d8;
    }

    @media (min-width: 1200px)
    .driving-school__anchor {
        padding: 2em 1.5em;
    }
    @media (min-width: 992px)
    .driving-school__anchor {
        padding: 1.5em;
    }
    .driving-school__anchor {
        color: #95989a;
        border-bottom: 4px solid #b3bcc7;
        cursor: pointer;
    }
    .driving-school__anchor-nav{
        width: 100%;
    }
    .font--bold {
        font-weight: 700;
    }
    .text-center {
        text-align: center!important;
    }
    .d-flex {
        display: -webkit-box!important;
        display: -ms-flexbox!important;
        display: flex!important;
    }
    .align-items-center {
        -webkit-box-align: center!important;
        -ms-flex-align: center!important;
        align-items: center!important;
    }
    .justify-content-center {
        -webkit-box-pack: center!important;
        -ms-flex-pack: center!important;
        justify-content: center!important;
    }
    .flex-fill {
        -webkit-box-flex: 1!important;
        -ms-flex: 1 1 auto!important;
        flex: 1 1 auto!important;
    }
    .search-bar {
        margin-top: 90px;
        background: #fff;
        height: 80px;
        z-index: 1;
    }



    .sticky {
  position: fixed;
  top: 0;
  margin-top: 0;
  width: 100%;
}

.sticky + .content {
  padding-top: 0px;
}
    </style>
    @endsection