/*****************
 * 
 * Animacion movimiento del fondo
 */
function repeat(rep){
    $('.ball').removeClass('red-ball');
    setTimeout(function(){  $('.ball').addClass('red-ball');},100)
    if(rep!=1){
      if($(".st0").css("fill")=="rgb(0, 128, 234)"){
         $(".st0").css({'fill':'rgb(0, 0, 51)','transition': 'fill 1000ms linear'});
         if ($(".header__inner").hasClass('sticky-header')){
           $(".header__inner").css({'background-color':'#001d31','transition': 'fill 1000ms linear'});
           $(".mm-text").css({'color':'#ffffff','transition': 'fill 1000ms linear'});
        }else{
            $(".header__inner").css({'background-color':'rgb(0, 0, 51)','transition': 'fill 1000ms linear'});
            $(".mm-text").css({'color':'#ffffff','transition': 'fill 1000ms linear'});
        }
    
      }else{
         $(".st0").css({'fill':'rgb(0, 128, 234)','transition': 'fill 1000ms linear'}); 
         if ($(".sticky-header").hasClass('sticky-header')){
            $(".header__inner").css({'background-color':'#001d31','transition': 'fill 1000ms linear'});
            $(".mm-text").css({'color':'#ffffff','transition': 'fill 1000ms linear'});
         }else{
            $(".header__inner").css({'background-color':'rgb(0, 128, 234)','transition': 'fill 1000ms linear'});
            $(".mm-text").css({'color':'#ffffff','transition': 'fill 1000ms linear'});
         }
     
      }
    }
    if($("body").width()<1380){
    $(".mask").animate({left: 0 +"px"});
     $(".mask").animate({left: "-560px"}, 9000,repeat)
    }else{
        //$(".mask").animate({left:  +"-50px"},17000,repeat)
        $(".mask").animate({left: "-350px"}, 17000).animate({left: 0 +"px"}, 2000,repeat)
    }
    
}
function capturarCiudad(input){
   // if(!$("#zip_code").is(':checked')){
        var input = document.getElementById('search_string');
         var options = {
            types: ['(cities)',],
            componentRestrictions: {country: 'es'}
        };
        autocomplete = new google.maps.places.Autocomplete(input, options);
        autocomplete.addListener('place_changed', onPlaceChanged);
        $("#search_string").keyup(function(){getLocation();});
   /* }else{
        $("#search_string").keyup(function(){getLocation();});
    }*/
       
}
function onPlaceChanged() {
    var place = autocomplete.getPlace();
    if (place.geometry) {
        // map.panTo(place.geometry.location);
        // map.setZoom(15);
        // search();
        $("#latitud_search").val(place.geometry.location.lat());
            $("#longitud_search").val(place.geometry.location.lng());
        } /*else {
        document.getElementById('autocomplete').placeholder = 'Ingrese una ciudad a buscar';
    }*/
}
function getLocation(){
    getAddressInfoByZip(document.getElementById('search_string').value);
}
//trae los datos
function response(obj){
    console.log(obj);
    if(obj.success!=false){
        if(obj.country=='España'){
            $('#search_string').val(obj.city+', '+obj.country);
            var geocoder =  new google.maps.Geocoder();
            geocoder.geocode( { 'address': obj.city+', '+obj.country}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //alert("location : " + results[0].geometry.location.lat() + " " +results[0].geometry.location.lng()); 
                    $("#latitud_search").val(results[0].geometry.location.lat());
                    $("#longitud_search").val(results[0].geometry.location.lng());
                } else {
                    alert("No se encuentra el codigo postal " + status);
                    $('#search_string').val("");
                    $('#search_string').focus();
                }
            });
        }
    }

}
/***********
 * 
 * Busca la ciudad de acuerdo a un código zip
 * 
 **********/
function getAddressInfoByZip(zip){
    if(zip.length >= 5 && typeof google != 'undefined'){
        var addr = {};
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': zip,"country":"ES" }, function(results, status){
        if (status == google.maps.GeocoderStatus.OK){
            if (results.length >= 1) {
        for (var ii = 0; ii < results[0].address_components.length; ii++){
            console.log(results[0]);
            var street_number = route = street = city = state = zipcode = country = formatted_address = '';
            var types = results[0].address_components[ii].types.join(",");
            if (types == "street_number"){
            addr.street_number = results[0].address_components[ii].long_name;
            }
            if (types == "route" || types == "point_of_interest,establishment"){
            addr.route = results[0].address_components[ii].long_name;
            }
            if (types == "sublocality,political" || types == "locality,political" || types == "neighborhood,political" || types == "administrative_area_level_3,political"){
            addr.city = (city == '' || types == "locality,political") ? results[0].address_components[ii].long_name : city;
            }
            if (types == "administrative_area_level_1,political"){
            addr.state = results[0].address_components[ii].short_name;
            }
            if (types == "postal_code" || types == "postal_code_prefix,postal_code"){
            addr.zipcode = results[0].address_components[ii].long_name;
            }
            if (types == "country,political"){
            addr.country = results[0].address_components[ii].long_name;
            }
        }
        addr.success = true;
        for (name in addr){
            console.log('### google maps api ### ' + name + ': ' + addr[name] );
        }
        response(addr);
            } else {
            response({success:false});
            }
        } else {
            response({success:false});
        }
        });
    } else {
        response({success:false});
    }
}
/********
 * 
 * Retorna la cantida de escuelas encontradas en una busqueda, por cada tipo de licencia
 * 
 *******/
