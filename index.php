<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="js/jquery.js"></script>
	<style>
		body{
			color: #333333;
			font-family: Century Gothic, sans-serif;
			background-color: #E0E0E0;
			margin: 0;
		}
		.box_logo{
			padding: 20px;
			text-align: center;
			margin: 0 auto;
			width: 300px;
			font-family: "Arial Rounded MT Bold", "Sans-serif";
			font-size: 30px;
			color: #ECF0F1;
			text-shadow: 1px 1px 10px #000;
		}
		.box_center{
			background-color: #ECF0F1;
			text-align: right;
			margin: 10px auto;
			border-radius: 5px;
			border: 1px solid #95A5A6;
			padding: 10px;
			width: 400px;
			box-shadow: 3px 4px 10px #95A5A6;
		}
		.div_btn{
			background-color: #D1D5D8;
			width: 100px;
			margin: 10px auto;
			border-radius: 5px;
			text-align: center;
			padding: 4px;
		}
		.div_btn:hover{
			box-shadow: 3px 4px 10px #95A5A6;
			cursor: pointer;
		}
		.div_btn:active{
			background-color: #CDC9C9;
		}
		.box_error{
			margin: 10px auto;
			border-radius: 5px;
			border: 1px solid #95A5A6;
			padding: 10px;
			width: 400px;
		    color: #D8000C;
		    background-color: #FFBABA;
			box-shadow: 3px 4px 10px #95A5A6;
			text-align: center;
			font-size: 15px;
		}
		.input{
			width: 90%;
		}
		select{
			width: 100%;	
		}
		#div_block{
			display: none;
			position: fixed;
		    width: 100%;
		    height: 100%;
		    left: 0;
		    top: 0;
		}
		#div_loading{
			display: none;
			margin: 10px auto;
			border-radius: 5px;
			border: 1px solid #95A5A6;
			padding: 10px;
			width: 400px;
			background-color: #ECF0F1;
			box-shadow: 3px 4px 10px #95A5A6;
			text-align: center;
			font-size: 15px;
		}
		#div_message{
			display: none;
			margin: 10px auto;
			border-radius: 5px;
			border: 1px solid #95A5A6;
			padding: 10px;
			width: 400px;
			color: #4F8A10;
    		background-color: #DFF2BF;
			box-shadow: 3px 4px 10px #95A5A6;
			text-align: center;
			font-size: 15px;
		}
		table{
			table-layout: fixed;
			width: 100%;
		}
	</style>
