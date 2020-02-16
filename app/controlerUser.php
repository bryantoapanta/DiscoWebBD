<?php
// ------------------------------------------------
// Controlador que realiza la gestiÃ³n de usuarios
// ------------------------------------------------
include_once 'config.php';
// include_once 'modeloUser.php';
include_once 'modeloUserDB.php';

/*
 * Inicio Muestra o procesa el formulario (POST)
 */
function ctlUserInicio()
{
    $msg = "";
    $user = "";
    $clave = "";
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['user']) && isset($_POST['clave'])) {
            $user = $_POST['user'];
            $clave = $_POST['clave'];
            echo "antes de ok";
            if (ModeloUserDB::modeloOkUser($user, $clave)) {
                echo "despues de ok";

                $_SESSION['user'] = $user;
                $_SESSION['tipouser'] = ModeloUserDB::ObtenerTipo($user);
                echo "<br>tipo usuario=" . $_SESSION['tipouser'] . "<br>";
                echo $user;
                if ($user == "admin") { // si el usuario es administrador tendra acceso a la pagina
                                        // $_SESSION['tipouser'] =0;
                    if ($_SESSION['tipouser'] == "Máster") 
                    // if ($_SESSION['tipouser'] == 0)
                    {
                        echo "estas en gestion usuarios";
                        $_SESSION['modo'] = GESTIONUSUARIOS;
                        header('Location:index.php?orden=VerUsuarios');
                    }
                } else {
                    ($_SESSION['modo'] = GESTIONFICHEROS);
                    header('Location:index.php?operacion=VerFicheros');
                    // Usuario normal;
                    // PRIMERA VERSIÃ“N SOLO USUARIOS ADMISTRADORES
                    // $_SESSION['modo'] = GESTIONFICHEROS;
                    // Cambio de modo y redireccion a verficheros
                }
            } else {
                $msg = "Error: usuario y contraseña no válidos.";
            }
        }
    }

    if (isset($_GET['orden']) == "AltaUser") {
        // La orden tiene una funcion asociada
        $procRuta = "ctlUserAltaUser";
    }
    include_once 'plantilla/facceso.php';
}

// Cierra la sesiÃ³n y vuelva los datos
function ctlUserCerrar()
{
    session_destroy();
    ModeloUserDB::closeDB();
    // modeloUserSave();
    header('Location:index.php');
}

// Muestro la tabla con los usuario
function ctlUserVerUsuarios($msg)
{
    // Obtengo los datos del modelo
    $usuarios = ModeloUserDB::GetAll();
    // Invoco la vista
    include_once 'plantilla/verusuariosp.php';
}

function ctlUserBorrar()
{
    $msg = "";
    $user = $_GET['id'];
    if (ModeloUserDB::UserDel($user)) {
        $msg = "El usuario se borró correctamente.";
    } else {
        $msg = "No se pudo borrar el usuario.";
    }
    // modeloUserSave();
    ctlUserVerUsuarios($msg);
}

// errorValoresAlta ($user,$clave1, $clave2, $nombre, $email, $plan, $estado)
function ctlUserAlta()
{
    if (! isset($_POST["iduser"])) {
        include_once 'plantilla/fnuevoAdmin.php';
    } else {
        $msg = "";
        // echo "Estas en ctlUserAlta";
        $id = $_POST["iduser"];
        $data = [
            $_POST["clave1"],
            $_POST["nombre"],
            $_POST["email"],
            $_POST["plan"],
            $_POST["estado"]
        ];
        echo $id;
        var_dump($data);
        // modeloUserAdd($id, $data);

        // cifrar clave
        if (! ModeloUserDB::errorValoresAlta($id, $data[0], $_POST["clave2"], $data[1], $data[2], $data[3], $data[4])) {
            echo "Esta en añadir";
            $data[0] = Cifrador::cifrar($data[0]);
            if (ModeloUserDB::UserAdd($id, $data)) {
                $msg = "El usuario fue creado con Ã©xito";
            } else {
                $msg .= "<br>El usuario no fue creado";
                include_once "plantilla/fnuevoAdmin.php";
            }
        }
        else {
            $msg = "El usuario no fue creado";
        }
        // modeloUserSave();
        ctlUserVerUsuarios($msg);
    }
}

// ALTA USUARIO A TRAVES DE INICIO
function ctlUserAltaUser()
{
    if (! isset($_POST["iduser"])) {
        include_once 'plantilla/fnuevoUser.php';
    } else {
        $msg = "";
        // echo "Estas en ctlUserAlta";
        $id = $_POST["iduser"];
        $data = [
            $_POST["clave1"],
            $_POST["nombre"],
            $_POST["email"],
            $_POST["plan"],
            "I"
        ];
        echo $id;
        var_dump($data);
        // SI NO HAY ERRORES
        if (! ModeloUserDB::errorValoresAlta($id, $data[0], $_POST["clave2"], $data[1], $data[2], $data[3], $data[4])) {
            $data[0] = Cifrador::cifrar($data[0]);
            if (ModeloUserDB::UserAdd($id, $data)) {
                echo $msg = "El usuario fue creado con Ã©xito";
            } else {
                $msg = "El usuario no fue creado";
            }
        }
        else {
            $msg = "El usuario no fue creado";
        }
        // modeloUserSave();
        ctlUserInicio();
    }
}

function ctlUserModificar()
{
    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['clave1']) && isset($_POST['email']) && isset($_POST['estado']) && isset($_POST['nombre']) && isset($_POST['plan'])) {
            $id = $_POST['iduser'];
            $nombre = $_POST['nombre'];
            $clave = $_POST['clave1'];
            $mail = $_POST['email'];
            $plan = $_POST['plan'];
            $estado = $_POST['estado'];
            $data = [
                $clave,
                $nombre,
                $mail,
                $plan,
                $estado
            ];
            // SI NO HAY ERRORES
            if (! ModeloUserDB::errorValoresModificar($id, $data[0], $_POST["clave2"], $data[1], $data[2], $data[3], $data[4])) {
                $data[0] = Cifrador::cifrar($data[0]);
                if (ModeloUserDB::UserUpdate($id, $data)) {
                    $msg = "El usuario fue modificado con Ã©xito";
                } else {
                    $msg = "El usuario no pudo ser modificado";
                }
            } else {
                $msg = "El usuario no pudo ser modificado";
            }
        }
    } else {

        // al pulsar en modificar le paso el id, con ese id sacamos los datos del id(usuario) para, que luego se mostraran a la hora de modificar
        $user = $_GET['id'];
        $datosusuario = modelouserdb::GetAllModificar($user);

        $clave = $datosusuario[0];
        $nombre = $datosusuario[1];
        $mail = $datosusuario[2];
        $plan = $datosusuario[3];
        $estado = $datosusuario[4];
        echo $estado . include_once 'plantilla/fmodificar.php';
    }
    
    ctlUserVerUsuarios($msg);
}

function ctlUserDetalles()
{
    $user = $_GET['id'];
    $usuarios = ModeloUserDB::UserGet($user);
    include_once 'plantilla/fdetalles.php';
}


