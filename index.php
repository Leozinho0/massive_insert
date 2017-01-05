<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<script src="js/jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<!-- JS Functions Import -->
	<script src="js/scripts.js"></script>
</head>
<body>
	<div id="" class="box_logo">
		<img src="img/db_icon.png" alt="" width="80px">
		<div>
			<span>Database Populator</span>
		</div>
	</div>
	<div id="div_conexao" class="box_center">
		<div>
			<table>
				<tr>
					<td><span class="field_name">Banco</span></td>
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
						<input type="text" id="conn_user" class="input" onkeydown="if(event.keyCode == 13){js_conn();}" autofocus>
					</td>
				</tr>
			</table>
		</div>
		<div>
			<table>
				<tr>
					<td><span class="field_name">Senha</span></td>
					<td>
						<input type="password" id="conn_password" class="input" onkeydown="if(event.keyCode == 13){js_conn();}">
					</td>
				</tr>
			</table>
		</div>
		<div onclick="js_conn();" class="div_btn" id="button_connect">
			<span onclick="togle();">Conectar</span>
		</div>
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
						<select id="id_bases" onchange="js_listTables(); insert_enable('div_btn_disabled', 'div_btn'); ">
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
					<td>Quantidade de Registros</td>
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
				<tr>
					<td>Mostrar SQL</td>
					<td>
						<input type="radio" name="debug" value="on" id="debug">On
						<input type="radio"  name="debug" checked="checked">Off
					</td>
				</tr>
			</table>
		</div>
		<div id="button_insert" class="div_btn_disabled" onclick="js_insert();">Inserir</div>
	</div>
	<!--Div Message Success -->
	<div id="div_message">
	</div>
	<div id="div_loading">
		<img src="http://s1.ticketm.net/tm/en-us/img/sys/1000/gray75_polling.gif" alt="" width="30px"/> <br>
		<span>Inserindo dados... Aguarde!</span>
	</div>
	<!--Div ERRORS -->
	<div style="display:none;" id="div_error" class="box_error">
	</div>

</body>
</html>