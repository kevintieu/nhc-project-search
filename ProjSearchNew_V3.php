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
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  		<script src="scripts.js" type="text/javascript"></script>
	</head>

	<body class="main">
		<div class="container-fluid search-bar">
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
								<input type="text" class="form-control" value="<?php echo htmlspecialchars($_POST['input']); ?>" name="input" placeholder="Enter a keyword">		
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
						echo "<button type='button' class='btn btn-success btn-xs center-block'>New Entry</button>";
					} else {
						echo "<button type='button' class='btn btn-success btn-xs center-block disabled' data-toggle='tooltip' data-placement='bottom' title='You do not have permission'>New Entry</button>";
					}
					?>
				</div>
				<div class="col-xs-8">
					<div class="radio">
						<label class="radio-inline">Operator: </label>
						<label class="radio-inline"><input type="radio" value="And" name="optradio" checked="">AND</label>
						<label class="radio-inline"><input type="radio" value="Or" name="optradio">OR</label>
					</div>
				</div>
				<div class="col-xs-2">
					<p>Logged in as: <strong><?php echo $name_full; ?></strong></p> 
				</div>
			</div>
		</div>
		<div class="container-fluid no-padding">
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
							// $result = pg_query("SELECT * FROM (SELECT proj_cd, proj_nm, proj_mgr, proj_loc, client_nm, ts_rank_cd(to_tsvector('english', body), to_tsquery('".$SearchStr."')) AS score FROM proj) s WHERE score > 0 ORDER BY score DESC, proj_cd DESC;") or die(pg_last_error());
							$result = pg_query("SELECT * FROM proj WHERE LOWER(proj_nm) LIKE LOWER('%" . $_POST['input'] . "%') OR proj_cd LIKE '%" . $_POST['input'] . "%' OR LOWER(client_nm) LIKE LOWER('%" . $_POST['input'] . "%') ORDER BY proj_cd ASC");
							while($rows = pg_fetch_array($result)) {
								echo "
									<tr>
										<td>" . $rows['proj_cd'] . "</td>
										<td>" . $rows['proj_nm'] . "</td>
										<td>" . $rows['proj_mgr'] . "</td>
										<td>" . $rows['client_nm'] . "</td>
										<td>" . $rows['proj_loc'] . "</td>
										<td class='xx'><button class='btn btn-info edit-btn'>Edit</button></td>
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
	</body>

</html>