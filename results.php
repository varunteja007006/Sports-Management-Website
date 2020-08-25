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

$eventname= $firstprize = $secondprize = $thirdprize="";
$err = "";
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(empty(trim($_POST['ename'])) || empty(trim($_POST['first'])) || empty(trim($_POST['second'])) || empty(trim($_POST['third'])))
    {
        $err = "Please enter username + password";
        echo "<script>alert('$err');</script>";
    }
    else
    {
         
        $eventname= trim($_POST['ename']);
        $firstprize = trim($_POST['first']);
        $secondprize = trim($_POST['second']);
        $thirdprize= trim($_POST['third']);

    }
    /////////////unable to check whether the event already exists in bellow lines
    if(empty($err))
    {
        $sql = "SELECT ename FROM results WHERE ename = ?";
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
                    $msg="Admin result already exists!!";
                    $_SESSION["msg"] = $msg;
                    $err= "data exists!!";
                }

                else{
                        $eventname= trim($_POST['ename']);
                        $firstprize = trim($_POST['first']);
                        $secondprize = trim($_POST['second']);
                        $thirdprize= trim($_POST['third']);

                }
            }
               
        }
        mysqli_stmt_close($stmt);
    }

    if(empty($err))
    {
        $sql = "INSERT INTO results (ename, first, second, third) VALUES (?,?,?,?)";
        
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt)
        {
            mysqli_stmt_bind_param($stmt, "ssss", $param_ename, $param_first, $param_second, $param_third);

        // Set these parameters
            $param_ename = $eventname;
            $param_first = $firstprize;
            $param_second = $secondprize;
            $param_third = $thirdprize;
        // Try to execute the query
            if (mysqli_stmt_execute($stmt))
            {
              $msg="Admin result added";
              $_SESSION["msg"] = $msg;
            }   
            else{
            $err= "Something went wrong... cannot redirect!";
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
<title>Results</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="add_events.css">
</head>
<header>
<div class="menu">
 	<ul>
  		<li> <a href="add_events.php">Add Events</a> </li>
  		<li> <a href="results.php">Results</a></li>
  		<li> <a href="logout.php">Log out</a></li>
	</ul> 
</div>
</header>
<div class="phpmsg">
    <h4><?php echo $_SESSION["msg"]?> </h4>
</div>
<div class="result_body">
  <h1>RESULTS</h1>                          
    <form action="" method="post">
    <h2>Event name:</h2>
    <input type="text" name="ename" required class="bigtext">
    <br>
    <br>
    <h2>1st prize:</h2>                                                            
    <input type="text" name="first" required class="bigtext"><br><br>
    <h2>2nd prize :</h2>  
    <input type="text" name="second" required class="bigtext"><br><br>
    <h2>3rd prize :</h2>  
    <input type="text" name="third" required class="bigtext">
    <br>
    <br>
    <center><input type="submit" value="Submit"></center>
    </form>
</div>
</html>