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
$hora = $core->ver_hora($id_hor);

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
                    $fechas = explode(" ", $list[$i]['fecha']);
                    $fecha_aux = explode(":", $fechas[1]);
                    $fecha_mostrar = $fecha_aux[0].":".$fecha_aux[1];
                    $nombre_user = $list[$i]['nombre_user'];
                    $nombre_serv = $list[$i]['nombre_serv'];

                    if($list[$i]['eliminado'] == 1){
                        $style = "style='color: #900'";
                    }
                    if($list[$i]['eliminado'] == 0){
                        if($list[$i]['estado'] == 0){

                        }
                        if($list[$i]['estado'] == 1){
                            $style = "style='color: #090'";
                        }
                    }

                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre" <?php echo $style; ?>><?php echo $fecha_aux[0].":".$fecha_aux[1]." ".$nombre_user." ".$nombre_serv; ?></div>
                        <?php if($list[$i]['eliminado'] == 0){ ?><a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>/<?php echo $fecha; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $fecha_mostrar ?>')"></a><?php }else{ ?><div class="sinicono"></div><?php } ?>
                        <a class="icono ic3" onclick="navlink('<?php echo $page_ver; ?>?id_hor=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>