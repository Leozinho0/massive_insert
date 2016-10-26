

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
			$('#btn_insert').show();
		}
	});
}
function js_insert(){
	var d = 'sgbd=' + document.getElementById("conn_sgbd").value + '&adress=' + document.getElementById("conn_adress").value + '&user=' + document.getElementById("conn_user").value + '&password=' + document.getElementById("conn_password").value + "&base=" + document.getElementById("id_bases").value + "&table=" + document.getElementById("id_tables").value + "&qtd=" + document.getElementById("qtd_insert").value +"&id=4";
	$('#div_message_error').hide();
	$('#div_message_success').hide();
	$('#div_block').show();
	$('#div_loading').fadeIn();
	$.ajax({
		type: 'POST',
		url: 'conn_valid.php',
		data: d,
		success: function(ds){
			if(ds == 1){
				$('#div_block').hide();
				$('#div_loading').hide();
				$('#div_message_success').html("Dados Inseridos com Sucesso!");
				$('#div_message_success').fadeIn();
			}else{
				$('#div_block').hide();
				$('#div_loading').hide();
				$('#div_message_error').html(ds);
				$('#div_message_error').fadeIn();
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
			if(ds == 1){
				$('#div_connection_error').hide();
				js_listBases();
			}else {
				$('#div_connection_error').html(ds);
				$('#div_connection_error').fadeIn();
			}
		}
	});
}