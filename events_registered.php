<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: studentlogin.php");
}
  

?>
<!doctype html>
<html>
<head>
<title>Events Registered</title>
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
</header>
                    
<div class="user_name">
	<ul>
  		<li>
 			<?php echo "Welcome ". $_SESSION['username']?></a>
      </li>
  	</ul>
</div>

<div class="student_enroll">
	<h1>EVENTS REGISTERED</h1> 
      <div class="evregister">
        <table>
        <tr>
          <th><h4>Event ID</h4></th>
          <th><h4>Event Name</h4></th>
          <th><h4>Type</h4></th>
          <th><h4>Description</h4></th>
          <th><h4>Start Date</h4></th>
          <th><h4>End Date</h4></th>
          <th><h4>Event Time</h4></th>
          <th><h4>Booked Event on</h4></th>
      </tr>  

      <?php 
          require_once "config.php";

          $user=$_SESSION['username'];

          $sql = "SELECT ename_id,ename,type,description,start_date,end_date,event_time,booked_on FROM book WHERE username='$user'"; 
          $result = $conn->query($sql);     
          
                if ($result)
                {
                while ($row = $result->fetch_assoc()) 
                {
                  $ID = $row["ename_id"];
                  $Evenetname = $row["ename"];
                  $Type = $row["type"];
                  $Description = $row["description"];
                  $Start_date = $row["start_date"]; 
                  $End_date = $row["end_date"]; 
                  $event_time = $row["event_time"]; 
                  $Created_at = $row["booked_on"];

 
                  echo '<tr> 
                    <td><br><center>'.$ID.'</center></td>
                    <td><br><center>'.$Evenetname.'</center></td> 
                    <td><br><center>'.$Type.'</center></td> 
                    <td><br><center>'.$Description.'</center></td> 
                    <td><br><center>'.$Start_date.'</center></td> 
                    <td><br><center>'.$End_date.'</center></td> 
                    <td><br><center>'.$event_time.'</center></td>
                    <td><br><center>'.$Created_at.'</center></td>
                    
                    </tr>';
                }
                  $result->free();
                } 
                  mysqli_close($conn);
        ?>



        </form>
      </table>
    </div>
    
</div>
</html>