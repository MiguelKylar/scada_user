<?php 
include("bd.php");
session_start(); 
$_SESSION['usuario']=$_REQUEST['usuario'];
$_SESSION['clave']=$_REQUEST['contrasena'];

$usuario = $_SESSION['usuario'];
$contrasena = $_SESSION['clave'];
$sql = "SELECT id_empresa FROM usuario WHERE usuario='$usuario' AND contrasena='$contrasena'";
if ($resultado = mysql_query($sql, $conEmp)){
	if (mysql_num_rows($resultado) > 0){
    	if($datatmp = mysql_fetch_array($resultado)){
       		$id_empresa=$datatmp["id_empresa"];
			$_SESSION['id_empresa']=$id_empresa;
    		header('Location: index.php');
       	}else{
		
			header('Location: error.php');
		
		
		}
    }else{
		header('Location: error.php');
	
	}
}
else{
    	echo false;
		header('Location: error.php');
}
mysql_close($conEmp);
?>