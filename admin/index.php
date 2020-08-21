<?php
session_start();

if((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off")) {
    $location = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}

if(isset($_GET["accion"]) && $_GET["accion"] == "logout"){
    session_destroy();
    echo '<meta http-equiv="refresh" content="0; url=index.php">';
    exit;
}

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if(!isset($_SESSION['user']['info']['id_usr'])){
    include("ingreso_login.php");
}else{
    include("inicio.php");
}

?>
