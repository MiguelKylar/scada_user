<?php
include("bd.php");
$sql = "select max(id_elemento) as maximo_elemento from elemento";
$consulta = mysql_query($sql, $conEmp);
if($datatmp = mysql_fetch_array($consulta)) {
    $maximo_elemento = $datatmp['maximo_elemento'];
	$maximo_elemento = $maximo_elemento+1;
}else{
 $maximo_elemento = 1;   
}
$nombre = 'valvula mariposa '.$maximo_elemento;
$sql = "INSERT INTO elemento (id_elemento,descripcion) VALUES($maximo_elemento,'$nombre')";
$consulta = mysql_query($sql, $conEmp) or die(mysql_error());
echo $maximo_elemento;
?>