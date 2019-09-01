<?php

require_once DIR."db.php";
require_once DIR_BASE."config/config.php";
date_default_timezone_set("America/Santiago");

class Core{
    
    public $con = null;

    public function __construct(){
        
        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);

    }
    public function get_data(){

        $arr_usuarios = [];
        $arr_servicios = [];
        $arr_excepciones = [];
        $arr_rangos = [];
        $arr_sucursales = [];

        if($sqlsu = $this->con->prepare("SELECT t1.id_suc, t1.nombre, t1.direccion FROM sucursal t1, rangos t2 WHERE t2.id_suc=t1.id_suc")){
            if($sqlsu->execute()){
                $result = $sqlsu->get_result();
                while($row = $result->fetch_assoc()){
                    if(!in_array($row['id_suc'], $arr_sucursales)){
                        $arr_sucursales[] = $row['id_suc'];
                        $aux_sucursal['id_suc'] = $row['id_suc'];
                        $aux_sucursal['nombre'] = $row['nombre'];
                        $aux_sucursal['direccion'] = $row['direccion'];
                        $data['sucursales'][] = $aux_sucursal;
                        unset($aux_sucursal);
                    }
                }
                $sqlsu->free_result();
                $sqlsu->close();
            }
        }
        if($sqlra = $this->con->prepare("SELECT t1.id_ran, t1.dia_ini, t1.hora_ini, t1.dia_fin, t1.hora_fin, t1.id_suc, t1.id_usr, t2.id_ser FROM rangos t1, rango_servicios t2 WHERE t1.id_ran=t2.id_ran")){
            if($sqlra->execute()){
                $result = $sqlra->get_result();
                while($row = $result->fetch_assoc()){
                    if(!in_array($row['id_ran'], $arr_rangos)){

                        $arr_rangos[] = $row['id_ran'];
                        $aux_rangos['dia_ini'] = $row['dia_ini'];
                        $aux_rangos['hora_ini'] = $row['hora_ini'];
                        $aux_rangos['dia_fin'] = $row['dia_fin'];
                        $aux_rangos['hora_fin'] = $row['hora_fin'];
                        $aux_rangos['id_suc'] = $row['id_suc'];
                        $aux_rangos['id_usr'] = $row['id_usr'];
                        $aux_rangos['lista_servicios'][] = $row['id_ser'];
                        $data['rangos'][] = $aux_rangos;
                        unset($aux_rangos);

                    }else{

                        for($i=0; $i<count($arr_rangos); $i++){
                            if($arr_rangos[$i] == $row['id_ran']){
                                $data['rangos'][$i]['lista_servicios'][] = $row['id_ser'];
                            }
                        }

                    }
                }
                $sqlra->free_result();
                $sqlra->close();
            }
        }
        if($sqlex = $this->con->prepare("SELECT t1.id_exc, t1.fecha, t1.hora_ini, t1.hora_fin, t1.id_usr, t1.id_suc, t2.id_ser FROM excepciones t1, excepcion_servicios t2 WHERE t1.id_exc=t2.id_exc")){
            if($sqlex->execute()){
                $result = $sqlex->get_result();
                while($row = $result->fetch_assoc()){
                    if(!in_array($row['id_exc'], $arr_excepciones)){
                        $arr_excepciones[] = $row['id_exc'];
                        $aux_excepcion['fecha'] = $row['fecha'];
                        $aux_excepcion['hora_ini'] = $row['hora_ini'];
                        $aux_excepcion['hora_fin'] = $row['hora_fin'];
                        $aux_excepcion['id_usr'] = $row['id_usr'];
                        $aux_excepcion['id_suc'] = $row['id_suc'];
                        $aux_excepcion['lista_servicios'][] = $row['id_ser'];
                        $data['excepciones'][] = $aux_excepcion;
                        unset($aux_excepcion);
                    }else{
                        for($i=0; $i<count($arr_excepciones); $i++){
                            if($arr_excepciones[$i] == $row['id_exc']){
                                $data['excepciones'][$i]['lista_servicios'][] = $row['id_ser'];
                            }
                        }
                    }
                }
                $sqlex->free_result();
                $sqlex->close();
            }
        }
        if($sql = $this->con->prepare("SELECT t3.id_usr, t3.nombre as doctor_nombre, t1.id_ser, t1.nombre as servicio_nombre, t1.descripcion as servicio_descripcion, t2.tiempo_min, t2.precio FROM servicios t1, servicio_usuarios t2, usuarios t3 WHERE t1.id_ser=t2.id_ser AND t2.id_usr=t3.id_usr")){
            if($sql->execute()){
                $result = $sql->get_result();
                while($row = $result->fetch_assoc()){

                    if(!in_array($row['id_usr'], $arr_usuarios)){

                        $arr_usuarios[] = $row['id_usr'];
                        $user['id'] = $row['id_usr'];
                        $user['nombre'] = $row['doctor_nombre'];
                        $user['min'] = 30;
                        $user['lista_servicios'] = [];

                        $aux_serv['id'] = $row['id_ser'];
                        $aux_serv['nombre'] = $row['servicio_nombre'];
                        $aux_serv['descripcion'] = $row['servicio_descripcion'];
                        $aux_serv['tiempo_min'] = $row['tiempo_min'];
                        $aux_serv['precio'] = $row['precio'];

                        $user['lista_servicios'][] = $aux_serv;
                        unset($aux_serv);

                        if($sqlh = $this->con->prepare("SELECT t1.id_hor, t1.fecha, t3.tiempo_min as tiempo FROM horas t1, servicios t2, servicio_usuarios t3  WHERE t1.id_usr=? AND t1.id_ser=t2.id_ser AND t1.id_ser=t3.id_ser AND t3.id_usr=? ORDER BY t1.fecha")){
                            $sqlh->bind_param("ii", $row['id_usr'], $row['id_usr']);
                            if($sqlh->execute()){
                                $resulth = $sqlh->get_result();
                                while($rowh = $resulth->fetch_assoc()){
                                    $user['horas'][] = $rowh;
                                }
                            }
                        }

                        $data['doctores'][] = $user;
                        unset($user);

                    }else{
                        for($i=0; $i<count($data['doctores']); $i++){
                            if($data['doctores'][$i]['id'] == $row['id_usr']){

                                $aux_serv['id'] = $row['id_ser'];
                                $aux_serv['nombre'] = $row['servicio_nombre'];
                                $aux_serv['descripcion'] = $row['servicio_descripcion'];
                                $aux_serv['tiempo_min'] = $row['tiempo_min'];
                                $aux_serv['precio'] = $row['precio'];
                                
                                $data['doctores'][$i]['lista_servicios'][] = $aux_serv;
                                unset($aux_serv);

                            }
                        }
                    }
                    if(!in_array($row['id_ser'], $arr_servicios)){

                        $arr_servicios[] = $row['id_ser'];

                        $serv['id'] = $row['id_ser'];
                        $serv['nombre'] = $row['servicio_nombre'];
                        $serv['descripcion'] = $row['servicio_descripcion'];
                        $serv['lista_doctores'] = [];

                        $aux_user['id'] = $row['id_usr'];
                        $aux_user['nombre'] = $row['doctor_nombre'];
                        $aux_user['tiempo_min'] = $row['tiempo_min'];
                        $aux_user['precio'] = $row['precio'];

                        $serv['lista_doctores'][] = $aux_user;
                        $data['servicios'][] = $serv;

                        unset($serv);

                    }else{
                        for($i=0; $i<count($data['servicios']); $i++){
                            if($data['servicios'][$i]['id'] == $row['id_ser']){

                                $aux_user['id'] = $row['id_usr'];
                                $aux_user['nombre'] = $row['doctor_nombre'];
                                $aux_user['tiempo_min'] = $row['tiempo_min'];
                                $aux_user['precio'] = $row['precio'];

                                $data['servicios'][$i]['lista_doctores'][] = $aux_user;
                                unset($aux_user);

                            }
                        }
                    }
                }
                $sql->free_result();
                $sql->close();
            }
        }
        file_put_contents(DIR."js/info.js", "var data=".json_encode($data).";");

    }
    public function getrandstring($n){
        
        $r = "";
        $s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$n; $i++){
            $r .= $s{$i};
        }
        return $r;

    }
    public function process_horas($horas){

        $arr = [];
        for($i=0; $i<count($horas); $i++){

            $aux['h1'] = strtotime($horas[$i]['fecha']);
            $aux['h2'] = $aux['h1'] + $horas[$i]['tiempo'] * 60;
            $aux['date'] = date("d-m-Y H:i:s", $aux['h1']);
            if(count($arr) == 0){
                $arr[] = $aux;
            }else{
                $aux['aux1'] = $arr[count($arr)-1]['h2'] - $aux['h1'];
                if($arr[count($arr)-1]['h2'] <= $aux['h1']){
                    $arr[count($arr)-1]['h2'] = $arr[count($arr)-1]['h2'] + $horas[$i]['tiempo'] * 60;
                }else{
                    $arr[] = $aux;
                }
            }

        }
        return $arr;
    }
    public function reservar_hora(){

        /*
        $data['op'] = 1;
        $data['rut'] = $_POST["rut"];
        $data['nombre'] = $_POST["nombre"];
        $data['correo'] = $_POST["correo"];
        $data['telefono'] = $_POST["telefono"];
        $data['id_ser'] = $_POST["id_ser"];
        $data['id_usr'] = $_POST["id_usr"];
        $data['fecha'] = $_POST["f_fec"];
        $data['hora'] = $_POST["f_hor"];
        return $data;
        */

        //$res = $_POST["g-recaptcha-response"]; 
        //if(isset($res) && $res){
            
            //$secret = "6Lf8j3sUAAAAAP6pYvdgk9qiWoXCcKKXGsKFQXH4";
            //$v = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$_POST["g-recaptcha-response"]."&remoteip=".$_SERVER["REMOTE_ADDR"]); 
            //$data = json_decode(($v)); 
            //if($data->{'success'}){
                
                $correo = $_POST["correo"];

                if(filter_var($correo, FILTER_VALIDATE_EMAIL)){

                    $id_ser = $_POST["id_ser"];
                    $id_usr = $_POST["id_usr"];

                    if($sql = $this->con->prepare("SELECT * FROM servicio_usuarios WHERE id_ser=? AND id_usr=?")){
                        if($sql->bind_param("ii", $id_ser, $id_usr)){
                            if($sql->execute()){
                                
                                $res = $sql->get_result();
                                if($res->{"num_rows"} == 1){

                                    $aux_ser = $res->fetch_all(MYSQLI_ASSOC)[0];
                                    $tiempo = $aux_ser["tiempo_min"];
                                    $valor = $aux_ser["valor"];
                                    $fecha = $_POST["f_fec"];
                                    $hora = $_POST["f_hor"];

                                    $now_ini = intval($hora);
                                    $now_fin = $now_ini + $tiempo;

                                    if($sqlexc = $this->con->prepare("SELECT * FROM excepciones t1, excepcion_servicios t2 WHERE t1.id_usr=? AND t1.fecha=? AND t1.id_exc=t2.id_exc AND t2.id_ser=?")){
                                        if($sqlexc->bind_param("isi", $id_usr, $fecha, $id_ser)){
                                            if($sqlexc->execute()){

                                                $resexc = $sqlexc->get_result();
                                                if($resexc->{"num_rows"} == 0){

                                                    $dia = date("w", strtotime($fecha));
                                                    if($sqlran = $this->con->prepare("SELECT * FROM rangos t1, rango_servicios t2 WHERE t1.id_usr=? AND t1.dia_ini>=? AND t1.dia_fin<=? AND t1.id_ran=t2.id_ran AND t2.id_ser=?")){
                                                        if($sqlran->bind_param("iiii", $id_usr, $dia, $dia, $id_ser)){
                                                            if($sqlran->execute()){
                                                                
                                                                $resran = $sqlran->get_result();
                                                                while($row = $resran->fetch_assoc()){

                                                                    $hora_ini = explode(":", $row["hora_ini"]);
                                                                    $hora_fin = explode(":", $row["hora_fin"]);

                                                                    $h_ini = intval($hora_ini[0]) * 60 + intval($hora_ini[1]);
                                                                    $h_fin = intval($hora_fin[0]) * 60 + intval($hora_fin[1]);

                                                                    if($now_ini > $h_ini && $now_fin < $h_fin){
                                                                        $data['ran_dentro'] = 1;
                                                                    }

                                                                }
                                                            }else{}
                                                        }else{}
                                                    }else{}

                                                }
                                                if($resexc->{"num_rows"} > 0){
                                                    
                                                    while($row = $resexc->fetch_assoc()){
                                                        
                                                        $hora_ini = explode(":", $row["hora_ini"]);
                                                        $hora_fin = explode(":", $row["hora_fin"]);

                                                        $h_ini = intval($hora_ini[0]) * 60 + intval($hora_ini[1]);
                                                        $h_fin = intval($hora_fin[0]) * 60 + intval($hora_fin[1]);

                                                        if($now_ini > $h_ini && $now_fin < $h_fin){

                                                            $data['exc_dentro'] = 1;

                                                            if($sqlhor = $this->con->prepare("SELECT * FROM horas WHERE id_usr=? AND fecha_1<? AND fecha_2>?")){
                                                                if($sqlhor->bind_param("isi", $id_usr, $fecha)){
                                                                    if($sqlhor->execute()){

                                                                    }
                                                                }
                                                            }

                                                        }

                                                    }
                                                    
                                                }
                                                $sqlexc->free_result();
                                                $sqlexc->close();
                                                
                                            }else{}
                                        }else{}
                                    }else{}
                                }
                                if($res->{"num_rows"} == 0){
                                    // ERROR
                                }
                                
                            }else{}
                        }else{}
                    }else{}

                }else{
                    $data['op'] = 2;
                    $data['msg'] = "Correo invalido";
                }

            //}

        //}
        return $data;
    }
    public function get_no_servicios(){
        if($sqlsu = $this->con->prepare("SELECT * FROM servicios WHERE NOT IN (SELECT * FROM servicio_usuarios WHERE id_usr='1')")){
            if($sqlsu->bind_param("i", $id_loc)){
                if($sqlsu->execute()){
                    return $sqlsu->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{
                    return "HOLA MUNDO3";
                }
            }else{
                return "HOLA MUNDO2";
            }
        }else{
            return "HOLA MUNDO1";
        }
    }

}