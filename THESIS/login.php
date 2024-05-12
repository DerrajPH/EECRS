<?php 
include "api/database.php";

session_start();

if (isset($_SESSION['username']))
{
    header('location: main.php');
    exit; // Add exit to prevent further execution
}

if (isset($_POST['login']))
{
    // removes backslashes
    $username = stripslashes($_REQUEST['username']);
    //escapes special characters in a string
    $username = mysqli_real_escape_string($conn, $username);
    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($conn, $password);
    //Checking is user existing in the database or not
    $query = "SELECT * FROM `users` WHERE username='$username' and password='" . md5($password) . "'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn)); // Use mysqli_error
    $rows = mysqli_fetch_assoc($result); // Fetch the result as an associative array
    if ($rows) {
  
        $_SESSION['username'] = $rows['username']; // Assuming 'username' is the column name in your database
        $_SESSION['user_id'] = $rows['id']; // Change 'user_id' to the actual column name
        // Redirect user to main.php

       // Display success message using SweetAlert
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () {';
        echo 'swal.fire("Flux","Successfully Logged In!","success");';
        echo '}, 1000);</script>'; // 3000 milliseconds = 3 seconds

        // Delay the redirection
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () {';
        echo 'window.location.href = "main.php";';
        echo '}, 3000);</script>'; // Redirect after 3 seconds

    }
    
    else
    {
      
       echo '<script type="text/javascript">';
       echo 'setTimeout(function () { swal.fire("Flux","Incorrect Username or Password","error");';
       echo "}, 1000);</script>";

    }
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>EECRS - Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="Bootstrap/css/bootstrap.min.css">
  <!-- External CSS -->
  <link rel="stylesheet" type="text/css" href="external/style.css">
  <!-- JavaScript -->
  <script src="Bootstrap/js/jquery.min.js"></script>
  <script src="Bootstrap/js/bootstrap.js"></script>
  <!-- Sweet Alert-->
  <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@3/dark.css" rel="stylesheet">
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@9/dist/sweetalert2.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">EECRS</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="index.php">Home</a></li>
        <li><a href="">About Us</a></li>
        <li><a href="">Help</a></li>
        <li><a href="main.php">Examination</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
          <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>
      </ul>
    </div>
  </div>
</nav>

<br><br><br><br>
<div class="container-login">
	<form method="post">
    <b><h2>Log In</h2></b>
    <div class="form-group">
      <label class="required" for="username">Username:</label>
      <input type="username" class="form-control" name="username" id="username" placeholder="Enter your username">
    </div>
    <div class="form-group">
      <label class="required" for="password">Password:</label>
      <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password">
    </div>
    <button type="submit" name="login" class="btn btn-default">Login</button>
  </form>
</div>

</body>
</html>