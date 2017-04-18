<?php
##
##This classes creates a connection object of a SGBD type
##Paramentes passed: SGBD(mysql), address, user, password 
##
## TINYBLOB, BLOB, MEDIUMBLOB, and LONGBLOB NOT DEFINED YET
class Conn{
##Conection Variables
	private $conn_sgbd;
	private $conn_address;
	private $conn_obj;
	private $conn_user;
	private $conn_pwd;
##Connection Status
	private $conn_status = false;
##Connection Errors
	private $conn_error;
##DS Arrays
	private $arr_ds_nomes;
	private $arr_ds_char;
	private $arr_ds_date;
	private $arr_ds_datetime;
	private $arr_ds_text;
	private $arr_ds_time;
	private $arr_ds_timestamp;
	private $arr_ds_year;

##Constructors
	##Complete
	function __construct($sgbd, $address, $user, $pwd, $instance = 'XE'){
		$this->connect($sgbd, $address, $user, $pwd, $instance);
		$this->arr_ds_nomes = json_decode (file_get_contents("json/ds_nomes.json"));
		$this->arr_ds_char = json_decode (file_get_contents("json/ds_char.json"));
		$this->arr_ds_date = json_decode (file_get_contents("json/ds_date.json"));
		$this->arr_ds_datetime = json_decode (file_get_contents("json/ds_datetime.json"));
		$this->arr_ds_text = json_decode (file_get_contents("json/ds_text.json"));
		$this->arr_ds_time = json_decode (file_get_contents("json/ds_time.json"));
		$this->arr_ds_timestamp = json_decode (file_get_contents("json/ds_timestamp.json"));
		$this->arr_ds_year = json_decode (file_get_contents("json/ds_year.json"));
		//sintaxe para pegar echo $this->arr_ds_char[0]->value;
	}
##Private functions
	private function connect($sgbd, $address, $user, $pwd, $instance){
		try{
			if($sgbd == 'oracle'){
				$name = $instance;
				$tns = " (DESCRIPTION =(ADDRESS_LIST =(ADDRESS = (PROTOCOL = TCP) (HOST = ".$address.")(PORT = 1521)))(CONNECT_DATA = (SID = ".$name.")))";
				$this->conn_obj = new PDO("oci:dbname=".$tns,$user,$pwd);
				$this->setSgbd($sgbd);
				$this->setAddress($address);
				$this->setUser($user);
				$this->setPassword($pwd);
				$this->setConnectionStatus();	
			}else if($sgbd == 'mysql'){
				$this->conn_obj = new PDO("{$sgbd}:host={$address}",$user,$pwd);
				$this->setSgbd($sgbd);
				$this->setAddress($address);
				$this->setUser($user);
				$this->setPassword($pwd);
				$this->setConnectionStatus();	
			}else if($sgbd == 'postgres'){
				$this->conn_obj = new PDO('pgsql:dbname=leo;host='.$address.';user='.$user.';password='.$pwd);
				$this->setSgbd($sgbd);
				$this->setAddress($address);
				$this->setUser($user);
				$this->setPassword($pwd);
				$this->setConnectionStatus();
			}
		}catch(PDOException $e){
			$this->conn_error = $e->getMessage();
		}
	}

	public function setSgbd($sgbd){
		$this->conn_sgbd = $sgbd;
	}

	public function setAddress($address){
		$this->conn_address = $address;
	}
	##Database Functions
	//This functions executes a describe on a table and shall return an array with the columns type

	public function setUser($user){
		$this->conn_user = $user;
	}
	public function setPassword($pwd){
		$this->conn_pwd = $pwd;
	}
	//Função retorna uma string com os valores de insert já pronto pra ser jogado numa query
	//retorno: null, 'string1', '2016-10-12'

	private function setConnectionStatus(){
		$this->conn_status = true;
	}
##Public Functions
	##Connection Functions
	##Sets Functions

	public function connStatus(){
		return $this->conn_status;
	}

	public function getSgbd(){
		return $this->conn_sgbd;
	}

	public function getAdress(){
		return $this->conn_address;
	}

	public function getUser(){
		return $this->conn_user;
	}

	public function getPassword(){
		return $this->conn_pwd;
	}
	##Gets Functions

	public function getStatus(){
		return $this->conn_status;
	}

	public function newConn($sgbd, $address, $user, $pwd){
		$this->connect($sgbd, $address, $user, $pwd);
	}

