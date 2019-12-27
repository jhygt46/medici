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
$titulo = "Mis Horas";
$titulo_list = "Fecha proximas horas";
$page_mod = "pages/ver_horas.php";
/* CONFIG PAGE */

$class = ($_POST['w'] < 600) ? 'resp' : 'normal' ;
$list = $core->get_fechas_horas();

$servicios = $core->get_servicios();
$sucursal = $core->get_sucursales();

?>
<script>
    $(function(){
        $("#fecha").datepicker({
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
                    <input id="accion" type="hidden" value="guardar_hora_admin" />
                    <label class="clearfix">
                        <span><p>Servicio:</p></span>
                        <select id="id_ser">
                            <option value="0">Seleccionar</option> 
                            <?php for($i=0; $i<count($servicios); $i++){ ?>
                            <option value="<?php echo $servicios[$i]["id_ser"]; ?>"><?php echo $servicios[$i]["nombre"]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Sucursal:</p></span>
                        <select id="id_suc">
                            <?php for($i=0; $i<count($sucursal); $i++){ ?>
                            <option value="<?php echo $sucursal[$i]["id_suc"]; ?>"><?php echo $sucursal[$i]["nombre"]; ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label class="clearfix">
                        <span><p>Fecha:</p></span>
                        <input id="fecha" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Hora:</p></span>
                        <input id="hora" class="inputs" type="text" value="" require="" placeholder="10:30" />
                    </label>
                    <label class="clearfix">
                        <span><p>Rut:</p></span>
                        <input id="rut" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Nombre:</p></span>
                        <input id="nombre" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Correo:</p></span>
                        <input id="correo" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>Telefono:</p></span>
                        <input id="telefono" class="inputs" type="text" value="" require="" placeholder="" />
                    </label>
                    <label class="clearfix">
                        <span><p>HTML 2:</p></span>
                        <TEXTAREA id="mensaje"></TEXTAREA>
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
                    $fecha = explode(" ", $list[$i]['DATE(fecha)']);
                ?>
                <div class="l_item">
                    <div class="detalle_item clearfix">
                        <div class="nombre"><?php echo $fecha[0]; ?></div>
                        <a class="icono ic8" onclick="navlink('<?php echo $page_mod; ?>?fecha=<?php echo $fecha[0]; ?>')"></a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>