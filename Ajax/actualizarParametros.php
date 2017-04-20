<?php
include("bd.php");
$json = $_POST['arregloJson'];
$largo = $_POST['largo'];
$json = json_decode($json, true);
$sql = "";
for ($m = 0; $m < $largo; $m++){
	$array = json_decode($json[$m], true);
	$top    = $array["_top"];    echo "--";
	$left   = $array['_left'];   echo "--";
	$width  = $array['_width'];  echo "--";
	$heigth = $array['_heigth']; echo "--";
	$img    = $array['_img'];    echo "--";
	$idelemento    = $array['_idelemento'];    echo "--";
	$sql= "UPDATE `elemento` SET `top`='$top',`lefts`='$left',`width`='$width',`height`='$heigth',`img`='$img' WHERE id_elemento =$idelemento;";
	$consulta = mysql_query($sql, $conEmp);
}
?> 