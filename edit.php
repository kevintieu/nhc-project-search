<?php
	include 'loadDB.php';

	function sanitize($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	$proj_cd = $_POST['proj_cd'];
	$field_name = $_POST['field_name'];
	$value = $_POST['value'];

	sanitize($proj_cd);
	sanitize($field_name);
	sanitize($value);

	$result = pg_query("UPDATE proj SET " . $field_name . "='" . $value . "' WHERE proj_cd='" . $proj_cd ."'") or die('Could not execute query.');
?>
