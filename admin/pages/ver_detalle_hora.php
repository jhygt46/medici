<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."medici/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."medici/");
}

require_once(DIR."admin/class/core_class.php");
$core = new Core();

/* CONFIG PAGE */
$id_hor = $_GET["id_hor"];
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$hora = $core->ver_detalle_hora($id_hor);
$aux = explode(" ", $hora['fecha']);
$aux2 = explode(":", $aux[1]);
$titulo = "Hora: ".$aux2[0].":".$aux2[1];

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/ver_horas.php?fecha=<?php echo $aux[0]; ?>')"></li>
        </ul>
    </div>
    <hr>
    <div style="padding: 10px 0px">
        <div class="info_tit">Nombre</div>
        <div class="info_data"><?php echo $hora['nombre_usr']; ?></div>
        <div class="info_tit">Rut</div>
        <div class="info_data"><?php echo $hora['rut']; ?></div>
        <div class="info_tit">telefono</div>
        <div class="info_data"><?php echo $hora['telefono']; ?></div>
        <div class="info_tit">Correo</div>
        <div class="info_data"><?php echo $hora['correo']; ?></div>
        <div class="info_tit">Mensaje</div>
        <div class="info_data"><?php echo $hora['mensaje']; ?></div>
        <div class="info_tit">Servicio</div>
        <div class="info_data"><?php echo $hora['nombre_ser']; ?></div>
        <div class="info_tit">Tiempo</div>
        <div class="info_data"><?php echo $hora['tiempo_min']; ?></div>
        <div class="info_tit">Precio</div>
        <div class="info_data"><?php echo $hora['precio']; ?></div>
        <div class="info_tit">Estado</div>
        <div class="info_data"><?php echo $hora['estado']; ?></div>
    </div>
</div>