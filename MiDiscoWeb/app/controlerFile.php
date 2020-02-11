<?php
include_once 'config.php';
//include_once 'modeloUser.php';

function ctlFileVerFicheros($msg)
{
    $ficheros = modeloUserGetFiles(); // almaceno dentro de $usuarios el contenido de la sesion usuarios
                                      // Invoco la vista
    include_once 'plantilla/verarchivos.php';
}

function ctlFileNuevo($msg)
{
    // se incluyen los códigos de error que produce la subida de archivos en PHPP
    // Posibles errores de subida
    $codigosErrorSubida = [
        0 => 'Subida correcta',
        1 => 'El tamaño del archivo excede el admitido por el servidor', // directiva upload_max_filesize en php.ini
        2 => 'El tamaño del archivo excede el admitido por el cliente', // directiva MAX_FILE_SIZE en el formulario HTML
        3 => 'El archivo no se pudo subir completamente',
        4 => 'No se seleccionó ningún archivo para ser subido',
        6 => 'No existe un directorio temporal donde subir el archivo',
        7 => 'No se pudo guardar el archivo en disco', // permisos
        8 => 'Una extensión PHP evito la subida del archivo' // extensión PHP
    ];
    $msg = '';

        // si no se reciben el archivo, se se carga la pagina para subir el archivo
    if ((! isset($_FILES['archivo1']['name']))) {
        include_once 'plantilla/subirfichero.php';
    } else { // se reciben el directorio de alojamiento y el archivo
        $directorioSubida = RUTA_UBUNTU.$_SESSION["user"]."/"; // $_session user para crear una carpetadel usuario
        $directorioubuntu = "/home/alummo2019-20/Escritorio/prueba/" . $_SESSION["user"] . "/";
        if (!(file_exists($directorioSubida) || file_exists($directorioubuntu))) {
            // mkdir($directorioSubida, 0777, true);
            mkdir($directorioSubida, 0777, true);
        }
        // debe permitir la escritua para Apache
        echo $directorioubuntu; // Información sobre el archivo subido
        $nombreFichero = $_FILES['archivo1']['name'];
        $tipoFichero = $_FILES['archivo1']['type'];
        $tamanioFichero = $_FILES['archivo1']['size'];
        $temporalFichero = $_FILES['archivo1']['tmp_name'];
        $errorFichero = $_FILES['archivo1']['error'];

        // CREO UN ARRAY DONDE ALMACENAR LOS DATOS DEL FICHERO
        $id = $_SESSION["user"];
        $data = [
            $nombreFichero,
            $directorioSubida,
            $tipoFichero,
            $tamanioFichero
            // $temporalFichero,
        ];

        // PRIMERO SUBO EL FICHERO Y LUEGO SI SE SUBE ALMACENO LOS DATOS EN EL JSON
        if (modelouserSubirfichero($directorioSubida, $nombreFichero, $tipoFichero, $tamanioFichero, $temporalFichero, $errorFichero, $msg)) {
            if (modeloficheroAdd($id, $data,$nombreFichero)) {
                $msg .= "<br>Exito al almacenar datos";
            }
        }
    }
    modeloUserSave();
    ctlFileVerFicheros($msg);
}

function ctlFileBorrar($msg)
{
    $msg = "";
    $user = $_GET['id'];
    $nombre= $_GET['nombre'];
    
    echo $user;
    if (modeloUserDelfichero($user)) {
        $directorioSubida = RUTA_UBUNTU.$_SESSION["user"]."/".$nombre;
        //$directorioubuntu= "/home/alummo2019-20/Escritorio/prueba/".$_SESSION["user"]."/";
        echo $directorioSubida;
        unlink($directorioSubida);
        
        $msg = "El archivo se borró correctamente.";
    } else {
        $msg = "No se pudo borrar el archivo.";
    }
    modeloUserSave();
    ctlFileVerFicheros($msg);
}

