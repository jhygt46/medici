<?php

echo "CORE_CLASS<br/>";
echo $dir;
exit;

require_once "config.php";
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
        return $data;
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

}