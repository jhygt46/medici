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
$titulo = "Mis Horarios";
$titulo_list = "Horarios";
$sub_titulo1 = "Ingresar Horario";
$sub_titulo2 = "Modificar Horario";
$accion = "crear_horario";

$eliminaraccion = "eliminar_horario";
$id_list = "id_ran";
$eliminarobjeto = "Horario";
$page_mod = "pages/mis_horarios.php";
/* CONFIG PAGE */

$id_ran = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list = $core->get_rangos();
$list_servicios = $core->get_servicios();

$semana = ["Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado"];

if(isset($_GET["id_ran"]) && is_numeric($_GET["id_ran"]) && $_GET["id_ran"] != 0){

    $id_ran = $_GET["id_ran"];
    $sub_titulo = $sub_titulo2;
    $that = $core->get_rango($id_ran);
    $that_list = $core->get_servicios_rango($id_ran);

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
                        <span><p>Dia inicio:</p></span>
                        <select id="dia_ini">
                            <option value="0">Seleccionar</option> 
                            <option value="1" <?php if($that['dia_ini'] == 1){ echo "selected"; } ?>>Lunes</option>
                            <option value="2" <?php if($that['dia_ini'] == 2){ echo "selected"; } ?>>Martes</option>
                            <option value="3" <?php if($that['dia_ini'] == 3){ echo "selected"; } ?>>Miercoles</option>
                            <option value="4" <?php if($that['dia_ini'] == 4){ echo "selected"; } ?>>Jueves</option>
                            <option value="5" <?php if($that['dia_ini'] == 5){ echo "selected"; } ?>>Viernes</option>
                            <option value="6" <?php if($that['dia_ini'] == 6){ echo "selected"; } ?>>Sabado</option>
                            <option value="7" <?php if($that['dia_ini'] == 7){ echo "selected"; } ?>>Domingo</option>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Dia fin:</p></span>
                        <select id="dia_fin">
                            <option value="0">Seleccionar</option> 
                            <option value="1" <?php if($that['dia_fin'] == 1){ echo "selected"; } ?>>Lunes</option>
                            <option value="2" <?php if($that['dia_fin'] == 2){ echo "selected"; } ?>>Martes</option>
                            <option value="3" <?php if($that['dia_fin'] == 3){ echo "selected"; } ?>>Miercoles</option>
                            <option value="4" <?php if($that['dia_fin'] == 4){ echo "selected"; } ?>>Jueves</option>
                            <option value="5" <?php if($that['dia_fin'] == 5){ echo "selected"; } ?>>Viernes</option>
                            <option value="6" <?php if($that['dia_fin'] == 6){ echo "selected"; } ?>>Sabado</option>
                            <option value="7" <?php if($that['dia_fin'] == 7){ echo "selected"; } ?>>Domingo</option>
                        </select>   
                    </label>
                    <label class="clearfix">
                        <span><p>Hora inicio:</p></span>
                        <input id="hora_ini" class="inputs" type="text" value="<?php echo $that['hora_ini']; ?>" require="" placeholder="08:30" />
                    </label>
                    <label class="clearfix">
                        <span><p>Hora fin:</p></span>
                        <input id="hora_fin" class="inputs" type="text" value="<?php echo $that['hora_fin']; ?>" require="" placeholder="14:30" />
                    </label>
                    <label class="clearfix">
                        <span><p>Servicios:</p></span>
                        <div class="perfil_preguntas">
                            <?php foreach($list_servicios as $value){ ?>
                                <div class="clearfix">
                                    <input style="margin-top: 4px; width: 18px; height: 18px; float: left" id="pregunta-<?php echo $value['id_pre']; ?>" <?php echo $checked; ?> type="checkbox" value="1" />
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
                    $nombre = $semana[$list[$i]['dia_ini']]." ".$list[$i]['hora_ini']." - ".$semana[$list[$i]['dia_fin']]." ".$list[$i]['hora_fin'];
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