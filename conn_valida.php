<?php
require_once 'class/conn.class.php';
//
##Tests if connection succeed
if(isset($_POST['id']) && $_POST['id'] == 1){
	switch ($_POST['sgbd']) {
		case 'mysql':
			$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
			if($conn->connStatus()){
				echo "y";
			}else{
				echo $conn->getError();
			}
			break;
		case 'oracle':
			$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
			if($conn->connStatus()){
				echo "y";
			}else{
				echo $conn->getError();
			}
			break;
		case 'mssql':
			echo "Ainda não configurado";
			break;
		case 'postgres':
			echo "Ainda não configurado";
			break;
		case 'firebird':
			echo "Ainda não configurado";
			break;
	}
}
//
//Show databases
else if(isset($_POST['id']) && $_POST['id'] == 2){
	switch ($_POST['sgbd']) {
		case 'mysql':
			$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
			echo json_encode($conn->showDatabases());
			break;
	}
}
//
//Show tables
else if(isset($_POST['id']) && isset($_POST['base']) && $_POST['id'] == 3){
	$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
	if($conn->useDatabase($_POST['base'])){
		echo json_encode($conn->showTables($_POST['base']));
	}
}
//
//Massive Insert
else if(isset($_POST['id']) && isset($_POST['base']) && isset($_POST['table']) && $_POST['id'] == 4){
	$conn = new Conn($_POST['sgbd'], $_POST['adress'], $_POST['user'], $_POST['password']);
	if($conn->useDatabase($_POST['base'])){
		//CREATE HERE INSERT INTO
		$conn->massiveInsert($_POST['table'], $_POST['qtd']);
	}
}
else if(isset($_POST['id']) && $_POST['id'] == 5){

}
else{
	header('location: index.php');
}
?>