<?php
include("bd.php");
$id_nodo = $_POST['id_nodo'];
$date1 = new DateTime("now");
$date1 = $date1->format('Y-m-d H:i:s');
$n_identificador = 1;

$hoy = strtotime($date1);
$minutos = 2;
$minutos = $minutos * 60;
$hoy+=$minutos;
$hoy = date("Y-m-d H:i:s", $hoy);


$sql = "select id_empresa,red from nodo where id_nodo = $id_nodo";
$resEmp = mysql_query($sql,$conEmp);
if($datatmp = mysql_fetch_array($resEmp)){
   $id_empresaControl = $datatmp['id_empresa'];
   $red = $datatmp['red'];
}

$sql = "select * from control_riego where id_nodo = $id_nodo";
$resEmp = mysql_query($sql,$conEmp);
if($datatmp = mysql_fetch_array($resEmp)){
   $id_sectorControl = $datatmp['id_sector'];
   $casetaControl = $datatmp['caseta'];
   $equipoControl = $datatmp['equipo'];
   $nombre_sectorControl = $datatmp['sector'];
}

$sql = "select max(n_identificador) from programacion where id_nodo = '$id_nodo'";
$consulta = mysql_query($sql, $conEmp);
if ($datatmp4 = mysql_fetch_array($consulta)) {
    $n_identificadormax = $datatmp4['max(n_identificador)'];
    $n_identificador = $n_identificadormax + 1;
}


echo $sql = "select estado,id_programacion_riego from programacion where id_sector = $id_sectorControl and id_empresa = $id_empresaControl and (estado = 1 or estado = 0) and p_fecha_ini < '$date1'and p_fecha_fin > '$date1'";
$resEmp2 = mysql_query($sql, $conEmp);
$sw3 = 0;
if (($datatmp2 = mysql_fetch_array($resEmp2))) {
    $id_programacion_riego = $datatmp2['id_programacion_riego'];    
    $sql4 = "UPDATE programacion SET  estado = 3 WHERE id_programacion_riego = '$id_programacion_riego'";
    $consulta = mysql_query($sql4, $conEmp); 
}

?>