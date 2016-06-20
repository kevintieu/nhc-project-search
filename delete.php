<?php
	include 'loadDB.php';

	$proj_cd = $_POST['proj_cd'];
	$result = pg_query("DELETE FROM proj WHERE proj_cd='" . $proj_cd . "'");
?>
