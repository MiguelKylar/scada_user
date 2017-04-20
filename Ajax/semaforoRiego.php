<?php
include("bd.php");
$id_nodo = $_POST['id_nodo'];

 $date1 = new DateTime("now");
 $date1 = $date1->format('Y-m-d H:i:s');

$sql = "select * from programacion where id_nodo = $id_nodo order by id_programacion_riego desc limit 1";
$resEmp2 = mysql_query($sql, $conEmp);
if($datatmp2 = mysql_fetch_assoc($resEmp2)) {
  $riego[0] = $datatmp2;
  $riego[0]['fecha_now'] = $date1;
}
echo json_encode($riego);
?>