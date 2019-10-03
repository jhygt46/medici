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
if(isset($_GET["fecha"])){ $fecha = $_GET["fecha"]; }else{ exit; }
$titulo = "Excepcion ".$fecha;
$titulo_list = "Excepciones";
$sub_titulo = "Ingresar Excepcion";
$sub_titulo2 = "Modificar Excepcion";
$accion = "crear_rango_excepcion";

$eliminaraccion = "eliminar_rango_excepcion";
$id_list = "id_exc";
$eliminarobjeto = "Excepcion";
$page_mod = "pages/rangos_excepciones.php";
/* CONFIG PAGE */

$id = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list = $core->get_todas_excepciones($fecha);
$list_servicios = $core->get_servicios();
$sucursales = $core->get_sucursales();

if(isset($_GET["id_exc"]) && is_numeric($_GET["id_exc"]) && $_GET["id_exc"] != 0){

    $id = $_GET["id_exc"];
    $sub_titulo = $sub_titulo2;
    $that = $core->get_excepcion($id);
    $that_list = $core->exp_servicios($id);

}


?>

<div class="pagina">
    <div class="title">
        <h1><?php echo $titulo; ?></h1>
        <ul class="clearfix">
            <li class="back" onclick="navlink('pages/mis_excepciones.php')"></li>
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
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="fecha" type="hidden" value="<?php echo $fecha; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Hora inicio:</p></span>
                        <input id="hora_ini" class="inputs" type="text" value="<?php echo $that['hora_ini']; ?>" require="" placeholder="08:30:00" />
                    </label>
                    <label class="clearfix">
                        <span><p>Hora fin:</p></span>
                        <input id="hora_fin" class="inputs" type="text" value="<?php echo $that['hora_fin']; ?>" require="" placeholder="14:30:00" />
                    </label>
                    <label class="clearfix">
                        <span><p>Sucursal:</p></span>
                        <select id="id_suc">
                            <?php for($i=0; $i<count($sucursales); $i++){ ?>
                            <option value="1" <?php if($sucursales[$i]['id_suc'] == $that['id_suc']){ echo "selected"; } ?>><?php echo $sucursales[$i]['nombre']; ?></option>
                            <?php } ?>
                        </select>   
                    </label>
                    <label class="clearfix">
                        <span><p>Servicios:</p></span>
                        <div class="perfil_preguntas">
                            <?php foreach($list_servicios as $value){ $checked=""; for($i=0; $i<count($that_list); $i++){ if($that_list[$i]["id_ser"] == $value['id_ser']){ $checked="checked"; } } ?>
                                <div class="clearfix">
                                    <input style="margin-top: 4px; width: 18px; height: 18px; float: left" id="servicio-<?php echo $value['id_ser']; ?>" <?php echo $checked; ?> type="checkbox" value="1" />
                                    <div style="font-size: 18px; padding-left: 4px; float: left" class='detail'><?php echo $value['nombre']; ?></div>
                                </div>
                            <?php } ?>
                        </div>
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
                    $nombre = $list[$i]["hora_ini"]." - ".$list[$i]["hora_fin"];
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>/<?php echo $fecha; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_exc=<?php echo $id; ?>&fecha=<?php echo $fecha; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>