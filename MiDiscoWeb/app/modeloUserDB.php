<?php

include_once 'config.php';
include_once 'Cifrador.php';

class ModeloUserDB {
    
    private static $dbh = null;
    private static $consulta_user = "Select * from usuarios where id = ?";
    private static $consulta_email = "Select * from usuarios where email = ?";
    private static $consulta_emailAntiguo = "Select email from usuarios where id = ?";
    private static $borrar_usuario = "Delete from usuarios where id = ?";
    private static $add_usuario = "INSERT INTO usuarios (id, clave, nombre, email, plan, estado) VALUES (?, ?, ?, ?, ?, ?)";
    private static $modificar_usuario = "Update usuarios set clave = ? , nombre = ? , email = ? ,
                                            plan = ? , estado = ? where id = ?";
    private static $obtenerdatosModificar="select * from usuarios where id= ?";
    
    
    public static function init(){
        
        
        if (self::$dbh == null){
            try {
                // Cambiar  los valores de las constantes en config.php
                $dsn = "mysql:host=".DBSERVER.";dbname=".DBNAME.";charset=utf8";
                self::$dbh = new PDO($dsn,DBUSER,DBPASSWORD);
                // Si se produce un error se genera una excepción;
                self::$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e){
                echo "Error de conexión ".$e->getMessage();
                exit();
            }
            
        }
        
    }
    
    
    // Comprueba usuario y contraseña son correctos (boolean)
    public static function modeloOkUser($user,$clave){
        
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$user);
        $stmt->execute();
        if ($stmt->rowCount() > 0 ){
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $fila = $stmt->fetch();
            $clavecifrada = $fila['clave'];
            if ( Cifrador::verificar($clave, $clavecifrada)){
                return true;
            }
        }
        return false;
    }
    
    // Comprueba si ya existe un usuario con ese identificar
    public static function ExisteID(String $user):bool{
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$user);
        $stmt->execute();
        if ($stmt->rowCount() == 0 ){
            return false;
        }else{
            return true;
        }
    }
    
    //Comprueba si existe el email en la BD
    public static function existeEmail(String $email):bool{
        $stmt = self::$dbh->prepare(self::$consulta_email);
        $stmt->bindValue(1,$email);
        $stmt->execute();
        if ($stmt->rowCount() == 0 ){
            return false;
        }else{
            return true;
        }
    }
    
    public static function getEmail(String $user):string{
        $stmt = self::$dbh->prepare(self::$consulta_emailAntiguo);
        $stmt->bindValue(1,$user);
        $stmt->execute();
       
        if ($stmt->rowCount() == 0 ){
            return false;
        }else{
            //CREAMOS UN ARRAY ASOCIATIVO PARA LUEGO PODER OBTENER EL EMAIL
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $fila = $stmt->fetch();
            $email = $fila['email'];
            echo $email;
            return $email;
            }
        }
    
    
    /*
     * Chequea si hay error en el datos antes de guardarlos
     */
    public static function errorValoresAlta ($user,$clave1, $clave2, $nombre, $email, $plan, $estado){
        if ( self::ExisteID($user))                         return TMENSAJES['USREXIST'];
        if ( preg_match("/^[a-zA-Z0-9]+$/", $user) == 0)    return TMENSAJES['USRERROR'];
        if ( $clave1 != $clave2 )                           return TMENSAJES['PASSDIST'];
        if ( !self::EsClaveSegura($clave1) )                return TMENSAJES['PASSEASY'];
        if ( !filter_var($email, FILTER_VALIDATE_EMAIL))    return TMENSAJES['MAILERROR'];
        if ( self::ExisteEmail($email))                     return TMENSAJES['MAILREPE'];
        return false;
    }
    
    public static function errorValoresModificar($user, $clave1, $clave2, $nombre, $email, $plan, $estado){
        
        if ( $clave1 != $clave2 )                           return TMENSAJES['PASSDIST'];
        if ( !self::EsClaveSegura($clave1) )                return TMENSAJES['PASSEASY'];
        if ( !filter_var($email, FILTER_VALIDATE_EMAIL))    return TMENSAJES['MAILERROR'];
        // SI se cambia el email
        $emailantiguo = self::GetEmail($user);
        echo $emailantiguo ."-".$email;
        if ( $email != $emailantiguo && self::ExisteEmail($email))   return TMENSAJES['MAILREPE'];
        return false;
    }
    
    /*
     * Comprueba que la contraseña es segura
     */
    
    public static function EsClaveSegura (String $clave):bool {
        if ( empty($clave))         return false;
        if (  strlen($clave) < 8 )  return false;
        if ( !self::hayMayusculas($clave) || !self::hayMinusculas($clave)) return false;
        if ( !self::hayDigito($clave))         return false;
        if ( !self::hayNoAlfanumerico($clave)) return false;
        
        return true;
    }
    
    
    
    public static function hayMayusculas($clave):bool{
        $may = false;
        for ($i = 0; $i < strlen($clave); $i ++) {
            if ($clave[$i] == strtoupper($clave[$i])) {
                $may = true;
            }
        }
        return $may;
    }
    
    public static function hayMinusculas($clave):bool{
        $min = false;
        for($i=0;$i<strlen($clave);$i++){
            if($clave[$i] == strtolower($clave[$i])){
                $min = true;
            }
        }
        return $min; 
    }
    
    public static function hayDigito($clave):bool{
        $dig = false;
        for($i=0;$i<strlen($clave);$i++){
            if ($clave[$i] == is_numeric($clave[$i])) {
                $dig = true;
            }
        }
        return $dig;
    }
    
    public static function hayNoAlfanumerico($clave):bool{
        $alpha = false;
        for($i=0;$i<strlen($clave);$i++){
            if ($clave[$i] == ctype_alnum($clave[$i])){
                $alpha = true;
            }
        }
        return $alpha;
    }
    
    
    // Devuelve el plan de usuario (String)
    public static function ObtenerTipo($user):string{
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$user);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($fila = $stmt->fetch()){
            $plan = PLANES[$fila['plan']];
        }
        return $plan;
    }
    
    // Borrar un usuario (boolean)
    public static function UserDel($userid):bool{
        $stmt = self::$dbh->prepare(self::$borrar_usuario);
        $stmt->bindValue(1,$userid);
        if($stmt->execute()){
            $msg = "Usuario borrado";
            return true;
        }
        return false;
    }
    // Añadir un nuevo usuario (boolean)
    public static function UserAdd($userid, $userdat):bool{
        $stmt = self::$dbh->prepare(self::$add_usuario);
        $stmt->bindValue(1,$userid);
        $posicion=0; //para moverme por el array
        for ($x=2;$x<=6;$x++){
            $stmt->bindValue($x,$userdat[$posicion]);
            $posicion++;
        }
        if($stmt->execute()){
            $msg = "Usuario creado";
            return true;
        }
        return false;
       
    }
    
    // Actualizar un nuevo usuario (boolean)
    public static function UserUpdate ($userid, $userdat):bool{
        $stmt = self::$dbh->prepare(self::$modificar_usuario);
        $stmt->bindValue(1,$userdat[0]);
        $stmt->bindValue(2,$userdat[1]);
        $stmt->bindValue(3,$userdat[2]);
        $stmt->bindValue(4,$userdat[3]);
        $stmt->bindValue(5,$userdat[4]);
        $stmt->bindValue(6,$userid);
        $stmt->execute();
        return true;
    }
    
    
    // Tabla de todos los usuarios para visualizar
    public static function GetAll ():array{
        // Genero los datos para la vista que no muestra la contraseña ni los códigos de estado o plan
        // sino su traducción a texto  PLANES[$fila['plan']],
        $stmt = self::$dbh->query("select * from usuarios");
        
        $tUserVista = [];
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ( $fila = $stmt->fetch()){
            $datosuser = [
                $fila['nombre'],
                $fila['email'],
                PLANES[$fila['plan']],
                ESTADOS[$fila['estado']]
            ];
            $tUserVista[$fila['id']] = $datosuser;
        }
        return $tUserVista;
    }
    
    public static function GetAllModificar ($id):array{
        // Genero los datos para la vista que no muestra la contraseña ni los códigos de estado o plan
        // sino su traducción a texto  PLANES[$fila['plan']],
        echo $id;
        $stmt = self::$dbh->prepare(self::$obtenerdatosModificar);
        $stmt->bindValue(1,$id);
        $tUserVista = [];
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ( $fila = $stmt->fetch()){
            $datosuser = [
                $fila["clave"],
                $fila['nombre'],
                $fila['email'],
                PLANES[$fila['plan']],
                ESTADOS[$fila['estado']]
            ];
            $tUserVista = $datosuser;
        }
        return $tUserVista;
    }
    
    
    
    // Datos de un usuario para visualizar
    public static function UserGet ($userid):array{
        $stmt = self::$dbh->prepare(self::$consulta_user);
        $stmt->bindValue(1,$userid);
        $tUserVista = [];
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ( $fila = $stmt->fetch()){
            $datosuser = [
                $fila['nombre'],
                $fila['email'],
                PLANES[$fila['plan']],
                ESTADOS[$fila['estado']]
            ];
            $tUserVista[$fila['id']] = $datosuser;
        }
        return $tUserVista;
    }
    
    public static function closeDB(){
        self::$dbh = null;
    }
    
} // class