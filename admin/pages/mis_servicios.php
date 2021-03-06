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
if(isset($_GET["id_usr_admin"]) && is_numeric($_GET["id_usr_admin"]) && $_GET["id_usr_admin"] != 0){
    $core->transform($_GET["id_usr_admin"]);
}

/* CONFIG PAGE */
$titulo = "Mis Servicios";
$titulo_list = "Servicios";
$sub_titulo1 = "Ingresar Servicio";
$sub_titulo2 = "Modificar Servicio";
$accion = "crear_servicio_usuario";

$eliminaraccion = "eliminar_servicio_usuario";
$id_list = "id_ser";
$eliminarobjeto = "Servicio de Usuario";
$page_mod = "pages/mis_servicios.php";
/* CONFIG PAGE */

$id_ser = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list = $core->get_servicios();
$sub_titulo = $sub_titulo1;
$disabled = "";

if(isset($_GET["id_ser"]) && is_numeric($_GET["id_ser"]) && $_GET["id_ser"] != 0){

    $id = $_GET["id_ser"];
    $sub_titulo = $sub_titulo2;
    $that = $core->get_servicio_usuario($id);
    $select = $core->get_no_servicios_3($id);
    $disabled = "disabled";

}else{

    $select = $core->get_no_servicios();

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
    <?php if(count($select) > 0){ ?>
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
                        <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                        <label class="clearfix">
                            <span><p>Servicio:</p></span>
                            <select id="id_ser" <?php echo $disabled; ?>>
                                <option value="0">Seleccionar</option> 
                                <?php for($i=0; $i<count($select); $i++){ ?>
                                <option value="<?php echo $select[$i]["id_ser"]; ?>" <?php if($that['id_ser'] == $select[$i]["id_ser"]){ echo "selected"; } ?>><?php echo $select[$i]["nombre"]; ?></option>
                                <?php } ?>
                            </select>
                        </label>
                        <label class="clearfix">
                            <span><p>Tiempo:</p></span>
                            <input id="tiempo" class="inputs" type="text" value="<?php echo $that['tiempo_min']; ?>" require="" placeholder="" />
                        </label>
                        <label class="clearfix">
                            <span><p>Precio:</p></span>
                            <input id="precio" class="inputs" type="text" value="<?php echo $that['precio']; ?>" require="" placeholder="" />
                        </label>
                        <label class="clearfix">
                            <span><p>HTML 1:</p></span>
                            <TEXTAREA id="html_1"><?php echo $that['html_1']; ?></TEXTAREA>
                        </label>
                        <label class="clearfix">
                            <span><p>HTML 2:</p></span>
                            <TEXTAREA id="html_2"><?php echo $that['html_2']; ?></TEXTAREA>
                        </label>
                        <label>
                            <div class="enviar"><a onclick="form(this)">Enviar</a></div>
                        </label>
                    </fieldset>
                </form>
            </div>
        </div>
    <?php } ?>
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
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_ser=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>