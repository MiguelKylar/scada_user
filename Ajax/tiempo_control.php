<?php
function tiempo_caido_control($id_nodo) {
include("bd.php");
	        $sql = "select fecha from control_riego where id_nodo = $id_nodo";
		$consulta2 = mysql_query($sql, $conEmp);
		if ($datatmp2 = mysql_fetch_array($consulta2)) {
		     $fecha = $datatmp2['fecha'];
		}
		$date1 = new DateTime("now");
		 $date1 = $date1->format('Y-m-d H:i:s');
		 $resultado = strtotime($date1) - strtotime($fecha);
        $resultado = round($resultado / 60);
        if($resultado <1){
        	$resultado = 0;
        }
 return $resultado;
}
?>

