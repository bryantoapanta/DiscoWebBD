<?php
include_once 'config.php';

/*
 * DATOS DE USUARIO
 * • Identificador ( 5 a 10 caracteres, no debe existir previamente, solo letras y números)
 * • Contraseña ( 8 a 15 caracteres, debe ser segura)
 * • Nombre ( Nombre y apellidos del usuario
 * • Correo electrónico ( Valor válido de dirección correo, no debe existir previamente)
 * • Tipo de Plan (0-Básico |1-Profesional |2- Premium| 3- Máster)
 * • Estado: (A-Activo | B-Bloqueado |I-Inactivo )
 */
// Inicializo el modelo
// Cargo los datos del fichero a la session
function modeloUserInit()
{

    /*
     * $tusuarios = [
     * "admin" => ["12345" ,"Administrado" ,"admin@system.com" ,3,"A"],
     * "user01" => ["user01clave","Fernando Pérez" ,"user01@gmailio.com" ,0,"A"],
     * "user02" => ["user02clave","Carmen García" ,"user02@gmailio.com" ,1,"B"],
     * "yes33" => ["micasa23" ,"Jesica Rico" ,"yes33@gmailio.com" ,2,"I"]
     * ];
     */
    if (! isset($_SESSION['tusuarios'])) {
        $datosjson = @file_get_contents(FILEUSER) or die("ERROR al abrir fichero de usuarios");
        $tusuarios = json_decode($datosjson, true);
        $_SESSION['tusuarios'] = $tusuarios;
    }

    // SI LA SESION FICHEROS NO EXISTE , CREO UNA Y CARGO LOS DATOS
    if (! isset($_SESSION['ficheros'])) {
        $datosjson = @file_get_contents(FILE) or die("ERROR al abrir fichero de datos");
        $ficheros = json_decode($datosjson, true);
        $_SESSION['ficheros'] = $ficheros;
    }
}

// Comprueba usuario y contraseña (boolean)
function modeloOkUser($user, $clave)
{
    // Comprobamos la contrase�a
    foreach ($_SESSION['tusuarios'] as $key => $valor) {
        // comprobamos si el usuario existe
        // echo "usuario: ",$key;
        if ($user == $key) {
            // echo "usuario: ", $key, "Contra: ", $valor[0];
            // devolvemos el usuario y la contrase�a.En el index se comprobara si la contrase�a coincide
            // return ($user == $key) && ($clave == $valor[0]);
            if( Cifrador::verificar($clave, $valor[0])){return true;};
        }
    }
    return;
}

// Devuelve el plan de usuario (String)
function modeloObtenerTipo($user)
{
    return PLANES[3]; // Máster
}

// Borrar un usuario (boolean)
function modeloUserDel($user)
{
    $borrado = false;
    foreach ($_SESSION["tusuarios"] as $clave => $valor) {
        if ($clave == $user) {
            unset($_SESSION["tusuarios"][$clave]);
            array_values($_SESSION["tusuarios"]);
            $borrado = true;
        }
    }
    return $borrado;
}

// Añadir un nuevo usuario (boolean)
function modeloUserAdd($userid, $userdat)
{
    $_SESSION["tusuarios"][$userid] = $userdat;
    return true;
}

// A�ADIR DATOS DE FICHERO

// Actualizar un nuevo usuario (boolean)
function modeloUserUpdate($userid, $userdat)
{
    foreach ($_SESSION['tusuarios'] as $clave => $valor) {
        if ($clave == $userid) {
            $_SESSION['tusuarios'][$userid] = $userdat; // ACTUALIZA TODO EXCEPTO EL USERID
        }
    }
    return true;
}

// Tabla de todos los usuarios para visualizar
function modeloUserGetAll()
{
    // Genero lo datos para la vista que no muestra la contraseña ni los códigos de estado o plan
    // sino su traducción a texto
    $tuservista = [];
    foreach ($_SESSION['tusuarios'] as $clave => $datosusuario) {
        $tuservista[$clave] = [
            $datosusuario[1],
            $datosusuario[2],
            PLANES[$datosusuario[3]],
            ESTADOS[$datosusuario[4]]
        ];
    }
    return $tuservista;
}

// Datos de un usuario para visualizar
function modeloUserGet($user)
{
    $tdetallesUsuario = [];

    foreach ($_SESSION['tusuarios'] as $clave => $datosusuario) {

        if ($clave == $user) {
            $tdetallesUsuario[$user] = [
                $datosusuario[1],
                $datosusuario[2],
                PLANES[$datosusuario[3]],
                ESTADOS[$datosusuario[4]]
            ];
        }
    }
    return $tdetallesUsuario;
}

// Vuelca los datos al fichero
function modeloUserSave()
{
    $datosjon = json_encode($_SESSION['tusuarios']);
    file_put_contents(FILEUSER, $datosjon) or die("Error al escribir en el fichero.");
    // fclose($fich);
    // GUARDAR DATOS FICHEROS
    $datosficherojon = json_encode($_SESSION['ficheros']);
    file_put_contents(FILE, $datosficherojon) or die("Error al escribir en el fichero.");
}

