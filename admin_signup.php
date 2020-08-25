<?php
require_once "config.php";

$username = $password = $confirm_password = $email ="";
$username_err = $password_err = $confirm_password_err = $err="";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"])))
    {
        $username_err = "Username cannot be blank";
        echo "<script>alert('$username_err');</script>";
    }
    else{
        $sql = "SELECT id FROM admin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Set the value of param username
            $param_username = trim($_POST['username']);
            // Try to execute this statement
            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                    echo "<script>alert('$username_err');</script>";
                }
                else
                {
                    $username = trim($_POST['username']);
                   
                }
            }
            else
            {
                    $err= "Something went wrong";
                    echo "<script>alert('$err');</script>";
            }
        }
    }

    mysqli_stmt_close($stmt);

    if(empty(trim($_POST["email"])))
    {
        $err="Email ID cannot be empty";
        echo "<script>alert('$err');</script>";
    }
    else
    {
        $sql = "SELECT id FROM admin WHERE email_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Set the value of param username
            $param_email = trim($_POST['email']);
            // Try to execute this statement
            if(mysqli_stmt_execute($stmt))
            {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $err = "This email is already taken"; 
                    echo "<script>alert('$err');</script>";
                }
                else
                {
                    $email= trim($_POST['email']);
                    /*mail($username,'Registration','You have been sucessfully registered');*/
                }
            }
            else
            {
                    $err= "Something went wrong";
                    echo "<script>alert('$err');</script>";

            }
        }
    }



// Check for password
if(empty(trim($_POST['password'])))
{
    $password_err = "Password cannot be blank";
    echo "<script>alert('$password_err');</script>";
}
elseif(strlen(trim($_POST['password'])) < 5)
{
    $password_err = "Password cannot be less than 5 characters";
    echo "<script>alert('$password_err');</script>";
}
else
{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password']))
{
    $password_err = "Passwords should match";
    echo "<script>alert('$password_err');</script>";
}


// If there were no errors, go ahead and insert into the database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($err))
{
    $sql = "INSERT INTO admin (email_id, username, password) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "sss", $param_email, $param_username, $param_password);

        // Set these parameters
        $param_email = $email;
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: studentlogin.php");
            
                    $to_email = $email;
                    $subject = "Username registeration";
                    $body = "Hi, You have successfully registered in sportz website";
                    //add headers example from:xoxoxo
                    $headers = "";

                    if (mail($to_email, $subject, $body,$headers))
                    {
                        echo "Email successfully sent to $to_email...";
                    } else 
                    {
                        echo "Email sending failed...";
                    }
        }
        else
        {
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
<title>Sign Up</title>
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

    <div class="signup">
        <h1 >ADMIN SIGN UP</h1>
        <form action="" method="POST">
            <p>Email</p>
            <input type="text" name="email" placeholder="Enter your Email" required>
            <p>Username</p>
            <input type="text" name="username" placeholder="Enter Username" required>
            <p>Password</p>
            <input type="password" name="password" placeholder="Enter Password" required>
            <p>Confirm Password</p>
            <input type="password" name="confirm_password" placeholder="Enter Password again" required>
            <input type="submit" name="" value="Sign in">

        </form>
    </div>

</body>

</html>