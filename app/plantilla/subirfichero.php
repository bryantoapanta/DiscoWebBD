<?php

// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();
// FORMULARIO DE ALTA DE USUARIOS
?>
<div id='aviso'>
	<b><?= (isset($msg))?$msg:"" ?></b>
</div>
<center>
<h2>Subida y alojamiento de archivo en el servidor del usuario <?php $_SESSION["user"]?></h2>
<!-- el atributo enctype del form debe valer "multipart/form-data" -->
<!-- el atributo method del form debe valer "post" -->
<form name="f1" enctype="multipart/form-data" action="index.php?operacion=Nuevo" method="post">


<!-- Se fija en el cliente el tama�o m�ximo en bytes ( no es seguro ) el limite m�ximo se debe tener el archivo 
  Se debe controlar tambi�n en el servidor (php.ini)
-->
<input type="hidden" name="MAX_FILE_SIZE" value="100000" /> <!--  100Kbytes -->

<label>Elija el archivo a subir</label> <input name="archivo1" type="file"  /> <br />

<p>
<input type='button' name="orden" value='Volver' onclick="verFicheros()"> 
<input type="submit" value="Subir" />
</p>
</form>
</center>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>