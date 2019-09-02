<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" lang="es-CL">
    <head>
        <title>ArteMedici</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel='shortcut icon' type='image/x-icon' href='/images/favicon.ico' />
        <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <!--<script src="https://code.highcharts.com/highcharts.js"></script>-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
        <script type="text/javascript" src="./js/base_1.js"></script>
        <script type="text/javascript" src="./js/form_1.js"></script>
        <script type="text/javascript" src="./js/maps.js"></script>
        
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.css" type="text/css" media="all">
        <link rel="stylesheet" href="./css/reset.css" type="text/css" media="all">
        <link rel="stylesheet" href="./css/layout.css" type="text/css" media="all">
        <!--<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">-->
    </head>
    <body>
        <div class="contenedor relative">
            <div class="modals">
                <div class="relative">
                    <div class="modal_perfil cont_modal vhalign">
                        <div class="cont_relative">
                            <div class="close" onclick="closes(this)"></div>
                            <div class="mo_content">
                                <div class="mo_cont">
                                    <ul class="clearfix">
                                        <li class="foto"><img src="images/no-user.png" alt="" /></li>
                                        <li class="info">
                                            <div class="cont_info">
                                                <h2><?php echo $_SESSION['user']['info']['nombre']; ?></h2>
                                                <h3>Administrador</h3>
                                                <a href="?accion=logout">Salir</a>
                                            </div>
                                        </li>
                                    </ul> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal_error cont_modal vhalign">
                        <div class="relative">
                            <div class="close" onclick="closes(this)"></div>
                        </div>
                    </div>
                    <div class="modal_loading cont_modal vhalign">
                        <div class="relative">
                            <div class="close" onclick="closes(this)"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sitio">
                <div class="relative">
                    <div class="menu_top">
                        <div class="relative">
                            <div class="btn_menu valign" onclick="menu_toggle()"></div>
                            <div class="btn_perfil valign" onclick="open_perfil()"></div> 
                            <div class="logo clearfix">
                                <div class="logo_img"></div>
                                <div class="logo_txt">ArteMedici</div>
                            </div>
                        </div>
                    </div>
                    <div class="menu_left">
                        <div class="cont_menu relative">
                            <div class="menu">
                                <?php if($_SESSION['user']['info']['tipo'] == 1){ ?>
                                <div class="bloque">
                                    <div class="titulo" onclick="open_bloque(this)">
                                        <div class="icono ic1"></div>
                                        <div class="texto">Administrador</div>
                                    </div>
                                    <ul class="bloque_lista">
                                        <li onclick="navlink('pages/ingresar_servicios.php')">Ingresar Servicios</li>
                                        <li onclick="navlink('pages/ingresar_medicos.php')">Ingresar Medicos</li>
                                    </ul>
                                </div>
                                <?php } ?>
                                <div class="bloque">
                                    <div class="titulo" onclick="open_bloque(this)">
                                        <div class="icono ic1"></div>
                                        <div class="texto">Mi Cuenta</div>
                                    </div>
                                    <ul class="bloque_lista">
                                        <li onclick="navlink('pages/mis_horas.php')">Mis Horas</li>
                                        <li onclick="navlink('pages/mis_servicios.php')">Mis Servicios</li>
                                        <li onclick="navlink('pages/mis_horarios.php')">Mis Horarios</li>
                                        <li onclick="navlink('pages/mis_excepciones.php')">Mis Excepciones</li>
                                    </ul>
                                </div>
                            </div>                            
                        </div>
                    </div>
                    <div class="contenido">
                        <div class="cont_contenido relative">
                            <div class="html">
                                <?php
                                    if($inicio['admin'] == 1){
                                        require 'pages/msd/giros.php';
                                    }
                                    if($inicio['admin'] == 0){
                                        require 'pages/msd/ver_giro.php';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAq6hw0biMsUBdMBu5l-bai9d3sUI-f--g&libraries=places" async defer></script>
    </body>
</html>