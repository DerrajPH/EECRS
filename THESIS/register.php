<?php 
include "api/database.php";

session_start();

if (isset($_SESSION['username']))
{
    header('location: main.php');
    exit; // Add exit to prevent further execution
}

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal.fire("EECRS", "Invalid Username. Only underscores and alphanumeric characters are allowed.", "error");';
        echo '}, 1000);</script>';
    } else {
        $checkusername = "SELECT * FROM `users` WHERE username='$username'";
        $result = mysqli_query($conn, $checkusername);
        if (mysqli_num_rows($result) > 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal.fire("EECRS", "Username Already Used", "error");';
            echo '}, 1000);</script>';
        } else {
            $password = md5($password);
            $query = "INSERT INTO users (id, username, password, membership) VALUES (NULL, '$username', '$password', '1')";
            if (mysqli_query($conn, $query)) {
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = mysqli_insert_id($conn);

                // Display success message using SweetAlert
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () {';
                echo 'swal.fire("Flux","Successfully Registered!","success");';
                echo '}, 1000);</script>'; // 3000 milliseconds = 3 seconds

                // Delay the redirection
                echo '<script type="text/javascript">';
                echo 'setTimeout(function () {';
                echo 'window.location.href = "main.php";';
                echo '}, 3000);</script>'; // Redirect after 3 seconds

            } else {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
            }
        }
    }
}
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

<br><br>
<div class="container-login">
  <b><h2>Register</h2></b>
	<form method="post">
    <div class="form-group">
      <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
        <span id="username-availability-msg" class="text-muted"></span>
    </div>
    <div class="form-group">
      <label class="form-label" for="password">Password</label>
        <input type="password" id="password" class="form-control" name="password" placeholder="Enter your password" aria-describedby="password" required/>
        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
    </div>
    <div class="form-group">
      <label class="form-label" for="confirm-password">Confirm Password</label>
        <input type="password" id="confirm-password" class="form-control" name="confirm-password" placeholder="Confirm your password" aria-describedby="confirm-password" required/>
        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
    </div>
      <div>
        <span id="password-match-msg" class="text-success"></span>
      </div>
    <button type="submit" class="btn btn-default" id="register-btn" name="register" disabled>Register</button>
  </form>
</div>

<script>
 const usernameInput = document.getElementById('username');
 const usernameAvailabilityMsg = document.getElementById('username-availability-msg');
 const passwordInput = document.getElementById('password');
 const confirmPasswordInput = document.getElementById('confirm-password');
 const passwordMatchMsg = document.getElementById('password-match-msg');
 const registerBtn = document.getElementById('register-btn');

 // Function to check username availability
 usernameInput.addEventListener('input', () => {
     const username = usernameInput.value;

     if (username.trim() !== '') {
         fetch(`check_username.php?username=${encodeURIComponent(username)}`)
             .then(response => response.json())
             .then(data => {
                 if (data.available) {
                     usernameAvailabilityMsg.textContent = 'Username available';
                     usernameAvailabilityMsg.classList.add('text-success');
                     usernameAvailabilityMsg.classList.remove('text-danger');
                     checkEnableRegisterButton();
                 } else {
                     usernameAvailabilityMsg.textContent = data.message;
                     usernameAvailabilityMsg.classList.add('text-danger');
                     usernameAvailabilityMsg.classList.remove('text-success');
                     disableRegisterButton();
                 }
             })
             .catch(error => {
                 console.error('Error checking username availability:', error);
             });
     } else {
         usernameAvailabilityMsg.textContent = '';
         usernameAvailabilityMsg.classList.remove('text-success', 'text-danger');
         disableRegisterButton();
     }
 });

 // Function to enable Register button if password matches
 confirmPasswordInput.addEventListener('input', () => {
     if (confirmPasswordInput.value === passwordInput.value) {
         passwordMatchMsg.textContent = 'Passwords match';
         passwordMatchMsg.classList.add('text-success');
         passwordMatchMsg.classList.remove('text-danger');
         checkEnableRegisterButton();
     } else {
         passwordMatchMsg.textContent = 'Passwords do not match';
         passwordMatchMsg.classList.add('text-danger');
         passwordMatchMsg.classList.remove('text-success');
         disableRegisterButton();
     }
 });

 // Function to enable Register button
 function checkEnableRegisterButton() {
     if (usernameAvailabilityMsg.classList.contains('text-success') &&
         passwordMatchMsg.classList.contains('text-success')) {
         registerBtn.removeAttribute('disabled');
     } else {
         disableRegisterButton();
     }
 }

 // Function to disable Register button
 function disableRegisterButton() {
     registerBtn.setAttribute('disabled', 'true');
 }
 </script>

</body>
</html>