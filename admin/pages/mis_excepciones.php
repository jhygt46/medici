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
$titulo = "Mis Excepciones";
$titulo_list = "Excepciones";
$sub_titulo = "Ingresar Excepcion";
$sub_titulo2 = "Modificar Excepcion";
$accion = "crear_excepcion";

$eliminaraccion = "eliminar_excepcion";
$id_list = "id_exc";
$eliminarobjeto = "Excepcion";
$page_mod = "pages/mis_excepciones.php";
/* CONFIG PAGE */

$fecha = "";
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list = $core->get_excepciones();

$semana = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];

if(isset($_GET["fecha"])){

    $fecha = $_GET["fecha"];
    $that = $core->get_fecha($fecha);

    echo "<pre>";
    print_r($that);
    echo "</pre>";

}

?>
<script>
    $(function(){
        $("#datepicker").datepicker({
            onSelect: function(dateText){
                //navlink('pages/mis_excepciones.php?fecha='+dateText);
            },
            dateFormat: 'yy-mm-dd'
        });
    });
</script>
<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/inicio.php')"></li>
        </ul>
    </div>
    <hr>
    <div class="cont_pagina">
        <div class="cont_pag">
            <form action="" method="post">
                <div class="form_titulo clearfix">
                    <div class="titulo"><?php echo $sub_titulo; ?></div>
                    <ul class="opts clearfix">
                        <li class="opt">1</li>
                        <li class="opt">2</li>
                    </ul>
                </div>
                <fieldset class="<?php echo $class; ?>">
                    <input id="id" type="hidden" value="<?php echo $id_user; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Fecha:</p></span>
                        <input id="datepicker" class="inputs" type="text" value="" placeholder="27/09/1984" />
                    </label>
                    <label>
                        <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                    </label>
                </fieldset>
            </form>
        </div>
    </div>
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
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['fecha'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_ran=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>