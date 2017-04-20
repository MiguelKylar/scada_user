<?php
$id_elemento = $_POST['id_elemento'];
$id_elemento = explode("_",$id_elemento);
$id_elemento = $id_elemento[1];
include("bd.php");
include("caudalimetro.php");
include("tiempoCaido.php");
include("m3totales.php");
$indice = 0;
$sensores = NULL;
$sw = 0;
$sql = "select * from elemento_aplicacion where id_elemento = $id_elemento group by id";
$consulta = mysql_query($sql, $conEmp);
while($datatmp = mysql_fetch_array($consulta)) {	
    $id = $datatmp['id'];
	$id_aplicacion = $datatmp['id_aplicacion'];
	$tabla = $datatmp['tabla'];
	$id_tabla = $datatmp['id_tabla'];
	if($tabla == 'sensor'){
	 $sql = "select * from sensor where id_sensor = $id";
	$consulta1 = mysql_query($sql, $conEmp);
	$resultado = 0;
	while($datatmp1 = mysql_fetch_assoc($consulta1)){
	$swCaudal =0;
	$swTiempo =0;
	$swM3Totales =0;
	$sql = "select id_aplicacion as id2, (SELECT url FROM aplicacion2 WHERE id_aplicacion = id2) as url,(SELECT icono FROM aplicacion2 WHERE id_aplicacion = id2) as icono,(SELECT aplicacion FROM aplicacion2 WHERE id_aplicacion = id2) as app,(SELECT tipo FROM aplicacion2 WHERE id_aplicacion = id2) as tipo from elemento_aplicacion where id = $id and tabla = '$tabla' and id_elemento = $id_elemento";
	$consulta3 = mysql_query($sql, $conEmp);
	$m=0;
	while($datatmp3 = mysql_fetch_assoc($consulta3)) {
		$aplicacion[$m]= $datatmp3;
		$m++;
	}	
		$resultado = count($sensores);
			if($resultado == 0){
					$sensores[$indice] = $datatmp1;	
					$sensores[$indice]['id_aplicacion'] = $aplicacion;
					$sensores[$indice]['aux_app'] = $aplicacion;
					$tipo = $sensores[$indice]['tipo'];
					$sql = "select unidad from parametro where tipo = '$tipo'";
					$consulta2 = mysql_query($sql, $conEmp);
					if($datatmp2 = mysql_fetch_array($consulta2)){
						$unidad = $datatmp2['unidad'];
						$sensores[$indice]['unidad'] = $unidad;
					}
					$largo =  count($sensores[$indice]['aux_app']);
					for ($i=0; $i < $largo;$i++) {
		 					if($sensores[$indice]['aux_app'][$i]['tipo'] == 1){
		 						if($i ==0){
		 						if($sensores[$indice]['aux_app'][$i]['app'] == "Caudal" && $swCaudal != 1 ){
		 							$swCaudal = 1;
		 							$sensores[$indice]['nombre'] = "Caudalimetro";
		 							$sensores[$indice]['valor'] = caudal($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
		 							$sensores[$indice]['descripcion'] = "Caudalimetro";
		 							$sensores[$indice]['id_aplicacion'] = "";
		 							$sensores[$indice]['id_aplicacion'][0] = new ArrayObject;
			 						$sensores[$indice]['id_aplicacion'][0]['url'] = "http://200.24.229.186/optimusV2/graficos.php"; 
		 							$sensores[$indice]['id_aplicacion'][0]['icono'] = "diluviar.png";
		 							$sensores[$indice]['id_aplicacion'][0]['app'] = "Caudal";
		 							$sensores[$indice]['id_aplicacion'][0]['tipo'] = "1";
		 							$sensores[$indice]['unidad'] = "m3/min";
}
								if($sensores[$indice]['aux_app'][$i]['app'] == "Tiempo caido" && $swTiempo != 1){
											$swTiempo = 1;
				 							$sensores[$indice]['valor'] = tiempo_caido($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
				 							$sensores[$indice]['descripcion'] = "Tiempo Caido";
				 							$sensores[$indice]['unidad'] = "min";
				 							$sensores[$indice]['id_aplicacion'] = " ";
		 						}
								if($sensores[$indice]['aux_app'][$i]['app'] == "M3 Totales" && $swM3Totales != 1){
											$swM3Totales = 1;
				 							$sensores[$indice]['valor'] = m3_totales($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
				 							$sensores[$indice]['descripcion'] = "Tiempo Caido";
				 							$sensores[$indice]['unidad'] = "min";
				 							$sensores[$indice]['id_aplicacion'] = " ";
		 						}
		 						}else{
		 							$indice = $indice+1;
			 						$sensores[$indice] = $datatmp1;	
									$sensores[$indice]['id_aplicacion'] = $aplicacion;
									$sensores[$indice]['aux_app'] = $aplicacion;
									$tipo = $sensores[$indice]['tipo'];
									$sql = "select unidad from parametro where tipo = '$tipo'";
									$consulta2 = mysql_query($sql, $conEmp);
									if($datatmp2 = mysql_fetch_array($consulta2)){
										$unidad = $datatmp2['unidad'];
										$sensores[$indice]['unidad'] = $unidad;
									}
									
									$largo =  count($sensores[$indice]['aux_app']);
													for ($x=0; $x < $largo;$x++) { 
										 					if($sensores[$indice]['aux_app'][$x]['tipo'] == 1){

										 						if($sensores[$indice]['aux_app'][$x]['app'] == "Caudal" && $swCaudal != 1){
										 							$swCaudal = 1;
										 							$sensores[$indice]['nombre'] = "Caudalimetro";
										 							$sensores[$indice]['valor'] = caudal($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
										 							$sensores[$indice]['descripcion'] = "Caudalimetro";
										 							$sensores[$indice]['id_aplicacion'] = "";
										 							$sensores[$indice]['id_aplicacion'][0] = new ArrayObject;
											 						$sensores[$indice]['id_aplicacion'][0]['url'] = "http://200.24.229.186/optimusV2/graficos.php"; 
										 							$sensores[$indice]['id_aplicacion'][0]['icono'] = "diluviar.png";
										 							$sensores[$indice]['id_aplicacion'][0]['app'] = "Caudal";
										 							$sensores[$indice]['id_aplicacion'][0]['tipo'] = "1";
										 							$sensores[$indice]['unidad'] = "m3/min";
										 							$x = $largo;
										 						}

																if($sensores[$indice]['aux_app'][$x]['app'] == "Tiempo caido" && $swTiempo != 1){
																	$swTiempo = 1;
										 							$sensores[$indice]['valor'] = tiempo_caido($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
										 							$sensores[$indice]['descripcion'] = "Tiempo Caido";
										 							$sensores[$indice]['unidad'] = "min";
										 							$sensores[$indice]['id_aplicacion'] = " ";
										 							$x = $largo;
										 						}
										 						if($sensores[$indice]['aux_app'][$i]['app'] == "M3 Totales" && $swM3Totales != 1){
																	$swM3Totales = 1;
										 							$sensores[$indice]['valor'] = m3_totales($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
										 							$sensores[$indice]['descripcion'] = "Tiempo Caido";
										 							$sensores[$indice]['unidad'] = "min";
										 							$sensores[$indice]['id_aplicacion'] = " ";
										 							$x = $largo;
										 						}	
										 					}	
										 			}
										 			
												
									}
							}	
		 			}
		 		$indice = $indice+1;	
			}else{
				$sensores2[$indice] = $datatmp1;
				for ($i = 0; $i < $resultado; $i++) {
					if($sensores2[$indice]['tipo'] == $sensores[$i]['tipo']){
					$sw = 1;
					}
					if($sw != 1){
						$sensores[$indice] = $datatmp1;	
						$sensores[$indice]['id_aplicacion'] = $aplicacion;
						$sensores[$indice]['aux_app'] = $aplicacion;
						while($datatmp2 = mysql_fetch_array($consulta2)){
							$unidad = $datatmp2['unidad'];
							$sensores[$indice]['unidad'] = $unidad;
						}
						
						
						$tipo = $sensores[$indice]['tipo'];
						$sql = "select unidad from parametro where tipo = '$tipo'";
						$consulta2 = mysql_query($sql, $conEmp);
						$largo =  count($sensores[$indice]['id_aplicacion']);
						for ($i=0; $i < $largo;$i++) {
			 					if($sensores[$indice]['aux_app'][$i]['tipo'] == 1){
			 						if($i ==0){
			 						if($sensores[$indice]['aux_app'][$i]['app'] == "Caudal" && $swCaudal != 1){
			 							$swCaudal = 1;
			 							$sensores[$indice]['descripcion'] = "Caudalimetro";
			 							$sensores[$indice]['nombre'] = "Caudalimetro";
			 							$sensores[$indice]['valor'] = caudal($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
			 							$sensores[$indice]['id_aplicacion'] = "";
			 							$sensores[$indice]['id_aplicacion'][0] = new ArrayObject;
				 						$sensores[$indice]['id_aplicacion'][0]['url'] = "http://200.24.229.186/optimusV2/graficos.php"; 
			 							$sensores[$indice]['id_aplicacion'][0]['icono'] = "diluviar.png";
			 							$sensores[$indice]['id_aplicacion'][0]['app'] = "Caudal";
			 							$sensores[$indice]['id_aplicacion'][0]['tipo'] = "1";
			 							$sensores[$indice]['unidad'] = "m3/min";

			 						}
									if($sensores[$indice]['aux_app'][$i]['app'] == "Tiempo caido" && $swTiempo != 1){
										$swTiempo = 1;
			 							$sensores[$indice]['valor'] = tiempo_caido($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
			 							$sensores[$indice]['descripcion'] = "Tiempo Caido";
			 							$sensores[$indice]['unidad'] = "min";
			 							$sensores[$indice]['id_aplicacion'] = " ";
			 						}
			 						if($sensores[$indice]['aux_app'][$i]['app'] == "M3 Totales" && $swM3Totales != 1){
										$swM3Totales = 1;
			 							$sensores[$indice]['valor'] = m3_totales($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
			 							$sensores[$indice]['descripcion'] = "Tiempo Caido";
			 							$sensores[$indice]['unidad'] = "min";
			 							$sensores[$indice]['id_aplicacion'] = " ";
			 						}	
		
			 						}else{
			 							$indice = $indice+1;
				 						$sensores[$indice] = $datatmp1;	
										$sensores[$indice]['id_aplicacion'] = $aplicacion;
										$sensores[$indice]['aux_app'] = $aplicacion;
										$tipo = $sensores[$indice]['tipo'];
										$sql = "select unidad from parametro where tipo = '$tipo'";
										$consulta2 = mysql_query($sql, $conEmp);
										if($datatmp2 = mysql_fetch_array($consulta2)){
											$unidad = $datatmp2['unidad'];
											$sensores[$indice]['unidad'] = $unidad;
										}
										
										
										$largo =  count($sensores[$indice]['id_aplicacion']);
														for ($x=0; $x < $largo;$x++) { 
											 					if($sensores[$indice]['aux_app'][$x]['tipo'] == 1){
											 						if($sensores[$indice]['aux_app'][$x]['app'] == "Caudal"  && $swCaudal != 1 ){
											 							$sensores[$indice]['descripcion'] = "Caudalimetro";
											 							$swCaudal = 1;
											 							$sensores[$indice]['nombre'] = "Caudalimetro";
											 							$sensores[$indice]['valor'] = caudal($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
											 							$sensores[$indice]['id_aplicacion'] = "";
											 							$sensores[$indice]['id_aplicacion'][0] = new ArrayObject;
												 						$sensores[$indice]['id_aplicacion'][0]['url'] = "http://200.24.229.186/optimusV2/graficos.php"; 
											 							$sensores[$indice]['id_aplicacion'][0]['icono'] = "diluviar.png";
											 							$sensores[$indice]['id_aplicacion'][0]['app'] = "Caudal";
											 							$sensores[$indice]['id_aplicacion'][0]['tipo'] = "1";
											 							$sensores[$indice]['unidad'] = "m3/min";
											 							$x = $largo;
											 						}
																	else if($sensores[$indice]['aux_app'][$x]['app'] == "Tiempo caido" && $swTiempo != 1){
																		$swTiempo = 1;
										 								$sensores[$indice]['valor'] = tiempo_caido($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
										 								$sensores[$indice]['descripcion'] = "Tiempo Caido";
										 								$sensores[$indice]['unidad'] = "min";
										 								$sensores[$indice]['id_aplicacion'] = " ";
											 							$x = $largo;
											 						}
											 						else if($sensores[$indice]['aux_app'][$i]['app'] == "M3 Totales" && $swM3Totales != 1){
																		$swM3Totales = 1;
											 							$sensores[$indice]['valor'] = m3_totales($sensores[$indice]['id_sensor'],$sensores[$indice]['id_nodo']);
											 							$sensores[$indice]['descripcion'] = "M3 Totales";
											 							$sensores[$indice]['unidad'] = "m3";	
											 							$sensores[$indice]['id_aplicacion'] = " ";
											 							$x = $largo;
											 						}	
											 						//echo $sensores[$indice]['descripcion'] ;			echo "\n";	
											 					}	
											 			}
											 			
										
										}
								}	
			 			}	
					}else{
						$indice=$indice-1;
					}
				}$sw = 0;
		    }
		
		$aplicacion = null;
	}
	}elseif($tabla == 'control_riego'){
	   $sql = "SELECT post,icono,url,id_aplicacion FROM aplicacion2 WHERE id_aplicacion = $id_aplicacion";
	   $consulta33 = mysql_query($sql, $conEmp);
	   while($datatmp9 = mysql_fetch_assoc($consulta33)){
		  $apis = $datatmp9;
	   }	
	   $sql = "select id_empresa,equipo,id_nodo,estado,nombre_sector from control_riego where id_control_riego = $id";
	   $consulta33 = mysql_query($sql, $conEmp);
	   while($datatmp9 = mysql_fetch_assoc($consulta33)){
		   $id_nodo22 = $datatmp9['id_nodo'];
		   $estado22  = $datatmp9['estado'];
		   $id_empresa = $datatmp9['id_empresa'];
		   $id_equipo  = $datatmp9['equipo'];
		   if($estado22 == 1){
			   $estado22 = 'Funcionando';
		   }elseif($estado22 == 2  or $estado22 == 0){
			   $estado22 = 'Detenido';
		   }
		   $nombre_sector  = $datatmp9['nombre_sector'];
       	   $sensores[$indice]['id_nodo'] =  $id_nodo22;
		   $sensores[$indice]['valor'] =  $estado22;
		   $sensores[$indice]['unidad'] =  " ";
		   $sensores[$indice]['id_equipo'] =  $id_equipo;	
		   $sensores[$indice]['id_empresa'] =  $id_empresa;	
		   $sensores[$indice]['descripcion'] =  $nombre_sector;
		   $sensores[$indice]['tipo'] =  "control";
		   $sensores[$indice]['id_aplicacion'][0] =  $apis ;

		   
		   $date1 = new DateTime("now");
		   $date1 = $date1->format('Y-m-d H:i:s');
		   $sql = "SELECT estado,p_fecha_ini,p_fecha_fin FROM  programacion WHERE id_nodo = $id_nodo22  order by id_programacion_riego desc LIMIT 1";
		   $consulta1 = mysql_query($sql, $conEmp);
		   if($datatmp1 = mysql_fetch_array($consulta1)){
		   	  $sensores[$indice]['ultimo_estado'] =  $datatmp1['estado']; 	
		   	  $sensores[$indice]['p_fecha_ini'] =  $datatmp1['p_fecha_ini'];
		   	  $sensores[$indice]['p_fecha_fin'] =  $datatmp1['p_fecha_fin'];
		   	  $sensores[$indice]['fecha_now'] =  $date1; 	
		   }
	   }
	   $apis = null;
	   $indice++;
	}elseif($tabla == 'sensor_a'){
	$sql = "select * from sensor_a where id_sensor = $id";
	$consulta1 = mysql_query($sql, $conEmp);
	$resultado = 0;
	while($datatmp1 = mysql_fetch_assoc($consulta1)){
	$sql = "select id_aplicacion as id2, (SELECT url FROM aplicacion2 WHERE id_aplicacion = id2) as url,(SELECT icono FROM aplicacion2 WHERE id_aplicacion = id2) as icono from elemento_aplicacion where id = $id and tabla = '$tabla'";
	$consulta3 = mysql_query($sql, $conEmp);
	$m=0;
	while($datatmp3 = mysql_fetch_assoc($consulta3)) {
		$aplicacion[$m]= $datatmp3;
		$m++;
	}	
		$resultado = count($sensores);
			if($resultado == 0){
					$sensores[$indice] = $datatmp1;	
					$sensores[$indice]['id_aplicacion'] = $aplicacion;
					$tipo = $sensores[$indice]['tipo'];
					$sql = "select unidad from parametro where tipo = '$tipo'";
					$consulta2 = mysql_query($sql, $conEmp);
					if($datatmp2 = mysql_fetch_array($consulta2)){
						$unidad = $datatmp2['unidad'];
						$sensores[$indice]['unidad'] = $unidad;
					}
					
					
					
									
			}else{
				$sensores2[$indice] = $datatmp1;
				for ($i = 0; $i < $resultado; $i++) {
					if($sensores2[$indice]['tipo'] == $sensores[$i]['tipo']){
					$sw = 1;
					}
					if($sw != 1){
						$sensores[$indice] = $datatmp1;	
						$sensores[$indice]['id_aplicacion'] = $aplicacion;
						$tipo = $sensores[$indice]['tipo'];
						$sql = "select unidad from parametro where tipo = '$tipo'";
						$consulta2 = mysql_query($sql, $conEmp);
						while($datatmp2 = mysql_fetch_array($consulta2)){
							$unidad = $datatmp2['unidad'];
							$sensores[$indice]['unidad'] = $unidad;
						}
						
					}else{
						$indice=$indice-1;
					}
				}$sw = 0;
		    }
		$indice = $indice+1;
		$aplicacion = null;
	}
	}
}


$sql = "select descripcion from elemento where id_elemento = $id_elemento";
if($datatmp = mysql_fetch_array($consulta)) {
	$sensores[0]['nombre_elemento'] = $datatmp['descripcion'];
}
echo json_encode($sensores);
?>