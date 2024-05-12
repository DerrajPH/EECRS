<?php 
session_start();

session_unset();
session_destroy();

// Display success message using SweetAlert
echo '<script type="text/javascript">';
echo 'setTimeout(function () {';
echo 'swal.fire("Flux","Logging Out!","success");';
echo '}, 1000);</script>'; // 3000 milliseconds = 3 seconds

// Delay the redirection
echo '<script type="text/javascript">';
echo 'setTimeout(function () {';
echo 'window.location.href = "index.php";';
echo '}, 3000);</script>'; // Redirect after 3 seconds

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>EECRS - Logout</title>
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
        <?php if(isset($_SESSION['username'])) : ?>
          <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['username']; ?> (<?php echo isset($membership) ? $membership : ''; ?>) <span class="caret"></span>
            </a>
            <?php if ($main_admin == "admin" || $check_membership == 4) : ?>
              <ul class="dropdown-menu">
                <li><a href="admin.php"><span class="glyphicon glyphicon-hdd"></span> Panel</a></li>
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            <?php else : ?>
              <ul class="dropdown-menu">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
              </ul>
            <?php endif; ?>
          </li>
        <?php else : ?>
          <li><a href="login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
          <li><a href="register.php"><span class="glyphicon glyphicon-user"></span> Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

</body>
</html>