function cumplerequisitos($clave1, $clave2, $user, $email, &$msg)
{
    $may = false;
    $min = false;
    $dig = false;
    $ok = false;
    for ($i = 0; $i < strlen($clave1); $i ++) {
        if ($clave1[$i] == strtoupper($clave1[$i])) {
            $may = true;
        }
        if ($clave1[$i] == strtolower($clave1[$i])) {
            $min = true;
        }
        if ($clave1[$i] == is_numeric($clave1[$i])) {
            $dig = true;
        }
    }
    if ($may == false || $min == false) {
        $msg .= "La contraseña no cumple los requisitos";
        return false;
    }

    if ($may == true && $min == true && $dig == true && $clave1 == $clave2 && strlen($clave1) >= 8) { // si la clave coincide y es mayor de 8 caracteres ok es true
        $ok = true;
    } else {
        $msg = "La contraseña no cumple los requisitos";
        ;
        return false;
    }

    foreach ($_SESSION['tusuarios'] as $clave => $datosusuario) { // recorremos para comprobar si existe ,o no, el email o usuario
        if ($clave != $user && $datosusuario[2] != $email) { // si el usuario no existe y el correo tampoco
            $ok = true;
        } else {
            $msg = "El usuario o email ya existe";
            return false;
        }
    }
    return $ok;
}

// MODELO USER FICHEROS--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function modeloUserGetFiles()
{
    $vista = [];
    // REALIZAMOS UN FOR-EACH PARA SACAR LOS DATOS DEL USUARIO CONECTADO. EL USUARIO LO SACAMOS A ATRAVES DE $_SESSION["USER"]
    foreach ($_SESSION['ficheros'] as $usuario => $nombrefich) {

        if ($usuario == $_SESSION["user"]) { // si el usuario coincide con el usuario de la sesion

            foreach ($_SESSION['ficheros']['user01'] as $nombrefich => $datos) { // realizo un for each para recorrer la tabla de archivos con sus respectivos datos.
                echo "<br>" . $nombrefich;

                $vista[$nombrefich] = [
                    $datos[0],
                    $datos[1],
                    $datos[2],
                    $datos[3]
                    // $datosusuario[4]
                ];
            }
        }
    }
    return $vista;
}

// A�ADIR DATOS DE FICHERO
function modeloficheroAdd($userid, $userdat, $nombrefichero)
{
    $_SESSION["ficheros"][$userid][$nombrefichero] = $userdat;
    return true;
}

// SUBIR ARCHIVO A LA "NUBE"
function modelouserSubirfichero($directorioSubida, $nombreFichero, $tipoFichero, $tamanioFichero, $temporalFichero, $errorFichero, &$msg)
{
    // Obtengo el código de error de la operación, 0 si todo ha ido bien
    if ($errorFichero > 0) {
        $msg .= "Se a producido el error: $errorFichero:" . $codigosErrorSubida[$errorFichero] . ' <br />';
        return FALSE;
    } else { // subida correcta del temporal
             // si es un directorio y tengo permisos
        if (is_dir($directorioSubida) && is_writable($directorioSubida)) {
            // Intento mover el archivo temporal al directorio indicado
            if (move_uploaded_file($temporalFichero, $directorioSubida . '/' . $nombreFichero) == true) {
                // $msg .= 'Archivo guardado en: ' . $directorioSubida .'/'. $nombreFichero . ' <br />';
                $msg = "Archivo guardado con exito";
                return true;
            } else {
                $msg .= 'ERROR: Archivo no guardado correctamente <br />';
                return false;
            }
        } else {
            $msg .= 'ERROR: No es un directorio correcto o no se tiene permiso de escritura <br />';
            return FALSE;
        }
    }
}

// DESCARGAR FICHERO
function modelouserDescargar($nombrefichero, $directorio, &$msg)
{
    // OBTENGO EL NOMBRE DEL FICHERO
    $fileName = basename($nombrefichero);
    // OBTENGO LA RUTA COMPLETA DEL FICHERO
    $filePath = $directorio . "/" . $fileName;
    // SI NO ESTA VACIO Y EXISTE LA RUTA
    if (! empty($fileName) && file_exists($filePath)) {
        // Define headers
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: binary");

        // Read the file
        readfile($filePath);
        $msg = "Archivo descargado";
        exit();
    } else {
        $msg = 'The file does not exist.';
    }
}

// BORRAR DATOS FICHERO
FUNCTION modeloUserDelfichero($fichero)
{
    $borrado = false;
   
    foreach ($_SESSION["ficheros"][$_SESSION["user"]] as $nFichero => $valor) { // recorremos el array en busca del usuario

        if ($fichero == $nFichero) {
            //foreach ($_SESSION["ficheros"][$_SESSION["user"]][$clave] as $nombrefich => $valor) { // borramos el archivo y sus datos
                unset($_SESSION["ficheros"][$_SESSION["user"]][$fichero]);
                array_values($_SESSION["ficheros"][$_SESSION["user"]]);
                $borrado = true;
           // }
        }
    }
    return $borrado;
}

// RENOMBRAR ARCHIVO
FUNCTION modeloUserRenamefichero($antiguo, $nuevo)
{
    $rename = false;
    foreach ($_SESSION["ficheros"][$_SESSION["user"]] as $clave => $valor) {
        if ($clave == $antiguo) {
            // SI SE ENCUENTRA EL USUARIO, CAMBIAMOS EL NOMBRE DEL ARCHIVO POR EL NUEVO VALOR.
            $_SESSION["ficheros"][$_SESSION["user"]][$clave][0] = $nuevo;
            $rename = true;
        }
    }
    return $rename;
}
