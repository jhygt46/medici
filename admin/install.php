<?php

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."medici/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."medici/");
}

require_once DIR."db.php";
require_once DIR_BASE."config/config.php";
$con = new mysqli($db_host[0], $db_user[0], $db_password[0]);

echo $db_password[0]."<br/>";

$tablas[0]['nombre'] = 'usuarios';
$tablas[0]['campos'][0]['nombre'] = 'id_usr';
$tablas[0]['campos'][0]['tipo'] = 'int(4)';
$tablas[0]['campos'][0]['null'] = 0;
$tablas[0]['campos'][0]['pk'] = 1;
$tablas[0]['campos'][0]['ai'] = 1;
$tablas[0]['campos'][1]['nombre'] = 'nombre';
$tablas[0]['campos'][1]['tipo'] = 'varchar(150) COLLATE utf8_spanish2_ci';
$tablas[0]['campos'][1]['null'] = 0;
$tablas[0]['campos'][1]['values'] = ["Diego"];
$tablas[0]['campos'][2]['nombre'] = 'correo';
$tablas[0]['campos'][2]['tipo'] = 'varchar(150) COLLATE utf8_spanish2_ci';
$tablas[0]['campos'][2]['null'] = 0;
$tablas[0]['campos'][2]['values'] = ["diegomez13@hotmail.com"];
$tablas[0]['campos'][3]['nombre'] = 'tipo';
$tablas[0]['campos'][3]['tipo'] = 'tinyint(1)';
$tablas[0]['campos'][3]['null'] = 0;
$tablas[0]['campos'][3]['values'] = [1];
$tablas[0]['campos'][4]['nombre'] = 'pass';
$tablas[0]['campos'][4]['tipo'] = 'varchar(32) COLLATE utf8_spanish2_ci';
$tablas[0]['campos'][4]['null'] = 0;
$tablas[0]['campos'][4]['values'] = ["ddbe4c26697d350d05da6eb4c1ed0ab4"];
$tablas[0]['campos'][5]['nombre'] = 'mailcode';
$tablas[0]['campos'][5]['tipo'] = 'varchar(32) COLLATE utf8_spanish2_ci';
$tablas[0]['campos'][5]['null'] = 0;
$tablas[0]['campos'][5]['values'] = [""];
$tablas[0]['campos'][6]['nombre'] = 'eliminado';
$tablas[0]['campos'][6]['tipo'] = 'tinyint(1)';
$tablas[0]['campos'][6]['null'] = 0;
$tablas[0]['campos'][6]['values'] = [0];

$tablas[1]['nombre'] = 'sucursal';
$tablas[1]['campos'][0]['nombre'] = 'id_suc';
$tablas[1]['campos'][0]['tipo'] = 'int(4)';
$tablas[1]['campos'][0]['null'] = 0;
$tablas[1]['campos'][0]['pk'] = 1;
$tablas[1]['campos'][0]['ai'] = 1;
$tablas[1]['campos'][1]['nombre'] = 'nombre';
$tablas[1]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][1]['null'] = 0;
$tablas[1]['campos'][1]['values'] = ["Sucursal Providencia"];
$tablas[1]['campos'][2]['nombre'] = 'direccion';
$tablas[1]['campos'][2]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[1]['campos'][2]['null'] = 0;
$tablas[1]['campos'][2]['values'] = ["Avda Providencia 1206"];
$tablas[1]['campos'][3]['nombre'] = 'eliminado';
$tablas[1]['campos'][3]['tipo'] = 'tinyint(1)';
$tablas[1]['campos'][3]['null'] = 0;
$tablas[1]['campos'][3]['values'] = [0];

