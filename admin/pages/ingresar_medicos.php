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
$titulo = "Medicos";
$titulo_list = "Todos los Medicos";
$sub_titulo = "Ingresar Medico";
$sub_titulo2 = "Modificar Medico";
$accion = "crear_medico";

$eliminaraccion = "eliminar_medico";
$id_list = "id_usr";
$eliminarobjeto = "Medico";
$page_mod = "pages/ingresar_medicos.php";
$page_img = "pages/ingresar_imagen.php?t=medicos";
/* CONFIG PAGE */

$id = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list = $core->get_medicos();

if(isset($_GET["id_usr"]) && is_numeric($_GET["id_usr"]) && $_GET["id_usr"] != 0){

    $id = $_GET["id_usr"];
    $sub_titulo = $sub_titulo2;
    $that = $core->get_medico($id);

}

?>
<script>
    <?php if(isset($_GET['sortable'])){ ?>
    $('.listado_items').sortable({
        stop: function(e, ui){
            var order = [];
            $(this).find('.l_item').each(function(){
                order.push($(this).attr('rel'));
            });
            var send = {accion: 'ordermed', values: order};
            $.ajax({
                url: "ajax/index.php",
                type: "POST",
                data: send,
                success: function(data){ console.log(data); },
                error: function(e){}
            });
        }
    });
    <?php } ?>
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
                    <input id="id" type="hidden" value="<?php echo $id; ?>" />
                    <input id="accion" type="hidden" value="<?php echo $accion; ?>" />
                    <label class="clearfix">
                        <span><p>Nombre:</p></span>
                        <input id="nombre" class="inputs" type="text" value="<?php echo $that['nombre']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Correo:</p></span>
                        <input id="correo" class="inputs" type="text" value="<?php echo $that['correo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Tipo:</p></span>
                        <select id="tipo"><option value="0" <?php if($that['tipo'] == 0){ echo "selected"; } ?>>Medico</option><option value="1" <?php if($that['tipo'] == 1){ echo "selected"; } ?>>Medico y Administrador</option></select>
                    </label>
                    <label class="clearfix">
                        <span><p>Titulo Servicios:</p></span>
                        <input id="titulo" class="inputs" type="text" value="<?php echo $that['titulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Password:</p></span>
                        <input id="pass1" class="inputs" type="password" value="" require="" placeholder="opcional" />
                    </label>
                    <label class="clearfix">
                        <span><p>Confirmar Password:</p></span>
                        <input id="pass2" class="inputs" type="password" value="" require="" placeholder="opcional" />
                    </label>
                    <label class="clearfix">
                        <span><p>Titulo Servicios:</p></span>
                        <input id="titulo" class="inputs" type="text" value="<?php echo $that['titulo']; ?>" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                            <span><p>HTML descripcion:</p></span>
                            <TEXTAREA id="html_descripcion"><?php echo $that['html_descripcion']; ?></TEXTAREA>
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
                    <li class="opt"><div onclick="navlink('pages/ingresar_medicos.php?sortable=1')" class="order"></div></li>
                </ul>
            </div>
            <div class="listado_items">
                <?php 
                for($i=0; $i<count($list); $i++){
                    $id = $list[$i][$id_list];
                    $nombre = $list[$i]['nombre'];
                ?>
                <div class="l_item" rel="<?php echo $id; ?>">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $nombre; ?></div>
                        <a class="icono ic11" onclick="eliminar('<?php echo $eliminaraccion; ?>', '<?php echo $id; ?>', '<?php echo $eliminarobjeto; ?>', '<?php echo $nombre; ?>')"></a>
                        <a class="icono ic1" onclick="navlink('<?php echo $page_mod; ?>?id_usr=<?php echo $id; ?>')"></a>
                        <a class="icono ic2" onclick="navlink('pages/mis_servicios.php?id_usr_admin=<?php echo $id; ?>')"></a>
                        <a class="icono ic19" onclick="navlink('<?php echo $page_img; ?>&id=<?php echo $id; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>