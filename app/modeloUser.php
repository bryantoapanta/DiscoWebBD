<?php
include_once 'config.php';
include_once 'modeloUserDB.php';
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
   /* if (! isset($_SESSION['tusuarios'])) {
        $datosjson = @file_get_contents(FILEUSER) or die("ERROR al abrir fichero de usuarios");
        $tusuarios = json_decode($datosjson, true);
        $_SESSION['tusuarios'] = $tusuarios;
    }*/

    // SI LA SESION FICHEROS NO EXISTE , CREO UNA Y CARGO LOS DATOS
    if (! isset($_SESSION['ficheros'])) {
        $datosjson = @file_get_contents(FILE) or die("ERROR al abrir fichero de datos");
        $ficheros = json_decode($datosjson, true);
        $_SESSION['ficheros'] = $ficheros;
    }
}




// Vuelca los datos al fichero
function modeloUserSave()
{
    // fclose($fich);
    // GUARDAR DATOS FICHEROS
    $datosficherojon = json_encode($_SESSION['ficheros']);
    file_put_contents(FILE, $datosficherojon) or die("Error al escribir en el fichero.");
}



// MODELO USER FICHEROS--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function modeloUserGetFiles()
{
    $vista = [];
    // REALIZAMOS UN FOR-EACH PARA SACAR LOS DATOS DEL USUARIO CONECTADO. EL USUARIO LO SACAMOS A ATRAVES DE $_SESSION["USER"]
    foreach ($_SESSION['ficheros'] as $usuario => $nombrefich) {

        if ($usuario == $_SESSION["user"]) { // si el usuario coincide con el usuario de la sesion

            foreach ($_SESSION['ficheros'][$_SESSION["user"]] as $nombrefich => $datos) { // realizo un for each para recorrer la tabla de archivos con sus respectivos datos.
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
function modelouserSubirfichero($directorioSubida, $nombreFichero, $temporalFichero, $errorFichero, &$msg)
{
    // Obtengo el código de error de la operación, 0 si todo ha ido bien
    if ($errorFichero > 0) {
        $msg .= "Se a producido el error: $errorFichero:" . $codigosErrorSubida[$errorFichero] . ' <br />';
        return false;
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

function calcular_Espacio($userid){
    $suma=0;
    
    echo $userid;
    foreach ($_SESSION['ficheros'] as $usuario => $nombrefich) {
        
        if ($usuario == $userid) { // si el usuario coincide con el usuario de la sesion
            
            foreach ($_SESSION['ficheros'][$userid] as $nombrefich => $datos) { // realizo un for each para recorrer la tabla de archivos con sus respectivos datos.
                echo "<br>" . $nombrefich;
                
                $suma=$suma+$datos[3];//sumo el tama�o de cada archivo
                echo $suma;
            }
        }
    }
    return $suma;
}

function cumpleEspacio($user, $plan, $tamanho){
    $espacio=calcular_Espacio($user);
    
    switch ($plan) {
        case "B�sico":
            //Asignamos 2MB de espacio para el plan B�sico
            if(($tamanho + $espacio)< 2000000 ) return true;
            break;
        case "Profesional":
            //Asignamos 5MB de espacio para el plan Profesional
            if(($tamanho + $espacio)< 5000000 ) return true;
            break;
        case "Premium":
            //Asignamos 10MB de espacio para el plan Premium
            if(($tamanho + $espacio)< 10000000 ) return true;
            break;
    }
    return false;
}

// DESCARGAR FICHERO
function modelouserDescargar($nombrefichero, $directorio, &$msg)
{
    // OBTENGO EL NOMBRE DEL FICHERO
    $fileName = basename($nombrefichero);
    // OBTENGO LA RUTA COMPLETA DEL FICHERO
    $filePath = $directorio . "/" . $fileName;
    echo $filePath;
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
