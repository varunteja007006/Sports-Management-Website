<?php
session_start();

if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !==true)
{
    header("location: studentlogin.php");
    exit;
}

$msg="";
$_SESSION["msg"] = $msg;
$user=$_SESSION['username'];

require_once "config.php";

$bookename=$bookeventid=$bookevent=$booktype=$bookdescription=$bookstartdate=$bookenddate=$bookeventtime="";

$err=$err_insert="";

if($_SERVER['REQUEST_METHOD'] =="POST")
{
  if(empty(trim($_POST['bookname'])))
  {
    $err ="please enter event name to book";
    echo "<script>alert('$err');</script>";
  }
  else{
    $bookename=trim($_POST['bookname']);
  }

if(empty($err))
{
  $sql="SELECT ID,ename,type,description,start_date,end_date,event_time FROM addevents WHERE ename=?";
  $stmt =mysqli_prepare($conn,$sql);
  mysqli_stmt_bind_param($stmt,"s",$param_ename);
  $param_ename=$bookename;
  if(mysqli_stmt_execute($stmt))
  {
    mysqli_stmt_store_result($stmt);
    if(mysqli_stmt_num_rows($stmt)>0)
    {
      mysqli_stmt_bind_result($stmt,$enameid,$ename,$type,$descripiton,$start_date,$end_date,$event_time);
      if(mysqli_stmt_fetch($stmt))
      {
        $bookeventid=$enameid;
        $bookename=$ename;
        $booktype=$type;
        $bookdescription=$descripiton;
        $bookstartdate=$start_date;
        $bookenddate=$end_date;
        $bookeventtime=$event_time;
      }
      else{
        $err="something went wrong";
        echo "<script>alert('$err');</script>";
      }
    } 
   }
   mysqli_stmt_close($stmt);
}

if(empty($bookeventid)||empty($bookename)||empty($booktype)||empty($bookdescription)||empty($bookstartdate)||empty($bookenddate)||empty($bookeventtime))
  {
    $err_insert="some data is missing";
    echo "<script>alert('$err');</script>";
  }
else{

  $sql="SELECT ename from book WHERE ename=?";
  $stmt= mysqli_prepare($conn, $sql);
  if($stmt)
  {
    mysqli_stmt_bind_param($stmt,"s",$param_ename);
    $param_ename=$bookename;
    if(mysqli_stmt_execute($stmt))
    {
      mysqli_stmt_store_result($stmt);
      if(mysqli_stmt_num_rows($stmt)>1)
      {
        $err_insert="Already registered";
        $msg="Already registered!!";
        $_SESSION["msg"]=$msg;
      }
    }
   }
   mysqli_stmt_close($stmt);
}

if(empty($err_insert))
{
  $sql="INSERT INTO book (username,ename_id,ename,type,description,start_date,end_date,event_time) VALUES (?,?,?,?,?,?,?,?)";
  $stmt= mysqli_prepare($conn, $sql);
  if($stmt)
  {
    mysqli_stmt_bind_param($stmt,"ssssssss",$param_user,$param_enameid,$param_ename,$param_type,$param_description,$param_startdate,$param_enddate,$param_eventtime);
          $param_user=$user;
          $param_enameid=$bookeventid;
          $param_ename=$bookename;
          $param_type=$booktype;
          $param_description=$bookdescription;
          $param_startdate=$bookstartdate;
          $param_enddate=$bookenddate;
          $param_eventtime=$bookeventtime;
        if(mysqli_stmt_execute($stmt))
        {
          
          $msg="event booked!!!";
          $_SESSION["msg"]=$msg;
        }
        else
        {
          $err= "Something went wrong... cannot redirect!";
          echo "<script>alert('$err');</script>";
        }

  }

mysqli_stmt_close($stmt);

}


}
?>
<!doctype html>
<html>
<head>
<title>Book Events</title>
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
      <?php echo "Welcome ". $_SESSION['username']?>
      <?php echo " ".$_SESSION["msg"]?>
      </li>
    </ul>
</div>
<div class="bookit">
  <h2>Book your event here!!</h2>
  <form action="" method="post">
    <input type="text" name="bookname" placeholder="Type the event to register !!"><br>
    <input type="submit" name="button" value="Book">    
  </form>
</div>

</header>
<div class="students_page">
  <h1>EVENTS</h1>
    <div class="ongoing">
      <table>
        <tr>
        <th><h4>Event Name</h4></th>
        <th><h4>Type</h4></th>
        <th><h4>Start Date</h4></th>
        <th><h4>End Date</h4></th>
        <th><h4>Event Time</h4></th>
        <th><h4>Description</h4></th>
        
      </tr>
      
        <?php 
          require_once "config.php";
          $sql = "SELECT * FROM addevents"; 
          

                if ($result = $conn->query($sql))
                {
                while ($row = $result->fetch_assoc()) 
                {
                  $ID = $row["ID"];
                  $Evenetname = $row["ename"];
                  $Type = $row["type"];
                  $Description = $row["description"];
                  $Start_date = $row["start_date"]; 
                  $End_date = $row["end_date"]; 
                  $event_time = $row["event_time"]; 
                  $Created_at = $row["created_at"];

 
                  echo '<tr> 
                    <td><br><center>'.$Evenetname.'</center></td> 
                    <td><br><center>'.$Type.'</center></td> 
                    <td><br><center>'.$Start_date.'</center></td> 
                    <td><br><center>'.$End_date.'</center></td> 
                    <td><br><center>'.$event_time.'</center></td>
                    <td><br>'.$Description.'</td> 
                    
                    </tr>';
                }
                  $result->free();
                } 
                
        ?>



        
      </table>
    </div>
  </div>
</html>