<?php


// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();

?>

<center>
<?php
$auto = $_SERVER['PHP_SELF'];
/*
 * identificador => Nombre, email, plan y Estado
 */
foreach ($usuarios as $clave => $datosusuario) { // clave-->usuario $datosusuario--> array con datos del usuario
    ?>

<?php
    echo "<h1 class=\"paginas\">Detalles de ", $clave, "</h1>";

    for ($j = 0; $j < count($datosusuario); $j ++) {
        switch ($j) {
            case 0:
                echo "<p>Nombre: ", $datosusuario[$j], "</p>";
                break;
            case 1:
                echo "<p>Email: ", $datosusuario[$j], "</p>";
                break;

            case 2:
                echo "<p>Plan: ", $datosusuario[$j], "</p>";
                break;

            case 3:
                echo "<p>Estado: ", $datosusuario[$j], "</p>";
                break;
                
            case 4:
                echo "<p>Espacio ocupado: ", $datosusuario[$j], " KB</p>";
                break;
                
            case 5:
                echo "<p>Numero de ficheros: ", $datosusuario[$j], "</p>";
                break;

        }
    }
    ?>

<?php

}

?>
<br>
<form action='index.php'>
	<input type='hidden' name='orden' value='VerUsuarios'> <input
		type='submit' value='Volver'>
</form>

</center>

<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la página principal
$contenido = ob_get_clean();
include_once "principal.php";

?>