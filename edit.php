<?php
	include 'LoadDb.php';

	$proj_cd = $_POST['proj_cd'];
	$field_name = $_POST['field_name'];
	$value = $_POST['value'];

	$result = pg_query("UPDATE proj SET " . $field_name . "='" . $value . "' WHERE proj_cd='" . $proj_cd ."'") or die('Could not execute query.');
?>