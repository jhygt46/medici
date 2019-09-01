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
$titulo = "Mis Servicios";
$titulo_list = "Servicios";
$sub_titulo1 = "Ingresar Servicio";
$sub_titulo2 = "Modificar Servicio";
$accion = "crear_servicio_usuario";

$eliminaraccion = "eliminar_servicio_usuario";
$id_list_1 = "id_usr";
$id_list_2 = "id_ser";
$eliminarobjeto = "Servicio de Usuario";
$page_mod = "pages/msd/mis_servicios.php";
/* CONFIG PAGE */

$id_usr = 0;
$id_ser = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;

if(isset($_GET["id_usr"]) && is_numeric($_GET["id_usr"]) && $_GET["id_usr"] != 0){
    if(isset($_GET["id_ser"]) && is_numeric($_GET["id_ser"]) && $_GET["id_ser"] != 0){

        $id_usr = $_GET["id_usr"];
        $id_ser = $_GET["id_ser"];
        $sub_titulo = $sub_titulo2;
        $that = $core->get_servicio_usuario($id_usr, $id_ser);

    }
}
?>

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
                        <span><p>Nombre:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Correo:</p></span>
                        <input id="correo" class="inputs" type="text" value="<?php echo $that['correo']; ?>" require="" placeholder="" />
                    </label>
                    <?php if($inicio["id_user"] == 1){ ?>
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo">
                            <option value="0" <?php if($that['id_aux_user'] > 0){ echo "selected"; } ?>>Vendedor</option> 
                            <option value="1" <?php if($that['id_aux_user'] == 0){ echo "selected"; } ?>>Reclutador</option>
                        </select>
                    </label>
                    <?php } ?>
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
                    $nombre = $list[$i]['nombre'];
                    $dominio = $list[$i]['dominio'];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_user=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>