	public function useDatabase($db){
		if($this->conn_obj->query("USE {$db}")){
			return true;
		}else{
			return false;
		}
	}

	public function showDatabases($banco){
		$arr_retorno = array();
		switch($banco)
		{
			case 'mysql':
			{
				$a = $this->conn_obj->query("SHOW DATABASES;");
				foreach($a as $db)
				{
					$arr_retorno[] = $db;
				}
				break;
			}
			case 'postgres':
			{
				break;
			}
			case 'oracle':
			{
				$a = $this->conn_obj->query("SHOW DATABASES;");
				foreach($a as $db)
				{
					$arr_retorno[] = $db;
				}
				break;
			}
		}
		return $arr_retorno;
	}

	public function showTables(){
		$arr_retorno = array();
		$arr_table = $this->conn_obj->query("SHOW TABLES;");
		foreach($arr_table as $key)
		{
			$arr_retorno[] = $key;
		}
		return $arr_retorno;
	}

	public function massiveInsert($table, $qtd=1){
		$arr = $this->describeTable($table);
		//Declaração do array que vai retornar pro Javascript
		$arr_retorno = array();
		for($i = 0; $i < $qtd; $i++){
			$values = $this->generateRandomInsert($table, $arr); //retorna string

			$sql = "INSERT INTO {$table} VALUES({$values});";
			//echo $sql."<br>";
			$this->conn_obj->query($sql);
			//Este trecho de código verifica, através do retorno da função errorInfo() do PHP,
			//se ocorrue algum erro na execução da query acima ^. Caso positivo (retorno diferente de 00000),
			//joga o erro pro array[1] em diante (o [0] é pra verificar no javascript).
			//Caso não exista erro, retorna UM ARRAY com os inserts a partir do [1] (o [0] tbm é pra verificar no Javascript.);
			//OBS.: A função errorInfo() retorna um array.
			$error = $this->conn_obj->errorInfo();
			if($error[0] == "00000"){
				$arr_retorno[0] = '';
				$arr_retorno[] = $sql."<br>";
			}else if($error[0] == "23000"){
				//$tabela_fk = $this->checkForeignTable($table);
				$pos1 = strpos($error[2], 'REFERENCES `');
				$pos1 = $pos1 + 12;
				$tabela_fk = substr($error[2] , $pos1, -9);
				//echo $tabela_fk;

				$arr_retorno[0] = "ERRO!<br>";
				$arr_retorno[1] = "Tabela com chave estrangeira!<br>Popular tabela {$tabela_fk} primeiro!<br>";
				//$arr_retorno[] = $error;
			}else{
				$arr_retorno[0] = 'ERRO!<br>';
				$arr_retorno[] = $error;
			}
		}
		echo json_encode($arr_retorno);
	}
	##Queries Functions

	private function describeTable($table){
		$arr_retorno = array();
		$a = $this->conn_obj->query("DESCRIBE {$table};");
		foreach($a as $describe)
		{
			$arr_retorno[] = $describe;
		}
		return $arr_retorno;

	}

	private function generateRandomInsert($table ,$arr){
		$arr_retorno = array();
		foreach ($arr as $key){
			//First checks if strstr function find "("
			//Second, if true, get the first characters before the "(" wich mens the datatype name
			$typeParam = "";
			$type = "";
			if(strpos($key[1], "(")){
				$posIni = strpos($key[1], "(");
				$posFin = strpos($key[1], ")");
				$type = substr($key[1], 0, $posIni);
				$typeParam = substr ($key[1], $posIni+1, $posFin-1);
				//esse pos n tava pegando, dai dei um replace
				$typeParam = str_replace(")", "", $typeParam);
			}else{
				$type = $key[1];
			}
			//PAREI AQUIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
			//IIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
			//FALTA FAZER A VERIFICACAO DE TAMANHO DO TIPO VARCHAR, ESTA DANDO ERRO E INSERINDO MENOS DADOS
			//Ja fiz em VARCHAR
			$arr_retorno[] = $this->dsDataGet($type, $typeParam, $key[3], $key[5], $table);
			/*
						foreach ($this->arr_ds_date as $key=>$value){
							if(strlen($value->value) <= $typeParam || $typeParam == ""){
								$arr_retorno[] = "'".$value->value."'";
							}
						}*/
		}
		return implode (", ", $arr_retorno);
	}

