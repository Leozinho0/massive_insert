<?php
//teste saída PDO
try{
	$conn = new PDO("mysql:host=127.0.0.1","root","root");
}catch(PDOException $e){
	echo $e;
}
try{
	$conn->query("sadasd");
}catch(PDOException $e){
	echo $e;
}
?>