$tablas[2]['nombre'] = 'excepciones';
$tablas[2]['campos'][0]['nombre'] = 'id_exc';
$tablas[2]['campos'][0]['tipo'] = 'int(4)';
$tablas[2]['campos'][0]['null'] = 0;
$tablas[2]['campos'][0]['pk'] = 1;
$tablas[2]['campos'][0]['ai'] = 1;
$tablas[2]['campos'][1]['nombre'] = 'fecha';
$tablas[2]['campos'][1]['tipo'] = 'date';
$tablas[2]['campos'][1]['null'] = 0;
$tablas[2]['campos'][1]['values'] = ["2019-09-30", "2019-09-31"];
$tablas[2]['campos'][2]['nombre'] = 'hora_ini';
$tablas[2]['campos'][2]['tipo'] = 'varchar(8) COLLATE utf8_spanish2_ci';
$tablas[2]['campos'][2]['null'] = 0;
$tablas[2]['campos'][2]['values'] = ["09:30:00", "14:30:00"];
$tablas[2]['campos'][3]['nombre'] = 'hora_fin';
$tablas[2]['campos'][3]['tipo'] = 'varchar(8) COLLATE utf8_spanish2_ci';
$tablas[2]['campos'][3]['null'] = 0;
$tablas[2]['campos'][3]['values'] = ["12:30:00", "19:30:00"];
$tablas[2]['campos'][4]['nombre'] = 'eliminado';
$tablas[2]['campos'][4]['tipo'] = 'tinyint(1)';
$tablas[2]['campos'][4]['null'] = 0;
$tablas[2]['campos'][4]['values'] = [0, 0];
$tablas[2]['campos'][5]['nombre'] = 'id_suc';
$tablas[2]['campos'][5]['tipo'] = 'int(4)';
$tablas[2]['campos'][5]['null'] = 0;
$tablas[2]['campos'][5]['k'] = 1;
$tablas[2]['campos'][5]['kt'] = 1;
$tablas[2]['campos'][5]['kc'] = 0;
$tablas[2]['campos'][5]['values'] = [1, 1];
$tablas[2]['campos'][6]['nombre'] = 'id_usr';
$tablas[2]['campos'][6]['tipo'] = 'int(4)';
$tablas[2]['campos'][6]['null'] = 0;
$tablas[2]['campos'][6]['k'] = 1;
$tablas[2]['campos'][6]['kt'] = 0;
$tablas[2]['campos'][6]['kc'] = 0;
$tablas[2]['campos'][6]['values'] = [1, 1];

$tablas[3]['nombre'] = 'servicios';
$tablas[3]['campos'][0]['nombre'] = 'id_ser';
$tablas[3]['campos'][0]['tipo'] = 'int(4)';
$tablas[3]['campos'][0]['null'] = 0;
$tablas[3]['campos'][0]['pk'] = 1;
$tablas[3]['campos'][0]['ai'] = 1;
$tablas[3]['campos'][1]['nombre'] = 'nombre';
$tablas[3]['campos'][1]['tipo'] = 'varchar(100) COLLATE utf8_spanish2_ci';
$tablas[3]['campos'][1]['null'] = 0;
$tablas[3]['campos'][2]['nombre'] = 'descripcion';
$tablas[3]['campos'][2]['tipo'] = 'varchar(255) COLLATE utf8_spanish2_ci';
$tablas[3]['campos'][2]['null'] = 0;
$tablas[3]['campos'][3]['nombre'] = 'eliminado';
$tablas[3]['campos'][3]['tipo'] = 'tinyint(1)';
$tablas[3]['campos'][3]['null'] = 0;

$tablas[4]['nombre'] = 'excepcion_servicios';
$tablas[4]['campos'][0]['nombre'] = 'id_exc';
$tablas[4]['campos'][0]['tipo'] = 'int(4)';
$tablas[4]['campos'][0]['null'] = 0;
$tablas[4]['campos'][0]['pk'] = 1;
$tablas[4]['campos'][0]['k'] = 1;
$tablas[4]['campos'][0]['kt'] = 2;
$tablas[4]['campos'][0]['kc'] = 0;
$tablas[4]['campos'][1]['nombre'] = 'id_ser';
$tablas[4]['campos'][1]['tipo'] = 'int(4)';
$tablas[4]['campos'][1]['null'] = 0;
$tablas[4]['campos'][1]['pk'] = 1;
$tablas[4]['campos'][1]['k'] = 1;
$tablas[4]['campos'][1]['kt'] = 3;
$tablas[4]['campos'][1]['kc'] = 0;

