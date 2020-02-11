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

	<h1 class="paginas">Crear nuevo usuario</h1>

	<form name='ALTA' method="POST" action="index.php?orden=Alta">
		<!-- COMPLETAR -->
		<table id="fnuevo">
			<tr>
				<td>Identificador:
				<td>
				
				<td><input type="text" name="iduser" required>
				
				<td>
			
			</tr>

			<tr>
				<td>Nombre:
				
				<td>
				
				<td><input type="text" name="nombre" required>
				
				<td>
			
			</tr>

			<tr>
				<td>Correo electrónico:
				
				<td>
				
				<td><input type="email" name="email" required>
				
				<td>
			
			</tr>

			<tr>
				<td>Contraseña:
				
				<td>
				
				<td><input type="password" name="clave1" required>
				
				<td>
			
			</tr>

			<tr>
				<td>Repetir Contraseña:
				
				<td>
				
				<td><input type="password" name="clave2" required>
				
				<td>
			
			</tr>

			<tr>
				<td>Plan:
				
				<td>
				
				<td><select name="plan" required>
						<option value="0">Básico</option>
						<option value="1">Profesional</option>
						<option value="2">Premium</option>
						<option value="3">Máster</option>
				</select>
				
				<td>

			</tr>
			<tr>
				<td>Estado:
				<td>
				<td><select name="estado">
						<option value="A">Activo</option>
						<option value="I">Inactivo</option>
						<option value="B">Bloqueado</option>
				</select>
				<td>
			</tr>
		</table>

		<p>
			<input type='button' name="orden" value='Volver'
				onclick="verUsuarios()"> <input type='submit' value='Crear'>
		</p>
	</form>
</center>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido
$contenido = ob_get_clean();
include_once "principal.php";

?>