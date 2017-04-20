<?php
function m3_totales($id_sensor,$id_nodo) {
include("bd.php");

$date1 = new DateTime("now");
$fechanow = $date1->format('Y-m-d');

$fecha_ini = $fechanow.' 00:00:00';
$fecha_fin = $fechanow.' 23:59:59';


$sql = "select nombre_tabla_muestra,nombre_tabla_muestra_mensual from nodo where id_nodo = $id_nodo";
$consulta2 = mysql_query($sql, $conEmp);
while ($datatmp2 = mysql_fetch_array($consulta2)) {
    $nombre_tabla_muestra = $datatmp2['nombre_tabla_muestra'];
    $nombre_tabla_muestra_mensual = $datatmp2['nombre_tabla_muestra_mensual'];
}

$asd="SELECT valor, fecha FROM $nombre_tabla_muestra WHERE id_sensor='$id_sensor' and fecha >'$fecha_ini' AND fecha <'$fecha_fin' order by fecha asc";
$sql = "select m3 from configuracion where id_sensor = $id_sensor";
        $consulta2 = mysql_query($sql, $conEmp);
        if ($datatmp2 = mysql_fetch_array($consulta2)) {
            $m3porpulso = $datatmp2['m3'];
        }
$m=0;
$valor = 0;
$consultaaux = mysql_query($asd, $conEmp) or die(mysql_error());
$objeto = mysql_num_rows($consultaaux);
            $valor_ini = 0;
            $sumavalor = 0;
            $contcaudal = 0;
            $v = 0;
            $q = 0;
            $valor_anterior = 0;
            $counter=0;
            $datosensorcaudal[$m] = array();
            $fechadatosensorcaudal[$m] = array();
            while ($datatmp1 = mysql_fetch_array($consultaaux)) {
                $value_d= $datatmp1['valor'] * 10;
                $valor= $valor + ($datatmp1['valor']*$m3porpulso) * 10/1000; // Se multiplica por 10 por tamaño del registro en base de datos y luego se divide po 100 para mostrar en m3
                $fecha_d = $datatmp1['fecha'];
                $fecha[$counter] =$datatmp1['fecha'];
                $counter++;
                if ($valor_ini != 0) {
                    $diff = (abs(strtotime($fecha_d) - strtotime($fecha_anterior)) / 60);
                    $factor = $m3porpulso / $diff;
                    if ($diff > 1) {
                        if (($value_d - $valor_anterior) > 65535) {
                            $valor_anteanterior = $valor_anterior;
                            $valor_anterior = $value_d - 0.3;
                        }
                        if (($value_d - $valor_anterior) >= 0) {
                            //$datosensorcaudalsintime[$q]=round($value_d*$m3porpulso-$valor_anterior*$m3porpulso,2)*60;// iba *10
                            $datosensorcaudal[$m][$q] = round(($value_d - $valor_anterior) * $factor, 2); // iba *10
                        } else {

                            if (($value_d - $valor_anterior + 65535) > ($valor_anterior - $valor_anteanterior) * 2) {// el problema es cuando $q=0
                                $datosensorcaudal[$m][$q] = $datosensorcaudal[$m][$q - 1];
                            } else {
                                //echo ($valor_anteanterior*10);
                                //$datosensorcaudalsintime[$q]=round($datatmp1['valor']*10*$m3porpulso-$valor_anterior*10*$m3porpulso+65535,2)*60;// iba *10
                                $datosensorcaudal[$m][$q] = round(($value_d - $valor_anterior + 65535) * $factor, 2);  // iba *10
                            }
                        }
                        /* if($asd ==1){
                          $fechadatosensorcaudal[$q]=$datatmp1['fecha'];
                          } */
                        $fechadatosensorcaudal[$m][$q] = $fecha_d;

                        $valor_anteanterior = $valor_anterior;
                        $fecha_anterior = $fecha_d;
                        $valor_anterior = $value_d;
                        $q++;
                    }
                } else {
                    $valor_anteanterior = $valor_anterior;
                    $valor_anterior = $value_d;
                    $fecha_anterior = $fecha_d;
                    $valor_ini = 1;
                }
            }

            mysql_free_result($consultaaux);

   // $puntero = $puntero -1;
    return $valor;
}
?>