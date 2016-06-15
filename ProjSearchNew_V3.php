<?php
	include 'LogIn_Security.php';
	include 'LoadDb.php';
	//include 'indexnew.php';

	function logout() {
		session_destroy();
		header("Location: indexnew.php");
	}

	function export($input) {
		$output = "";
		$table = "proj"; // Enter Your Table Name 
		$query = pg_query("SELECT * FROM proj WHERE LOWER(proj_nm) LIKE LOWER('%" . $input . "%') OR proj_cd LIKE '%" . $input . "%' OR LOWER(client_nm) LIKE LOWER('%" . $input . "%') ORDER BY proj_cd ASC");
		$columns_total = pg_num_fields($query);

		// Get The Field Name

		for ($i = 0; $i < $columns_total; $i++) {
			$heading = pg_field_name($query, $i);
			$output .= '"'.$heading.'",';
		}
		$output .="\r\n";

		// Get Records from the table

		while ($row = pg_fetch_array($query)) {
			for ($i = 0; $i < $columns_total; $i++) {
				$output .= '"'.$row["$i"].'",';
			}
			$output .= "\r\n";
		}

		// Download the file

		$filename = "SearchResults.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);

		echo $output;
		exit;
	}

	if(isset($_POST['logout'])) {
		logout();
	}
	
	if(isset($_POST['export'])) {
		$input = $_POST['hidden_input'];
		$input = htmlspecialchars($input, ENT_QUOTES);
		if($input == "" || $input == " ") {

		} else {
			export($input);
		}
	}

	$query2 = pg_query("SELECT permissions FROM person WHERE person = '" . $_SESSION['username'] . "'");
	while($row = pg_fetch_array($query2)) {
		$permissions = $row['permissions'];
	}

	$query3 = pg_query("SELECT name_first, name_last FROM person WHERE person = '" . $_SESSION['username'] . "'");
    while($row2 = pg_fetch_array($query3)) {
    	$name_first = $row2['name_first'];
    	$name_last = $row2['name_last'];
    	$name_full = $name_first . " " . $name_last;
    }

?>

