<!doctype html>
<html class="no-js" lang="ES">
 
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>TuPermiso listado de autoescuelas de manejo.</title>
    <meta name="description" content="Tupermiso directorio de autoescuelas">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- ************************* CSS Files ************************* -->

    <!-- Vendor CSS -->
    <meta name="description" content="Tupermiso directorio de autoescuelas">
    <link rel="shortcut icon" href="{{ URL::asset('assets/img/favicon.png')}}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicons -->
    <link rel="apple-touch-icon" href="{{ URL::asset('assets/img/favicon.png')}}">

    <!-- ************************* CSS Files ************************* -->

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/vendor.css?v=00011')}}">

    <!-- style css -->
    <link rel="stylesheet" href="{{ URL::asset('assets/css/main.css?v=00011')}}">
    <link rel="stylesheet" href="{{ URL::asset('fonts/font-awesome.css')}}">
    <script> var URL_BASE='<?php echo env('APP_URL'); ?>';</script>
</head>

<body>

    <!-- Preloader Start -->
    <div class="ft-preloader active">
        <div class="ft-preloader-inner h-100 d-flex align-items-center justify-content-center">
            <div class="ft-child ft-bounce1"></div>
            <div class="ft-child ft-bounce2"></div>
            <div class="ft-child ft-bounce3"></div>
        </div>
    </div>
    <!-- Preloader End -->
    <div class="wrapper">
        <!-- Header Start -->
        <header class="header">
            <div class="header__inner fixed-header">
                <div class="header__main">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="header__main-inner">
                                    <div class="header__main-left">
                                        <div class="logo">
                                            <a href="{{ URL::asset('/')}}" class="logo--normal">
                                                <img src="{{ URL::asset('/assets/img/logo/logo_b.png')}}" alt="Logo">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="header__main-center">                                        
                                        <nav class="navbar pull-right navbar-expand-lg navbar-light" style="float:right">
                                        <button class="navbar-toggler pull-right" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                                            <span class="navbar-toggler-icon"></span>
                                        </button>
                                        <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                                            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                                            <li class="nav-item active">
                                                <a href="{{ URL::asset('/') }}" class="nav-link mainmenu__link">
                                                        <span class="mm-text">Inicio</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="{{ URL::asset('list/')}}" class="nav-link mainmenu__link">
                                                    <span class="mm-text">Listar</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link mainmenu__link">
                                                    <span class="mm-text">Licencias</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link mainmenu__link">
                                                    <span class="mm-text">Quieres publicar tu agencia?</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="#" class="nav-link mainmenu__link">
                                                    <span class="mm-text"><i class="fa fa-sign-out"></i></span>
                                                </a>
                                            </li>
                                            </ul>
                                        </div>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <style>
        .mm-text{
            padding-right:10px;
        }
        </style>
        <!-- Header End -->
        @yield('content')
     <!-- Main Content Wrapper End -->

        <!-- Footer Start-->
        <footer class="footer bg-color secciones" data-bg-color="#ffffff">
            <div class="footer-top">
                <div class="container-fluid">
                    <div class="row border-bottom pt--70 pb--70">
                        <div class="col-lg-3 col-sm-6 offset-md-1 offset-lg-0 mb-md--45">
                            <div class="footer-widget">
                                <div class="textwidget">
                                    <figure class="footer-logo mb--30">
                                        <img src="{{ URL::asset('/assets/img/logo/logo.png')}}" alt="Logo">
                                    </figure>
                                    <p>On the other hand, we denounce with righteous indignation and dislike men who are so beguiled and demoralized by the charms. </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-3 offset-lg-1 offset-sm-2 mb-md--45">
                            <div class="footer-widget">
                                <h3 class="widget-title mb--35 mb-sm--20">Company</h3>
                                <div class="footer-widget">
                                    <ul class="footer-menu">
                                        <li><a href="index.html">About Us</a></li>
                                        <li><a href="blog.html">Terms and conditions</a></li>
                                        <li><a href="#">Privacy Policy</a></li>
                                        <li><a href="contact-us.html">Faq</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4 offset-md-1 offset-lg-0 mb-xs--45">
                            <div class="footer-widget">
                                <h3 class="widget-title mb--35 mb-sm--20">Licencias</h3>
                                <div class="footer-widget">
                                    <ul class="footer-menu">
                                        <li><a href="#">A - Motos</a></li>
                                        <li><a href="#">AM - Motos</a></li>
                                        <li><a href="#">B - Coches</a></li>
                                        <li><a href="#">C - Cami&oacute;n</a></li>
                                        <li><a href="#">D - Bus</a></li>
                                        <li><a href="#">E - Vans</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-4 mb-xs--45">
                            <div class="footer-widget">
                                <h3 class="widget-title mb--35 mb-sm--20">Contacto</h3>
                                <div class="footer-widget">
                                    <ul class="footer-menu">
					<li><i class="fa fa-home"></i>Madrid</li>
					<li><i class="fa fa-headphones"></i>1234567890</li>
					<li><i class="fa fa-envelope"></i><a href="#0">info@TuPermiso.esy.es</a></li>
				
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-3 col-sm-4">
                            <div class="footer-widget">
                                <h3 class="widget-title mb--35 mb-sm--20">Social Network</h3>
                                <div class="footer-widget">
                                    <ul class="footer-menu footer-social" style="display:inline">
                                        <li style="display:inline"><a href="#"><i class="fa fa-facebook"></i></a></li>
                                        <li style="display:inline"><a href="#"><i class="fa fa-twitter"></i></a></li>
                                        <li style="display:inline"><a href="#"><i class="fa fa-instagram"></i></a></li>
                                        <li style="display:inline"><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-middle ptb--40">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-11">
                            <div class="footer-widget">
                                <div class="taglist">
                                    <span>Tags:</span>
                                    <a href="#">Licencias</a>
                                    <a href="#">Permisos</a>
                                    <a href="#">AutoEscuelas</a>
                                    <a href="#">Autos</a>
                                    <a href="#">Motos</a>
                                    <a href="#">Camiones</a>
                                    <a href="#">Bus</a>
                                    <a href="#">Vans</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container-fluid">
                    <div class="row border-top ptb--20">
                        <div class="col-12 text-center">
                            <p class="copyright-text">TuPermiso &copy; 2019 all rights reserved</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Footer End-->

        <!-- Qicuk View Modal End -->

        <!-- Global Overlay Start -->
        <div class="global-overlay"></div>
        <!-- Global Overlay End -->

        <!-- Global Overlay Start -->
        <a class="scroll-to-top" href=""><i class="fa fa-angle-double-up"></i></a>
        <!-- Global Overlay End -->
    </div>
    <!-- Main Wrapper End --></div>
    
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCxSs15KtKzJVGlnOAkCx4chii8egAb-jk&callback=initMap&libraries=places">
    </script>
    <!-- jQuery JS -->
    <script src="{{ URL::asset('assets/js/vendor.js?V=00011')}}"></script>

    <!-- Main JS -->
    <script src="{{ URL::asset('assets/js/main.js?V=00011')}}"></script>

    <script src="{{ URL::asset('assets/js/mapas.js?V=0003')}}"></script>
    <script>
        
        if(!localStorage.getItem("favoritos_autoescuela")){
            var arreglo=[];
            localStorage.setItem("favoritos_autoescuela",JSON.stringify(arreglo));
        }
        function addFavoritos(id){
            var local_array =JSON.parse(localStorage.getItem("favoritos_autoescuela"));
            console.log(local_array)
            var favoritos=[];
            if(Array.isArray(local_array)){
                console.log("es array")
                favoritos = local_array;
            }
            console.log(Array.isArray(favoritos))
            var index =favoritos.indexOf(id);
            if( index > -1){
                console.log("borra",favoritos)
                favoritos.splice(index, 1);
                console.log("borrado",favoritos)
                localStorage.setItem("favoritos_autoescuela",JSON.stringify(favoritos));
            }else{
                favoritos.push(id);
                localStorage.setItem("favoritos_autoescuela",JSON.stringify(favoritos));
            }
            console.log("local",JSON.parse(localStorage.getItem("favoritos_autoescuela")));
            findFavoritos(id);
            
        }
        function findFavoritos(id){
            var local_array =JSON.parse(localStorage.getItem("favoritos_autoescuela"));
            var favoritos=[];
            if(Array.isArray(local_array)){
                favoritos = local_array;
            }
            if(favoritos.indexOf(id) >= 0) $("#favorito-"+id).css("background-color","#d8ff00")
            else $("#favorito-"+id).css("background-color","#ffffff")
        }
    </script>
    <style>
            
    .animation{ 
        width:105%;
        height: 100vh;
        min-height: 500px;
        background-color: #7197ba;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        padding-top:0px;
    }
    .custom-search-input-2 {
        background-color: #fff;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        -ms-border-radius: 5px;
        border-radius: 5px;
        margin-top: 10px;
        -webkit-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.3);
        -moz-box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.3);
        box-shadow: 0px 0px 30px 0px rgba(0, 0, 0, 0.3);
    }
    .form-animation{
        /*top:350px;z-index:100;position:absolute;margin:0 auto;left:15%;*/
        z-index:100;
    }

    .form-animation h1 {
        font-size: 62px;
        color:#ffffff;
        text-shadow: 4px 4px 12px rgba(0, 0, 0, 0.3);
    }
    .form-animation p{
        color:#ffffff;
        text-shadow: 4px 4px 12px rgba(0, 0, 0, 0.3);
    }
    .nice-select {
        -webkit-tap-highlight-color: transparent;
        background-color: #fff;
        border-radius: 3px;
        border: none;
        box-sizing: border-box;
        clear: both;
        cursor: pointer;
        display: block;
        float: left;
        font-family: inherit;
        font-size: 14px;
        font-weight: normal;
        height: 50px;
        line-height: 48px;
        outline: none;
        padding-left: 15px;
        padding-right: 27px;
        position: relative;
        text-align: left !important;
        transition: all 0.2s ease-in-out;
        user-select: none;
        white-space: nowrap;
        width: auto;
        color: #555;
        padding-top: 2px;
    }
    @media (max-width: 767px) {
        .form-animation h1 {
        font-size: 26px;
        
        margin-top: 30px;
    }
    }
    @media (max-width: 991px) {
    .custom-search-input-2 {
        background: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }
    }
    .custom-search-input-2 input {
        border: 0;
        height: 50px;
        padding-left: 15px;
        border-right: 1px solid #d2d8dd;
        font-weight: 500;
        font-size:20px;
    }
    @media (max-width: 991px) {
        .custom-search-input-2 input {
            border: none;
        }
    }
    .custom-search-input-2 input:focus {
        box-shadow: none;
        border-right: 1px solid #d2d8dd;
    }
    @media (max-width: 991px) {
        .custom-search-input-2 input:focus {
            border-right: none;
        }
    }
    .custom-search-input-2 select {
        display: none;
    }
    .custom-search-input-2 .nice-select .current {
        font-weight: 500;
        color: #6f787f;
    }
    .custom-search-input-2 .form-group {
        margin: 0;
    }
    @media (max-width: 991px) {
        .custom-search-input-2 .form-group {
            margin-bottom: 5px;
        }
    }
    .custom-search-input-2 i {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        border-radius: 3px;
        font-size: 18px;
        font-size: 1.125rem;
        position: absolute;
        background-color: #fff;
        line-height: 50px;
        top: 1px;
        right: 1px;
        padding-right: 15px;
        display: block;
        width: 20px;
        box-sizing: content-box;
        height: 48px;
        z-index: 9;
        color: #999;
    }
    @media (max-width: 991px) {
        .custom-search-input-2 i {
            padding-right: 10px;
        }
    }
    .custom-search-input-2 input[type='submit'] {
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        color: #fff;
        font-weight: 600;
        font-size: 14px;
        font-size: 0.875rem;
        border: 0;
        padding: 0 25px;
        height: 50px;
        cursor: pointer;
        outline: none;
        width: 100%;
        -webkit-border-radius: 0 3px 3px 0;
        -moz-border-radius: 0 3px 3px 0;
        -ms-border-radius: 0 3px 3px 0;
        border-radius: 0 3px 3px 0;
        background-color: #045498;
        margin-right: -1px;
    }
    @media (max-width: 991px) {
    .custom-search-input-2 input[type='submit'] {
        margin: 20px 0 0 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        border-radius: 3px;
    }
    }
    .custom-search-input-2 input[type='submit']:hover {
        background-color: #ec8b03;
        color: #222;
    }
    .custom-search-input-2.map_view {
        background-color: transparent;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }
    .custom-search-input-2.map_view input {
     border: none;
    }
    .custom-search-input-2.map_view input[type='submit'] {
        margin: 20px 0 0 0;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        border-radius: 3px;
    }
    .custom-search-input-2.inner {
        margin-top: 0;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }
    @media (max-width: 991px) {
        .custom-search-input-2.inner {
            display: none;
        }
    }
    .custom-search-input-2.inner input {
        border: 0;
        height: 40px;
        padding-left: 15px;
        border-right: 1px solid #d2d8dd;
        font-weight: 500;
    }
    @media (max-width: 991px) {
        .custom-search-input-2.inner input {
            border: none;
        }
    }
    .custom-search-input-2.inner input:focus {
        box-shadow: none;
        border-right: 1px solid #d2d8dd;
    }
    @media (max-width: 991px) {
        .custom-search-input-2.inner input:focus {
            border-right: none;
        }
    }
    .custom-search-input-2.inner .nice-select {
        height: 40px;
        line-height: 38px;
    }
    .custom-search-input-2.inner .nice-select:after {
        right: 15px;
    }
    .custom-search-input-2.inner i {
        height: 38px;
        line-height: 38px;
        padding-right: 10px;
    }
    .custom-search-input-2.inner input[type='submit'] {
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
        background: #ec8b03 url(../images/search.svg) no-repeat center center;
    }
    .custom-search-input-2.inner input[type='submit']:hover {
     background-color: #32a067;
    }
    @media (max-width: 575px) {
        .custom-search-input-2.inner input[type='submit'] {
            text-indent: -999px;
            background: #045498 url(../images/search.svg) no-repeat center center;
        }
    }
    @media (max-width: 991px) {
        .custom-search-input-2.inner {
            margin: 0 0 20px 0;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            box-shadow: none;
        }
    }
    .custom-search-input-2.inner-2 {
        margin: 0 0 20px 0;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        background: none;
    }
    .custom-search-input-2.inner-2 .form-group {
        margin-bottom: 10px;
    }
    .custom-search-input-2.inner-2 input {
    border: 1px solid #ededed;
    }
    .custom-search-input-2.inner-2 input[type='submit'] {
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -ms-border-radius: 3px;
        border-radius: 3px;
        margin-top: 10px;
    }
    .custom-search-input-2.inner-2 i {
        padding-right: 10px;
        line-height: 48px;
        height: 48px;
        top: 1px;
    }
    .custom-search-input-2.inner-2 .nice-select {
        border: 1px solid #ededed;
    }
    .main-navigation .mainmenu__link {
        font-weight:400 !important;
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
        border:solid 1px #ccc;
    }
    @media (max-width: 991px) {
        /*a.grid_item {
            height: 180px;
        }*/
    }
    @media (max-width: 767px) {
       /* a.grid_item {
            height: 150px;
        }*/
    }
    @media (max-width: 575px) {
       /* a.grid_item {
            height: 180px;
        }*/
    }
    a.grid_item .info {
        position: absolute;
        width: 100%;
        z-index: 9;
        display: block;
        padding: 15px 15px 10px 5px;
        color: #001d31;
        left: 0;
        bottom: 0;
        background: transparent;
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
        color: #001d31;
        font-size: 15px;
        text-align:center;
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
       /* a.grid_item figure {
            height: 150px;
        }*/
    }
    @media (max-width: 575px) {
       /* a.grid_item figure {
            height: 180px;
        }*/
    }
    a.grid_item figure img {
        -moz-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        -webkit-transition: all 0.3s ease-in-out;
        -ms-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
        position: absolute;
        left: 50%;
       top: 15px;
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


    /*Carousel home page*/
    #reccomended {
    margin-top: 40px;
    }
    @media (max-width: 767px) {
    #reccomended {
        margin-top: 0;
    }
    }
    .main-navigation .mainmenu__item > a {
        padding-right: 0px;
        padding-left: 20px;
    }
    .main-navigation .mainmenu__link .la{
    font-size:20px;
    }
    .footer-social .la{
    font-size:30px;
    }
    }
    @media (max-width: 1450px) {
    .form-animation{
        /*left:5%;*/
        }
    }
    @media (max-width: 1280px) {
    .form-animation{
        /*left:2%;*/
        }
    }
    .nice-select {
        width: 100%;
    }
    .search-bar{
    margin-top:90px;
        padding: 20px 0;
        color: #fff;
        background: #004dda;
        z-index:1;
    }
    .ft-product{
    background-color:#ffffff;
    padding:20px;
    }
    #map {
            height: 600px;  /* The height is 400 pixels */
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
    font-size:22px;
    font-weight:500;
    color:#001d31;

    }
    .product-title{
    font-size:12px;
    font-weight:500;
    color:#333333;
    }
    .product-image {
        min-height: 200px;
    }
    .product-image figure img {
        max-height: 250px;
    }
    .sticky-header{
    background-color:#001d31 !important;
    }
    .header__inner{
    background-color:#001d31;
    }

    </style>
     @yield('scripts')
     @yield('styles')
</body>
</html>