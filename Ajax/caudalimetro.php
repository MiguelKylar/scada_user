<?php
function caudal($id_sensor,$id_nodo) {
        include("bd.php");
        $valor_ini = 0;
        $sumavalor = 0;
        $contcaudal = 0;
        $v = 0;
        $q = 0;
        $valor_anterior = 0;



		$sql = "select nombre_tabla_muestra,nombre_tabla_muestra_mensual from nodo where id_nodo = $id_nodo";
		$consulta2 = mysql_query($sql, $conEmp);
		while ($datatmp2 = mysql_fetch_array($consulta2)) {
		    $nombre_tabla_muestra = $datatmp2['nombre_tabla_muestra'];
		    $nombre_tabla_muestra_mensual = $datatmp2['nombre_tabla_muestra_mensual'];
		}
		$id_sensor_caudal_1 = $id_sensor;
        $sql = "select m3 from configuracion where id_sensor = $id_sensor_caudal_1";
        $consulta2 = mysql_query($sql, $conEmp);
        if ($datatmp2 = mysql_fetch_array($consulta2)) {
            $m3porpulso = $datatmp2['m3'];
        }

        if ($id_sensor_caudal_1 != 0) {
            $sensorescaudal = $id_sensor_caudal_1; 
            $auxnumerocaudal1 = 1;
            
			$asdaux = "SELECT valor,fecha, ROUND( UNIX_TIMESTAMP( fecha ) / ( 2 *300 ) ) AS timekey
			FROM $nombre_tabla_muestra
			WHERE id_sensor =$id_sensor_caudal_1
			GROUP BY timekey
			ORDER BY timekey DESC 
			LIMIT 2";
			$consultaaux = mysql_query($asdaux, $conEmp) or die(mysql_error());
            while ($datatmp1 = mysql_fetch_array($consultaaux)) {
                $value_d = $datatmp1['valor'] * 10;
                $fecha_d = $datatmp1['fecha'];
                if ($valor_ini != 0) {
                     $diff = (abs(strtotime($fecha_d) - strtotime($fecha_anterior)) / 60);
                     $factor = $m3porpulso / $diff * 60; 
                    if ($diff > 1) {
                        if (($valor_anterior - $value_d) > 65535) {
                            $valor_anteanterior = $valor_anterior;
                            $valor_anterior = $value_d - 0.3;
                        }
                        if (($valor_anterior - $value_d) >= 0) {
                              $datosensorcaudal = round(($valor_anterior - $value_d) * $factor,2);// iba *10

                        } else {
                        	 $datosensorcaudal = ($valor_anterior - $value_d + 65535) * $factor;  // iba *10
                            
                        }

                        $valor_anteanterior = $valor_anterior;
                        $fecha_anterior = $fecha_d;
                        $valor_anterior = $value_d;
                        $q++;
                    }else{
                    	$datosensorcaudal = 0;
                    }
                }else {
                    $valor_anteanterior = $valor_anterior;
                    $valor_anterior = $value_d;
                    $fecha_anterior = $fecha_d;
                    $valor_ini = 1;
                }
            }
        }
    return $datosensorcaudal;
}

?>