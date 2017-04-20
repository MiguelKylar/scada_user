<?php
include("bd.php");
$id_elemento = $_POST['id_elemento'];
$sql = "DELETE FROM `elemento` WHERE id_elemento = $id_elemento";
$consulta = mysql_query($sql, $conEmp);


?>