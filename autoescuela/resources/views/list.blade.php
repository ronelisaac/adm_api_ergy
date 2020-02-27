@extends('layout.layout')
  @section('content')
    <div  class="main-content-wrapper" style="margin-top:0px;overflow-x: hidden;">
            <div class="shop-page-wrapper">
                <div class="container-fluid" style="background-color:#ffffff;">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning alert-dismissible fade " style="
                                 @if( count( $search['markers']) > 0 )display:none; @else margin-top:20px; @endif
                            " id="sin_escuelas" role="alert">
                                <strong>No se encontraron autoescuelas!</strong> intente con otra ubicación.
                                <i class="fa fa-close close" data-dismiss="alert" aria-label="Close">
                                </i>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 mb-md--50">
                            <div class="shop-toolbar mb--50 pd--20  rounded border border-light">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                    <p class="text-right"> 
                                            <h3> <i class="fa fa-map-marker"></i> <small><label style="top:0px;position:relative;padding-left:5px;font-weight:bold;color:#1a41d8;"><?php echo $search['search_string'];?></label> </small><h3>                                            
                                    </p>
                                    <p > 
                                    <ul class="widget-list price-list" style="border-top:solid 1px #efefef;padding-top:10px;">
                                        <li>
                                            <a>
                                                <span>Autos escuelas Encontradas</span>
                                                 <span class="badge badge-primary" style="padding-top:5px">{{ count($search['markers'])}}</span>
                                            </a>
                                        </li>
                                    </ul>
                                    </p>
                                        <p > 
                                            <h5 style="color:#666666;border-top:solid 1px #efefef;padding-top:10px;"> Buscar
                                                <small class="pull-right">
                                                    <!--<label style="top:0px;position:relative;padding-left:5px;font-weight:bold;color:#1a41d8;font-size:12px;">Zip Code</label> 
                                                    <input type="radio" id="zip_code" name="zip_code" onchange="capturarCiudad2()" >-->
                                                    <input type="hidden" name="latitud_search" id="latitud_search" value="<?php echo $search['latitud_search']?>" />
                                                    <input type="hidden" name="longitud_search" id="longitud_search" value="<?php echo $search['longitud_search']?>" />                                                    
                                                    <input type="hidden" name="sas" id="sas"  value="<?php echo $search['search_string']?>" />
                                                </small>
                                            </h5>
                                        </p>
                                        <p>
                                            <div class="input-group">
                                                <span class="icon-inside">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </span>
                                                <input type="text" class="form-control input_search"type="text" name="search_string" id="search_string" placeholder="Ciudad o Cod. Zip" />
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="listAE()">IR</button>
                                                </div>
                                                
                                            </div>
                                        </p>
                                    </div>
                                <div class="col-12"  style="border-top:solid 1px #efefef;padding-top:20px;margin-top:20px;margin-bottom:20px;">
                                <h4> Filtros <i class="fa fa-filter pull -right"></i></h4>
                                </div>
                                <div class="col-12">
                                        <!-- Single Item of popular category starts -->
                                        <div class="col-12 "><h6 style="color:#373737"><i class="fa fa-angle-right" aria-hidden="true"></i> Licencias  <i class="pull-right fa fa-angle-double-up  btn-licencias" onclick="ocultarMostrar('licencias')"></i></h6></div>
                                        <div class="row ">
                                            
                                            <div class="col-2 offset-1 filter-licencias ">
                                                <a  class="grid_item  <?php if($search['licencia']) if($search['licencia']=='A') echo "active_licence"?>" id="A" >
                                                    <figure>
                                                        <img src="<?php echo env('APP_URL') ?>/images/a624aaa2450dec05753798003536fcf7.jpg" alt="" style="height:40px;width:40px;">
                                                        <div class="info text-center">
                                                            <h3>A</h3>
                                                        </div>
                                                    </figure>
                                                </a>
                                            </div>
                                            <div class="col-2 filter-licencias">
                                                <a  class="grid_item  <?php if($search['licencia']) if($search['licencia']=='B') echo "active_licence"?>"  id="B">
                                                    <figure>
                                                        <img src="<?php echo env('APP_URL') ?>/images/auto.jpg" alt="" style="height:40px;width:40px;">
                                                        <div class="info text-center">
                                                            <h3>B</h3>
                                                        </div>
                                                    </figure>
                                                </a>
                                            </div>
                                            <div class="col-2  filter-licencias" >
                                                <a  class="grid_item  <?php if($search['licencia']) if($search['licencia']=='C') echo "active_licence"?>" id="C">
                                                    <figure>
                                                        <img src="<?php echo env('APP_URL') ?>/images/camion.jpg" alt="" style="height:40px;width:40px;">
                                                        <div class="info text-center">
                                                            <h3>C</h3>
                                                        </div>
                                                    </figure>
                                                </a>
                                            </div>
                                            <div class="col-2 filter-licencias" >
                                                <a  class="grid_item  <?php if($search['licencia']) if($search['licencia']=='D') echo "active_licence"?>" id="D">
                                                    <figure>
                                                        <img src="<?php echo env('APP_URL') ?>/images/bus.jpg" alt="" style="height:40px;width:40px;">
                                                        <div class="info text-center">
                                                            <h3>D</h3>
                                                        </div>
                                                    </figure>
                                                </a>
                                            </div>
                                            <div class="col-2 filter-licencias" >
                                                <a class="grid_item  <?php if($search['licencia']) if($search['licencia']=='E') echo "active_licence"?>" id="E">
                                                    <figure>
                                                        <img src="<?php echo env('APP_URL') ?>/images/vans.jpg" alt="" style="height:40px;width:40px;">
                                                        <div class="info text-center">
                                                            <h3>E</h3>
                                                        </div>
                                                    </figure>
                                                </a>
                                            </div>
                                        </div>
                                                <input type="hidden" name="licencia" id="licencia" />
                                </div>
                                <div class="col-12 filter-distancia" >
                                        <div class="shop-toolbar__left text-center">                                           
                                            <div class="product-view-mode">
                                                <div class="distance"> <h6 style="color:#373737"><i class="fa fa-angle-right" aria-hidden="true"></i> Distancia dentro <span id="distance_span"><?php if($search['distancia']){if((int)$search['distancia']>0 AND (int)$search['distancia']<21) echo $search['distancia'] ;else echo 10; }else echo 10;?></span> KM</h6></div>
                                                <input type="range" class="custom-range" min="1" max="20" step="1" id="distancia" value="<?php if($search['distancia'])if((int)$search['distancia']>0 AND (int)$search['distancia']<21) echo $search['distancia'] ;?>" name="distancia" >
                                            </div>
                                        </div>
                                </div>
                                <div class="col-12" >
                                        <div class="shop-toolbar__left text-center" style="padding-top:20px">
                                           
                                            <div class="product-view-mode">
                                                <div class="distance"> <h6 style="color:#373737"><i class="fa fa-angle-right" aria-hidden="true"></i> Horarios de atención  <i class="pull-right fa fa-angle-double-up  btn-horarios" onclick="ocultarMostrar('horarios')"></i></h6></div>
                                                    <div class="row">
                                                        <div  class="col-6"  style="padding-top:20px;">
                                                            <label class="checkbox-inline filter-horarios"><input type="checkbox" name="horarios[]" value="1" <?php if(in_array(1, $search['horarios_checked'])) echo 'checked';?>> &nbsp; Lunes</label>
                                                            <label class="checkbox-inline filter-horarios"><input type="checkbox" name="horarios[]" value="2" <?php if(in_array(2, $search['horarios_checked'])) echo 'checked';?>> &nbsp; Martes</label>
                                                            <label class="checkbox-inline filter-horarios"><input type="checkbox" name="horarios[]" value="3" <?php if(in_array(3, $search['horarios_checked'])) echo 'checked';?>> &nbsp; Miercoles</label>
                                                            <label class="checkbox-inline filter-horarios"><input type="checkbox" name="horarios[]" value="4" <?php if(in_array(4, $search['horarios_checked'])) echo 'checked';?>> &nbsp; Jueves</label>
                                                             </div>
                                                        <div  class="col-6"  style="padding-top:20px;">
                                                             <label class="checkbox-inline filter-horarios"><input type="checkbox" name="horarios[]" value="5" <?php if(in_array(5, $search['horarios_checked'])) echo 'checked';?>> &nbsp; Viernes</label>
                                                            <label class="checkbox-inline filter-horarios"><input type="checkbox" name="horarios[]" value="6" <?php if(in_array(6, $search['horarios_checked'])) echo 'checked';?>> &nbsp; Sábado</label>
                                                            <label class="checkbox-inline filter-horarios"><input type="checkbox" name="horarios[]" value="7" <?php if(in_array(7, $search['horarios_checked'])) echo 'checked';?>> &nbsp; Domingo</label>
                                                        </div>
                                                    </div>
                                                
                                            </div>
                                        </div>
                                </div>
                                <div class="col-6">
                                        <div class="shop-toolbar__left text-center" style="padding-top:20px">
                                           
                                            <div class="product-view-mode">
                                                <div class="distance"> <h6 style="color:#373737"><i class="fa fa-angle-right" aria-hidden="true"></i> Idiomas <i class="pull-right fa fa-angle-double-down btn-idiomas" onclick="ocultarMostrar('idiomas')"></i><h6></div>
                                                <p class="col-12 "  style="display:none" >
                                                    <?php 
                                                        foreach(json_decode($search['idiomas'], true) as $item){
                                                            $checked="";
                                                            if(in_array($item['id'], $search['idiomas_checked'])) $checked= 'checked="checked"';
                                                            echo '<div class="checkbox filter-idiomas" style="display:none"><label><input type="checkbox" name="idiomas[]" value="'.$item['id'].'" '.$checked.'> &nbsp; '.$item['nombre'].'</label></div>';
                                                        }  
                                                    ?>
                                                </p>
                                                
                                            </div>
                                        </div>
                                </div>
                                <div class="col-6">
                                        <div class="shop-toolbar__left text-center" style="padding-top:20px">
                                           
                                            <div class="product-view-mode">
                                                <div class="distance"> <h6 style="color:#373737"><i class="fa fa-angle-right" aria-hidden="true"></i> Otros <i class="pull-right fa fa-angle-double-down  btn-otros" onclick="ocultarMostrar('otros')"></i><h6></div>
                                                <p class="col-12 "  >
                                                    <?php 
                                                        foreach(json_decode($search['otros'], true) as $item){
                                                            $checked="";
                                                           if(in_array($item['id'], $search['otros_checked'])) $checked= 'checked';
                                                            echo '<div class="checkbox filter-otros" style="display:none"><label><input type="checkbox" name="otros[]" value="'.$item['id'].'" '.$checked.' > &nbsp; '.$item['nombre'].'</label></div>';
                                                        }  
                                                    ?>
                                                </p>
                                                
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                            
                        </div>
                        <div class="col-12 col-md-9 mb-md--50" style='padding:0px;'>
                            <div id="map">Cargando autoescuelas ...</div>
                            <div class="container list" style="margin-top:30px;">
                                <h2>{{ count($search['markers'])}} <small>Autos escuelas Encontradas en </small>  <small style="color:#333;font-weight:600;"><?php echo $search['search_string'];?></small></h2>
                                <div class="row list-schools" style="padding:5px;margin-top:30px;">                                 

                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-10">
                                        <nav class="pagination-wrap" style="padding-top:20px;" >
                                            <ul class="pagination">
                                                
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <script>
    
    /********* MAPA ***********/
    // Initialize and add the map
    var map;
    var markers = <?php echo json_encode($search['markers'])?>;
    var infowindows = <?php echo json_encode($search['infowindows'])?>;
    var infoWindowContent = infowindows;
    
   //console.log(markers);
    function initMap() {
        var map;
        var bounds = new google.maps.LatLngBounds();
        var mapOptions = {
            mapTypeId: 'roadmap',
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
        map = new google.maps.Map(document.getElementById("map"), mapOptions);
        map.setTilt(50);
            
    

        // Add multiple markers to map
        var infoWindow = new google.maps.InfoWindow({ maxHeight: 320 }), marker, i;
        
        // Place each marker on the map  
        var marcadores = [];
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
            var marcador = new google.maps.Marker({
          		position: position,
          		map: map,
          	});
            marcadores.push(marcador);//usado para el clusters

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

    @section('scripts')
    
  
    <script>
        $(document).ready(function() {
            var $html = $('html'),
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
		$fixedHeaderHeight = ( $fixedHeader.elExists() ) ? $fixedHeader[0].getBoundingClientRect().height : 0,
		$dom = $('.wrapper').children(),
		$elementCarousel = $('.element-carousel'),
        $footer = $('.footer');
        var width_=0;

            window.state = state;
            $(window).on('load', function(){
            state.window_width = $(window).width();
            width_=state.window_width ;
                if(state.window_width  > 991){ 
                    state.stickyHeader();
                }
                $('.ft-preloader').removeClass("active");
               
            });

            $(window).on('resize', function(){
                state.window_width = $(window).width();
                if(state.window_width  > 991){ 
                    state.stickyHeader();
                }
            });
         
                $("#customRange").change(function(){console.log($(this).val())})

            /* --------------------construye el listado --------------------------- */
            <?php
                echo 'var pagina=1;';
                if(isset($pagina)){
                    if(is_numeric($pagina)){
                        echo 'pagina='.$pagina.';';
                    }
                }
            ?>
           var paginas = <?php echo $paginas?>;
           <?php if($paginas>0) {?>
            construirListado(<?php print_r($search['datos'])?>,pagina,paginas);
           <?php }
               ?>
            /********************************************* */
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
        $("#distancia").change(function(){
          $("#distance_span").html($(this).val())
          listAE();
        })
        $("#licencias_filter").change(function(){
          listAE();
        })

        $(".grid_item").click(function(){
            $(".grid-item").each(function(){
        	   $(this).removeClass("active_licence");
            });
            if(!$(this).hasClass('active_licence')){
                $(this).addClass("active_licence");
                $("#licencia").val( $(this).attr("id"))
            }else{
                $(this).removeClass("active_licence");
                $("#licencia").val("")
            }
            listAE();
        })
        $(".todos-item").click(function(){
            $(".grid-item").each(function(){
        	   $(this).removeClass("active_licence");
            });
        })

        //busca cuando pulse un checkbox
        $('input[type=checkbox]').click(function (e) {listAE();});
        capturarCiudad();
      });
        function construirListado(datos,pagina,paginas){
            var lat=0;
            var lng=0;
           <?php
           if(isset($search['latitud_search']) and isset($search['longitud_search'])){
                if($search['latitud_search']!="") echo 'lat='.$search['latitud_search'].';';
                if($search['longitud_search']!="") echo 'lng='.$search['longitud_search'].';';
            }
           ?>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
             });
          //console.log(datos)
         
          var  limite=10;
          //$(".list-schools").html("");
          var entra = true;
          var inicio = 0;
          var fin = 0;
          //mostramos las paginas
          var vuelta =0;
            if(paginas>0){
               /* $('.pagination').html('<li><span class="page-number current" onclick="paginar(1,'+paginas+')">1</span></li>');
                if(pagina>1){
                    var anterior = pagina-1;
                    $('.pagination').append('<li><span class="page-number"><a href="#" onclick="paginar('+anterior+','+paginas+')">'+anterior+'</a></span></li>');
                }
               
                var maximo=pagina+5;
                if((paginas-pagina) < 5) maximo = paginas-pagina;
                for(var j=pagina;j <= maximo ;j++){
                    if(j!=1){
                        if(j==pagina)  current ='current';
                        else    current ='';
                        $('.pagination').append('<li><span class="page-number '+current+'"><a href="#" onclick="paginar('+j+','+paginas+')">'+j+'</a></span></li>');
                    }
                }
                if(pagina<paginas) $('.pagination').append('<li><span class="page-number current" onclick="paginar('+paginas+','+paginas+')">'+paginas+'</span></li>')
                if(pagina == paginas){
                    for(var j=pagina-8;j < pagina ;j++){
                        $('.pagination').append('<li><span class="page-number '+current+'"><a href="#" onclick="paginar('+j+','+paginas+')">'+j+'</a></span></li>');
                    }
                    $('.pagination').append('<li><span class="page-number current" onclick="paginar('+paginas+','+paginas+')">'+paginas+'</span></li>')
                } */
                if(pagina<paginas)
                $('.pagination').html('<li><span style="padding-left:10px;padding-right:10px;width:auto;font-weight:bold;cursor:pointer;" class="page-number " onclick="paginar('+(pagina+1)+','+paginas+')">VER MAS</span></li>')

                jQuery.each(datos, function(i, val) {
                
                    fin = pagina*limite;
                    inicio = fin-limite;
                    if(i >= inicio && i < fin){
                        entra = true;
                    }else{
                        entra = false;
                    }
                    if( i >= fin ){
                        return false;
                    }
                    if(entra == true){
                        var img="";
                        //$.get( URL_BASE+"/api/comprobar_imagen", { "request":"comprobar_imagen","logo" : val.logo } )
                        $.ajax({
                            method: "post",
                            url:"{{ route('comprobar_imagen.post') }}",                
                            data: { "request":"comprobar_imagen","logo" : val.logo ,'_token':  $('meta[name="csrf-token"]').attr('content') },
                            success:function( data ) {
                            $('#img-school'+val.id).attr("src",data.logo);     
                            }           
                        });
                        //$.get( URL_BASE+"/api/abierto_hoy", { "request":"abierto_hoy","horarios" : val.horarios } )
                        $.ajax({
                            method: "post",
                            url:"{{ route('abierto_hoy.post') }}",                
                            data: { "horarios" : val.horarios,'_token':  $('meta[name="csrf-token"]').attr('content') },
                            success:function( data ) {
                                if(data.flag!="") $("#abierto"+val.id).html('<div class="widget-color" style="color:#1a41d8"><i class="fa fa-clock-o"></i> Abierto</div>');                
                                else $("#abierto"+val.id).html('<div class="widget-color gray"><i class="fa fa-clock-o"></i> Cerrado</div>');   

                                }
                        });
                        $.ajax({
                            method: "post",
                            url:"{{ route('lang_otros.post') }}",                
                            data: { "request":"lang_otros","auto_school_id" : val.id ,"latitud_search" : lat ,"longitud_search" : lng ,'_token':  $('meta[name="csrf-token"]').attr('content') },
                            success:function( data ) {
                                if(data.otros!=""){
                                    $(".info-escuela"+val.id).append('<li class="list_root" id="f_0"><i class="fa fa-check" style="color:#44f1bf"></i> <b>Otros servicios</b> '+data.otros+'</li> ');
                                }
                                if(data.lenguajes!=""){
                                    $(".info-escuela"+val.id).append('<li class="list_root" id="f_0"><i class="fa fa-check" style="color:#44f1bf"></i> <b>Atendemos en</b> '+data.lenguajes+'</li> ');
                                }
                            //$('#img-school'+val.id).attr("src",data.logo);     
                            }           
                        });  
                    if(!val.direccion)  val.direccion=""; else val.direccion='<i class="fa fa-map-marker" aria-hidden="true"></i>'+val.direccion;
                  
                                        $(".list-schools").append('<div class="col-12 col-md-10 schools-grid ae-grid">'+
                                            '<div class="ft-product-list">'+
                                                '<div class="product-inner">'+
                                                    '<div class="product-image">'+
                                                        '<figure class="product-image" style="background-color:#ecf7fb;border: solid 1px #e2e2e2;text-align:center;">'+
                                                            '<a href="detail?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'"><img id="img-school'+val.id+'" src="'+img+'" alt="Autoescuela"></a>'+
                                                        '</figure>'+
                                                        '<a href="detail?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'" class="product-overlay"></a>'+
                                                       
                                                    '</div>'+
                                                    '<div class="product-info">'+
                                                        '<div class="product-title mb--0">'+
                                                            '<a style="font-size: 16px;" href="detail?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'">'+val.nombre+'</a>'+
                                                            '<a style="cursor:pointer" id="favorito-'+val.id+'" onclick="addFavoritos('+val.id+')" class="action-btn pull-right">'+
                                                                '<i class="fa fa-heart-o"></i>'+
                                                            '</a>'+
                                                        '</div>'+
                                                        '<h3 class="product-short-description mb--0" style="color:#ccc;">'+
                                                            '<a href="detail?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'"> '+val.direccion+'</a><br>'+
                                                            '<span class="small" id="abierto'+val.id+'"></small>'+
                                                        '</h3>'+
                                                        '<div class="row" style="padding-top:0px;padding-bottom:0px;">'+
                                                            
                                                            '<div class="col-md-10"><ul class=" info-escuela'+val.id+'" id="rtl_func"><li class="list_root" id="f_0"><i class="fa fa-check" style="color:#44f1bf"></i> A '+val.distancia+'Km. de ti </li></ul></div>'+
                                                            '<div class="col-md-2 text-center" style="text-align:center">'+
                                                                '<a href="detail?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'" class="btn btn-primary_" style=" border-radius:25px;width: 97px;margin-bottom:15px">M&aacute;s</a>'+
                                                                '<a class="btn btn-primary__" style=" border-radius:25px;">Preguntar</a>'+
                                                            '</div>'+
                                                            
                                                        '</div>'+
                                                        
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+                                       
                                        '</div>');  
                                        if(vuelta==0){
                                            $(".list-schools").append('<div class="col-0 col-md-2">'+
                                                                    '<p class=" schools-grid ae-grid" style="font-size:12px"><b>Recuerda</b><br> Te gustaría incluir esta escuela de manejo en tu lista de deseos personal? Para que no pierdas de vista a tus favoritos mientras buscas la escuela de manejo adecuada.</p>'+
                                                                    '</div>');
                                        }
                                        //console.log(val.horarios);
                                        diasAtencion(val.horarios,val.id);
                                        //verifica los favoritos
                                        findFavoritos(val.id)
                    }
                    vuelta++;
                });
            
            }
        }

      /************
      * 
      * 
      ***********/
      function listAE(page){       
        var licencia="";var lugar="";
        if($("#licencia").val()!="") licencia=$("#licencia").val();
        if( licencia  == "")  licencia = <?php if($search['licencia']){if($search['licencia']!="") echo $search['licencia'];else echo "''";}else echo"''";?>; 
        if($("#search_string").val()!=""){
            lugar=$("#search_string").val();
        }else{
            lugar=$("#sas").val();
        }
        var idiomas=[];
        var horarios=[];
        var otros=[];
        $('input[name="idiomas[]"]:checked').each(function(){
            idiomas.push($(this).val());
        })
         
        $('input[name="horarios[]"]:checked').each(function(){
            horarios.push($(this).val());
        })
        $('input[name="otros[]"]:checked').each(function(){
            otros.push($(this).val());
        })    
        <?php
            echo 'var pagina=1;';
            if(isset($pagina)){
                if(is_numeric($pagina)){
                    echo 'pagina='.$pagina.';';
                }
            }
        ?>
       
        location.href=URL_BASE+'/list?search_string='+lugar+'&latitud_search='+$("#latitud_search").val()+'&longitud_search='+$("#longitud_search").val()+'&licencia='+licencia+'&distancia='+$("#distancia").val()+'&horarios='+horarios+'&idiomas='+idiomas+'&otros='+otros,'&pagina='+pagina;
      }
      function paginar(pagina,paginas){
        construirListado(<?php print_r($search['datos'])?>,pagina,paginas);
      }
      function ocultarMostrar(capa){
        if($(".filter-"+capa).is(":visible")){
            $(".btn-"+capa).removeClass("fa-angle-double-up");
            $(".btn-"+capa).addClass("fa-angle-double-down");
            $(".filter-"+capa).hide();
        }else{
            $(".btn-"+capa).removeClass("fa-angle-double-down");
            $(".btn-"+capa).addClass("fa-angle-double-up");
            $(".filter-"+capa).show();
        }
      }
      function diasAtencion(horarios,id){
        var dias=['','Lunes','Martes','Miercoles','Jueves','Viernes','Sábado','Domingo'];
        var atencion=[];
        var string_="";
        var j=0;      
        //console.log(horarios)
        ///console.log("Entra -----------------------------------------------------")  
        for(var i=0;i<horarios.length;i++){
          
            if(horarios[i].desde.search(":") != -1){
                if(atencion.indexOf(dias[horarios[i].dia]) == -1){    
                    atencion.push(dias[horarios[i].dia])
                    if(j==0){
                        coma="";
                    } 
                    else coma =",";
                    string_ += coma+" "+dias[horarios[i].dia];  
                    j++;       
                }
            }
        }    
            ;
        if(j>0)
        $(".info-escuela"+id).append('<li class="list_root" id="f_0"><i class="fa fa-check" style="color:#44f1bf"></i> <b>Atendemos los</b> '+string_+'</li> ');
       
      }
    </script>
    @endsection
    @section('styles')
    <style>
        .fa-angle-right{
            color:#4bdbdd;
        }
        .ft-product-list .product-thumbnail-action{
            height:30px !important;
        }
        .action-btn :focus{
            color : #1a41d8  !important;
            -webkit-transition: all 250ms ease-out  !important;
            -moz-transition: all 250ms ease-out  !important;
            -ms-transition: all 250ms ease-out  !important;
            -o-transition: all 250ms ease-out  !important;
            transition: all 250ms ease-out  !important;
            outline: none;
            background-color : #ccc  !important;
        }
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
        .search-bar h4{padding-top:15px;font-weight:200;font-size:19px;color:#ffffff;}
        .main-content-wrapper{
            margin-top:30px;background-color:#f3f3f3;
        }
        .side-bar{background-color:#ffffff;}
        .shop-toolbar, .schools-grid{
            padding:20px;
        }

        a.grid_item{
            height:50px;
        }
        a.grid_item .info{
            top:5px;
        }
        a.grid_item .info h3 {
            color: #001d31;
            margin-left: 10px;
            margin-top:10px;
        }
        .active_licence{
            border:solid 1px #001d31 !important;
        }
        @media (max-width: 768px){
        
            a.grid_item figure img {top: 15px !important;}
            .col-2{
                padding-left:5px !important;
                padding-right:5px !important;
            }
            a.grid_item .info {
                padding: 15px 0px;
            }
            .ft-product-list .product-image {
                flex-basis: 100% !important;
                max-width: 100% !important;
                margin-bottom:0px;
                padding-bottom:0px;                
                background-color: #ffffff !important;
                border: none !important;
            }

        }
        @media (max-width: 1024px){
            
        .btn {
            padding:5px;
        }
        }
        @media (max-width: 74.9375em){
            .btn {
                padding: 5px 5px;
            }
        }
    </style>
    
    <style>
    .header__inner{
        background-color:#001d31;
    }
    .icon-inside{
        position: absolute;
        margin-left: 5px;
        height: 30px;
        display: flex;
        align-items: center;
        z-index:10;
        font-weight:bold;
        color:#cccccc;padding-top:10px;
    }
    .icon-inside i{font-size:20px;font-weight:bold;}
    .input_search{
        padding-left: 35px;
        height: 40px;
        font-size: 20px;
    }
      ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
        padding-top:5px;
        font-size:17px;
    }
      ::-moz-placeholder { /* Firefox 19+ */
        padding-top:5px;
        font-size:17px;
    }
     ::-ms-input-placeholder { /* IE 10+ */
        padding-top:5px;
        font-size:17px;
    }
    ::-moz-placeholder { /* Firefox 18- */
        color: #fff;
        background-color: #1a41d8;
        font-size:14px;
        font-weight:bold;
    }
    .btn-outline-secondary{
        color:#fff;
        background-color:#1a41d8;
    }
    .btn:hover {
        color: #1a41d8;
        background-color: #fff;
        font-size:14px;
        font-weight:bold;
        border:none;;
    }
    .checkbox label{
        color:#001d31;
        font-size:11px;
        font-weight:bold;
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
    }
    .btn-primary__{
        margin-left:5px;
        color: #fff !important;
        transition: all .4s ease-in-out;
        background: linear-gradient(130deg,#22daf6,#01a5e1,#2957cb,#8400f2 90%,#c500b1) !important;
        border-color: transparent !important;
        font-size:14px;
        font-weight:bold;
    }

    ul#rtl_func {
  display: flex;
  flex-flow: row wrap;
  justify-content: space-between;
  overflow: auto;
  padding: 0 3px;
  list-style: none;
}
ul#rtl_func > li {
  min-width: 45%;
  padding: 3px 5px;
  transition: box-shadow .25s ease;
}
ul#rtl_func > li:hover {
   border-radius: 4px;
  border: 1px solid #dedede;
  box-shadow: 0px 0px 15px 5px rgba(0,0,0,.15);
  cursor: default;
}
ul#rtl_func > li > ul > li {
  list-style: none;
  font-weight: normal;
}
ul#rtl_func > li > ul > li:first-of-type {
  border-top: 1px solid #eee;
  margin-top: 4px;
}
.filter-licencias {
    padding:2px;
}
.ae-grid{
    margin-top:20px;
    padding: 10px;
    border-radius: 4px;
  border: 1px solid #dedede;
  box-shadow: 0px 0px 7px 2px rgba(0,0,0,.15);
}
.ft-product-list .product-image {
    flex-basis: 150px;
    max-width: 150px;
    margin-bottom:0px;
    padding-bottom:0px;
}
.page-number {
    color: #fff  !important;
    background-color: #1a41d8 !important;
}
.current {
    background-color: #fff  !important;
    color: #1a41d8 !important;
}
.alert-warning {
    color: #856404 !important;
    background-color: #fff3cd !important;
    border-color: #ffeeba !important;
}
.alert-dismissible {
    padding-right: 4rem !important;
}
.alert {
    position: relative !important;
    padding: .75rem 1.25rem !important;
    margin-bottom: 1rem !important;
    border: 1px solid transparent !important;
    border-radius: .25rem !important;
}
.fade {
    opacity: 10 !important;
    transition: opacity .15s linear !important;
}
.hide{
    display:none;
}
    </style>
    @endsection