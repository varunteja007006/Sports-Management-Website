<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: studentlogin.php");
}
$row="";

require_once "config.php";

$eventname=$firstplace=$secondplace=$thirdplace= "";
$err = "";
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    if(empty(trim($_POST['ename'])))
    {
        $err = "Please enter event name";
        echo "<script>alert('$err');</script>";
    }
    else
    {         
        $eventname= trim($_POST['ename']);
    }
    /////////////unable to check whether the event already exists in bellow lines
    if(empty($err))
    {
      $sql = "SELECT ename,first,second,third FROM results WHERE ename = ?";
      $stmt = mysqli_prepare($conn, $sql);

      mysqli_stmt_bind_param($stmt, "s", $param_ename);
      $param_ename = $eventname;
      if(mysqli_stmt_execute($stmt))
      {
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt)>0)
        {
          mysqli_stmt_bind_result($stmt,$ename,$first,$second,$third);
          if(mysqli_stmt_fetch($stmt))
          {
            $eventname=$ename;
            $firstplace=$first;
            $secondplace=$second;
            $thirdplace=$third;
          }
        }
      }
        

        mysqli_stmt_close($stmt); 
      }
        
}
 
    mysqli_close($conn);

?>


<!doctype html>
<html>
<head>
  
<script>
function showHint(str) {
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "gethint.php?q=" + str, true);
        xmlhttp.send();
    }
}
</script>

<title>Results</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="students_page.css">
</head>
<header>
<div class="menu">
 	<ul>
  		<li> <a href="ongoing_events.php">Events</a> </li>
  		<li> <a href="events_registered.php">Events Registered</a></li>
  		<li> <a href="display_results.php">Results</a></li>
  		<li> <a href="logout.php">Log out</a></li>
	</ul> 
</div>
<div class="user_name">
  <ul>
      <li>
      <?php echo "Welcome ". $_SESSION['username']?></a>
      </li>
    </ul>
</div>
<div class="result_body">
  <h1>RESULTS</h1>   <br>                       
    <form action="" method="post">
    <input name="ename" placeholder="Please enter the event " required class="smalltext" onkeyup="showHint(this.value)"> 
    <p>Suggestions:<span id="txtHint"></span></p>
    <input type="submit" value="Search">
    <br>
    <br>
    <div class="result_table">
    <table>
        <tr>
          <th><h4>Event Name</h4></th>
          <th><h4>First Prize</h4></th>
          <th><h4>Second Prize</h4></th>
          <th><h4>Third Prize</h4></th>
        </tr>
        <tr>
          <td><br><center><?php echo $eventname?></center></td>
          <td><br><center><?php echo $firstplace?></center></td>
          <td><br><center><?php echo $secondplace?></center></td>
          <td><br><center><?php echo $thirdplace?></center></td>
        </tr>
    </table>
    </div>
    
</div>
</header>
</html>
