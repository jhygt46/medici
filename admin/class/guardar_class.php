<?php
session_start();

if($_SERVER["HTTP_HOST"] == "localhost"){
    define("DIR_BASE", $_SERVER["DOCUMENT_ROOT"]."/");
    define("DIR", DIR_BASE."medici/");
}else{
    define("DIR_BASE", "/var/www/html/");
    define("DIR", DIR_BASE."medici/");
}

require_once DIR."db.php";
require_once DIR_BASE."config/config.php";

class Guardar{
    
    public $con = null;
    public $id_user = null;
    public $tipo = null;
    public $eliminado = 0;
    
    public function __construct(){
        
        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        $this->id_usr = (isset($_SESSION['user']['info']['id_usr'])) ? $_SESSION['user']['info']['id_usr'] : 0 ;
        $this->tipo = (isset($_SESSION['user']['info']['tipo'])) ? $_SESSION['user']['info']['tipo'] : 0 ;

    }
    public function process(){

        if($this->id_usr > 0){
            if($_POST['accion'] == "crear_servicio"){
                return $this->crear_servicio();
            }
            if($_POST['accion'] == "eliminar_servicio"){
                return $this->eliminar_servicio();
            }
            if($_POST['accion'] == "crear_medico"){
                return $this->crear_medico();
            }
            if($_POST['accion'] == "eliminar_medico"){
                return $this->eliminar_medico();
            }
            if($_POST['accion'] == "crear_servicio_usuario"){
                return $this->crear_servicio_usuario();
            }
        }

    }
    private function crear_servicio(){
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if($this->tipo == 1){
                
            if($id == 0){

                $sqligir = $this->con->prepare("INSERT INTO servicios (nombre, descripcion, eliminado) VALUES (?, ?, ?)");
                $sqligir->bind_param("ssi", $nombre, $descripcion, $this->eliminado);
                if($sqligir->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Servicio ingresado exitosamente";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: B1";
                }
                $sqligir->close();

            }
            if($id > 0){

                $sqlugi = $this->con->prepare("UPDATE servicios SET nombre=?, descripcion=? WHERE id_ser=? AND eliminado=?");
                $sqlugi->bind_param("ssii", $nombre, $descripcion, $id, $this->eliminado);
                if($sqlugi->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Servicio modificado exitosamente";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: Permisos A2";
                }
                $sqlugi->close();

            }
            
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: F01";
        }

        $info['reload'] = 1;
        $info['page'] = "ingresar_servicios.php";
        return $info;

    }
    private function eliminar_servicio(){

        $id_ser = $_POST['id'];
        $nombre = $_POST['nombre'];

        if($this->tipo == 1){

            $sqlugi = $this->con->prepare("UPDATE servicios SET eliminado='1' WHERE id_ser=?");
            $sqlugi->bind_param("i", $id_ser);
            if($sqlugi->execute()){

                $info['tipo'] = "success";
                $info['titulo'] = "Eliminado";
                $info['texto'] = "Servicio ".$nombre." Eliminado";
                $info['reload'] = 1;
                $info['page'] = "ingresar_servicios.php";

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Servicio ".$nombre." no pudo ser eliminado";

            }
            $sqlugi->close();
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Servicio ".$nombre." no pudo ser eliminado";

        }

        return $info;
        
    }
    private function crear_medico(){
        
        if($this->tipo == 1){
            
            $id = $_POST['id'];
            $nombre = $_POST['nombre'];
            $correo = $_POST['correo'];
            $tipo = $_POST['tipo'];

            if($id == 0){

                $sqlu = $this->con->prepare("SELECT * FROM usuarios WHERE correo=? AND eliminado=?");
                $sqlu->bind_param("si", $correo, $this->eliminado);
                $sqlu->execute();
                $res = $sqlu->get_result();

                if($res->{"num_rows"} == 0){

                    $sql = $this->con->prepare("INSERT INTO usuarios (nombre, correo, tipo, eliminado) VALUES (?, ?, ?, ?)");
                    $sql->bind_param("ssii", $nombre, $correo, $tipo, $this->eliminado);
                    if($sql->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Medico ingresado exitosamente";
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error: B1";
                    }
                    $sql->close();

                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: B2";
                }
                $sqlu->close();

            }
            if($id > 0){

                $sqlu = $this->con->prepare("SELECT id_usr FROM usuarios WHERE correo=? AND eliminado=?");
                $sqlu->bind_param("sii", $correo, $id, $this->eliminado);
                $sqlu->execute();
                $res = $sqlu->get_result();
                
                if($res->{"num_rows"} == 1){
                    $id_usr = $res->fetch_all(MYSQLI_ASSOC)[0]['id_usr'];
                    if($id_usr == $id){

                        $sql = $this->con->prepare("UPDATE usuarios SET nombre=?, correo=?, tipo=? WHERE id_usr=? AND eliminado=?");
                        $sql->bind_param("ssiii", $nombre, $correo, $tipo, $id, $this->eliminado);
                        if($sql->execute()){
                            $info['op'] = 1;
                            $info['mensaje'] = "Medico modificado exitosamente";
                        }else{
                            $info['op'] = 2;
                            $info['mensaje'] = "Error: Permisos e2";
                        }
                        $sql->close();

                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error: Permisos e2";
                    }

                }elseif($res->{"num_rows"} == 0){

                    $sql = $this->con->prepare("UPDATE usuarios SET nombre=?, correo=?, tipo=? WHERE id_usr=? AND eliminado=?");
                    $sql->bind_param("ssiii", $nombre, $correo, $tipo, $id, $this->eliminado);
                    if($sql->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Medico modificado exitosamente";
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "Error: Permisos A2";
                    }
                    $sql->close();

                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: Permisos A3";
                }

                $sqlu->close();
                
            }
            
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: F01";
        }

        $info['reload'] = 1;
        $info['page'] = "ingresar_medicos.php";
        return $info;

    }
    private function eliminar_medico(){

        $id_usr = $_POST['id'];
        $nombre = $_POST['nombre'];

        if($this->tipo == 1){

            $sqlugi = $this->con->prepare("UPDATE usuarios SET eliminado='1' WHERE id_usr=?");
            $sqlugi->bind_param("i", $id_usr);
            if($sqlugi->execute()){

                $info['tipo'] = "success";
                $info['titulo'] = "Eliminado";
                $info['texto'] = "Medico ".$nombre." Eliminado";
                $info['reload'] = 1;
                $info['page'] = "ingresar_medicos.php";

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Medico ".$nombre." no pudo ser eliminado";

            }
            $sqlugi->close();
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Medico ".$nombre." no pudo ser eliminado";

        }

        return $info;
        
    }
    private function crear_servicio_usuario(){

        $tiempo = $_POST['tiempo'];
        $precio = $_POST['precio'];
        $tipo = $_POST['tipo'];

        $sqlu = $this->con->prepare("SELECT * FROM servicio_usuarios WHERE id_ser=? AND id_usr=?");
        $sqlu->bind_param("ii", $tipo, $this->id_usr);
        $sqlu->execute();
        $res = $sqlu->get_result();

        if($res->{"num_rows"} == 0){

            $sql = $this->con->prepare("INSERT INTO servicio_usuarios (id_ser, id_usr, tiempo_min, precio, eliminado) VALUES (?, ?, ?, ?, ?)");
            $sql->bind_param("iiiii", $tipo, $this->id_usr, $tiempo, $precio, $this->eliminado);
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Servicio-Medico ingresado exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: B1";
            }

        }

        if($res->{"num_rows"} == 1){
            
            $sql = $this->con->prepare("UPDATE servicio_usuarios SET tiempo_min=?, precio=? WHERE id_ser=? AND id_usr=?");
            $sql->bind_param("iiii", $tiempo, $precio, $tipo, $this->id_usr);
            $sql->execute();
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Servicio-Medico modificado exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: B2";
            }

        }
        $info['reload'] = 1;
        $info['page'] = "mis_servicios.php";
        return $info;
        
    }

}