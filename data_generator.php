<?php


//for time
$bissexto = 4;

	for($ano = 1992; $ano <= 2016; $ano++){
		for($mes = 1; $mes <= 12; $mes++){
			
			$mes = sprintf("%02d", $mes);

			if($mes == '04' || $mes == '06' || $mes == '09' || $mes == '11'){
				for($dia = 1; $dia <= 30; $dia++){
					$dia = sprintf("%02d", $dia);

					echo "{\"value\":\"{$ano}-{$mes}-{$dia}\"},<br>";
				}
			}else if($mes == '02' && $bissexto == 4){
				for($dia = 1; $dia <= 29; $dia++){
					$dia = sprintf("%02d", $dia);

					echo "{\"value\":\"{$ano}-{$mes}-{$dia}\"},<br>";
				}

				$bissexto = 0;
			}else if($mes == '02' && $bissexto != 4){
				for($dia = 1; $dia <= 28; $dia++){
					$dia = sprintf("%02d", $dia);

					echo "{\"value\":\"{$ano}-{$mes}-{$dia}\"},<br>";
				}
			}else{
				for($dia = 1; $dia <= 31; $dia++){
					$dia = sprintf("%02d", $dia);

					echo "{\"value\":\"{$ano}-{$mes}-{$dia}\"},<br>";
				}
			}
		}
		$bissexto++;
	}

//For time
/*
	for($hour = 0; $hour <= 23; $hour++){
				$hour = sprintf("%02d", $hour);
		for($min = 0; $min <= 59; $min++){
			
				$min = sprintf("%02d", $min);
			for($sec = 0; $sec <= 59; $sec++){
				$sec = sprintf("%02d", $sec);



				echo "{
		\"value\": \"{$hour}-{$min}-{$sec}\"
	},<br>";
			}
		}
	}
*/
//for year
/*
	for($year = 1990; $year <= 2016; $year++){
		echo "{
		\"value\": \"{$year}\"
	},";
	}
*/
?>