$tablas[5]['nombre'] = 'horas';
$tablas[5]['campos'][0]['nombre'] = 'id_hor';
$tablas[5]['campos'][0]['tipo'] = 'int(4)';
$tablas[5]['campos'][0]['null'] = 0;
$tablas[5]['campos'][0]['pk'] = 1;
$tablas[5]['campos'][0]['ai'] = 1;
$tablas[5]['campos'][1]['nombre'] = 'fecha';
$tablas[5]['campos'][1]['tipo'] = 'datetime';
$tablas[5]['campos'][1]['null'] = 0;
$tablas[5]['campos'][2]['nombre'] = 'tiempo_min';
$tablas[5]['campos'][2]['tipo'] = 'smallint(2)';
$tablas[5]['campos'][2]['null'] = 0;
$tablas[5]['campos'][3]['nombre'] = 'precio';
$tablas[5]['campos'][3]['tipo'] = 'int(5)';
$tablas[5]['campos'][3]['null'] = 0;
$tablas[5]['campos'][4]['nombre'] = 'eliminado';
$tablas[5]['campos'][4]['tipo'] = 'tinyint(1)';
$tablas[5]['campos'][4]['null'] = 0;
$tablas[5]['campos'][5]['nombre'] = 'id_ser';
$tablas[5]['campos'][5]['tipo'] = 'int(4)';
$tablas[5]['campos'][5]['null'] = 0;
$tablas[5]['campos'][5]['k'] = 1;
$tablas[5]['campos'][5]['kt'] = 3;
$tablas[5]['campos'][5]['kc'] = 0;
$tablas[5]['campos'][6]['nombre'] = 'id_usr';
$tablas[5]['campos'][6]['tipo'] = 'int(4)';
$tablas[5]['campos'][6]['null'] = 0;
$tablas[5]['campos'][6]['k'] = 1;
$tablas[5]['campos'][6]['kt'] = 0;
$tablas[5]['campos'][6]['kc'] = 0;
$tablas[5]['campos'][7]['nombre'] = 'id_suc';
$tablas[5]['campos'][7]['tipo'] = 'int(4)';
$tablas[5]['campos'][7]['null'] = 0;
$tablas[5]['campos'][7]['k'] = 1;
$tablas[5]['campos'][7]['kt'] = 1;
$tablas[5]['campos'][7]['kc'] = 0;

$tablas[6]['nombre'] = 'rangos';
$tablas[6]['campos'][0]['nombre'] = 'id_ran';
$tablas[6]['campos'][0]['tipo'] = 'int(4)';
$tablas[6]['campos'][0]['null'] = 0;
$tablas[6]['campos'][0]['pk'] = 1;
$tablas[6]['campos'][0]['ai'] = 1;
$tablas[6]['campos'][1]['nombre'] = 'dia_ini';
$tablas[6]['campos'][1]['tipo'] = 'tinyint(1)';
$tablas[6]['campos'][1]['null'] = 0;
$tablas[6]['campos'][2]['nombre'] = 'dia_fin';
$tablas[6]['campos'][2]['tipo'] = 'tinyint(1)';
$tablas[6]['campos'][2]['null'] = 0;
$tablas[6]['campos'][3]['nombre'] = 'hora_ini';
$tablas[6]['campos'][3]['tipo'] = 'varchar(8) COLLATE utf8_spanish2_ci';
$tablas[6]['campos'][3]['null'] = 0;
$tablas[6]['campos'][4]['nombre'] = 'hora_fin';
$tablas[6]['campos'][4]['tipo'] = 'varchar(8) COLLATE utf8_spanish2_ci';
$tablas[6]['campos'][4]['null'] = 0;
$tablas[6]['campos'][5]['nombre'] = 'eliminado';
$tablas[6]['campos'][5]['tipo'] = 'tinyint(1)';
$tablas[6]['campos'][5]['null'] = 0;
$tablas[6]['campos'][6]['nombre'] = 'id_suc';
$tablas[6]['campos'][6]['tipo'] = 'int(4)';
$tablas[6]['campos'][6]['null'] = 0;
$tablas[6]['campos'][6]['k'] = 1;
$tablas[6]['campos'][6]['kt'] = 1;
$tablas[6]['campos'][6]['kc'] = 0;
$tablas[6]['campos'][7]['nombre'] = 'id_usr';
$tablas[6]['campos'][7]['tipo'] = 'int(4)';
$tablas[6]['campos'][7]['null'] = 0;
$tablas[6]['campos'][7]['k'] = 1;
$tablas[6]['campos'][7]['kt'] = 0;
$tablas[6]['campos'][7]['kc'] = 0;