function ctlFileRenombrar($msg)
{
    $msg = "";
    $nombrefich = $_GET['id'];
    $nombre= $_GET['nombre'];
    $nuevoNombre= $_GET['nuevo'];
    echo $nuevoNombre;
    echo $nombrefich;
    echo $nombre;
    
    if (modeloUserRenamefichero($nombrefich,$nuevoNombre)) {
        //$nombreAntiguo = "/home/alummo2019-20/Escritorio/prueba/".$_SESSION["user"]."/".$nombre;
        //$nombreNuevo = "/home/alummo2019-20/Escritorio/prueba/".$_SESSION["user"]."/".$nuevoNombre;
        $nombreAntiguo = RUTA_UBUNTU.$_SESSION["user"]."/".$nombre;
        $nombreNuevo = RUTA_UBUNTU.$_SESSION["user"]."/".$nuevoNombre;
        
        echo $nombreAntiguo."---->".$nombreNuevo;
        rename($nombreAntiguo, $nombreNuevo);
        
        
        $msg = "El archivo se ha sido modificado correctamente.";
    } else {
        $msg = "No se pudo modificar el archivo.";
    }
    modeloUserSave();
    ctlFileVerFicheros($msg);
}

function ctlFileCompartir($msg)
{
    $usuarios = modeloUserGetAll(); // almaceno dentro de $usuarios el contenido de la sesion usuarios
                                    // Invoco la vista
    include_once 'plantilla/verarchivos.php';
}

function ctlFileUserCerrar($msg)
{
    session_destroy();
    modeloUserSave();
    header('Location:index.php');
}

function ctlFileDescargar($msg)
{
  
    $nombre_fichero = $_GET["id"];
    echo $nombre_fichero;
    $datosfichero = $_SESSION["ficheros"][$_SESSION["user"]][$nombre_fichero];
    //$nombre = $datosusuario[0];
    $directorio = $datosfichero[1];
   
    
    modelouserDescargar($nombre_fichero,$directorio,$msg);
    ctlFileVerFicheros($msg);
}

function ctlFileModificar()
{
    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['clave1']) && isset($_POST['email']) && isset($_POST['nombre']) && isset($_POST['plan'])) {
            $id = $_POST['iduser'];
            $nombre = $_POST['nombre'];
            $clave = $_POST['clave1'];
            $mail = $_POST['email'];
            $plan = $_POST['plan'];

            // Si el plan se modifica entonces el estado pasa a BLOQUEADO
            // ECHO $plan . " plan antiguo: " . $plan2;
            if ($plan != ($_SESSION["tusuarios"][$_SESSION["user"]][3])) {
                $estado = "B";
            } else {
                $estado = $_SESSION["tusuarios"][$_SESSION["user"]][4];
            }
            echo $estado;
            // CREO UN ARRAY DONDE ALMACENAR LA INFORMACION PARA LUEGO PASARLO COMO PARAMETRO
            $modificado = [
                $clave,
                $nombre,
                $mail,
                $plan,
                $estado
            ];

            // if (cumplecontra($_POST["clave1"], $_POST["clave2"],$_POST["iduser"],$_POST["email"])) {
            if (cumplerequisitos($_POST["clave1"], $_POST["clave2"], $_POST["iduser"], $_POST["email"], $msg)) {
                if (modeloUserUpdate($id, $modificado)) {
                    $msg = "El usuario fue modificado con éxito";
                }
            } else {
                $msg = "El usuario no pudo ser modificado";
            }
        }
    } else {

        // al pulsar en modificar le paso el id, con ese id sacamos los datos del id(usuario) para, que luego se mostraran a la hora de modificar
        $user = $_SESSION["user"];
        $datosusuario = $_SESSION["tusuarios"][$user];
        $clave = $datosusuario[0];
        $nombre = $datosusuario[1];
        $mail = $datosusuario[2];
        $plan = $datosusuario[3];
        $estado = $datosusuario[4];

        include_once 'plantilla/modificarficheros.php';
    }
    modeloUserSave();
    ctlFileVerFicheros($msg);
}

?>
