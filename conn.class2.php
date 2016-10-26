<?php
##
##This classes creates a connection object of a SGBD type
##Paramentes passed: SGBD(mysql), conn ipt(127.0.0.1 default), 
##
class Conn{

	private $conn_sgbd;
	private $conn_adress;
	private $conn_obj;
	private $conn_user;
	private $conn_pwd;

##Constructors
	##Complete
	function __construct($sgbd, $adress, $user='', $pwd=''){

		$this->setSGDB($sgbd);
		$this->setSGDB($sgbd);
		$this->setSGDB($sgbd);
		$this->setSGDB($sgbd);

		$this->connect();

		try{
			if(new PDO("{$sgbd}:host={$adress}",$user,$pwd)){
				##Commit the conenction
				$this->conn_obj = new PDO("{$sgbd}:host={$adress}",$user,$pwd);
				##Set the variables
				$this->conn_sgbd = $sgbd;
				$this->conn_adress = $adress;
				$this->conn_user = $user;
				$this->conn_pwd = $pwd;
			}
		}catch(PDOException $e){
			$this->status = false;
			$this->status_error = $e->getCode();
			//throw new Exception("e_".$e->getCode());

		}

	}
##Public Functions
	public function selectDB($db){
		if($this->conn_obj->query("USE {$db}")){
			return true;
		}else{
			return false;
		}
	}
	public function showDB(){
		 $a = $this->conn_obj->query("SHOW DATABASES;");
		 return $a;
	}
##Connection Functions
	public function getConn(){ ##bool-Displays info about the connection
		if($this->conn_obj){
			echo "Status: Ok!"."<br />";
			echo "SGBD: ".$this->conn_sgbd."<br />";
			echo "Connection: ".$this->conn_adress."<br />";
			return true;
		}else{
			return false;
		}
	}
	public function editConn($sgbd, $adress, $user, $pwd){ ##bool-Edit a connection - Changes Adress, User, Password and SBBD
		try{
			if(new PDO("{$sgbd}:host={$adress}",$user,$pwd)){
				$this->conn_obj = new PDO("{$sgbd}:host={$adress}",$user,$pwd);
				$this->conn_sgbd = $sgbd;
				$this->conn_adress = $adress;
				$this->conn_user = $user;
				$this->conn_pwd = $pwd;
				return true;
			}
		}catch(PDOException $e){
			return false;
		}
	}
}
?>