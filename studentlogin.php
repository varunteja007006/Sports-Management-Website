<?php
session_start();

// check if the user is already logged in
if(isset($_SESSION['username']))
{
    header("location:ongoing_events.php");
    exit;
}

//checking the connectivity
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
    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
       
    if(mysqli_stmt_execute($stmt))
    {
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
        {
            mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            if(mysqli_stmt_fetch($stmt))
            {
                if(password_verify($password, $hashed_password))
                {
                    // password is true
                    session_start();
                    $_SESSION["username"] = $username;
                    $_SESSION["id"] = $id;
                    $_SESSION["loggedin"] = true;

                    //Redirect user 
                    header("location: ongoing_events.php");
          
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
<title>Student Login</title>
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
    <img src="avatar.png" class="avatar">
        <h1>Login Here</h1>
        <form action="" method="post">
            <p>Username</p>
            <input type="text" name="username" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="submit" name="" value="Login">
            <!--<a href="forgotpassword.html">Lost your password?</a><br>-->
            <a href="signup.php">Don't have an account?</a>
        </form>
    </div>

</body>

</html>