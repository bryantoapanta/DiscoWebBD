/**
 * Funciones auxiliares de javascripts
 */
function confirmarBorrar(nombre, id) {
	if (confirm("¿Quieres eliminar el usuario:  " + nombre + "?")) {
		document.location.href = "?orden=Borrar&id=" + id;
	}
}

function verUsuarios() {
	if (confirm("¿Quieres volver atrás?")) {
		document.location.href = "?orden=VerUsuarios";
	}
}

function volverInicio() {
	if (confirm("¿Quieres volver atrás?")) {
		document.location.href = "index.php?orden=Inicio";
	}
}

function cerrarSesion() {
	document.location.href = "index.php?orden=Cerrar";
}

function altaUsuario() {
	document.location.href = "?orden=Alta";
}

function alerta() {
	alert("Usuario creado");
}



//--------------------------------------------------------JS FCHEROS--------------------------
function cerrarSesionUsuario() {
	document.location.href = "index.php?operacion=Cerrar";
}

function subirFicheros() {
	document.location.href = "?operacion=Nuevo";
	
}

function verFicheros() {
	if (confirm("¿Quieres volver atrás?")) {
		document.location.href = "?operacion=VerFicheros";
	}
}

function modificarDatos() {
	if (confirm("¿Quieres modificar tus datos?")) {
		document.location.href = "?operacion=Modificar";
	}
}

function confirmarBorrarfichero(nombre, id) {
	if (confirm("¿Quieres eliminar el archivo:  " + nombre + "?")) {
		document.location.href = "?operacion=Borrar&id=" + id+"&nombre="+nombre;
	}
}

function confirmarRenombrarfichero(nombre, id) {
	if (confirm("¿Quieres cambiar el nombre del archivo:  " + nombre + "?")) {
		var nuevo=String(prompt("Introduce nuevo nombre"));
		document.location.href = "?operacion=Renombrar&id=" + id+"&nombre="+nombre+"&nuevo="+nuevo;
	}
}
