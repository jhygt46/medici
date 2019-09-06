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
date_default_timezone_set('America/Santiago');

class Login {
    
    public $con = null;
    public $eliminado = 0;
    public function __construct(){
        
        global $db_host;
        global $db_user;
        global $db_password;
        global $db_database;

        $this->con = new mysqli($db_host[0], $db_user[0], $db_password[0], $db_database[0]);
        
    }
    public function recuperar_password(){

        $user = $_POST['user'];
        if(filter_var($user, FILTER_VALIDATE_EMAIL)){

            $sql = $this->con->prepare("SELECT * FROM usuarios WHERE correo=? AND eliminado=?");
            $sql->bind_param("si", $user, $this->eliminado);
            $sql->execute();
            $res = $sql->get_result();
            $aux_user = $res->fetch_all(MYSQLI_ASSOC)[0];
            $id_usr = $aux_user["id_usr"];
            $correo = $aux_user["correo"];

            $acciones = $this->acciones($id_usr, 2);

            if($acciones < 1){

                if($res->{"num_rows"} == 1){


                    // 1 ERRAR
                    // 2 PEDIR PASSWORD
                    $tipo = 2;
                    $sqlia = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_usr) VALUES (?, now(), ?)");
                    $sqlia->bind_param("ii", $tipo, $id_usr);
                    if($sqlia->execute()){

                        // CURL 
                        $send['correo'] = $correo;
                        $code = $this->getrandstring(32);

                        $sqluu = $this->con->prepare("UPDATE usuarios SET pass='', mailcode=? WHERE id_usr=? AND eliminado=?");
                        $sqluu->bind_param("sii", $code, $id_usr, $this->eliminado);
                        if($sqluu->execute()){

                            $send['link'] = "http://35.225.100.155/ingreso_nuevo_pass.php?id_usr=".$id_usr."&code=".$code;

                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, 'https://www.izusushi.cl/mail_recuperar_medici');
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($send));
                            $resp = json_decode(curl_exec($ch));
                            curl_close($ch);

                            $info['resp'] = $resp;

                            if($resp->{'op'} == 1){
                                $info['op'] = 1;
                                $info['message'] = "Correo Enviado";
                            }else{
                                $info['op'] = 2;
                                $info['message'] = "Error: 1";
                            }
                        }else{
                            $info['op'] = 2;
                            $info['message'] = "Error: 2";
                        }
                        $sqluu->close();

                    }else{
                        $info['op'] = 2;
                        $info['message'] = "Error: 3";
                    }
                    $sqlia->close();

                }
                if($res->{"num_rows"} == 0){
                    $info['op'] = 2;
                    $info['message'] = "Error: 4";
                }

            }else{
                $info['op'] = 2;
                $info['message'] = "Error: El correo ya ha sido enviado";
            }
            
            $sql->free_result();
            $sql->close();

        }else{
            $info['op'] = 2;
            $info['message'] = "Error: debe ingresar correo valido";
        }
        return $info; 

    }
    public function nueva_password(){

        $id = $_POST['id_usr'];
        $code = $_POST['code'];
        $pass_01 = $_POST['pass_01'];
        $pass_02 = $_POST['pass_02'];

        $sqlb = $this->con->prepare("SELECT * FROM usuarios WHERE id_usr=? AND mailcode=? AND eliminado=?");
        $sqlb->bind_param("isi", $id, $code, $this->eliminado);
        $sqlb->execute();
        $resb = $sqlb->get_result();

        if($resb->{"num_rows"} == 1 && strlen($code) == 32){

            $acciones = $this->acciones($id, 3);
            if($acciones < 5){
                if(strlen($pass_01) >= 8){
                    if($pass_01 == $pass_02){

                        $sql = $this->con->prepare("UPDATE usuarios SET mailcode='', pass=? WHERE id_usr=? AND eliminado=?");
                        $sql->bind_param("sii", md5($pass_01), $id, $this->eliminado);
                        if($sql->execute()){

                            $info['op'] = 1;
                            $info['url'] = "";
                            $info['message'] = "Felicidades! se ha creado su password";

                        }else{

                            $info['op'] = 2;
                            $info['message'] = "Error:";
                            $this->registrar('14', 0, 'usuario no modificado: ');

                        }
                        $sql->close();
                        
                    }else{
                        $info['op'] = 2;
                        $info['message'] = "Error: password diferentes";
                    }
                }else{
                    $info['op'] = 2;
                    $info['message'] = "Error: password debe tener mas de 8 caracteres";
                }
            }else{
                $info['op'] = 2;
                $info['message'] = "Error: Demaciados intentos";
                $this->registrar('14', 0, 'demaciado intentos: ');
            }

        }
        if($resb->{"num_rows"} == 0){

            $info['op'] = 2;
            $info['message'] = "Error: usuario y codigo";
            
            $tipo = 3;
            $sql = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_usr) VALUES (?, now(), ?)");
            $sql->bind_param("ii", $tipo, $id);
            $sql->execute();
            $sql->close();
            $this->registrar('14', 0, 'new password id - code diferentes: ');

        }

        $sqlb->free_result();
        $sqlb->close();
        
        return $info;

    }
    public function login_back(){

        if(filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)){

            $acciones = $this->acciones($_POST["user"], 1);
            $info['accion'] = $acciones;

            if($acciones < 5){

                $sqlu = $this->con->prepare("SELECT * FROM usuarios WHERE correo=? AND eliminado=?");
                $sqlu->bind_param("si", $_POST["user"], $this->eliminado);
                $sqlu->execute();
                $res = $sqlu->get_result();

                if($res->{"num_rows"} == 0){

                    $info['op'] = 2;
                    $info['message'] = "Error: Correo o Contraseña invalida";
                    $this->registrar('12', '0', 'usuario no existe: '.$_POST["user"]);

                }
                if($res->{"num_rows"} == 1){
                    
                    $result = $res->fetch_all(MYSQLI_ASSOC)[0];
                    $pass = $result['pass'];
                    $id_usr = $result['id_usr'];

                    if($pass == md5($_POST['pass'])){

                        $info['op'] = 1;
                        $info['message'] = "Ingreso Exitoso";

                        $ses['info']['id_usr'] = $id_usr;
                        $ses['info']['nombre'] = $result['nombre'];
                        $ses['info']['tipo'] = $result['tipo'];
                        $_SESSION['user'] = $ses;

                        $this->registrar('9', $result['id_usr'], '');

                    }else{

                        // 1 ERRAR
                        // 2 PEDIR PASSWORD

                        $tipo = 1;
                        $sqlic = $this->con->prepare("INSERT INTO fw_acciones (tipo, fecha, id_usr) VALUES (?, now(), ?)");
                        $sqlic->bind_param("ii", $tipo, $id_usr);
                        $sqlic->execute();
                        $sqlic->close();  

                        $info['op'] = 2;
                        $info['message'] = "Error: Correo o Contraseña invalida";
                        $this->registrar('12', $id_usr, 'pass diferente');

                    }

                }

                $sqlu->free_result();
                $sqlu->close();

            }else{
                $info['op'] = 2;
                $info['message'] = "Error: Demaciados intentos";
                $this->registrar('12', 0, 0, 0, 'demaciados intentos:');
            }
        
        }else{
            $info['op'] = 2;
            $info['message'] = "Error: Correo o Contraseña invalida";
            $this->registrar('12', 0, 0, 0, 'correo invalido:');
        }
        
        return $info;  
        
    }
    public function acciones($id_user, $tipo){

        $sql = $this->con->prepare("SELECT * FROM usuarios t1, fw_acciones t2 WHERE t1.correo=? AND t1.id_usr=t2.id_usr AND t2.tipo=? AND t2.fecha > DATE_ADD(NOW(), INTERVAL -1 DAY)");
        $sql->bind_param("ii", $id_user, $tipo);
        $sql->execute();
        $res = $sql->get_result();
        $sql->free_result();
        $sql->close();
        return $res->{"num_rows"};

    }
    public function getrandstring($n){
        
        $r = "";
        $s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$n; $i++){
            $r .= $s{rand(0, strlen($s) - 1)};
        }
        return $r;

    }
    public function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}

?>

