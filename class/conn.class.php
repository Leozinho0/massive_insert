<?php
##
##This classes creates a connection object of a SGBD type
##Paramentes passed: SGBD(mysql), address, user, password 
##
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

##Constructors
	##Complete
	function __construct($sgbd, $address, $user, $pwd){
		$this->connect($sgbd, $address, $user, $pwd);
	}
##Private functions
	private function connect($sgbd, $address, $user, $pwd){
		try{
			$this->conn_obj = new PDO("{$sgbd}:host={$address}",$user,$pwd);
			$this->setSgbd($sgbd);
			$this->setAddress($address);
			$this->setUser($user);
			$this->setPassword($pwd);
			$this->setConnectionStatus();
		}catch(PDOException $e){
			$this->conn_error = $e->getMessage();
		}
	}
	private function setConnectionStatus(){
		$this->conn_status = true;
	}
	private function f_rand($min=0,$max=1,$mul=100000){
	    if ($min>$max) return false;
	    return mt_rand($min*$mul,$max*$mul)/$mul;
	}
	##Database Functions
	//This functions executes a describe on a table and shall return an array with the columns type
	private function describeTable($table){
		 $arr_retorno = array();
		 $a = $this->conn_obj->query("DESCRIBE {$table};");
		 foreach($a as $describe)
		 {
		 	$arr_retorno[] = $describe;
		 }
		 return $arr_retorno;
	}
	//Parametro - Tabela que tem a foreign
	//Função checka se tabela foreign tem dados. Senão tiverm retorna false, se tiver--->
	//Função retorna um numero ou string em aspas simples que é o resultado de um select na foreign key
	private function checkForeignTable($table){
		$sql = "select
				    concat(table_name, '.', column_name) as 'foreign key',
				    concat(referenced_table_name, '.', referenced_column_name) as 'references',
				    constraint_name as 'constraint name'
				from
				    information_schema.key_column_usage
				where table_name = '{$table}';";

		$arr_retorno = array();
		$a = $this->conn_obj->query($sql);
		foreach($a as $describe)
		{
			if($describe[1]){
				$posIni = strpos($describe[1], ".");
				$table_foreign = substr($describe[1], 0, $posIni);
				$column_foreign = substr($describe[1], $posIni+1);

				$sql = "select count(*) from {$table_foreign};";
				$a = $this->conn_obj->query($sql);
				foreach($a as $describe)
				 {
				 	if($describe[0] == 0){
						echo "You need to populate {$table_foreign} first!";
						return false;
				 	}else{
				 		$sql = "select {$column_foreign} from {$table_foreign};";
				 		$a = $this->conn_obj->query($sql);
				 		foreach($a as $describe)
						{
						 	$arr_retorno[] = $describe;
						}
						$desc = $this->describeTable($table_foreign);
						$posIni = strpos($desc[0][1], "(");
						$posFin = strpos($desc[0][1], ")");
						$type = substr($desc[0][1], 0, $posIni);
						if($type == "int"){
							return $arr_retorno[array_rand($arr_retorno, 1)][0];
						}else{
							return "'".$arr_retorno[array_rand($arr_retorno, 1)][0]."'";
						}
						
				 	}
				}
			}
		}
	}
	//Função retorna um random numero ou string entre aspas simples para popular uma tabela
	//Checka se a a tabela possui chave estrangeira e então retorna um registro dq tabela foreign
	//Parmetros: ds: Dataset Json do tipo de dado. $TypeParam: Parêntesis do tipo de dado (exeplo varchat(100))
	//$key: campo key do describe table que diz se é chave estrangeira
	//extra: Last field of the describe ask for auto_increment
	private function dsDataGet($ds, $typeParam, $key, $extra, $table){

		if($extra == 'auto_increment'){
			return 'NULL';
		}else{
			if($key == 'MUL'){
				return $this->checkForeignTable($table);
			}
			if($ds == 'int' || $ds == 'tinyint' || $ds == 'smallint' || $ds == 'mediumint' || $ds == 'bigint'){
				return rand(0, 9999);
			}else if($ds == 'float' || $ds == 'double' || $ds == 'decimal'){
				return $this->f_rand(0, 9999, 100000);
			}else{
				$json = file_get_contents($ds);
				$arr = json_decode($json);
				$arr_retorno = array();
				foreach ($arr as $key=>$value){
					if(strlen($value->value) <= $typeParam || $typeParam == ""){
						$arr_retorno[] = "'".$value->value."'";
					}
				}
				return $arr_retorno[array_rand($arr_retorno, 1)];
			}
		}
	}
	//Função retorna uma string com os valores de insert já pronto pra ser jogado numa query
	//retorno: null, 'string1', '2016-10-12'
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
			switch ($type) {
				//int types
				case 'int':{
					$arr_retorno[] = $this->dsDataGet('int', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'tinyint':{
					$arr_retorno[] = $this->dsDataGet('tinyint', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'smallint':{
					$arr_retorno[] = $this->dsDataGet('smallint', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'mediumint':{
					$arr_retorno[] = $this->dsDataGet('mediumint', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'bigint':{
					$arr_retorno[] = $this->dsDataGet('bigint', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'float':{
					$arr_retorno[] = $this->dsDataGet('float', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'double':{
					$arr_retorno[] = $this->dsDataGet('double', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'decimal':{
					$arr_retorno[] = $this->dsDataGet('decimal', $typeParam, $key[3], $key[5], $table);
					break;
				}
				//data types
				case 'date':{
					$arr_retorno[] = $this->dsDataGet('json/ds_date.json', $typeParam, $key[3], $key[5], $table);
					break;
				}	
				case 'datetime':{
					$arr_retorno[] = $this->dsDataGet('json/ds_datetime.json', $typeParam, $key[3], $key[5], $table);
					break;
				}	
				case 'timestamp':{
					$arr_retorno[] = $this->dsDataGet('json/ds_timestamp.json', $typeParam, $key[3], $key[5], $table);
					break;
				}	
				case 'time':{
					$arr_retorno[] = $this->dsDataGet('json/ds_time.json', $typeParam, $key[3], $key[5], $table);
					break;
				}	
				case 'year':{
					$arr_retorno[] = $this->dsDataGet('json/ds_year.json', $typeParam, $key[3], $key[5], $table);
					break;
				}				
				//text types
				case 'varchar':{
					//generate random string here
					$arr_retorno[] = $this->dsDataGet('json/ds_nomes.json', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'char':{
					$arr_retorno[] = $this->dsDataGet('json/ds_char.json', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'tinytext':{
					$arr_retorno[] = $this->dsDataGet('json/ds_text.json', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'text':{
					$arr_retorno[] = $this->dsDataGet('json/ds_text.json', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'mediumtext':{
					$arr_retorno[] = $this->dsDataGet('json/ds_text.json', $typeParam, $key[3], $key[5], $table);
					break;
				}
				case 'longtext':{
					$arr_retorno[] = $this->dsDataGet('json/ds_text.json', $typeParam, $key[3], $key[5], $table);
					break;
				}
			}
		}
		return implode (", ", $arr_retorno);
	}
##Public Functions
	##Connection Functions
	##Sets Functions
	public function connStatus(){
		return $this->conn_status;
	}
	public function setSgbd($sgbd){
		$this->conn_sgbd = $sgbd;
	}
	public function setAddress($address){
		$this->conn_address = $address;
	}
	public function setUser($user){
		$this->conn_user = $user;
	}
	public function setPassword($pwd){
		$this->conn_pwd = $pwd;
	}
	##Gets Functions
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
	public function getStatus(){
		return $this->conn_status;
	}
	public function newConn($sgbd, $address, $user, $pwd){
		$this->connect($sgbd, $address, $user, $pwd);
	}
	##Queries Functions
	public function useDatabase($db){
		if($this->conn_obj->query("USE {$db}")){
			return true;
		}else{
			return false;
		}
	}
	public function showDatabases(){
		 $arr_retorno = array();
		 $a = $this->conn_obj->query("SHOW DATABASES;");

		 foreach($a as $db)
		 {
		 	$arr_retorno[] = $db;
		 }
		 return $arr_retorno;
	}
	public function showTables($base){
		 $arr_retorno = array();
		 $a = $this->conn_obj->query("SHOW TABLES;");

		 foreach($a as $db)
		 {
		 	$arr_retorno[] = $db;
		 }
		 return $arr_retorno;
	}
	public function massiveInsert($table, $qtd=1){
		$arr = $this->describeTable($table);
		for($i = 0; $i < $qtd; $i++){
			$values = $this->generateRandomInsert($table, $arr); //retorna string
			echo "\n".$values;
			$sql = "INSERT INTO {$table} VALUES({$values});";
			$insert = $this->conn_obj->query($sql);
		}
	}
	##Error Functions
	public function getError(){
		return $this->conn_error;
	}
}
?>