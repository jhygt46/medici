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
$fecha = $_GET["fecha"];
$titulo = "Horas de ".$fecha;
$titulo_list = "Fecha proximas horas";
$page_mod = "pages/detalle_horas.php";
$id_list = "id_hor";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list = $core->get_horas_fecha_admin($fecha);

?>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/mis_horas.php')"></li>
        </ul>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <div class="list_titulo clearfix">
                <div class="titulo"><h1><?php echo $titulo_list; ?></h1></div>
                <ul class="opts clearfix">
                    <li class="opt">1</li>
                    <li class="opt">2</li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){

                    $style = "";
                    $id = $list[$i][$id_list];
                    $fecha = explode(" ", $list[$i]['fecha']);
                    $fecha_aux = explode(":", $fecha[1]);
                    $nombre_user = $list[$i]['nombre_user'];
                    $nombre_serv = $list[$i]['nombre_serv'];
                    $eliminado = $list[$i]['eliminado'];

                    echo "<pre>";
                    print_r($fecha);
                    echo "</pre>";

                    echo "<pre>";
                    print_r($fecha_aux);
                    echo "</pre>";

                    if($list[$i]['eliminado'] == 0){
                        if($list[$i]['estado'] == 0){

                        }
                        if($list[$i]['estado'] == 1){
                            $style = 'style="color: #090"';
                        }
                    }else{
                        $style = 'style="color: #900"';
                    }

                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre">  </div>
                        <a class="icono ic3" onclick="navlink('<?php echo $page_mod; ?>?id_hor=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>