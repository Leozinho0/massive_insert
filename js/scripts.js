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
function js_insert(){
	//APAGAR ISSO
	var quantidade = document.getElementById("qtd_insert").value;
	var debug = document.getElementById("debug").checked;
	//APAGAR ESSA MERDS

	var d = 'sgbd=' + document.getElementById("conn_sgbd").value + '&adress=' + document.getElementById("conn_adress").value + '&user=' + document.getElementById("conn_user").value + '&password=' + document.getElementById("conn_password").value + "&base=" + document.getElementById("id_bases").value + "&table=" + document.getElementById("id_tables").value + "&qtd=" + document.getElementById("qtd_insert").value +"&id=4";
	$('#div_message').hide();
	$('#div_error').hide();
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
			//1.Ds retorna um objeto JSON. Fazer um JSON.parse
			//2.Verificação do retorno ds. Pode ser um erro ds.[0] -> erro!
			
			try{
				arr = JSON.parse(ds);
				//if->Modo Debug
				//else->Modo Normal
				//if->Sem erro->Mostra a query
				//else->Erro->Mostra o info do erro
				if(arr[0] == ''){
					if(debug){
						$('#div_message').html(arr);
						$('#div_message').fadeIn();
					}else{
						$('#div_message').html(mensagem);
						$('#div_message').fadeIn();
					}	
				}else if(arr[0] == 'ERRO!<br>'){
					$('#div_error').html(arr[1]);
					$('#div_error').fadeIn();
				}
			}catch(e){
				$('#div_error').html(ds);
				$('#div_error').fadeIn();
			}
		}
	});
}
function js_conn(){
	var d = 'sgbd=' + document.getElementById("conn_sgbd").value + '&adress=' + document.getElementById("conn_adress").value + '&user=' + document.getElementById("conn_user").value + '&password=' + document.getElementById("conn_password").value + "&id=1";
	$.ajax({
		type: 'POST',
		url: 'conn_valid.php',
		data: d,
		success: function(ds){
			if(ds == "y"){
				$('#div_error').hide();
				conn_disable('div_btn','div_btn_disabled');
				js_listBases();
			}else {
				$('#div_error').html(ds);
				$('#div_error').fadeIn();
			}
		}
	});
}

//TORNAR ESSAS FUNÇÕES UMA SÓ, POSTERIORMENTE
//Fiz assim pq tava com preguiça
function conn_disable(class_name_remove, class_name_add){
	//Desativar botão 'conectar' trocando classes:
	$('#button_connect').removeClass(class_name_remove).addClass(class_name_add);
	//destaivar elementos da div:
	$('#div_conexao :input').attr('disabled', true);
}
function insert_enable(class_name_remove, class_name_add){
	//Desativar botão 'conectar' trocando classes:
	$('#button_insert').removeClass(class_name_remove).addClass(class_name_add);
}