<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."medici/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."medici/");
}

require_once DIR."admin/class/core_class.php";
$core = new Core();

if($_POST["accion"] == "reserva"){
    echo "RESERVA";
    echo "<pre>";
    echo print_r($core->reservar_hora());
    echo "</pre>";
}
if($_POST["accion"] == "contacto"){
    echo "<pre>";
    echo print_r($core->contacto());
    echo "</pre>";
}

?>