$tablas[7]['nombre'] = 'rango_servicios';
$tablas[7]['campos'][0]['nombre'] = 'id_ran';
$tablas[7]['campos'][0]['tipo'] = 'int(4)';
$tablas[7]['campos'][0]['null'] = 0;
$tablas[7]['campos'][0]['pk'] = 1;
$tablas[7]['campos'][0]['k'] = 1;
$tablas[7]['campos'][0]['kt'] = 6;
$tablas[7]['campos'][0]['kc'] = 0;
$tablas[7]['campos'][1]['nombre'] = 'id_ser';
$tablas[7]['campos'][1]['tipo'] = 'int(4)';
$tablas[7]['campos'][1]['null'] = 0;
$tablas[7]['campos'][1]['pk'] = 1;
$tablas[7]['campos'][1]['k'] = 1;
$tablas[7]['campos'][1]['kt'] = 3;
$tablas[7]['campos'][1]['kc'] = 0;

$tablas[8]['nombre'] = 'servicio_usuarios';
$tablas[8]['campos'][0]['nombre'] = 'id_usr';
$tablas[8]['campos'][0]['tipo'] = 'int(4)';
$tablas[8]['campos'][0]['null'] = 0;
$tablas[8]['campos'][0]['pk'] = 1;
$tablas[8]['campos'][0]['k'] = 1;
$tablas[8]['campos'][0]['kt'] = 0;
$tablas[8]['campos'][0]['kc'] = 0;
$tablas[8]['campos'][1]['nombre'] = 'id_ser';
$tablas[8]['campos'][1]['tipo'] = 'int(4)';
$tablas[8]['campos'][1]['null'] = 0;
$tablas[8]['campos'][1]['pk'] = 1;
$tablas[8]['campos'][1]['k'] = 1;
$tablas[8]['campos'][1]['kt'] = 5;
$tablas[8]['campos'][1]['kc'] = 0;
$tablas[8]['campos'][2]['nombre'] = 'tiempo_min';
$tablas[8]['campos'][2]['tipo'] = 'smallint(2)';
$tablas[8]['campos'][2]['null'] = 0;
$tablas[8]['campos'][3]['nombre'] = 'precio';
$tablas[8]['campos'][3]['tipo'] = 'int(4)';
$tablas[8]['campos'][3]['null'] = 0;
$tablas[8]['campos'][4]['nombre'] = 'eliminado';
$tablas[8]['campos'][4]['tipo'] = 'tinyint(1)';
$tablas[8]['campos'][4]['null'] = 0;

$tablas[9]['nombre'] = 'fw_acciones';
$tablas[9]['campos'][0]['nombre'] = 'id_acc';
$tablas[9]['campos'][0]['tipo'] = 'int(4)';
$tablas[9]['campos'][0]['null'] = 0;
$tablas[9]['campos'][0]['pk'] = 1;
$tablas[9]['campos'][0]['ai'] = 1;
$tablas[9]['campos'][1]['nombre'] = 'tipo';
$tablas[9]['campos'][1]['tipo'] = 'tinyint(1)';
$tablas[9]['campos'][1]['null'] = 0;
$tablas[9]['campos'][2]['nombre'] = 'fecha';
$tablas[9]['campos'][2]['tipo'] = 'datetime';
$tablas[9]['campos'][2]['null'] = 0;
$tablas[9]['campos'][3]['nombre'] = 'id_usr';
$tablas[9]['campos'][3]['tipo'] = 'int(4)';
$tablas[9]['campos'][3]['null'] = 0;
$tablas[9]['campos'][3]['k'] = 1;
$tablas[9]['campos'][3]['kt'] = 0;
$tablas[9]['campos'][3]['kc'] = 0;

