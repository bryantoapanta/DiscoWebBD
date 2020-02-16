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
	<h1 class="paginas">MODIFICAR USUARIO <?= $user ?> </h1>
	<form name='modificar' method="POST" action="index.php?operacion=Modificar" >
		<!-- COMPLETAR -->
		<table id="fmodificar">
			<tr>
				<td>Identificador</td>
				<td><input type="text" name="iduser" value="<?= $user ?>" readonly length></td>
			</tr>
			<tr>
				<td>Nombre</td>
				<td><input type="text" name="nombre" value="<?= $nombre ?>"></td>
			</tr>
			<tr>
				<td>Correo electrónico</td>
				<td><input type="email" name="email" value="<?= $mail ?>"></td>
			</tr>
			<tr>
				<td>Contraseña</td>
				<td><input type="password" name="clave1" value="<?= $clave ?>"></td>
			</tr>
			<tr>
				<td>Repetir contraseña</td>
				<td><input type="password" name="clave2" value="<?= $clave ?>"></td>
			</tr>
			<tr>
				<td>Plan</td>
				<td><select name="plan">
						<option value="0" <?= ($plan==0)?"selected=\"selected\"":""; ?>>Básico</option>
						<option value="1" <?= ($plan==1)?"selected=\"selected\"":""; ?>>Profesional</option>
						<option value="2" <?= ($plan==2)?"selected=\"selected\"":""; ?>>Premium</option>
						
				</select></td>
			</tr>
			
		</table>
		
		<p><input type='button' name="orden" value='Volver'
			onclick="verFicheros()"> <input type='submit' value='Modificar'>
			</p>
	</form>
</center>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>