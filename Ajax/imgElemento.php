<?php
$id_elemento = $_POST['id_elemento'];
include("bd.php");

$sql = "select img from elemento where id_elemento = $id_elemento";
$consulta = mysql_query($sql, $conEmp);
if($datatmp = mysql_fetch_array($consulta)) {
	$img = $datatmp['img'];
}

echo $img;
?>