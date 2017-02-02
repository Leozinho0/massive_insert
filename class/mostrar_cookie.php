<?php
session_start();
if(isset($_SESSION['leo'])){
	echo "In";
}else{
	echo "Not logged";
	}?>
<input type="button" value="sair" onclick="<?php unset($_SESSION['leo']) ?>">