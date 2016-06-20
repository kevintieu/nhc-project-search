<?php
  include 'loadDB.php';

  function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }

  $proj_cd_form = $_POST['proj_cd_form'];
  $proj_nm_form = $_POST['proj_nm_form'];
  $proj_mgr_form = $_POST['proj_mgr_form'];
  $client_nm_form = $_POST['client_nm_form'];
  $proj_loc_form = $_POST['proj_loc_form'];

  sanitize($proj_cd);
  sanitize($proj_nm);
  sanitize($proj_mgr);
  sanitize($client_nm);
  sanitize($proj_loc);

  $result = pg_query("INSERT INTO proj (proj_cd, proj_nm, proj_mgr, client_nm, proj_loc) 
  VALUES ('$proj_cd_form', '$proj_nm_form', '$proj_mgr_form', '$client_nm_form', '$proj_loc_form')"); 

?>
