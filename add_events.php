<?php

session_start();
$msg="Welcome Admin";
$_SESSION["msg"] = $msg;

if(!isset($_SESSION['adminloggedin']) || $_SESSION['adminloggedin'] !==true)
{
    header("location: Adminlogin.php");
    exit;
}
require_once "config.php";

$eventname= $option = $description = $start_date= $end_date= $event_time="";
$err = "";
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(empty(trim($_POST['ename'])) || empty(trim($_POST['type'])) || empty(trim($_POST['description'])) || empty(trim($_POST['start_date'])) || empty(trim($_POST['end_date'])) || empty(trim($_POST['time'])))
    {
		$err = "Please enter all details";
		echo "<script>alert('$err');</script>";
    }
    else
    {
      	 
      	$eventname= trim($_POST['ename']);
       	$option = trim($_POST['type']);
       	$description = trim($_POST['description']);
       	$start_date= trim($_POST['start_date']);
       	$end_date= trim($_POST['end_date']);
       	$time= trim($_POST['time']);
    }

    if(empty($err))
    {
    	$sql = "SELECT ename FROM addevents WHERE ename = ?";
    	$stmt = mysqli_prepare($conn, $sql);
    	if($stmt)
      	{
            mysqli_stmt_bind_param($stmt, "s", $param_ename);
            // Set the value of param username
            $param_ename = $eventname;

              // Try to execute this statement
          	if(mysqli_stmt_execute($stmt))
          	{
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) > 0)
                {
                  $err= "Event already exists";
                  $msg= "Admin event already exists!!!";
                  $_SESSION["msg"] = $msg;
                }

                else
                {

                  $eventname= trim($_POST['ename']);
       					  $option = trim($_POST['type']);
       					  $description = trim($_POST['description']);
       					  $start_date= trim($_POST['start_date']);
       					  $end_date= trim($_POST['end_date']);
       					  $time= trim($_POST['time']);
                }
         	}
         	
    	}
    	mysqli_stmt_close($stmt);  
    }



    if(empty($err))
	{
		$sql = "INSERT INTO addevents (ename, type, description, start_date, end_date, event_time) VALUES (?,?,?,?,?,?)";
		
		$stmt = mysqli_prepare($conn, $sql);

		if ($stmt)
    	{
        	mysqli_stmt_bind_param($stmt, "ssssss", $param_ename, $param_type, $param_description, $param_startdate, $param_enddate, $param_eventtime);

        // Set these parameters
        	$param_ename = $eventname;
        	$param_type = $option;
        	$param_description = $description;
        	$param_startdate = $start_date;
        	$param_enddate = $end_date;
        	$param_eventtime = $time;
        // Try to execute the query
        	if (mysqli_stmt_execute($stmt))
        	{
           	  $msg="Admin event added";
           	  $_SESSION["msg"] = $msg;
        	}	
        	else{
			$err="Something went wrong... cannot redirect!";
			echo "<script>alert('$err');</script>";
        	}
    
        }
    	mysqli_stmt_close($stmt);
	}
	mysqli_close($conn);

}

?>

<!doctype html>
<html>
<head>
<title>Add Events</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="add_events.css">
</head>
<header>
<div class="menu">
 	<ul>
  		<li> <a href="add_events.php">  Add Events </a> </li>
  		<li> <a href="results.php">     Results    </a></li>
  		<li> <a href="logout.php">    Log out    </a></li>
	</ul>
</div> 
<div class="phpmsg">
	<h4><?php echo $_SESSION["msg"]?> </h4>
</div>
</header>
<div class="adding">
	<h1>ADD EVENTS</h1>
	<form action="" method="post">
		<h2>Event name:</h2>
		<input type="text" name="ename" required class="smalltext" required>
		<h2>Type:</h2><select name="type" required class="smalltext" required>
  		<option value="indoor"> <h3>Indoor</h3> </option>
  		<option value="outoor"> <h3>Outdoor</h3> </option>

		</select><br><br>
	
		<h2> Description:</h2>   
		<input type="text" name="description" required class="bigtext" required><br><br>
		<h2> Start Date:</h2>  
		<input type="date" name="start_date" required class="smalltext" required>
		<h2> End Date:</h2>  
		<input type="date" name="end_date" required class="smalltext" required><br><br>
		<h2> Event Time: </h2>  
		<input type="time" name="time" required class="smalltext" required><br><br>
		<center><input type="submit" value="Submit"></center>
	</form>
</div>
</html>