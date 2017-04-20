<?
  session_start();
  ob_start();
  unset($_SESSION["usuario"]); 
  unset($_SESSION["clave"]);
  $_SESSION["id_empresa"] = "destruir";
  unset($_SESSION["nodo"]);
  unset($_SESSION["capas"]);
  
  
  //header("Location:index.php");
  //header( "Refresh: 0;" );
  //exit;
?>