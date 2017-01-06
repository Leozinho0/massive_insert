<?php

##DS Arrays
	$arr_ds_nomes;
	$arr_ds_char;
	$arr_ds_date;
	$arr_ds_datetime;
	$arr_ds_text;
	$arr_ds_time;
	$arr_ds_timestamp;
	$arr_ds_year;

	$arr_ds_nomes = json_decode (file_get_contents("../json/ds_nomes.json"));
	$arr_ds_char = json_decode (file_get_contents("../json/ds_char.json"));
	$arr_ds_date = json_decode (file_get_contents("../json/ds_date.json"));
	$arr_ds_datetime = json_decode (file_get_contents("../json/ds_datetime.json"));
	$arr_ds_text = json_decode (file_get_contents("../json/ds_text.json"));
	$arr_ds_time = json_decode (file_get_contents("../json/ds_time.json"));
	$arr_ds_timestamp = json_decode (file_get_contents("../json/ds_timestamp.json"));
	$arr_ds_year = json_decode (file_get_contents("../json/ds_year.json"));

?>