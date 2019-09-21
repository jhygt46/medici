<?php
session_start();

require_once DIR."db.php";
require_once DIR_BASE."config/config.php";
date_default_timezone_set("America/Santiago");

class Core{
    
    public $con = null;
    public $id_usr = null;
    public $eliminado = 0;
    public $tipo = 0;

    public function __construct(){
        
        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        $this->id_usr = (isset($_SESSION['user']['info']['id_usr'])) ? $_SESSION['user']['info']['id_usr'] : 0 ;
        $this->tipo = (isset($_SESSION['user']['info']['tipo'])) ? $_SESSION['user']['info']['tipo'] : 0 ;

    }
    public function transform($id){
        if($this->tipo == 1){
            $this->id_usr = $id;
            $_SESSION['user']['info']['id_usr'] = $id;
        }
    }
    public function get_data(){

        $arr_usuarios = [];
        $arr_servicios = [];
        $arr_excepciones = [];
        $arr_rangos = [];
        $arr_sucursales = [];

        if($sqlsu = $this->con->prepare("SELECT t1.id_suc, t1.nombre, t1.direccion FROM sucursal t1, rangos t2 WHERE t2.id_suc=t1.id_suc AND t1.eliminado='0'")){
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
        if($sqlra = $this->con->prepare("SELECT t1.id_ran, t1.dia_ini, t1.hora_ini, t1.dia_fin, t1.hora_fin, t1.id_suc, t1.id_usr, t2.id_ser FROM rangos t1, rango_servicios t2 WHERE t1.id_ran=t2.id_ran AND t1.eliminado='0'")){
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
        if($sqlex = $this->con->prepare("SELECT t1.id_exc, t1.fecha, t1.hora_ini, t1.hora_fin, t1.id_usr, t1.id_suc, t2.id_ser FROM excepciones t1, excepcion_servicios t2 WHERE t1.id_exc=t2.id_exc AND t1.eliminado='0'")){
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
        if($sql = $this->con->prepare("SELECT t3.id_usr, t3.nombre as doctor_nombre, t3.titulo, t3.html_descripcion, t3.imagen as imagen_user, t1.imagen as imagen_serv, t1.id_ser, t1.nombre as servicio_nombre, t1.descripcion as servicio_descripcion, t2.tiempo_min, t2.html_1, t2.html_2, t2.precio FROM servicios t1, servicio_usuarios t2, usuarios t3 WHERE t1.id_ser=t2.id_ser AND t2.id_usr=t3.id_usr AND t1.eliminado='0' AND t2.eliminado='0' AND t3.eliminado='0'")){
            if($sql->execute()){
                $result = $sql->get_result();
                while($row = $result->fetch_assoc()){

                    if(!in_array($row['id_usr'], $arr_usuarios)){

                        $arr_usuarios[] = $row['id_usr'];
                        $user['id'] = $row['id_usr'];
                        $user['nombre'] = $row['doctor_nombre'];
                        $user['imagen'] = $row['imagen_user'];
                        $user['titulo'] = $row['titulo'];
                        $user['html_descripcion'] = $row['html_descripcion'];
                        $user['min'] = 30;
                        $user['lista_servicios'] = [];

                        $aux_serv['id'] = $row['id_ser'];
                        $aux_serv['nombre'] = $row['servicio_nombre'];
                        $aux_serv['imagen'] = $row['imagen_serv'];
                        $aux_serv['descripcion'] = $row['servicio_descripcion'];
                        $aux_serv['tiempo_min'] = $row['tiempo_min'];
                        $aux_serv['precio'] = $row['precio'];
                        $aux_serv['html_1'] = $row['html_1'];
                        $aux_serv['html_2'] = $row['html_2'];

                        $user['lista_servicios'][] = $aux_serv;
                        unset($aux_serv);

                        if($sqlh = $this->con->prepare("SELECT t1.id_hor, t1.fecha, t3.tiempo_min as tiempo FROM horas t1, servicios t2, servicio_usuarios t3  WHERE t1.id_usr=? AND t1.id_ser=t2.id_ser AND t1.id_ser=t3.id_ser AND t3.id_usr=? AND t1.eliminado='0' ORDER BY t1.fecha")){
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
                                $aux_serv['imagen'] = $row['imagen_serv'];
                                $aux_serv['html_1'] = $row['html_1'];
                                $aux_serv['html_2'] = $row['html_2'];
                                
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
                        $serv['imagen'] = $row['imagen_serv'];
                        $serv['lista_doctores'] = [];

                        $aux_user['id'] = $row['id_usr'];
                        $aux_user['nombre'] = $row['doctor_nombre'];
                        $aux_user['tiempo_min'] = $row['tiempo_min'];
                        $aux_user['precio'] = $row['precio'];
                        $aux_user['html_1'] = $row['html_1'];
                        $aux_user['html_2'] = $row['html_2'];

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
            $r .= $s{rand(0, strlen($s) - 1)};
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

        //$res = $_POST["g-recaptcha-response"]; 
        //if(isset($res) && $res){
            
            //$secret = "6Lf8j3sUAAAAAP6pYvdgk9qiWoXCcKKXGsKFQXH4";
            //$v = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$_POST["g-recaptcha-response"]."&remoteip=".$_SERVER["REMOTE_ADDR"]); 
            //$data = json_decode(($v)); 
            //if($data->{'success'}){
                
                $correo = $_POST["telefono"];

                if(filter_var($correo, FILTER_VALIDATE_EMAIL)){

                    $id_ser = $_POST["id_ser"];
                    $id_usr = $_POST["id_usr"];
                    $rut = $_POST["rut"];
                    $nombre = $_POST["nombre"];
                    $telefono = $_POST["telefono"];
                    $id_suc = 1;

                    if($sql = $this->con->prepare("SELECT * FROM servicio_usuarios t1, usuarios t2 WHERE t1.id_ser=? AND t1.id_usr=? AND t1.id_usr=t2.id_usr")){
                        if($sql->bind_param("ii", $id_ser, $id_usr)){
                            if($sql->execute()){
                                
                                $res = $sql->get_result();
                                if($res->{"num_rows"} == 1){

                                    $aux_ser = $res->fetch_all(MYSQLI_ASSOC)[0];
                                    $tiempo = $aux_ser["tiempo_min"];
                                    $correo_doc = $aux_ser["correo"];
                                    $precio = $aux_ser["precio"];
                                    $fecha = $_POST["f_fec"];
                                    $hora = $_POST["f_hor"];

                                    $now_ini = intval($hora);
                                    $now_fin = $now_ini + $tiempo;

                                    if(intval($now_ini/60) < 10){
                                        $str_hr1 = "0".intval($now_ini/60);
                                    }else{
                                        $str_hr1 = intval($now_ini/60);
                                    }
                                    if(intval($now_ini%60) < 10){
                                        $str_hr2 = "0".intval($now_ini%60);
                                    }else{
                                        $str_hr2 = intval($now_ini%60);
                                    }
                                    
                                    $str_hora = $str_hr1.":".$str_hr2.":00";

                                    if($sqlexc = $this->con->prepare("SELECT * FROM excepciones t1, excepcion_servicios t2 WHERE t1.id_usr=? AND t1.fecha=? AND t1.id_exc=t2.id_exc AND t2.id_ser=?")){
                                        if($sqlexc->bind_param("isi", $id_usr, $fecha, $id_ser)){
                                            if($sqlexc->execute()){

                                                $resexc = $sqlexc->get_result();
                                                if($resexc->{"num_rows"} == 0){

                                                    $dia = date("w", strtotime($fecha));
                                                    if($sqlran = $this->con->prepare("SELECT * FROM rangos t1, rango_servicios t2 WHERE t1.id_usr=? AND t1.dia_ini<=? AND t1.dia_fin>=? AND t1.id_ran=t2.id_ran AND t2.id_ser=?")){
                                                        if($sqlran->bind_param("iiii", $id_usr, $dia, $dia, $id_ser)){
                                                            if($sqlran->execute()){
                                                                
                                                                $resran = $sqlran->get_result();
                                                                while($row = $resran->fetch_assoc()){
                                                                    
                                                                    $hora_ini = explode(":", $row["hora_ini"]);
                                                                    $hora_fin = explode(":", $row["hora_fin"]);

                                                                    $h_ini = intval($hora_ini[0]) * 60 + intval($hora_ini[1]);
                                                                    $h_fin = intval($hora_fin[0]) * 60 + intval($hora_fin[1]);
                                                                    
                                                                    $data["ni"][] = $now_ini;
                                                                    $data["nf"][] = $now_fin;
                                                                    $data["hi"][] = $h_ini;
                                                                    $data["hf"][] = $h_fin;

                                                                    if($now_ini >= $h_ini && $now_fin <= $h_fin){

                                                                        $data['ran_dentro'] = 1;
                                                                        if($this->insertar_horas($id_usr, $fecha, $now_ini, $now_fin, $h_ini, $h_fin)){
                                                                            
                                                                            $fi = strtotime($fecha." ".$str_hora);
                                                                            $fi_f = $fi + ($tiempo * 60);
                                                                            $code = $this->getrandstring(32);

                                                                            $sqli = $this->con->prepare("INSERT INTO horas (fecha, fecha_f, tiempo_min, precio, eliminado, id_ser, id_usr, id_suc, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                                                            $sqli->bind_param("ssiiiiiis", date("Y-m-d H:i:s", $fi), date("Y-m-d H:i:s", $fi_f), $tiempo, $precio, $this->eliminado, $id_ser, $id_usr, $id_suc, $code);
                                                                            if($sqli->execute()){

                                                                                $send['accion'] = "reserva";
                                                                                $send['rut'] = $rut;
                                                                                $send['nombre'] = $nombre;
                                                                                $send['correo'] = $correo;
                                                                                $send['telefono'] = $telefono;
                                                                                $send['mensaje'] = $mensaje;
                                                                                $send['code'] = $code;
                                                                                $send['correo_doc'] = $correo_doc;

                                                                                $ch = curl_init();
                                                                                curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_medici');
                                                                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                                                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                                                                                $resp = json_decode(curl_exec($ch));
                                                                                curl_close($ch);

                                                                                $id = $this->con->insert_id;
                                                                                header("Location: http://35.225.100.155/?status=1");

                                                                            }else{ $data["err"] = "Error: 1"; }
                                                                            
                                                                        }else{ $data["err"] = "Error: 2"; }

                                                                    }else{ $data["err"] = "Error: 3"; }

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
                                                            if($this->insertar_horas($id_usr, $fecha, $now_ini, $now_fin, $h_ini, $h_fin)){
                                                                
                                                                $fi = strtotime($fecha." ".$str_hora);
                                                                $fi_f = $fi + $tiempo;
                                                                $code = $this->getrandstring(32);

                                                                $sqli = $this->con->prepare("INSERT INTO horas (fecha, fecha_f, tiempo_min, precio, eliminado, id_ser, id_usr, id_suc, code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                                                $sqli->bind_param("ssiiiiiis", date("Y-m-d H:i:s", $fi), date("Y-m-d H:i:s", $fi_f), $tiempo, $precio, $this->eliminado, $id_ser, $id_usr, $id_suc, $code);
                                                                if($sqli->execute()){

                                                                    $send['accion'] = "reserva";
                                                                    $send['rut'] = $rut;
                                                                    $send['nombre'] = $nombre;
                                                                    $send['correo'] = $correo;
                                                                    $send['telefono'] = $telefono;
                                                                    $send['mensaje'] = $mensaje;
                                                                    $send['code'] = $code;
                                                                    $send['correo_doc'] = $correo_doc;

                                                                    $ch = curl_init();
                                                                    curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_medici');
                                                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                                                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                                                                    $resp = json_decode(curl_exec($ch));
                                                                    curl_close($ch);

                                                                    $id = $this->con->insert_id;
                                                                    header("Location: http://35.225.100.155/?status=1");
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
    public function insertar_horas($id_usr, $fecha, $now_ini, $now_fin, $min_ran, $max_ran){

        if($sql = $this->con->prepare("SELECT * FROM horas WHERE id_usr=? AND DATE(fecha)=?")){
            if($sql->bind_param("is", $id_usr, $fecha)){
                if($sql->execute()){

                    $res = $sql->get_result();
                    $horas = $res->fetch_all(MYSQLI_ASSOC);
                    $count_hrs = count($horas);

                    if($count_hrs == 0){
                        if($now_ini >= $min_ran && $now_fin <= $max_ran){
                            return true;
                        }else{
                            return false;
                        }
                    }else{
                        for($i=0; $i<$count_hrs; $i++){
                            if($i == 0){
                                $fe_ini = $this->get_horas_fechas($horas[$i]['fecha']);
                                if($now_ini >= $min_ran && $now_fin <= $fe_ini){
                                    return true;
                                }
                            }
                            if($i > 0){
                                $antf = $this->get_horas_fechas($horas[$i-1]['fecha_f']);
                                $acti = $this->get_horas_fechas($horas[$i]['fecha']);
                                if($now_ini >= $antf && $now_fin <= $acti){
                                    return true;
                                }
                            }
                            if($i == $count_hrs - 1){
                                $fe_fin = $this->get_horas_fechas($horas[$i]['fecha_f']);
                                if($now_ini >= $fecha_fin && $now_fin <= $max_ran){
                                    return true;
                                }
                                
                            }
                        }
                        return false;
                    }

                }else{ return false; }
            }else{ return false; }
        }else{ return false; }

    }
    private function get_horas_fechas($fecha){

        $aux = explode(" ", $fecha);
        $horas = explode(":", $aux[1]);
        return intval($horas[0]) * 60 + intval($horas[1]);

    }
    public function contacto(){

        $res = $_POST["token_contacto"]; 
        if(isset($res) && $res){
            
            $secret = "6Lfor7kUAAAAAH-BQ5sqjnCyvBlBWSgNZ-ec8rx0";
            $v = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$res."&remoteip=".$_SERVER["REMOTE_ADDR"]); 
            $data = json_decode(($v)); 
            if($data->{'success'}){
                
                $correo = $_POST["correo"];

                if(filter_var($correo, FILTER_VALIDATE_EMAIL)){

                    $send['accion'] = "contacto";
                    $send['correo'] = $correo;
                    $send['nombre'] = $_POST["nombre"];
                    $send['asunto'] = $_POST["asunto"];
                    $send['mensaje'] = $_POST["mensaje"];
                    /*
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_medici');
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                    $resp = json_decode(curl_exec($ch));
                    curl_close($ch);
                    */
                    $data['op'] = 1;
                    $data['msg'] = "Contacto";

                }else{

                    $data['op'] = 2;
                    $data['msg'] = "Correo invalido";

                }

            }

        }
        return $data;
    }
    public function get_sucursales(){

        if($sql = $this->con->prepare("SELECT * FROM sucursal WHERE eliminado=?")){
            if($sql->bind_param("i", $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_sucursal($id){
        
        if($sql = $this->con->prepare("SELECT * FROM sucursal WHERE id_suc=? AND eliminado=?")){
            if($sql->bind_param("ii", $id, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_horas_fecha($fecha){

        if($sql = $this->con->prepare("SELECT * FROM horas t1, servicios t2 WHERE t1.id_usr=? AND DATE(t1.fecha)=? AND t1.eliminado=? AND t1.id_ser=t2.id_ser ORDER BY t1.fecha")){
            if($sql->bind_param("isi", $this->id_usr, $fecha, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_fechas_horas(){

        $fecha = date("Y-m-d", time() - 60*60*24);
        if($sql = $this->con->prepare("SELECT DISTINCT DATE(fecha) FROM horas WHERE id_usr=? AND fecha>? AND eliminado=? ORDER BY fecha")){
            if($sql->bind_param("isi", $this->id_usr, $fecha, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_servicios_rango($id_ran){

        if($sql = $this->con->prepare("SELECT * FROM rango_servicios t1, servicios t2 WHERE t1.id_ran=? AND t1.id_ser=t2.id_ser")){
            if($sql->bind_param("i", $id_ran)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_fecha($fecha){

        $dia = date('w', strtotime($fecha));
        if($sql = $this->con->prepare("SELECT * FROM rangos WHERE dia_ini>=? AND dia_fin<=?")){
            if($sql->bind_param("ii", $dia, $dia)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }


    }
    public function get_servicio($id_ser){

        if($sql = $this->con->prepare("SELECT * FROM servicios WHERE id_ser=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_ser, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_medicos(){

        if($sql = $this->con->prepare("SELECT * FROM usuarios WHERE eliminado=?")){
            if($sql->bind_param("i", $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_medico($id_usr){

        if($sql = $this->con->prepare("SELECT * FROM usuarios WHERE id_usr=? AND eliminado=?")){
            if($sql->bind_param("ii", $id_usr, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_todos_servicios(){

        if($sql = $this->con->prepare("SELECT * FROM servicios WHERE eliminado=?")){
            if($sql->bind_param("i", $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_servicios(){

        if($sql = $this->con->prepare("SELECT * FROM servicios t1, servicio_usuarios t2 WHERE t2.id_usr=? AND t2.id_ser=t1.id_ser AND t2.eliminado=?")){
            if($sql->bind_param("ii", $this->id_usr, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_no_servicios(){

        if($sql = $this->con->prepare("SELECT * FROM servicios WHERE id_ser NOT IN (SELECT id_ser FROM servicio_usuarios WHERE id_usr=? AND eliminado=?) AND eliminado=?")){
            if($sql->bind_param("iii", $this->id_usr, $this->eliminado, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_no_servicios_2($id_ser){

        if($sql = $this->con->prepare("SELECT * FROM servicios WHERE (id_ser NOT IN (SELECT id_ser FROM servicio_usuarios WHERE id_usr=? AND eliminado=?) OR id_ser=?) AND eliminado=?")){
            if($sql->bind_param("iiii", $this->id_usr, $this->eliminado, $id_ser, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_no_servicios_3($id_ser){

        if($sql = $this->con->prepare("SELECT * FROM servicios WHERE id_ser=?")){
            if($sql->bind_param("i", $id_ser)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_servicio_usuario($id_ser){

        if($sql = $this->con->prepare("SELECT * FROM servicio_usuarios WHERE id_ser=? AND id_usr=? AND eliminado=?")){
            if($sql->bind_param("iii", $id_ser, $this->id_usr, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function exp_servicios($id){
        if($sql = $this->con->prepare("SELECT id_ser FROM excepcion_servicios WHERE id_exc=?")){
            if($sql->bind_param("i", $id)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }
    }
    public function get_excepcion($id){
        if($sql = $this->con->prepare("SELECT * FROM excepciones WHERE id_exc=? AND id_usr=? AND eliminado=?")){
            if($sql->bind_param("iii", $id, $this->id_usr, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }
    }
    public function get_todas_excepciones($fecha){
        if($sql = $this->con->prepare("SELECT * FROM excepciones WHERE id_usr=? AND fecha=? AND eliminado=? ORDER BY fecha")){
            if($sql->bind_param("isi", $this->id_usr, $fecha, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }
    }
    public function get_excepciones(){

        $fecha = date("Y-m-d", time() - 60*60*24);
        if($sql = $this->con->prepare("SELECT DISTINCT fecha FROM excepciones WHERE id_usr=? AND fecha>? AND eliminado=? ORDER BY fecha")){
            if($sql->bind_param("isi", $this->id_usr, $fecha, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_rangos(){

        if($sql = $this->con->prepare("SELECT * FROM rangos WHERE id_usr=? AND eliminado=?")){
            if($sql->bind_param("ii", $this->id_usr, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    public function get_rango($id_ran){
        
        if($sql = $this->con->prepare("SELECT * FROM rangos WHERE id_usr=? AND id_ran=? AND eliminado=?")){
            if($sql->bind_param("iii", $this->id_usr, $id_ran, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC)[0];
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }
        
    }

}