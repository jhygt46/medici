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
            if($_POST['accion'] == "eliminar_servicio_usuario"){
                return $this->eliminar_servicio_usuario();
            }
            if($_POST['accion'] == "crear_horario"){
                return $this->crear_horario();
            }
            if($_POST['accion'] == "eliminar_horario"){
                return $this->eliminar_horario();
            }
            if($_POST['accion'] == "eliminar_hora"){
                return $this->eliminar_hora();
            }
            if($_POST['accion'] == "crear_excepcion"){
                return $this->crear_excepcion();
            }
            if($_POST['accion'] == "eliminar_excepcion"){
                return $this->eliminar_excepcion();
            }
            if($_POST['accion'] == "crear_rango_excepcion"){
                return $this->crear_rango_excepcion();
            }
            if($_POST['accion'] == "eliminar_rango_excepcion"){
                return $this->eliminar_rango_excepcion();
            }
            if($_POST['accion'] == "crear_sucursal"){
                return $this->crear_sucursal();
            }
            if($_POST['accion'] == "eliminar_sucursal"){
                return $this->eliminar_sucursal();
            }
            if($_POST['accion'] == "ordermed"){
                return $this->ordermed();
            }
            if($_POST['accion'] == "orderser"){
                return $this->orderser();
            }
            if($_POST['accion'] == "subir_imagen"){
                return $this->subir_imagen();
            }
        }

    }
    private function subir_imagen(){

        if($this->tipo == 1){

            $id = $_POST['id'];
            $t = $_POST['t'];
            
            $image = $this->upload('/var/www/html/medici/images/', null);
            $info['img'] = $image;

            if($t == "medicos"){
                $sql = $this->con->prepare("UPDATE usuarios SET imagen=? WHERE id_usr=? AND eliminado=?");
            }else if($t == "servicios"){
                $sql = $this->con->prepare("UPDATE servicios SET imagen=? WHERE id_ser=? AND eliminado=?");
            }else{
                // REPORTAR ERROR
            }
            $sql->bind_param("sii", $image['image'], $id, $this->eliminado);
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Imagen agregada exitosamente";
                $info['reload'] = 1;
                $info['page'] = "ingresar_servicios.php";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: Permisos A2";
            }
            $sql->close();

        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: F01";
        }
        return $info;

    }
    private function ordermed(){

        $values = $_POST['values'];
        for($i=0; $i<count($values); $i++){
            if($sql = $this->con->prepare("UPDATE usuarios SET orders=? WHERE id_usr=? AND eliminado=?")){
                if($sql->bind_param("iii", $i, $values[$i], $this->eliminado)){
                    if($sql->execute()){
                        $sql->close();
                    }else{}
                }else{}
            }else{}
        }
        return $values;

    }
    private function orderser(){
        
        $values = $_POST['values'];
        for($i=0; $i<count($values); $i++){
            if($sql = $this->con->prepare("UPDATE servicios SET orders=? WHERE id_ser=? AND eliminado=?")){
                if($sql->bind_param("iii", $i, $values[$i], $this->eliminado)){
                    if($sql->execute()){
                        $sql->close();
                    }else{}
                }else{}
            }else{}
        }
        return $values;

    }
    private function actualizar(){
        file_get_contents("http://35.225.100.155?accion=actualizar");
    }
    private function crear_sucursal(){
        
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $direccion = $_POST['direccion'];

        if($this->tipo == 1){
                
            if($id == 0){

                $sqligir = $this->con->prepare("INSERT INTO sucursal (nombre, direccion, eliminado) VALUES (?, ?, ?)");
                $sqligir->bind_param("ssi", $nombre, $direccion, $this->eliminado);
                if($sqligir->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Sucursal ingresada exitosamente";
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Error: B1";
                }
                $sqligir->close();

            }
            if($id > 0){

                $sqlugi = $this->con->prepare("UPDATE sucursal SET nombre=?, direccion=? WHERE id_suc=? AND eliminado=?");
                $sqlugi->bind_param("ssii", $nombre, $direccion, $id, $this->eliminado);
                if($sqlugi->execute()){
                    $info['op'] = 1;
                    $info['mensaje'] = "Sucursal modificada exitosamente";
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
        $info['page'] = "ingresar_sucursales.php";
        return $info;

    }
    private function eliminar_sucursal(){

        $id_ser = $_POST['id'];
        $nombre = $_POST['nombre'];

        if($this->tipo == 1){

            $sqlugi = $this->con->prepare("UPDATE sucursal SET eliminado='1' WHERE id_suc=?");
            $sqlugi->bind_param("i", $id_ser);
            if($sqlugi->execute()){

                $info['tipo'] = "success";
                $info['titulo'] = "Eliminado";
                $info['texto'] = "Sucursal ".$nombre." Eliminado";
                $info['reload'] = 1;
                $info['page'] = "ingresar_sucursales.php";

            }else{

                $info['tipo'] = "error";
                $info['titulo'] = "Error";
                $info['texto'] = "Sucursal ".$nombre." no pudo ser eliminado";

            }
            $sqlugi->close();
            
        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Sucursal ".$nombre." no pudo ser eliminado";

        }

        return $info;
        
    }
    private function eliminar_hora(){

        $aux = explode("/", $_POST['id']);
        $nombre = $_POST['nombre'];
        $id_hor = $aux[0];
        $fecha = $aux[1];

        $sql = $this->con->prepare("UPDATE horas SET eliminado='1' WHERE id_hor=? AND id_usr=?");
        $sql->bind_param("ii", $id_hor, $this->id_usr);
        if($sql->execute()){

            $info['tipo'] = "success";
            $info['titulo'] = "Eliminado";
            $info['texto'] = "Hora ".$nombre." Eliminado";
            $info['reload'] = 1;
            $info['page'] = "ver_horas.php?fecha=".$fecha;

        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Hora ".$nombre." no pudo ser eliminado";

        }
        $sql->close();
        return $info;
        
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
            $titulo = $_POST['titulo'];
            $html_descripcion = $_POST['html_descripcion'];
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];
            if($id > 0){

                $sqlu = $this->con->prepare("SELECT id_usr FROM usuarios WHERE correo=? AND eliminado=?");
                $sqlu->bind_param("si", $correo, $this->eliminado);
                $sqlu->execute();
                $res = $sqlu->get_result();
                
                if($res->{"num_rows"} == 1){
                    $id_usr = $res->fetch_all(MYSQLI_ASSOC)[0]['id_usr'];
                    if($id_usr == $id){

                        $sql = $this->con->prepare("UPDATE usuarios SET nombre=?, correo=?, tipo=?, titulo=?, html_descripcion=? WHERE id_usr=? AND eliminado=?");
                        $sql->bind_param("ssissii", $nombre, $correo, $tipo, $titulo, $html_descripcion, $id, $this->eliminado);
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

                    $sql = $this->con->prepare("INSERT INTO usuarios SET nombre=?, correo=?, tipo=?, titulo=?, html_descripcion=? WHERE id_usr=? AND eliminado=?");
                    $sql->bind_param("ssissii", $nombre, $correo, $tipo, $titulo, $html_descripcion, $id, $this->eliminado);
                    if($sql->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Medico ingresado exitosamente";
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
            if($id == 0){

                $sqlu = $this->con->prepare("SELECT * FROM usuarios WHERE correo=? AND eliminado=?");
                $sqlu->bind_param("si", $correo, $this->eliminado);
                $sqlu->execute();
                $res = $sqlu->get_result();

                if($res->{"num_rows"} == 0){

                    $tiempo_minimo = 30;
                    $sql = $this->con->prepare("INSERT INTO usuarios (nombre, correo, tipo, titulo, html_descripcion, tiempo_minimo, eliminado) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $sql->bind_param("ssissii", $nombre, $correo, $tipo, $titulo, $html_descripcion, $tiempo_minimo, $this->eliminado);
                    if($sql->execute()){
                        $info['op'] = 1;
                        $info['mensaje'] = "Medico ingresado exitosamente";
                        $id = $this->con->insert_id;
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
                if($pass1 == $pass2 && strlen($pass1) >= 8){
                    $sqlup = $this->con->prepare("UPDATE usuarios SET pass=? WHERE id_usr=? AND eliminado=?");
                    $sqlup->bind_param("sii", md5($pass1), $id, $this->eliminado);
                    if($sqlup->execute()){}
                }
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
        $id_ser = $_POST['id_ser'];
        $html_1 = $_POST['html_1'];
        $html_2 = $_POST['html_2'];

        $sqlu = $this->con->prepare("SELECT * FROM servicio_usuarios WHERE id_ser=? AND id_usr=?");
        $sqlu->bind_param("ii", $id_ser, $this->id_usr);
        $sqlu->execute();
        $res = $sqlu->get_result();

        if($res->{"num_rows"} == 0){

            $sql = $this->con->prepare("INSERT INTO servicio_usuarios (id_ser, id_usr, tiempo_min, precio, html_1, html_2, eliminado) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param("iiiissi", $id_ser, $this->id_usr, $tiempo, $precio, $html_1, $html_2, $this->eliminado);
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Servicio-Medico ingresado exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: B1";
            }

        }

        if($res->{"num_rows"} == 1){
            
            $sql = $this->con->prepare("UPDATE servicio_usuarios SET tiempo_min=?, precio=?, html_1=?, html_2=?, eliminado=? WHERE id_ser=? AND id_usr=?");
            $sql->bind_param("iissiii", $tiempo, $precio, $html_1, $html_2, $this->eliminado, $id_ser, $this->id_usr);
            $sql->execute();
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Servicio-Medico modificado exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: B2";
            }

        }

        $sqlsel = $this->con->prepare("SELECT MIN(tiempo_min) as minimo FROM servicio_usuarios WHERE id_usr=? AND eliminado=?");
        $sqlsel->bind_param("ii", $this->id_usr, $this->eliminado);
        $sqlsel->execute();
        $ressel = $sqlsel->get_result();
        if($ressel->{"num_rows"} > 0){
            $minimo = $ressel->fetch_all(MYSQLI_ASSOC)[0]["minimo"];
        }else{
            $minimo = 30;
        }

        $sqlup = $this->con->prepare("UPDATE usuarios SET tiempo_minimo=? WHERE id_usr=?");
        $sqlup->bind_param("ii", $minimo, $this->id_usr);
        $sqlup->execute();

        $info['reload'] = 1;
        $info['page'] = "mis_servicios.php";
        return $info;
        
    }
    private function eliminar_servicio_usuario(){

        $id_ser = $_POST['id'];
        $nombre = $_POST['nombre'];

        $sql = $this->con->prepare("UPDATE servicio_usuarios SET eliminado='1' WHERE id_usr=? AND id_ser=?");
        $sql->bind_param("ii", $this->id_usr, $id_ser);
        if($sql->execute()){

            $info['tipo'] = "success";
            $info['titulo'] = "Eliminado";
            $info['texto'] = "Servicio Usario ".$nombre." Eliminado";
            $info['reload'] = 1;
            $info['page'] = "mis_servicios.php";

        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Servicio Usuario ".$nombre." no pudo ser eliminado";

        }
        $sql->close();
        return $info;
        
    }
    private function crear_horario(){

        $id_suc = $_POST['id_suc'];
        $id_ran = $_POST['id'];
        $dia_ini = $_POST['dia_ini'];
        $dia_fin = $_POST['dia_fin'];
        $hora_ini = $_POST['hora_ini'];
        $hora_fin = $_POST['hora_fin'];
        $lista_servicios = $this->get_servicios();

        if($id_ran > 0){

            $sql = $this->con->prepare("UPDATE rangos SET dia_ini=?, dia_fin=?, hora_ini=?, hora_fin=?, id_suc=? WHERE id_ran=? AND id_usr=?");
            $sql->bind_param("iissiii", $dia_ini, $dia_fin, $hora_ini, $hora_fin, $id_suc, $id_ran, $this->id_usr);
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Horario modificado exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: B2";
            }

        }

        if($id_ran == 0){

            $sql = $this->con->prepare("INSERT INTO rangos (dia_ini, dia_fin, hora_ini, hora_fin, eliminado, id_suc, id_usr) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $sql->bind_param("iissiii", $dia_ini, $dia_fin, $hora_ini, $hora_fin, $this->eliminado, $id_suc, $this->id_usr);
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Horario ingresado exitosamente";
                $id_ran = $this->con->insert_id;
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: B1";
            }

        }
        if($id_ran > 0){
            $sqld = $this->con->prepare("DELETE FROM rango_servicios WHERE id_ran=?");
            $sqld->bind_param("i", $id_ran);
            if($sqld->execute()){
                for($i=0; $i<count($lista_servicios); $i++){
                    if($_POST["servicio-".$lista_servicios[$i]["id_ser"]] == 1){
                        $sqli = $this->con->prepare("INSERT INTO rango_servicios (id_ran, id_ser) VALUES (?, ?)");
                        $sqli->bind_param("ii", $id_ran, $lista_servicios[$i]["id_ser"]);
                        if(!$sqli->execute()){
                            $info['op'] = 2;
                            $info['mensaje'] = "Error: J1";
                        }
                    }
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: F1";
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: D1";
        }

        $info['reload'] = 1;
        $info['page'] = "mis_horarios.php";
        return $info;

    }
    private function eliminar_horario(){

        $id_ran = $_POST['id'];
        $nombre = $_POST['nombre'];

        $sql = $this->con->prepare("UPDATE rangos SET eliminado='1' WHERE id_ran=?");
        $sql->bind_param("i", $id_ran);
        if($sql->execute()){

            $info['tipo'] = "success";
            $info['titulo'] = "Eliminado";
            $info['texto'] = "Horario ".$nombre." Eliminado";
            $info['reload'] = 1;
            $info['page'] = "mis_horarios.php";

        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Horario ".$nombre." no pudo ser eliminado";

        }
        $sql->close();
        return $info;

    }
    private function crear_excepcion(){
        
        $fecha = $_POST['datepicker'];
        $tipo = $_POST['tipo'];
        $dia = date("w", strtotime($fecha));
        $id_suc = 1;

        $info['op'] = 1;
        $info['mensaje'] = "Excepcion creada exitosamente";
        $info['reload'] = 1;
        $info['page'] = "mis_excepciones.php";

        if($tipo == 0){

            $sql = $this->con->prepare("SELECT * FROM rangos WHERE id_usr=? AND eliminado=? AND dia_ini<=? AND dia_fin>=?");
            $sql->bind_param("iiii", $this->id_usr, $this->eliminado, $dia, $dia);
            if($sql->execute()){
                $res = $sql->get_result();
                while($row = $res->fetch_assoc()){
                    $sqli = $this->con->prepare("INSERT INTO excepciones (fecha, hora_ini, hora_fin, eliminado, id_suc, id_usr) VALUES (?, ?, ?, ?, ?, ?)");
                    $sqli->bind_param("sssiii", $fecha, $row["hora_ini"], $row["hora_fin"], $this->eliminado, $id_suc, $this->id_usr);
                    if($sqli->execute()){
                        $id_exc = $this->con->insert_id;
                        $sqlrc = $this->con->prepare("SELECT id_ser FROM rango_servicios WHERE id_ran=?");
                        $sqlrc->bind_param("i", $row["id_ran"]);
                        if($sqlrc->execute()){
                            $resrc = $sqlrc->get_result();
                            while($rowrc = $resrc->fetch_assoc()){
                                $sqlx = $this->con->prepare("INSERT INTO excepcion_servicios (id_ser, id_exc) VALUES (?, ?)");
                                $sqlx->bind_param("ii", $rowrc["id_ser"], $id_exc);
                                if($sqlx->execute()){
                                }else{}
                            }
                        }else{}
                    }else{}
                }
            }else{}
            $sql->close();

        }
        if($tipo == 1){

            $_h = "08:00:00";
            $sqli = $this->con->prepare("INSERT INTO excepciones (fecha, hora_ini, hora_fin, eliminado, id_suc, id_usr) VALUES (?, ?, ?, ?, ?, ?)");
            $sqli->bind_param("sssiii", $fecha, $_h, $_h, $this->eliminado, $id_suc, $this->id_usr);
            if($sqli->execute()){

                $id_exc = $this->con->insert_id;
                $sqlx = $this->con->prepare("SELECT id_ser FROM servicio_usuarios WHERE id_usr=? AND eliminado=? LIMIT 1");
                $sqlx->bind_param("ii", $this->id_usr, $this->eliminado);
                $sqlx->execute();
                $resx = $sqlx->get_result();
                $aux_serx = $resx->fetch_all(MYSQLI_ASSOC)[0];
                $id_serx = $aux_serx["id_ser"];

                $sqlw = $this->con->prepare("INSERT INTO excepcion_servicios (id_ser, id_exc) VALUES (?, ?)");
                $sqlw->bind_param("ii", $id_serx, $id_exc);
                $sqlw->execute();

            }

        }

        return $info;
        
    }
    private function eliminar_excepcion(){
        
        $fecha = $_POST['id'];

        $sql = $this->con->prepare("UPDATE excepciones SET eliminado='1' WHERE fecha=? AND id_usr=?");
        $sql->bind_param("si", $fecha, $this->id_usr);
        if($sql->execute()){

            $info['tipo'] = "success";
            $info['titulo'] = "Eliminado";
            $info['texto'] = "Excepciones de ".$fecha." fueron eliminados";
            $info['reload'] = 1;
            $info['page'] = "mis_excepciones.php";

        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Excepciones de ".$fecha." no pudo ser eliminado";

        }
        $sql->close();
        return $info;

    }
    private function crear_rango_excepcion(){

        $id_exc = $_POST["id"];
        $fecha = $_POST["fecha"];
        $hora_ini = $_POST['hora_ini'];
        $hora_fin = $_POST['hora_fin'];
        $id_suc = $_POST["id_suc"];

        if($id_exc > 0){

            $sql = $this->con->prepare("UPDATE excepciones SET hora_ini=?, hora_fin=?, id_suc=? WHERE id_exc=? AND id_usr=?");
            $sql->bind_param("ssiii", $hora_ini, $hora_fin, $id_suc, $id_exc, $this->id_usr);
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Excepcion modificado exitosamente";
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: B2";
            }

        }

        if($id_exc == 0){

            $sql = $this->con->prepare("INSERT INTO excepciones (fecha, hora_ini, hora_fin, eliminado, id_suc, id_usr) VALUES (?, ?, ?, ?, ?, ?)");
            $sql->bind_param("sssiii", $fecha, $hora_ini, $hora_fin, $this->eliminado, $id_suc, $this->id_usr);
            if($sql->execute()){
                $info['op'] = 1;
                $info['mensaje'] = "Horario ingresado exitosamente";
                $id_exc = $this->con->insert_id;
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: B1";
            }

        }

        if($id_exc > 0){

            $lista_servicios = $this->get_servicios();
            $sqld = $this->con->prepare("DELETE FROM excepcion_servicios WHERE id_exc=?");
            $sqld->bind_param("i", $id_exc);
            if($sqld->execute()){
                for($i=0; $i<count($lista_servicios); $i++){
                    if($_POST["servicio-".$lista_servicios[$i]["id_ser"]] == 1){
                        $sqli = $this->con->prepare("INSERT INTO excepcion_servicios (id_exc, id_ser) VALUES (?, ?)");
                        $sqli->bind_param("ii", $id_exc, $lista_servicios[$i]["id_ser"]);
                        if(!$sqli->execute()){
                            $info['op'] = 2;
                            $info['mensaje'] = "Error: J1";
                            $info['sqli'] = $sqli;
                            $info['sqlx'] = $this->con;
                        }else{}
                    }
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Error: F1";
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] = "Error: D1";
        }

        $info['reload'] = 1;
        $info['page'] = "rangos_excepciones.php?fecha=".$fecha;

        return $info;

    }
    private function eliminar_rango_excepcion(){
        
        $aux = explode("/", $_POST['id']);

        $id = $aux[0];
        $fecha = $aux[1];

        $sql = $this->con->prepare("UPDATE excepciones SET eliminado='1' WHERE id_exc=? AND id_usr=?");
        $sql->bind_param("si", $id, $this->id_usr);
        if($sql->execute()){

            $info['tipo'] = "success";
            $info['titulo'] = "Eliminado";
            $info['texto'] = "Excepcion de ".$fecha." eliminadas exitosamente";
            $info['reload'] = 1;
            $info['page'] = "rangos_excepciones.php?fecha=".$fecha;

        }else{

            $info['tipo'] = "error";
            $info['titulo'] = "Error";
            $info['texto'] = "Excepcion de ".$fecha." no pudo ser eliminado";

        }
        $sql->close();
        return $info;

    }
    private function get_servicios(){

        if($sql = $this->con->prepare("SELECT * FROM servicios t1, servicio_usuarios t2 WHERE t2.id_usr=? AND t2.id_ser=t1.id_ser AND t2.eliminado=?")){
            if($sql->bind_param("ii", $this->id_usr, $this->eliminado)){
                if($sql->execute()){
                    return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
                }else{ return htmlspecialchars($sql->error); }
            }else{ return htmlspecialchars($sql->error); }
        }else{ return htmlspecialchars($this->con->error); }

    }
    private function upload($filepath, $filename){

        $filename = ($filename !== null) ? $filename : $this->getrandstring(20) ;
        $file_formats = array("jpg", "jpeg", "JPG", "JPEG");
        $name = $_FILES['file_image0']['name']; // filename to get file's extension
        $size = $_FILES['file_image0']['size'];
        if (strlen($name)){
            $extension = substr($name, strrpos($name, '.') + 1);
            if (in_array($extension, $file_formats)) { // check it if it's a valid format or not
                if ($size < (200 * 1024)) { // check it if it's bigger than 2 mb or no
                    $imagename = $filename.".".$extension;
                    $imagename_new = $filename."x.".$extension;
                    $tmp = $_FILES['file_image0']['tmp_name'];
                    if(move_uploaded_file($tmp, $filepath.$imagename)){
                            $data = getimagesize($filepath.$imagename);
                            if($data['mime'] == "image/jpeg"){
                                $destino = imagecreatetruecolor($data[0], $data[1]);
                                $origen = imagecreatefromjpeg($filepath.$imagename);
                                imagecopy($destino, $origen, 0, 0, 0, 0, $data[0], $data[1]);
                                imagejpeg($destino, $filepath.$imagename_new);
                                imagedestroy($destino);
                                $info['op'] = 1;
                                $info['mensaje'] = "Imagen subida";
                                $info['image'] = $imagename_new;
                            }else{
                                $info['op'] = 2;
                                $info['mensaje'] = "La imagen no es jpg";
                            }
                            unlink($filepath.$imagename);
                    }else{
                        $info['op'] = 2;
                        $info['mensaje'] = "No se pudo subir la imagen";
                    }
                }else{
                    $info['op'] = 2;
                    $info['mensaje'] = "Imagen sobrepasa los 2MB establecidos";
                }
            }else{
                $info['op'] = 2;
                $info['mensaje'] = "Formato Invalido";
            }
        }else{
            $info['op'] = 2;
            $info['mensaje'] =  "No ha seleccionado una imagen";
        }
        return $info;

    }
    private function getrandstring($n){
        
        $r = "";
        $s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        for($i=0; $i<$n; $i++){
            $r .= $s{rand(0, strlen($s) - 1)};
        }
        return $r;

    }
}