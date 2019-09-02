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
        $this->id_user = (isset($_SESSION['user']['info']['id_user'])) ? $_SESSION['user']['info']['id_user'] : 0 ;
        $this->tipo = (isset($_SESSION['user']['info']['tipo'])) ? $_SESSION['user']['info']['admin'] : 0 ;

    }
    public function process(){
        
        if($this->id_user > 0){
            if($_POST['accion'] == "crear_servicio"){
                return $this->crear_servicio();
            }
            if($_POST['accion'] == "eliminar_servicio"){
                return $this->eliminar_servicio();
            }
        }

    }
    private function registrar($id_des, $id_loc, $id_gir, $txt){

        $sqlipd = $this->con->prepare("INSERT INTO seguimiento (id_des, id_user, id_loc, id_gir, fecha, txt) VALUES (?, ?, ?, ?, now(), ?)");
        $sqlipd->bind_param("iiiis", $id_des, $this->id_user, $id_loc, $id_gir, $txt);
        $sqlipd->execute();
        $sqlipd->close();

    }
    private function crear_servicio(){
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        if($this->tipo == 1){
            
            $sql = $this->con->prepare("SELECT id_gir FROM giros WHERE dominio=?");
            $sql->bind_param("s", $dominio);
            $sql->execute();
            $res = $sql->get_result();
            $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                
            if($id == 0){

                $code = bin2hex(openssl_random_pseudo_bytes(10));
                $sqligir = $this->con->prepare("INSERT INTO servicios (nombre, descripcion, eliminado) VALUES (?, ?, ?)");
                $sqligir->bind_param("ssi", $nombre, $descripcion, $this->eliminado);
                if($sqligir->execute()){

                    $info['op'] = 1;
                    $info['mensaje'] = "Servicio ingresado exitosamente";

                }else{

                    $info['op'] = 2;
                    $info['mensaje'] = "Error: B1";
                    //$this->registrar(6, 0, 0, 'Giros: err ingreso');

                }

            }
            if($id > 0){

                $sqlugi = $this->con->prepare("UPDATE servicios SET nombre=?, descripcion=? WHERE id_ser=? AND eliminado=?");
                $sqlugi->bind_param("ssii", $nombre, $descripcio, $id_ser, $this->eliminado);
                if($sqlugi->execute()){

                    $info['op'] = 1;
                    $info['mensaje'] = "Servicio modificado exitosamente";

                }else{

                    $info['op'] = 2;
                    $info['mensaje'] = "Error: Permisos A2";
                    //$this->registrar(6, 0, 0, 'update giros');

                }
                $sqlugi->close();

            }
            
        }else{

            $info['op'] = 2;
            $info['mensaje'] = "Error: F01";
            //$this->registrar(4, 0, 0, 'crear giro');

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
                //$this->registrar(6, 0, 0, 'borrar giro');

            }
            $sqlugi->close();
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Servicio ".$nombre." no pudo ser eliminado";
            //$this->registrar(4, 0, 0, 'crear giro');

        }

        return $info;
        
    }

}