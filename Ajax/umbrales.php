<?php
$id_elementos = $_POST['id_elementos'];
$tipo = $_POST['tipo'];
include("bd.php");
$id_elementos = explode(",", $id_elementos);
$largo = count($id_elementos);
$sensores = NULL;
$indice = 0;
$sw = 0;
for ($indice2 = 0; $indice2 < $largo; $indice2++) {
 $sql = "select * from elemento_aplicacion where id_elemento = $id_elementos[$indice2]";
$consulta = mysql_query($sql, $conEmp);
while($datatmp = mysql_fetch_array($consulta)) {
    $id = $datatmp['id'];
	$id_aplicacion = $datatmp['id_aplicacion'];
	$tabla = $datatmp['tabla'];
	$id_tabla = $datatmp['id_tabla'];
	if($tabla == 'sensor'){
	$indice; 
	$sql = "select * from sensor where id_sensor = $id"; 
	$consulta1 = mysql_query($sql, $conEmp);
	$resultado = 0; 
	while($datatmp1 = mysql_fetch_assoc($consulta1)){
    $sql = "select prioridad,id_aplicacion as id2, (SELECT url FROM aplicacion2 WHERE id_aplicacion = id2) as url,(SELECT icono FROM aplicacion2 WHERE id_aplicacion = id2) as icono from elemento_aplicacion where id = $id and tabla = '$tabla'";
	$consulta3 = mysql_query($sql, $conEmp);
	$m=0;
	while($datatmp3 = mysql_fetch_assoc($consulta3)) {
		$aplicacion[$m]= $datatmp3;
		$m++;
	}	
		$resultado = count($sensores);
			if($resultado == 0){
					$sensores[$indice] = $datatmp1;	
					$sensores[$indice]['id_elemento'] = $id_elementos[$indice2];
					$sensores[$indice]['id_aplicacion'] = $aplicacion;
					$sensores[$indice]['prioridad'] = $aplicacion[0]["prioridad"];
					$tipo = $sensores[$indice]['tipo'];
					
					$sql = "SELECT nombre from parametro where tipo = '$tipo'";
					$consulta777 = mysql_query($sql, $conEmp);
					if($datatmp777 = mysql_fetch_array($consulta777)) {
					$sensores[$indice]['tipo'] = $datatmp777['nombre'];
					}
					
					$sql = "select unidad from parametro where tipo = '$tipo'";
					$consulta2 = mysql_query($sql, $conEmp);
					if($datatmp2 = mysql_fetch_array($consulta2)){
						$unidad = $datatmp2['unidad'];
						$sensores[$indice]['unidad'] = $unidad;
					}
					
			$indice++;
			$aplicacion = null;		
			}else{
				$sensores2[$indice] = $datatmp1;
				for ($i = 0; $i < $resultado; $i++) {
					if($sensores2[$indice]['tipo'] == $sensores[$i]['tipo'] && $id_elementos[$indice2] == $sensores[$i]['id_elemento']){
					
					$sw = 1;
					}
					if($sw != 1){
						$sensores[$indice] = $datatmp1;	
						$sensores[$indice]['id_elemento'] = $id_elementos[$indice2];
						$sensores[$indice]['id_aplicacion'] = $aplicacion;
						$sensores[$indice]['prioridad'] = $aplicacion[0]["prioridad"];
						$tipo = $sensores[$indice]['tipo'];
						$sql = "select unidad from parametro where tipo = '$tipo'";
						$consulta2 = mysql_query($sql, $conEmp);
						while($datatmp2 = mysql_fetch_array($consulta2)){
							$unidad = $datatmp2['unidad'];
							$sensores[$indice]['unidad'] = $unidad;
						}
						
						$sql = "SELECT nombre from parametro where tipo = '$tipo'";
						$consulta777 = mysql_query($sql, $conEmp);
						if($datatmp777 = mysql_fetch_array($consulta777)) {
						$sensores[$indice]['tipo'] = $datatmp777['nombre'];
						}
					
												
					}else{
						$indice=$indice-1;
					}
				}$sw = 0;$indice++;	
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
	   if($datatmp9 = mysql_fetch_assoc($consulta33)){

		   $sql = "select prioridad from elemento_aplicacion where id = $id order by prioridad desc";
		   $consulta333 = mysql_query($sql, $conEmp);
		   if($datatmp99 = mysql_fetch_array($consulta333)){
		   		$prioridad = $datatmp99["prioridad"];
		   }
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
       	   $sensores[$indice]['id_elemento'] = $id_elementos[$indice2];
		   $sensores[$indice]['valor'] =  $estado22;
		   $sensores[$indice]['unidad'] =  " ";
		   $sensores[$indice]['id_equipo'] =  $id_equipo;	
		   $sensores[$indice]['id_empresa'] =  $id_empresa;	
		   $sensores[$indice]['descripcion'] =  $nombre_sector;
		   $sensores[$indice]['tipo'] =  "control";
		   $sensores[$indice]['id_aplicacion'][0] =  $apis ;
	   	   $sensores[$indice]['prioridad'] =  $prioridad ;  	   
	   	   $indice++;
	   }
	   $apis = null;
	}elseif($tabla == 'sensor_a'){
	 $indice;
	 $sql = "select * from sensor_a where id_sensor = $id";
	$consulta1 = mysql_query($sql, $conEmp);
	$resultado = 0;
	while($datatmp1 = mysql_fetch_assoc($consulta1)){
	$sql = "select prioridad,id_aplicacion as id2, (SELECT url FROM aplicacion2 WHERE id_aplicacion = id2) as url,(SELECT icono FROM aplicacion2 WHERE id_aplicacion = id2) as icono from elemento_aplicacion where id = $id and tabla = '$tabla'";
	$consulta3 = mysql_query($sql, $conEmp);
	$m=0;
	while($datatmp3 = mysql_fetch_assoc($consulta3)) {
		$aplicacion[$m]= $datatmp3;
		$m++;
	}	
		$resultado = count($sensores);
			if($resultado == 0){
					$sensores[$indice] = $datatmp1;	
					$sensores[$indice]['id_elemento'] = $id_elementos[$indice2];
					$sensores[$indice]['id_aplicacion'] = $aplicacion;
					$sensores[$indice]['prioridad'] = $aplicacion[0]["prioridad"];
					$tipo = $sensores[$indice]['tipo'];
					$sql = "select unidad from parametro where tipo = '$tipo'";
					$consulta2 = mysql_query($sql, $conEmp);
					if($datatmp2 = mysql_fetch_array($consulta2)){
						$unidad = $datatmp2['unidad'];
						$sensores[$indice]['unidad'] = $unidad;
					}
					$sql = "SELECT nombre from parametro where tipo = '$tipo'";
					$consulta777 = mysql_query($sql, $conEmp);
					if($datatmp777 = mysql_fetch_array($consulta777)) {
					$sensores[$indice]['tipo'] = $datatmp777['nombre'];
					}
					
					$sql = "select url from parametro where tipo = '$tipo'";
					$consulta2 = mysql_query($sql, $conEmp);
					if($datatmp2 = mysql_fetch_array($consulta2)){
						$url = $datatmp2['url'];
						$sensores[$indice]['url'] = $url;
					}
					
					switch ($tipo) {
						case "us":;
							break;
						case "barra":
							echo "i es una barra";
							break;
						case "pastel":
							echo "i es un pastel";
							break;
					}
			$indice = $indice+1;
			$aplicacion = null;		
			}else{
				$sensores2[$indice] = $datatmp1;
				for ($i = 0; $i < $resultado; $i++) {
					if($sensores2[$indice]['tipo'] == $sensores[$i]['tipo']){
					$sw = 1;
					}
					if($sw != 1){
						$sensores[$indice] = $datatmp1;	
						$sensores[$indice]['id_elemento'] = $id_elementos[$indice2];
						$sensores[$indice]['id_aplicacion'] = $aplicacion;
						$sensores[$indice]['prioridad'] = $aplicacion[0]["prioridad"];
						$tipo = $sensores[$indice]['tipo'];
						$sql = "select unidad from parametro where tipo = '$tipo'";
						$consulta2 = mysql_query($sql, $conEmp);
						while($datatmp2 = mysql_fetch_array($consulta2)){
							$unidad = $datatmp2['unidad'];
							$sensores[$indice]['unidad'] = $unidad;
						}
						$sql = "SELECT nombre from parametro where tipo = '$tipo'";
						$consulta777 = mysql_query($sql, $conEmp);
						if($datatmp777 = mysql_fetch_array($consulta777)) {
						$sensores[$indice]['tipo'] = $datatmp777['nombre'];
						}
					
						$sql = "select url from parametro where tipo = '$tipo'";
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
		$indice++;
		$aplicacion = null; 
	}
	}
}
}
echo json_encode($sensores);
 
 
?>
