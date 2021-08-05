<?php
include 'inc/db.php';
include 'inc/config.php';
$success = false;
$msg = "";
if(isset($_GET['databaseHost']) && isset($_GET['databaseUser']) && isset($_GET['databasePassword']) && isset($_GET['webpanelUsername']) && isset($_GET['webpanelPassword'])){

	// Name of the data file
	$filename = 'sql_clean.sql';
	// MySQL host
	$mysqlHost = $_GET['databaseHost'];
	// MySQL username
	$mysqlUser = $_GET['databaseUser'];
	// MySQL password
	$mysqlPassword = $_GET['databasePassword'];
	// Webpanel Username
	$webpanelUser =  $_GET['webpanelUsername'];
	// Webpanel Password
	$webpanelPassword = $_GET['webpanelPassword'];

	// Create connection
	$conn = new mysqli($mysqlHost, $mysqlUser, $mysqlPassword);
	// Check connection
	if ($conn->connect_error) {
		$msg .=  "Connection failed: " . $conn->connect_error;
	}

	// Create database
	$sql = "CREATE DATABASE cyberwar_db";
	if ($conn->query($sql) === TRUE) {
		$msg .= "Creating Database .... DONE</br>";
	} else {
		$msg .= "Error creating database: " . $conn->error;
	}

	$conn->close();

	// Connect to MySQL server
	$link = mysqli_connect($mysqlHost, $mysqlUser, $mysqlPassword, "cyberwar_db") or die('Error connecting to MySQL Database: ' . mysqli_error());


	$tempLine = '';
	// Read in the full file
	$lines = file($filename);
	// Loop through each line
	foreach ($lines as $line) {

		// Skip it if it's a comment
		if (substr($line, 0, 2) == '--' || $line == '')
			continue;

		// Add this line to the current segment
		$tempLine .= $line;
		// If its semicolon at the end, so that is the end of one query
		if (substr(trim($line), -1, 1) == ';')  {
			// Perform the query
			mysqli_query($link, $tempLine) or print("Error in " . $tempLine .":". mysqli_error());
			// Reset temp variable to empty
			$tempLine = '';
		}
	}
	$options = [
		'cost' => 12,
	];
	$db = new db($mysqlHost, $mysqlUser, $mysqlPassword, "cyberwar_db");
	$sqlInsertUser = "INSERT INTO users (username, password) VALUES ('" .$webpanelUser. "','" .password_hash($webpanelPassword, PASSWORD_BCRYPT, $options). "')";
	$createResult = $db->query($sqlInsertUser);
	$success = true;
	$msg .= "User created .... DONE </br>";
	
	replace_in_file("inc/config.php", "%HOST%", $mysqlHost);
	replace_in_file("inc/config.php", "%USER%", $mysqlUser);
	replace_in_file("inc/config.php", "%PASS%", $mysqlPassword);
	$msg .= "Changing config.php .... DONE </br>";
}
function replace_in_file($FilePath, $OldText, $NewText)
{
    $Result = array('status' => 'error', 'message' => '');
    if(file_exists($FilePath)===TRUE)
    {
        if(is_writeable($FilePath))
        {
            try
            {
                $FileContent = file_get_contents($FilePath);
                $FileContent = str_replace($OldText, $NewText, $FileContent);
                if(file_put_contents($FilePath, $FileContent) > 0)
                {
                    $Result["status"] = 'success';
                }
                else
                {
                   $Result["message"] = 'Error while writing file';
                }
            }
            catch(Exception $e)
            {
                $Result["message"] = 'Error : '.$e;
            }
        }
        else
        {
            $Result["message"] = 'File '.$FilePath.' is not writable !';
        }
    }
    else
    {
        $Result["message"] = 'File '.$FilePath.' does not exist !';
    }
    //return $Result;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>CyberWar Installer....</title>

    <meta name="description" content="Source code generated using layoutit.com">
    <meta name="author" content="LayoutIt!">

    <link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  </head>
  <body class="bg-gradient-primary">

    <div class="container-fluid">
	<div class="row">
		<div class="col-md-4">
		</div>
		<div class="col-md-4">
</br>
			<img alt="header" src="assets/img/header.jpg" class="rounded mx-auto d-block">
			<h3 class="text-center text-primary">
				CyberWar - Webpanel Installer
			</h3>
			<p class="text-center text-info">
				Welcome stranger, <strong>fill out the form to install the Webpanel.</strong> If you have any questions dont hesitate to kill yourself! Dont play arround with tools like this! 
			</p>
			</br>
			<div>
				<?php if(isset($msg)){echo $msg;}?>
				</hr>
				<?php if($success == true){echo "<div class='alert alert-success' role='alert'>Your Webpanel was successfully installed! <b>IMPORTANT! DELETE YOUR INSTALL.PHP</b></div>";}?>
			</div>
			<form action='install.php' method='GET'>
				<div class="form-group">
					 
					<label for="databaseHost">
						Database - MySQL Host:
					</label>
					<input type="text" class="form-control" id="databaseHost" name="databaseHost">
				</div>
				<div class="form-group">
					 
					<label for="databaseUser">
						Database - MySQL Username:
					</label>
					<input type="text" class="form-control" id="databaseUser" name="databaseUser">
				</div>
				<div class="form-group">
					 
					<label for="databasePassword">
						Database - MySQL Password:
					</label>
					<input type="password" class="form-control" id="databasePassword" name="databasePassword">
				</div>
				<hr/>
				<div class="form-group">
					 
					<label for="exampleInputPassword1">
						Username Webpanel
					</label>
					<input type="text" class="form-control" id="webpanelUsername" name="webpanelUsername">
				</div>
				<div class="form-group">
					 
					<label for="exampleInputPassword1">
						Password Webpanel
					</label>
					<input type="password" class="form-control" id="webpanelPassword" name="webpanelPassword">
				</div>
				<hr/>
				<button type="submit" class="btn btn-primary">
					Install
				</button>
			</form>
		</div>
		<div class="col-md-4">
		</div>
	</div>
</div>

    <script src="assets/js/script.min.js"></script>
  </body>
</html>