	private function dsDataGet($type, $typeParam, $key, $extra, $table){
		if($extra == 'auto_increment'){
			return 'NULL';
		}else{
			/*
			if($key == 'MUL'){
				$foreign_table =  $this->checkForeignTable($table);
				return $foreign_table;
			}*/
			if($type == 'tinyint'){
				return rand(-127, 128);
			}else if($type == 'int' || $type == 'smallint' || $type == 'mediumint' || $type == 'bigint'){
				return rand(0, 9999);
			}else if($type == 'float' || $type == 'double' || $type == 'decimal'){
				return $this->f_rand(0, 999, 100);
			}else{
				$arr_retorno = array();
				switch($type){
					//data types
					case 'date':{
						foreach ($this->arr_ds_date as $key=>$value){
							$arr_retorno[] = "'".$value->value."'";
						}
						break;
					}
					case 'datetime':{
						$arr_date = array();
						$arr_time = array();
						$date = "";
						$time = "";
						foreach ($this->arr_ds_date as $key=>$value){
							$arr_date[] = $value->value;
						}
						$date = $arr_date[array_rand($arr_date, 1)];
						foreach ($this->arr_ds_time as $key=>$value){
							$arr_time[] = $value->value;
						}
						$time = $arr_time[array_rand($arr_time, 1)];
						$arr_retorno[] = "'".$date." ".$time."'";
						break;
					}
					case 'timestamp':{
						foreach ($this->arr_ds_date as $key=>$value){
							$arr_retorno[] = "'".$value->value."'";
						}
						break;
					}
					case 'time':{
						foreach ($this->arr_ds_time as $key=>$value){
							$arr_retorno[] = "'".$value->value."'";
						}
						break;
					}
					case 'year':{
						foreach ($this->arr_ds_year as $key=>$value){
							$arr_retorno[] = "'".$value->value."'";
						}
						break;
					}
					//text types
					case 'varchar':{
						//generate random string here
						foreach ($this->arr_ds_nomes as $key=>$value){
							if(strlen($value->value) <= $typeParam || $typeParam == ""){
								$arr_retorno[] = "'".$value->value."'";
							}
						}
						break;
					}
					case 'char':{
						foreach ($this->arr_ds_char as $key=>$value){
							if(strlen($value->value) <= $typeParam || $typeParam == ""){
								$arr_retorno[] = "'".$value->value."'";
							}
						}
						break;
					}
					case 'tinytext':{
						foreach ($this->arr_ds_text as $key=>$value){
							if(strlen($value->value) <= 10){
								$arr_retorno[] = "'".$value->value."'";
							}
						}
						break;
					}
					case 'text':{
						foreach ($this->arr_ds_text as $key=>$value){
							if(strlen($value->value) <= $typeParam || $typeParam == ""){
								$arr_retorno[] = "'".$value->value."'";
							}
						}
						break;
					}
					case 'mediumtext':{
						foreach ($this->arr_ds_text as $key=>$value){
							if(strlen($value->value) <= $typeParam || $typeParam == ""){
								$arr_retorno[] = "'".$value->value."'";
							}
						}
						break;
					}
					case 'longtext':{
						foreach ($this->arr_ds_text as $key=>$value){
							if(strlen($value->value) <= $typeParam || $typeParam == ""){
								$arr_retorno[] = "'".$value->value."'";
							}
						}
						break;
					}
					case 'blob':{
						/*foreach ($this->arr_ds_text as $key=>$value){
							if(strlen($value->value) <= $typeParam || $typeParam == ""){
								$arr_retorno[] = "'".$value->value."'";
							}
						}*/
						$arr_retorno[] = "'BLOB'";
						break;
					}
					case 'enum':{
						/*foreach ($this->arr_ds_text as $key=>$value){
							if(strlen($value->value) <= $typeParam || $typeParam == ""){
								$arr_retorno[] = "'".$value->value."'";
							}
						}*/
						$arr_retorno[] = "'1'";
						break;
					}
				}
				return $arr_retorno[array_rand($arr_retorno, 1)];
			}
		}
	}

	private function f_rand($min=0,$max=1,$mul=100000){
		if ($min>$max) return false;
		return mt_rand($min*$mul,$max*$mul)/$mul;
	}
	##Error Functions

	public function getError(){
		return $this->conn_error;
	}
}


/*


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
			echo "Ainda não configurado";
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

	*/
?>