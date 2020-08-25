<?php
//This script will handle login
session_start();

// check if the user is already logged in
if(isset($_SESSION['username']))
{
    header("location:add_events.php");
    exit;
}
require_once "config.php";

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password'])))
    {
        $err = "Please enter username + password";
        echo "<script>alert('$err');</script>";
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }


if(empty($err))
{
    $sql = "SELECT id, username, password FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
    
    
    // Try to execute this statement
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
        {
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            if(mysqli_stmt_fetch($stmt))
            {
                if(password_verify($password, $hashed_password))
                {
                            //Password is correct
                    session_start();
                    $_SESSION["username"] = $username;
                    $_SESSION["id"] = $id;
                    $_SESSION["adminloggedin"] = true;

                    //Redirect user
                    header("location: add_events.php");
                            
                }
            }

        }

    }
}    


}

?>
<!doctype html>
<html>
<head>
<title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="menu">
 	<ul>
  		<li> <a href="homepage.html">Home </a> </li>
  		<li> <a href="contact.html">Contact</a></li>
  		<li> <a href="aboutus.html">About us</a></li>
  		<li> <a href="chooseprofile.html">Log in</a></li>
  		<li> <a href="signup.php">Sign up</a></li>	
	</ul> 
</div>

    <div class="loginbox">
    <img src="avatar2.svg" class="avatar">
        <h1 >ADMINISTRATOR LOGIN</h1>
        <form action="" method="post">
            <p>Username</p>
            <input type="text" name="username" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" name="" value="Login">
            <!-- <a href="forgotpassword.html">Lost your password?</a><br> -->
        </form>
    </div>

</body>

</html>