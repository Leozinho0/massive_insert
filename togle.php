<?php 

 ?>

 <html>
 	<head>
 		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
 		<style>
 			body{
 				background-color: green;
 			}
 			.c_active{
 				background-color: yellow;
 			}
 			.c_disabled{
 				background-color: blue;
 			}	
 		</style>
 	</head>
 	<body>
 		<div id="lala" class="c_active" onclick="js_click();">
 			botao 1
 		</div>


 		 <script>
		 	function js_click(){
		 		$('#lala').toggleClass('c_disabled');
		 	}
		 </script>
 	</body>
 </html>