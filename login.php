<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
	
	if (isset($_POST['submitImg'])){
		$email = isset($_POST["login2Email"]) ? $_POST["login2Email"] : false;
		$pass = isset($_POST["login2Pass"]) ? $_POST["login2Pass"] : false;	
		$img = $_FILES['picToUpload'];
		/*foreach ($img as $key => $val) {
			   echo $val;
			   echo '\n';
		}*/
		$fullPath = "gallery/".basename($img['name']);
		//echo $fullPath;
		//echo '<br/>';
		$imgType = pathinfo($fullPath, PATHINFO_EXTENSION);
		//echo $imgType;
		$imgSize = ($img['size']);
		if ($imgType == 'jpeg' || $imgType == 'jpg'){

			if ($imgSize > 1048576){
				// file too large
				echo "<div class='alert alert-danger mt-3' role='alert'>
				Maximum image size is 1mb, please select a smaller image.
			    </div>";
			} else {
				// passed requirements 
					move_uploaded_file($img['tmp_name'],$fullPath);
					$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
					$res = $mysqli->query($query);

					if($row = mysqli_fetch_array($res)){
						$uid = $row['user_id'];
					$query = "INSERT INTO tbgallery (user_id,filename) VALUES ('".$uid."','".$fullPath."')";
					echo "<br/>";
				
					if ($mysqli->query($query)){
						//success i assume?
					} else {
						echo "<div class='alert alert-danger mt-3' role='alert'>
						Could not insert img to DB. 
						</div>";
						echo "<br/>";
						echo $mysqli->error;
					}
				
				}
					else {
						echo "<div class='alert alert-danger mt-3' role='alert'>
						User is not logged in.
			    		</div>";
					}
			}

		} else {
			echo "<div class='alert alert-danger mt-3' role='alert'>
			Select only .jpeg or .jpg filetypes.  
			</div>";
		}	
	} 

	
	
	?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Luke Partridge">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				 
				if($row = mysqli_fetch_array($res)){
					$uid = $row['user_id'];
						echo 	"<table class='table table-bordered mt-3'>
									<tr>
										<td>Name</td>
										<td>" . $row['name'] . "</td>
									<tr>
									<tr>
										<td>Surname</td>
										<td>" . $row['surname'] . "</td>
									<tr>
									<tr>
										<td>Email Address</td>
										<td>" . $row['email'] . "</td>
									<tr>
									<tr>
										<td>Birthday</td>
										<td>" . $row['birthday'] . "</td>
									<tr>
								</table>";
					
						echo 	"<form method='POST' action='login.php' enctype='multipart/form-data'>
									<div class='form-group'>
										<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
										<input type='submit' class='btn btn-standard' value='Upload Image' name='submitImg' />
										<input type='hidden' id='loginEmail' name='login2Email' value='".$email."'/>
										<input type='hidden' id='loginPass' name='login2Pass' value='".$pass."'/>
									</div>
								</form>";													
					

				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
		<?php
		if (isset($uid)){
			echo "</div>
				<h1>User Images</h1>
					<div class='row imageGallery'>";

					$query = "SELECT * FROM tbgallery WHERE user_id = '$uid'";
					$res = $mysqli->query($query);
					while ($row = mysqli_fetch_array($res))
					{
						echo "<div class='col-3' style='background-image: url(".$row['filename'].")'>
								</div>";
					}
			echo "</div>";
		}
		?>
			
		
		
	</div>
</body>
</html>