<!DOCTYPE html>
<html>

	<head>
		<title>Project Search</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<link rel="stylesheet" href="styles.css" type="text/css" >
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<script src="https://use.fontawesome.com/5a59419cf4.js"></script>
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  		<script src="scripts.js" type="text/javascript"></script>
	</head>

	<body class="main">
		<div class='container'>
			<div class='modal fade' id='new_entry' role='dialog'>
				<div class='modal-dialog modal-lg'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal'>&times;</button>
							<h4 class='modal-title'>New Entry</h4>
						</div>
						<div class='modal-body'>
							<form role='form' method='POST' action='' id='new_entry_form'>
								<div class='form-group'>
									<label for='proj_cd'>Project Number: </label>
									<input type='text' class='form-control' id='proj_cd_form'>
								</div>
								<div class='form-group'>
									<label for='proj_nm'>Project Name: </label>
									<input type='text' class='form-control' id='proj_nm_form'>
								</div>
								<div class='form-group'>
									<label for='proj_mgr'>Project Manager: </label>
									<input type='text' class='form-control' id='proj_mgr_form' placeholder='Last Name, First Name'>
								</div>
								<div class='form-group'>
									<label for='client_nm'>Client Name: </label>
									<input type='text' class='form-control' id='client_nm_form'>
								</div>
								<div class='form-group'>
									<label for='proj_loc'>Project Location: </label>
									<input type='text' class='form-control' id='proj_loc_form'>
								</div>
							</form>
						</div>
						<div class='modal-footer'>
							<button type='button' class='btn btn-success' data-dismiss='modal' id='insert'>Insert</button>
							<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
						</div>
					</div>
				</div>
			</div>
			<!--
			<div class='modal fade' id='delete_confirm' role='dialog'>
				<div class='modal-dialog modal-sm'>
					<div class='modal-content'>
						<div class='modal-header'>
							<button type='button' class='close' data-dismiss='modal'>&times;</button>
							<h4 class='modal-title'>Warning</h4>
						</div>
						<div class='modal-body'>
							<p>Are you sure you want to delete this row?</p>
						</div>
						<div class='modal-footer center-block'>
							<button type='button' class='btn btn-success' id='delete_yes' data-dismiss='modal'>Yes</button>
							<button type='button' class='btn btn-danger' id='delete_no' data-dismiss='modal'>No</button>
						</div>
					</div>
				</div>
			</div>
			-->
		</div>
		
			<div class="container-fluid search-bar fixed">
				<div class="row">
					<form role="form" action="ProjSearchNew_V3.php" method="POST">
						<div class="col-xs-2">
							<button type="submit" class="btn btn-success center-block" name="export" id="export" value="Export Results">Export</button>
							<input type="hidden" value="<?php echo htmlspecialchars($_POST['input']); ?>" name="hidden_input">
						</div>
					</form>
					<form role="form" action="ProjSearchNew_V3.php" method="POST">
						<div class="col-xs-8">
							<div class="row">
								<div class="form-group col-xs-11">
									<input type="text" class="form-control" value="<?php echo htmlspecialchars($_POST['input']); ?>" name="input" placeholder="Search by project number or keyword">		
								</div>
								<div class="form group col-xs-1">
									<button type="submit" class="btn btn-primary" name="search" id="search" value="Search">Search</button>
								</div>
							</div>
						</div>
						<div class="col-xs-2">
							<button type="submit" class="btn btn-danger center-block" name="logout" value="Log-Out">Logout</button>
						</div>
					</form>
				</div>
				<div class="row">
					<div class="col-xs-2">
						<?php 
						if($permissions == 't') {
							echo "<button type='button' class='btn btn-success btn-xs center-block' data-toggle='modal' data-target='#new_entry'>New Entry</button>";
						} else {
							echo "<button type='button' class='btn btn-success btn-xs center-block disabled' data-toggle='tooltip' data-placement='bottom' title='You do not have permission'>New Entry</button>";
						}
						?>
					</div>
					<div class="col-xs-8">
						<!--
						<div class="radio">
							<label class="radio-inline">Operator: </label>
							<label class="radio-inline"><input type="radio" value="And" name="optradio" checked="">AND</label>
							<label class="radio-inline"><input type="radio" value="Or" name="optradio">OR</label>
						</div>
						-->
					</div>
					<div class="col-xs-2">
						<p>Logged in as: <strong><?php echo $name_full; ?></strong></p> 
					</div>
				</div>
			</div>
		
		<div class="container-fluid no-padding clear">
			<div class="row">
				<div class="col-xs-1"></div>
				<div class="col-xs-10 display">
					<table class="table table-striped table-condensed">
						<tr>
							<th>Project Number</th>
							<th>Project Name</th>
							<th>Project Manager</th>
							<th>Client Name</th>
							<th>Project Location</th>
							<th>Action</th>
						</tr>
						<?php
						if(isset($_POST['search'])) {
							$result = pg_query("SELECT * FROM proj WHERE LOWER(proj_nm) LIKE LOWER('%" . $_POST['input'] . "%') OR proj_cd LIKE '%" . $_POST['input'] . "%' OR LOWER(client_nm) LIKE LOWER('%" . $_POST['input'] . "%') ORDER BY proj_cd ASC");
							while($rows = pg_fetch_array($result)) {
								echo "
									<tr>
										<td class='proj_cd' id=" . $rows['proj_cd'] . ">" . $rows['proj_cd'] . "</td>
										<td class='proj_nm' id='proj_nm'>" . $rows['proj_nm'] . "</td>
										<td class='proj_mgr' id='proj_mgr'>" . $rows['proj_mgr'] . "</td>
										<td class='client_nm' id='client_nm'>" . $rows['client_nm'] . "</td>
										<td class='proj_loc' id='proj_loc'>" . $rows['proj_loc'] . "</td>
										<td class='xx'>
											<form method='POST' action=''>
												<div class='btn-group btn-group-xs'>";
													if($permissions == 't') {
														echo "
															<button type='button' class='btn btn-success edit-btn' value='Edit' title='Edit'><span><i class='fa fa-pencil fa-lg' aria-hidden='true'></i></span></button>
															<button type='button' class='btn btn-success save-btn' value='Save' title='Save'><i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i></button>
															";
															//<button type='button' class='btn btn-success delete-btn' value='Delete' title='Delete' data-toggle='modal' data-target='#delete_confirm'><i class='fa fa-trash fa-lg' aria-hidden='true'></i></button>
													} else {
														echo "
															<button type='button' class='btn btn-success edit-btn disabled' disabled='disabled' value='Edit'><span><i class='fa fa-pencil fa-lg' aria-hidden='true'></i></span></button>
															<button type='button' class='btn btn-success save-btn disabled' disabled='disabled' value='Save'><i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i></button>
															";
															//<button type='button' class='btn btn-success delete-btn disabled' disabled='disabled' value='Delete' data-toggle='modal' data-target='#delete_confirm'><i class='fa fa-trash fa-lg' aria-hidden='true'></i></button>
													}
													echo "
												</div>
											</form>
										</td>
									</tr>";
							}
						}
						?>
					</table>
				</div>
				<div class="col-xs-1">
					<!--
					<a href="#" class="scroll text-center" data-toggle="tooltip" title="Back to top"><i class="material-icons md-56">vertical_align_top</i></a>
					-->
					<a href="#" class="scroll text-center" data-toggle="tooltip" title="Back to top">
						<img src="images/back-to-top.png" width="50" height="50">
					</a>
				</div>
			</div>
		</div>
		<div class='alert alert-success' id='save-alert'>
			<strong>Saved</strong>
		</div>
		<div class='alert alert-danger' id='delete-alert'>
			<strong>Deleted</strong>
		</div>
	</body>

</html>