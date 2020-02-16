<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CRUD DE USUARIOS</title>
<link href="web/css/default.css" rel="stylesheet" type="text/css" />
<link  rel="icon"   href="imagenes/cpu-tower.png" type="image/png">
<script type="text/javascript" src="web/js/funciones.js"></script>
<script type="text/javascript" src="web/js/funcionesjquery.js"></script>
<script type="text/javascript" src="web/js/jquery.js"></script>
</head>
<body>
<div id="container">
<div id="header">
<h1>MI DISCO EN LA NUBE versi�n 1.0</h1>
</div>
<div id="content">
<?= $contenido ?>
</div>
</div>
<div id="autores"></div>
<script type="text/javascript">

//function jqueryFunction(){
var x = $(document);
x.ready(inicializarEventos);

function inicializarEventos() {
	
	  $(".modificacion").click(presionModificar);
	  $(".detalle").click(presionDetalles);
	  $(".users").click(presionCambiaColor);
	  $(".borrador").mouseover(CambiaColorBorrar);
	  $(".borrador").mouseout(CambiaColorNormal);
	  $(".modificacion").mouseover(CambiaColorModificar);
	  $(".modificacion").mouseout(CambiaColorNormal);
	  $(".detalle").mouseover(CambiaColorDetalles);
	  $(".detalle").mouseout(CambiaColorNormal);
}

function presionModificar() {
	  alert("Usted accederá a modificar sus datos");
}

function presionDetalles() {
	  alert("Usted accederá a comprobar los detalles");
}

function presionCambiaColor() {
	  var x;
	  x=$(this);
	  x.css("color","#ff0000")
	  x.css("background-color","#ffff00")
	  x.css("font-family","courier")
}

function CambiaColorBorrar() {
	  var x;
	  x=$(this);
	  x.css("color","#ffffff")
	  x.css("background-color","#FF0000")
	  x.css("font-family","Arial")
}

function CambiaColorModificar() {
	  var x;
	  x=$(this);
	  x.css("color","#ffffff")
	  x.css("background-color","#3b83bd")
	  x.css("font-family","Arial")
}

function CambiaColorDetalles() {
	  var x;
	  x=$(this);
	  x.css("color","#ffffff")
	  x.css("background-color","#e5be01")
	  x.css("font-family","Arial")
}

function CambiaColorNormal() {
	  var x;
	  x=$(this);
	  x.css("color","#ffffff")
	  x.css("background-color","#FFFFFF")
	  x.css("font-family","sanssolid")
}

//}
</script>
</body>
</html>
