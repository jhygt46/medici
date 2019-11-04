<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."medici/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."medici/");
}

require_once DIR."admin/class/core_class.php";
$core = new Core();
$core->get_data();
/*
if($_GET["accion"] == "actualizar" || isset($_GET["status"])){
    
}
*/
$status = 0;
$contacto = 0;
if(isset($_GET["contacto"])){
    $contacto = $_GET["contacto"];
}else{
    if(isset($_GET["status"])){
        $status = $_GET["status"];
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>ARTEMEDICI</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="https://fonts.googleapis.com/css?family=Oswald|Cinzel&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="css/base.css" media="all" />
        <link rel="stylesheet" href="css/index.css" media="all" />

        <script src="js/base.js" type="text/javascript"></script>
        <!--<script src="js/info.js" type="text/javascript"></script>-->
        <script src="http://35.225.100.155/js/info.js" type="text/javascript"></script>
        <script>
            var n_year = <?php echo (isset($_GET["y"])) ? $_GET["y"] : date("Y") ; ?>;
            var n_month = <?php echo (isset($_GET["m"])) ? intval($_GET["m"]) : intval(date("m")) - 1 ; ?>;
            var n_day = <?php echo (isset($_GET["d"])) ? $_GET["d"] : date("d") ; ?>;
            var n_hours = <?php echo intval(date("H")); echo " + Math.round(".(date("i")/60).") + 1"; ?>;
            var status = <?php echo $status; ?>;
            var contacto = <?php echo $contacto; ?>;
        </script>
    </head>
    <body onload="inicio()" draggable="true">
        <div id="contenedor">
            <div class="pop_up" id="pop_up">
                <div class="cont_pop">
                    <div class="pop vhalign">
                        <div class="cp">
                            <div class="close" id="close" onclick="close()"><div class="cc c1"></div><div class="cc c2"></div></div>
                            <div class="titulo">Ingresa tus datos</div>
                            <div class="formulario">
                                <div class="form">
                                    <input type="hidden" name="id_ser" name="id_ser" value="" />
                                    <input type="hidden" name="id_usr" name="id_usr" value="" />
                                    <input type="hidden" name="f_fec" name="f_fec" value="" />
                                    <input type="hidden" name="f_hor" name="f_hor" value="" />
                                    <h3>Rut:</h3>
                                    <div class="input"><input type="text" id="re_rut" name="rut" placeholder="" /></div>
                                    <h3>Nombre completo:</h3>
                                    <div class="input"><input type="text" id="re_nombre" name="nombre" placeholder="" /></div>
                                    <h3>Correo electronico:</h3>
                                    <div class="input"><input type="text" id="re_correo" name="correo" placeholder="" /></div>
                                    <h3>Telefono:</h3>
                                    <div class="input"><input type="text" id="re_telefono" name="telefono" placeholder="" /></div>
                                    <h3>Mensaje:</h3>
                                    <div class="input"><TEXTAREA class="txta" id="re_mensaje" name="mensaje"></TEXTAREA></div>
                                    <div class="acciones"><input type="button" value="Enviar" class="empezar" onclick="enviar_reserva()" /></div>
                                    <small class="smallrecaptcha" style="display: block; font-size: 13px; padding-top: 10px">This site is protected by reCAPTCHA and the Google 
                                        <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                                        <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pop_up" id="pop_up2">
                <div class="cont_pop">
                    <div class="pop vhalign">
                        <div class="cp">
                            <div class="close" id="close" onclick="close2()"><div class="cc c1"></div><div class="cc c2"></div></div>
                            <div class="ser_usr_html" id="p2_html"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu m1" style="top: 0px; left: -280px; z-index: 5">
                <div class="cont_menu">
                    <div class="data_menu">
                        <div class="cont_data_menu">
                            <ul>
                                <li onclick="sitio_inicio_(this)">Inicio</li>
                                <li onclick="sitio_servicios_(this)">Servicios</li>
                                <li onclick="sitio_nosotros_(this)">Nosotros</li>
                                <li onclick="sitio_reservar_(this)">Reserva tu Hora</li>
                                <li onclick="sitio_contacto_(this)">Contacto</li>
                            </ul>
                            <div class="info_bottom">
                                <div class="info_dtl">Avenida Apoquindo # 6410</div>
                                <div class="info_dtl">Oficina: 309</div>
                                <div class="info_dtl">Las Condes</div>
                            </div>
                        </div>
                    </div>
                    <div class="btn_menu" id="men" onclick="btn_menu(this)">
                        <div class="cont_btn_menu">
                            <div class="linea l1"></div>
                            <div class="linea valign"></div>
                            <div class="linea l2"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sitio">
                <div class="cont_sitio">
                    <div class="titulo_pagina">
                        <div class="logo_pagina_mobile vhalign"></div>
                        <div class="logo_pagina_web vhalign">
                            <div class="cont_logo">
                                <div onclick="sitio_inicio()" class="logo valign"></div>
                                <div class="botones valign">
                                    <div class="btns clearfix">
                                        <div class="btn btn1" onclick="sitio_contacto()">CONTACTO</div>
                                        <div class="btn btn2" onclick="sitio_reservar()">RESERVA TU HORA</div>
                                        <div class="btn btn3" onclick="sitio_nosotros()">NOSOTROS</div>
                                        <div class="btn btn4" onclick="sitio_servicios()">SERVICIOS</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sitio_pagina" <?php if($status > 0){ ?>style="display: block"<?php }else{ ?>style="display: none" <?php } ?>>
                        <div class="seccion m_error"></div>
                        <div class="seccion btn_pre">
                            <div class="preguntas clearfix">
                                <div id="pre_servicio" class="pregunta" style="background: #fafafa">
                                    <div class="data valign"><h2 id="pre_serv_h2"></h2><h1 id="pre_serv_h1">Servicio</h1></div>
                                    <div onclick="close_servicio()" class="close" id="pre_serv_close" style="display: none"><div class="cont_close"><div class="line l1 valign"></div><div class="line l2 halign"></div></div></div>
                                </div>
                                <div id="pre_doctor" class="pregunta" style="background: #fafafa">
                                    <div class="data valign"><h2 id="pre_doc_h2"></h2><h1 id="pre_doc_h1">Doctor</h1></div>
                                    <div onclick="close_doctor()" class="close" id="pre_doc_close" style="display: none"><div class="cont_close"><div class="line l1 valign"></div><div class="line l2 halign"></div></div></div>
                                </div>
                                <div id="pre_fecha" class="pregunta" style="background: #fafafa">
                                    <div class="data valign"><h2 id="pre_fecha_h2"></h2><h1 id="pre_fecha_h1">Fecha</h1></div>
                                    <div onclick="close_fecha()" class="close" id="pre_fecha_close" style="display: none"><div class="cont_close"><div class="line l1 valign"></div><div class="line l2 halign"></div></div></div>
                                </div>
                                <div id="pre_estado" class="pregunta" style="background: #fafafa">
                                    <div class="data valign"><h2 id="pre_hora_h2"></h2><h1 id="pre_hora_h1">Hora</h1></div>
                                    <div onclick="close_hora()" class="close" id="pre_hora_close" style="display: none"><div class="cont_close"><div class="line l1 valign"></div><div class="line l2 halign"></div></div></div>
                                </div>
                            </div>
                        </div>
                        <div class="seccion info" id="info"></div>
                    </div>
                    <div class="sitio_pagina back_inicio" <?php if($contacto > 0 || $status > 0){ ?>style="display: none"<?php } ?>>
                        <div class="seccion inicio">
                            <div class="inicio_titulo">BIENVENIDO<br/>A TU<br/>SALUD INTEGRAL</div>
                            <div class="inicio_fotos" id="fotos">
                                <div class="foto" style="left: 0px"><img src="images/slide1.jpg" alt="" /></div>
                                <div class="foto" style="left: 850px"><img src="images/slide2.jpg" alt="" /></div>
                                <div class="foto" style="left: 850px"><img src="images/slide3.jpg" alt="" /></div>
                            </div>
                            <div class="cont_inicio_info">
                                <div class="inicio_info">
                                    <div class="inicio_info_titulo">Nuestra direcci&oacute;n</div>
                                    <div class="linea"></div>
                                    <div class="inicio_info_data">Avenida Apoquindo # 6410</div>
                                    <div class="inicio_info_data">Oficina: 309</div>
                                    <div class="inicio_info_data">Las Condes</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sitio_pagina" style="display: none">
                        <div class="seccion nosotros">
                            <div class="titulo_seccion">Nosotros</div>
                            <div class="linea"></div>
                            <div class="sec_cont">
                                <ul id="nosotros" class="clearfix"></ul>
                                <ul id="nosotros2" class="clearfix"></ul>
                            </div>
                        </div>
                    </div>
                    <div class="sitio_pagina" <?php if($contacto > 0){ ?>style="display: block"<?php }else{ ?>style="display: none" <?php } ?>>
                        <div class="seccion contacto">
                            <div class="titulo_seccion">Contacto</div>
                            <div class="linea"></div>
                            <div class="sec_cont">
                                <div class="cont_cont clearfix">
                                    <div class="cont_form">
                                        <div class="cont_forms">
                                            <?php if($contacto == 0 || $contacto == 2){ ?>
                                            <h3>Nombre:</h3>
                                            <div class="input"><input type="text" id="co_nombre" name="nombre" placeholder="" /></div>
                                            <h3>Correo electronico:</h3>
                                            <div class="input"><input type="text" id="co_correo" name="correo" placeholder="" /></div>
                                            <h3>Asunto:</h3>
                                            <div class="input"><input type="text" id="co_asunto" name="asunto" placeholder="" /></div>
                                            <h3>Mensaje:</h3>
                                            <div class="input"><TEXTAREA class="txta" id="co_mensaje" name="mensaje"></TEXTAREA></div>
                                            <div class="acciones"><input type="submit" value="Enviar" class="empezar" onclick="enviar_contacto()" /></div>
                                            <small class="smallrecaptcha" style="display: block; font-size: 13px; padding-top: 10px">This site is protected by reCAPTCHA and the Google 
                                                <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                                                <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                                            </small>
                                            <?php }else{ ?>
                                            <div class="felicitaciones">
                                                Su solictud fue enviado con exito, pronto nos contactaremos con usted
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="cont_map" id="map"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sitio_pagina" style="display: none">
                        <div class="seccion servicios">
                            <div class="titulo_seccion">Servicios</div>
                            <div class="linea"></div>
                            <div class="sec_cont">
                                <ul id="servicios"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://www.google.com/recaptcha/api.js?render=6Lfor7kUAAAAABomMyYcaO0RhvHJBmPF85PrNP2v"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbKlHezhqgy7z57ipcJk8mDK4rf6drvjY&libraries=places" async defer></script>
    </body>
</html>