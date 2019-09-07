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
$titulo = "Servicios";
$titulo_list = "Todos los Servicios";
$sub_titulo = "Ingresar Servicio";
$sub_titulo2 = "Modificar Servicio";
$accion = "crear_servicio";

$eliminaraccion = "eliminar_servicio";
$id_list = "id_ser";
$eliminarobjeto = "Servicio";
$page_mod = "pages/ingresar_servicios.php";
/* CONFIG PAGE */

$id = 0;
$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list = $core->get_todos_servicios();

if(isset($_GET["id_ser"]) && is_numeric($_GET["id_ser"]) && $_GET["id_ser"] != 0){

    $id = $_GET["id_ser"];
    $sub_titulo = $sub_titulo2;
    $that = $core->get_servicio($id);

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
            var send = {accion: 'orderser', values: order};
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
                        <span><p>Descripcion:</p></span>
                        <input id="descripcion" class="inputs" type="text" value="<?php echo $that['descripcion']; ?>" require="" placeholder="" />
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
                    <li class="opt"><div onclick="navlink('pages/ingresar_servicios.php?sortable=1')" class="order"></div></li>
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