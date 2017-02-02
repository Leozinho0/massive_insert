<?php
//teste saída PDO
try{
	$conn = new PDO("mysql:host=127.0.0.1","root","root");
}catch(PDOException $e){
	echo $e->getMessage();
}


	$conn->query("use ypa;");
	//$conn->query("INSERT INTO infeedweights VALUES(-62, '2005-04-10', 5171.46649);");

	$error = $conn->errorInfo();
	if($error[0] == 00000){
		echo "erro vazio!";
	}else{
		print_r($error);
	}



try{

}catch(PDOException $e){
	
}


?>