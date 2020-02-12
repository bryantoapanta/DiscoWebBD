<?php

define ('GESTIONUSUARIOS','1');
define ('GESTIONFICHEROS','2');

// Fichero donde se guardan los datos
define('FILEUSER','app/dat/usuarios.json');
define('FILE','app/dat/ficheros.json');
// Ruta donde se guardan los archivos de los usuarios
// Tiene que tener permiso 777 o permitir a Apache rwx
define('RUTA_UBUNTU', "/home/alummo2019-20/Escritorio/prueba/");
define('RUTA_WINDOWS',"C:\Users\Bryan\Desktop\Prueba\\");

// (0-BÃ¡sico |1-Profesional |2- Premium| 3- MÃ¡ster)
const  PLANES = ['B�sico','Profesional','Premium','M�ster'];
//  Estado: (A-Activo | B-Bloqueado |I-Inactivo )
const  ESTADOS = ['A' => 'Activo','B' =>'Bloqueado', 'I' => 'Inactivo']; 

// Definir otras constantes 


// DATOS DE CONEXION
//define ('DBSERVER','192.168.105.68');
define ('DBSERVER','localhost');
define ('DBNAME','discoweb' );
define ('DBUSER','root');
//define ('DBPASSWORD','alumno123CUATRO_');
define ('DBPASSWORD','');   



// Fichero donde se guardan los datos
//define('FILEUSER','app/dat/usuarios.json');
//define('FILEUSERSEGUR','app/dat/usuariossegur.json');
// Ruta donde se guardan los archivos de los usuarios
// Tiene que tener permiso 777 o permitir cambiar a usuario Apache
//define('RUTA_FICHEROS','app/dat');
//  Estado: (A-Activo | B-Bloqueado |I-Inactivo )
//const  ESTADOS = ['A' => 'Activo','B' =>'Bloqueado', 'I' => 'Inactivo'];


// (0-BÃ¡sico |1-Profesional |2- Premium| 3- MÃ¡ster)
//const  PLANES = ['BÃ¡sico','Profesional','Premium','MÃ¡ster'];
const LIMITE_FICHEROS = [50,100,200,0];
const LIMITE_ESPACIO  = [10000,20000,50000,0];
// TAMAÃ‘O MÃ�XIMO DEL FICHERO 2 MB
define ('TAMMAXIMOFILE',  2000000);
const TMENSAJES = [
    'USREXIST'     => "El ID del usuario ya existe",
    'USRERROR'     => "El ID de usuario solo puede tener letras y nÃºmeros",
    'PASSDIST'     => "Los valores de la contraseÃ±as no son iguales",
    'PASSEASY'     => "La contraseÃ±a no es segura",
    'MAILERROR'    => "El correo electrÃ³nico no es correcto",
    'MAILREPE'     => "La direcciÃ³n de correo ya estÃ¡ repetida",
    'NOVACIO'      => "Rellenar todos los campos",
    'LOGINERROR'   => "Error: usuario y contraseÃ±a no vÃ¡lidos.",
    'USERNOACTIVO' => "Su usuario esta bloqueado o inactivo",
    'USERSAVE'     => "Nuevo Usuario almacenado.",
    'USERNOSAVE'   => "Error: No se puede aÃ±adir el usuario",
    'USERUPDATE'   => "Se han modificado los datos del Usuario",
    'ERRORUPDATE'  => "Error al modificar el usuario",
    'ERRORADDUSER' => "Error: No se puede aÃ±adir el usuario",
    'USERREG'      => "Usuario registrado. Introduzca sus datos",
    'USERDEL'      => "Usuario eliminado.",
    'ERRORDEL'     => "Error no se puede eliminar el usuario."
];


// Definir otras constantes 