function countSchools(latitud,longitud){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
   // $.getJSON( URL_BASE+"/api/auto_escuelas.php", { "request":"count_auto_escuelas","latitud_search" : latitud, "longitud_search" :  longitud } )
   $.ajax({
    method: "post",
    url: URL_BASE+"/api/count_auto_escuelas",                
    data:{ "request":"count_auto_escuelas","latitud_search" : latitud, "longitud_search" :  longitud ,'_token':  $('meta[name="csrf-token"]').attr('content')}
    }) .done(function( data, textStatus, jqXHR ) {
            $(".number_scoolsA").html(data.A);$(".number_scoolsB").html(data.B);
            $(".number_scoolsC").html(data.C);$(".number_scoolsD").html(data.D);$(".number_scoolsE").html(data.E);
        })
        .fail(function( jqXHR, textStatus, errorThrown ) {
            if ( console && console.log ) {
                console.log( "Algo ha fallado: " +  textStatus );
            }
    });  

}
/********
 * 
 * Monta la lista de elementos encontrados en la api
 * 
 *******/
function construirListado(datos){
    //console.log(datos)
    $(".list-schools").html("");
    jQuery.each(datos, function(i, val) {
        if(val.id){
            var img="";
           // $.get( URL_BASE+"/api/auto_escuelas.php", { "request":"comprobar_imagen","logo" : val.logo } )
           $.ajax({
            method: "post",
            url: URL_BASE+"/api/comprobar_imagen",                
            data:{ "request":"comprobar_imagen","logo" : val.logo ,'_token':  $('meta[name="csrf-token"]').attr('content')}
            })
            .done(function( data, textStatus, jqXHR ) {
                $('#img-school'+val.id).attr("src",data);                
            });
           // $.get( URL_BASE+"/api/auto_escuelas.php", { "request":"abierto_hoy","horarios" : val.horarios } )
           $.ajax({
            method: "post",
            url: URL_BASE+"/api/comprobar_imagen",                
            data:{ "request":"comprobar_imagen","horarios" : val.horarios ,'_token':  $('meta[name="csrf-token"]').attr('content')}
            })
            .done(function( data, textStatus, jqXHR ) {
                if(data!="") $('.abierto'+val.id).html( '<div class="widget-color"><a class="gray" style="background-color:#fff;color:green;border:solid 0.5px green">Abierto</a></div>');                
                else $('.abierto'+val.id).html( '<div class="widget-color"><a class="gray">Cerrado</a></div>');   
            });  
            if(!val.direccion)  val.direccion=""; else val.direccion='<i class="la la-map-marker" aria-hidden="true"></i> '+val.direccion;
            $(".list-schools").append('<div class="col-12 col-md-6 col-lg-4  col-xl-4 mb--10 schools-grid">'+
                                  '<div class="ft-product">'+
                                      '<div class="product-inner">'+
                                          '<div class="product-image">'+
                                              '<figure class="product-image--holder">'+
                                                  '<img id="img-school'+val.id+'" src="'+img+'" alt="Product">'+
                                              '</figure>'+
                                              '<a href= "'+URL_BASE+'/autoescuela.php?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'" class="product-overlay"></a>'+
                                              '<div class="product-action">'+
                                                  '<a data-toggle="modal" data-target="#productModal" class="action-btn" href="'+URL_BASE+'/autoescuela.php?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'">'+
                                                      '<i class="la la-eye"></i>'+
                                                  '</a>'+
                                                  '<a href="#" class="action-btn">'+
                                                      '<i class="la la-heart-o"></i>'+
                                                  '</a>'+
                                              '</div>'+
                                          '</div>'+
                                          '<div class="product-info">'+
                                              '<div class="product-category">'+
                                                  '<a href="'+URL_BASE+'/autoescuela.php?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'">'+val.nombre+'</a>'+
                                              '</div>'+
                                              '<h3 class="product-title"><a href="'+URL_BASE+'"/autoescuela.php?auto_school_id='+val.id+'&latitud_search='+val.latitud+'&longitud_search='+val.longitud+'">'+val.direccion+'</a></h3>'+
                                              '<div class="product-info-bottom" style="padding-top:10px;padding-bottom:10px;padding-left:0px;padding-right:0pc;border-top:solid #ccc 0.5px">'+
                                                  '<div class="product-price-wrapper abierto'+val.id+'">'+
                                                     
                                                  '</div>'+
                                                 '<a  class=" pr--15" style="dispay:block">'+
                                                      '<i class="gray la la-comments"></i> 0 '+
                                                      '<span style="padding-left:5px;"><b>Opiniones/<b></span>'+
                                                  '</a>'+
                                             '</div>'+
                                          '</div>'+
                                      '</div>'+
                                  '</div>'+                                       
                              '</div>');
                }       
            });        
  }