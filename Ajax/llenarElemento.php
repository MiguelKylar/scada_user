<?php

$id_elemento = $_POST['id_elemento'];
include("bd.php");
$indice = 0;
$sw = 0;
$sensores = NULL;
$sql = "select * from elemento_aplicacion where id_elemento = $id_elemento and prioridad = 1";
$consulta = mysql_query($sql, $conEmp);
while ($datatmp = mysql_fetch_array($consulta)) {
    $id = $datatmp['id'];
    $id_aplicacion = $datatmp['id_aplicacion'];
    $tabla = $datatmp['tabla'];
    $id_tabla = $datatmp['id_tabla'];
    if ($tabla == 'sensor') {
        $sql = "select * from sensor where id_sensor = $id";
        $consulta1 = mysql_query($sql, $conEmp);
        $resultado = 0;
        while ($datatmp1 = mysql_fetch_assoc($consulta1)) {
            $resultado = count($sensores);
            if ($resultado == 0) {
                $sensores[$indice] = $datatmp1;
                $tipo = $sensores[$indice]['tipo'];
                $sql = "select unidad from parametro where tipo = '$tipo'";
                $consulta2 = mysql_query($sql, $conEmp);
                if ($datatmp2 = mysql_fetch_array($consulta2)) {
                    $unidad = $datatmp2['unidad'];
                    $sensores[$indice]['unidad'] = $unidad;
                }
            } else {
                $sensores2[$indice] = $datatmp1;
                for ($i = 0; $i < $resultado; $i++) {
                    if ($sensores2[$indice]['tipo'] == $sensores[$i]['tipo']) {
                        $sw = 1;
                    }
                    if ($sw != 1) {
                        $sensores[$indice] = $datatmp1;
                        $tipo = $sensores[$indice]['tipo'];
                        $sql = "select unidad from parametro where tipo = '$tipo'";
                        $consulta2 = mysql_query($sql, $conEmp);
                        while ($datatmp2 = mysql_fetch_array($consulta2)) {
                            $unidad = $datatmp2['unidad'];
                            $sensores[$indice]['unidad'] = $unidad;
                        }
                    } else {
                        $indice = $indice - 1;
                    }
                }$sw = 0;
            }
            $indice = $indice + 1;
        }
    }
}
echo json_encode($sensores);
?>