$tablas[10]['nombre'] = 'desc_seguimiento';
$tablas[10]['campos'][0]['nombre'] = 'id_des';
$tablas[10]['campos'][0]['tipo'] = 'int(4)';
$tablas[10]['campos'][0]['null'] = 0;
$tablas[10]['campos'][0]['pk'] = 1;
$tablas[10]['campos'][0]['ai'] = 1;
$tablas[10]['campos'][1]['nombre'] = 'nombre';
$tablas[10]['campos'][1]['tipo'] = 'varchar(30) COLLATE utf8_spanish2_ci';
$tablas[10]['campos'][1]['null'] = 0;

$tablas[11]['nombre'] = 'seguimiento';
$tablas[11]['campos'][0]['nombre'] = 'id_seg';
$tablas[11]['campos'][0]['tipo'] = 'int(4)';
$tablas[11]['campos'][0]['null'] = 0;
$tablas[11]['campos'][0]['pk'] = 1;
$tablas[11]['campos'][0]['ai'] = 1;
$tablas[11]['campos'][1]['nombre'] = 'fecha';
$tablas[11]['campos'][1]['tipo'] = 'datetime';
$tablas[11]['campos'][1]['null'] = 0;
$tablas[11]['campos'][2]['nombre'] = 'txt';
$tablas[11]['campos'][2]['tipo'] = 'varchar(10) COLLATE utf8_spanish2_ci';
$tablas[11]['campos'][2]['null'] = 0;
$tablas[11]['campos'][3]['nombre'] = 'id_des';
$tablas[11]['campos'][3]['tipo'] = 'int(4)';
$tablas[11]['campos'][3]['null'] = 0;
$tablas[11]['campos'][3]['k'] = 1;
$tablas[11]['campos'][3]['kt'] = 10;
$tablas[11]['campos'][3]['kc'] = 0;
$tablas[11]['campos'][4]['nombre'] = 'id_usr';
$tablas[11]['campos'][4]['tipo'] = 'int(4)';
$tablas[11]['campos'][4]['null'] = 0;
$tablas[11]['campos'][4]['k'] = 1;
$tablas[11]['campos'][4]['kt'] = 0;
$tablas[11]['campos'][4]['kc'] = 0;

for($i=0; $i<count($tablas); $i++){

    $tabla = "CREATE TABLE IF NOT EXISTS `".$tablas[$i]["nombre"]."` (";
    $aux_t = [];
    for($j=0; $j<count($tablas[$i]["campos"]); $j++){
        $aux = "`".$tablas[$i]["campos"][$j]["nombre"]."` ".$tablas[$i]["campos"][$j]["tipo"];
        $aux .= ($tablas[$i]["campos"][$j]["null"] == 0) ? " NOT NULL" : " NULL" ;
        $aux_t[] = $aux;
    }
    $tabla .= implode(",", $aux_t).") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;";
    $tables[] = $tabla;
    $tables_name[] = $tablas[$i]["nombre"];
    
}
for($i=0; $i<count($tablas); $i++){

    $key = "ALTER TABLE `".$tablas[$i]["nombre"]."`";
    $aux_t = [];
    $aux_c = [];
    $pk = [];
    $c = 1;

    for($j=0; $j<count($tablas[$i]["campos"]); $j++){
        if(isset($tablas[$i]["campos"][$j]['pk'])){
            $pk[] = "`".$tablas[$i]["campos"][$j]["nombre"]."`";
        }
        if(isset($tablas[$i]["campos"][$j]['k'])){
            $aux_t[] = " ADD KEY `".$tablas[$i]["campos"][$j]["nombre"]."` (`".$tablas[$i]["campos"][$j]["nombre"]."`)";
            if(isset($tablas[$i]["campos"][$j]['kt']) && isset($tablas[$i]["campos"][$j]['kc'])){
                $aux_c[] = " ADD CONSTRAINT `".$tablas[$i]["nombre"]."_ibfk_".$c."` FOREIGN KEY (`".$tablas[$i]["campos"][$j]["nombre"]."`) REFERENCES `".$tablas[$tablas[$i]["campos"][$j]['kt']]["nombre"]."` (`".$tablas[$tablas[$i]["campos"][$j]['kt']]["campos"][$tablas[$i]["campos"][$j]['kc']]["nombre"]."`) ON DELETE CASCADE ON UPDATE CASCADE";
                $c++;
            }
        }
        if(isset($tablas[$i]["campos"][$j]['ai'])){
            $ai = $key;
            $ai .= " MODIFY `".$tablas[$i]["campos"][$j]["nombre"]."` ".$tablas[$i]["campos"][$j]["tipo"]."";
            $ai .= ($tablas[$i]["campos"][$j]["null"] == 0) ? " NOT NULL" : " NULL" ;
            $ai .= " AUTO_INCREMENT, AUTO_INCREMENT=1;";
            $ais[] = $ai;
        }
    }
    
    if(count($aux_t) > 0 || count($pk) > 0){
        $aux_key = $key;
        if(count($pk) > 0){
            $aux_key .= " ADD PRIMARY KEY (".implode(",", $pk).")";
            if(count($aux_t) > 0){
                $aux_key .= ",";
            }
        }
        if(count($aux_t) > 0){
            $aux_key .= implode(",", $aux_t);
        }
        $keys[] = $aux_key;
    }
    if(count($aux_c) > 0){
        $cons[] = $key.implode(",", $aux_c).";";
    }

}

