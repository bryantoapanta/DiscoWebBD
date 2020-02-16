<?php
// Guardo la salida en un buffer(en memoria)
// No se envia al navegador
ob_start();

?>
<div id='aviso'><b><?= (isset($msg))?$msg:"" ?></b></div>
<center>
	<h1 class="paginas">Usuarios</h1>
	<table id="verusuarios">
		<tr>
		
			<th>ID</th>
			<th>NOMBRE</th>
			<th>CORREO</th>
			<th>TIPO</th>
			<th>ESTADO</th>
<?php
$auto = $_SERVER['PHP_SELF'];
// identificador => Nombre, email, plan y Estado
?>
<?php foreach ($usuarios as $clave => $datosusuario) : ?>
		
		<tr>
			<td class="users"><?= $clave ?></td> 
			<?php for  ($j=0; $j < count($datosusuario); $j++) :?>
     		<?php 
     		if ($j==0 || $j==1 || $j==3){
     		    echo "<td class=\"ocultos\">";
     		    echo $datosusuario[$j];
     		}else{
     		    echo "<td>";
     		    echo $datosusuario[$j];
     		}
     		 ?></td>
			<?php endfor;?>
			<td class="borrador"  ><a href="#" onclick="confirmarBorrar('<?= $datosusuario[0]."','".$clave."'"?>);">&#9760;&#9851;</a></td>
			<td class="modificacion"><a href="<?= $auto?>?orden=Modificar&id=<?= $clave ?>">&#9998;</a></td>
			<td class="detalle"><a href="<?= $auto?>?orden=Detalles&id=<?= $clave?>">&#9776;</a></td>
		</tr>
<?php endforeach; ?>
</table>

	<br>
	<form action='index.php'>
		<input type='button' value='Cerrar sesión' onclick="cerrarSesion()">
		<input type='button' value='Nuevo usuario' onclick="altaUsuario()">
		
	</form>
	
</center>
<?php
// Vacio el bufer y lo copio a contenido
// Para que se muestre en div de contenido de la pÃ¡gina principal
$contenido = ob_get_clean();
include_once "principal.php";

?>