</head>
<body>
	<div id="" class="box_logo">
		<img src="img/db_icon.png" alt="" width="80px">
		<div>
			<span>Database Populator</span>
		</div>
	</div>
	<div class="box_center">
		<div>
			<table>
				<tr>
					<td><span class="field_name">SGBD</span></td>
					<td>
						<select>
							<option value="mysql" id="conn_sgbd">MySQL</option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<div>
			<table>
				<tr>
					<td><span class="field_name">Conexão</span></td>
					<td>
						<input type="text" value="127.0.0.1" id="conn_adress" class="input">
					</td>
				</tr>
			</table>
		</div>
		<div>
			<table>
				<tr>
					<td><span class="field_name">Usuario</span></td>
					<td>
						<input type="text" id="conn_user" class="input">
					</td>
				</tr>
			</table>
		</div>
		<div>
			<table>
				<tr>
					<td><span class="field_name">Senha</span></td>
					<td>
						<input type="password" id="conn_password" class="input">
					</td>
				</tr>
			</table>
		</div>
		<div onclick="js_conn();" class="div_btn">
			<span>Conectar</span>
		</div>
	</div>
	<!--Div ERRORS -->
	<div style="display:none;" id="div_error" class="box_error">
	</div>
	<!--Div LOADING AJAX-->
	<div id="div_block">
	</div>
	<!--Div DADOS -->
	<div class="box_center" id="div_dados" style="display:none;">
		<div id="div_bases" style="display:none;">
			<table>
				<tr>
					<td>Base</td>
					<td>
						<select id="id_bases" onchange="js_listTables();">
						</select>
					</td>
				</tr>
			</table>
		</div>
		<div id="div_tabelas" style="display:none;">
			<table>
				<tr>
					<td>Tabela</td>
					<td>
						<select id="id_tables">
							
						</select>
					</td>
				</tr>
			</table>
		</div>
		<div>
			<table>
				<tr>
					<td>Quantidade de registros</td>
					<td>
						<select name="" id="qtd_insert">
							<option value="1" selected>1</option>
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="20">20</option>
							<option value="50">50</option>
							<option value="100">100</option>
							<option value="200">200</option>
							<option value="500">500</option>
							<option value="1000">1000</option>
							<option value="5000">5000</option>
							<option value="10000">10000</option>
						</select>
					</td>
				</tr>
			</table>
		</div>
		<div class="div_btn" onclick="js_insert();">Inserir</div>
	</div>
	<!--Div Message Success -->
	<div id="div_message">
	</div>
	<div id="div_loading">
		<img src="http://s1.ticketm.net/tm/en-us/img/sys/1000/gray75_polling.gif" alt="" width="30px"/> <br>
		<span>Inserindo dados... Aguarde!</span>
	</div>
	<script>
		function js_listBases(){
			var d = 'sgbd=' + document.getElementById("conn_sgbd").value + '&adress=' + document.getElementById("conn_adress").value + '&user=' + document.getElementById("conn_user").value + '&password=' + document.getElementById("conn_password").value + "&id=2";
			$.ajax({
				type: 'POST',
				url: 'conn_valid.php',
				data: d,
				success: function(ds){
					arr = JSON.parse(ds);

					$("#id_bases").html('');

					$("#id_bases").append("<option value=''></option>");
					for(it=0; it<arr.length; it++)
					{
						$("#id_bases").append("<option value='"+ arr[ it ].Database +"'>"+ arr[ it ].Database +"</option>");
					}

					$('#div_dados').show();
					$('#div_bases').show();
				}
			});
		}
	</script>
	<script>
		function js_listTables(){
			var d = 'sgbd=' + document.getElementById("conn_sgbd").value + '&adress=' + document.getElementById("conn_adress").value + '&user=' + document.getElementById("conn_user").value + '&password=' + document.getElementById("conn_password").value + "&base=" + document.getElementById("id_bases").value + "&id=3";
			$.ajax({
				type: 'POST',
				url: 'conn_valid.php',
				data: d,
				success: function(ds){
					arr = JSON.parse(ds);

					$("#id_tables").html('');
					for(it=0; it<arr.length; it++)
					{
						$("#id_tables").append("<option value='"+ arr[it][0] +"'>"+ arr[it][0] +"</option>");
					}
					$('#div_tabelas').show();
				}
			});
		}
	</script>
	<!--ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI
	ESTOU AQUI -->
	<script>
		function js_insert(){
			//APAGAR ISSO
			var quantidade = document.getElementById("qtd_insert").value;
			//APAGAR ESSA MERDS

			var d = 'sgbd=' + document.getElementById("conn_sgbd").value + '&adress=' + document.getElementById("conn_adress").value + '&user=' + document.getElementById("conn_user").value + '&password=' + document.getElementById("conn_password").value + "&base=" + document.getElementById("id_bases").value + "&table=" + document.getElementById("id_tables").value + "&qtd=" + document.getElementById("qtd_insert").value +"&id=4";
			$('#div_message').hide();
			$('#div_block').show();
			$('#div_loading').fadeIn();
			$.ajax({
				type: 'POST',
				url: 'conn_valid.php',
				data: d,
				success: function(ds){
					$('#div_block').hide();
					$('#div_loading').hide();
					var mensagem = quantidade+" registro(s) inserido(s) com sucesso!";
					$('#div_message').html(ds);
					$('#div_message').fadeIn();
				}
			});
		}
	</script>
	<script>
		function js_conn(){
			var d = 'sgbd=' + document.getElementById("conn_sgbd").value + '&adress=' + document.getElementById("conn_adress").value + '&user=' + document.getElementById("conn_user").value + '&password=' + document.getElementById("conn_password").value + "&id=1";
			$.ajax({
				type: 'POST',
				url: 'conn_valid.php',
				data: d,
				success: function(ds){
					if(ds == "y"){
						$('#div_error').hide();
						js_listBases();
					}else {
						$('#div_error').html(ds);
						$('#div_error').fadeIn();
					}
				}
			});
		}
	</script>
</body>
</html>