<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta viewport="device-width">
	<title>Database Populator</title>
	<script src="js/jquery.js"></script>
	<link rel="stylesheet" href="css/style.css">
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
	<div id="div_connection_error" class="box_error">
	</div>
	<!--Div LOADING AJAX-->
	<div id="div_block">
	</div>
	<!--Div DADOS -->
	<div class="box_center" id="div_dados" style="display: none;">
		<div id="div_bases">
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
		<div id="div_tabelas" class="hidden">
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
		<div id="div_qtd_reg" class="hidden">
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
						</select>
					</td>
				</tr>
			</table>
		</div>
		<div class="div_btn" onclick="js_insert();" id="btn_insert">Inserir</div>
	</div>
	<!--Div Message Success -->
	<div id="div_message_success" class="box_error">
	</div>
	<div id="div_message_error" class="box_error">
	</div>
	<div id="div_loading" class="box_error">
		<img src="http://s1.ticketm.net/tm/en-us/img/sys/1000/gray75_polling.gif" alt="" width="30px"/> <br>
		<span>Inserindo dados... Aguarde!</span>
	</div>
	<!--Including os js scripts and functions-->
	<script src="js/scripts.js"></script>
</body>
</html>