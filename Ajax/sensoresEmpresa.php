<?php
$id_empresa = $_POST['id_empresa'];
include("bd.php");
$sql = "SELECT id_elemento from elemento where id_empresa = $id_empresa group by id_elemento";
$consulta = mysql_query($sql, $conEmp);
$i=0;
$sw=0;
while($datatmp = mysql_fetch_array($consulta)) {
	$id_elemento = $datatmp['id_elemento'];
	
	$sql = "SELECT id from elemento_aplicacion where id_elemento = $id_elemento and tabla != 'control_riego'";
	$consulta1 = mysql_query($sql, $conEmp);
	while($datatmp1 = mysql_fetch_array($consulta1)) {
		$id = $datatmp1['id'];

		$sql = "SELECT tipo from sensor where id_sensor = $id";
		$consulta2 = mysql_query($sql, $conEmp);
		while($datatmp2 = mysql_fetch_array($consulta2)) {
		    $tipo = $datatmp2['tipo'];			
			$sql = "SELECT nombre from parametro where tipo = '$tipo'";
			$consulta3 = mysql_query($sql, $conEmp);
			while($datatmp3 = mysql_fetch_array($consulta3)) {
			
			$capas[$i] = $datatmp3['nombre'];				
			$i++;
			}
		}
	}
	$sql = "SELECT id from elemento_aplicacion where id_elemento = $id_elemento and tabla = 'control_riego'";
	$consulta1 = mysql_query($sql, $conEmp);
	while($datatmp1 = mysql_fetch_array($consulta1)) {
		$sw=1;
	}
}
if($sw == 1){
	$capas[$i] = "control";
}

echo json_encode($capas);
?>

