<?php
require_once 'class/conn.class.php';
##Tests if connection succeed
if(isset($_POST['id']) && $_POST['id'] == 1){
	$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
	if($conn->connStatus()){
		echo 1;
	}else{
		echo $conn->getError();
	}
}
##Show databases
else if(isset($_POST['id']) && $_POST['id'] == 2){
	$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
	echo json_encode($conn->showDatabases());
}
//Show tables
else if(isset($_POST['id']) && isset($_POST['base']) && $_POST['id'] == 3){
	$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
	if($conn->useDatabase($_POST['base'])){
		echo json_encode($conn->showTables($_POST['base']));
	}
}
//Massive Insert
else if(isset($_POST['id']) && isset($_POST['base']) && isset($_POST['table']) && $_POST['id'] == 4){
	$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
	if($conn->useDatabase($_POST['base'])){
		//CREATE HERE INSERT INTO
		$result = $conn->massiveInsert($_POST['table'], $_POST['qtd']);
		echo $result;
	}
}
else if(isset($_POST['id']) && $_POST['id'] == 5){

}
?>