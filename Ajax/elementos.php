<?php
$id_empresa = $_POST['id_empresa'];
include("bd.php");
$sql = "select * from elemento where id_empresa = $id_empresa order by id_elemento asc ";
$consulta = mysql_query($sql, $conEmp);
$i=0;
while($datatmp = mysql_fetch_assoc($consulta)) {
	$elementos[$i] = $datatmp;
	$i++;
}	
echo json_encode($elementos);
?>