if($con->query("CREATE DATABASE IF NOT EXISTS ".$db_database[0]." CHARACTER SET UTF8 COLLATE UTF8_GENERAL_CI")){
    echo "BASE CREADA: ".$db_database[0]."<br/><br/>TABLAS<br/><br/>";
    $con->select_db($db_database[0]);
    for($i=0; $i<count($tables); $i++){
        if($con->query($tables[$i])){
            echo "Tabla creada: ".$tables_name[$i]."<br/>";
        }else{
            echo "<strong>ERROR: ".$tables_name[$i]." NO FUE CREADA</strong> => ".$con->error."<br/>";
        }
    }
    echo "<br/><br/>KEYS<br/><br/>";
    for($i=0; $i<count($keys); $i++){
        echo $keys[$i]."<br/>";
        if($con->query($keys[$i])){
            echo "ALTER CREADO: <br/>";
        }else{
            echo "<strong>ERROR: KEY </strong> => ".$con->error."<br/>";
        }
    }
    echo "<br/><br/>AUTOINCREMENTS<br/><br/>";
    for($i=0; $i<count($ais); $i++){
        echo $ais[$i]."<br/>";
        if($con->query($ais[$i])){
            echo "ALTER CREADO: <br/>";
        }else{
            echo "<strong>ERROR: AUTO</strong> => ".$con->error."<br/>";
        }
    }

    echo "<br/><br/>FILTROS<br/><br/>";
    for($i=0; $i<count($cons); $i++){
        echo $cons[$i]."<br/>";
        if($con->query($cons[$i])){
            echo "ALTER CREADO: <br/>";
        }else{
            echo "<strong>ERROR: FILTRO</strong> => ".$con->error."<br/>";
        }
    }

    echo "<br/><br/>INSERT<br/><br/>";
    for($i=0; $i<count($tablas); $i++){

        $campos = [];
        $matriz = [];

        for($j=0; $j<count($tablas[$i]["campos"]); $j++){
            $cant = count($tablas[$i]["campos"][$j]["values"]);
            if($cant > 0){
                $campos[] = $tablas[$i]["campos"][$j]["nombre"];
                for($k=0; $k<$cant; $k++){
                    $matriz[$k][] = "'".$tablas[$i]["campos"][$j]["values"][$k]."'";
                }
            }
        }

        for($j=0; $j<count($matriz); $j++){
            $sql = "INSERT INTO ".$tablas[$i]["nombre"]." (".implode(", ", $campos).") VALUES (".implode(", ", $matriz[$j]).")";
            echo $sql."<br/>";
            if($con->query($sql)){
                echo "INSERTAR <br/>";
            }else{
                echo "<strong>ERROR: INSERT</strong> => ".$con->error."<br/>";
            }
        }

    }

}else{
    echo "ERROR CREAR BASE: ".$con->error."<br/>";
}

/*

*/