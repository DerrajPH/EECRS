<?php 
include ("api/database.php");
session_start();

$geographyScore = null;

if (isset($_SESSION["username"])) {
    $query = "SELECT * FROM `users` WHERE id='{$_SESSION["user_id"]}'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();

        $main_admin = $userData["username"];
        $check_membership = $userData["membership"];

        if ($main_admin == "admin") {
            $membership = "";
        } elseif ($check_membership == 4) {
            $membership = "Admin";
        } elseif ($check_membership == 1) {
            $membership = "Student";
        }

        $geographyScore = $userData["geography"];
    }
}

if ($geographyScore === null) {
} else {
    header("Location: main.php");
    exit();
}

$answers = array("a", "a", "a", "a", "c", "b", "a", "b", "b", "c", "a", "a", "a", "c", "b", "b", "a", "a", "c", "a");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $submitted_answers = array($_POST["q1"], $_POST["q2"], $_POST["q3"], $_POST["q4"], $_POST["q5"], $_POST["q6"], $_POST["q7"], $_POST["q8"], $_POST["q9"], $_POST["q10"]);
    for ($i = 0; $i < count($answers); $i++) {
        if ($submitted_answers[$i] == $answers[$i]) {
            $score++;
        }
    }
    
    $geographyScore = $score;
    $userId = $_SESSION["user_id"];
    $query = "UPDATE users SET geography = $geographyScore WHERE id = $userId";

    $result = $conn->query($query);
}

?> 


<!DOCTYPE html>
<html lang="en">
<head>
  <title>EECRS - Examination</title>
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

  <script>
  function checkAnswers() {
      var answered = document.querySelectorAll('input[type=radio]:checked').length;
      if (answered === 10) {
          document.getElementById('submitBtn').removeAttribute('disabled');
      } else {
          document.getElementById('submitBtn').setAttribute('disabled', 'disabled');
      }
  }
  </script>

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

<?php if(isset($_SESSION['username'])) : ?>
<br>
<div class="container2">
    <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST") : ?>
        <h2>Your score: <?php echo $score; ?> / 10</h2>
    <?php endif; ?>
    <?php else : ?>
<center><h1>Section 5: Geography</h1><br></center>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  1. Which continent is the largest by land area?<br>
     <input type="radio" name="q1" value="a" onchange="checkAnswers()"> a) Asia<br>
     <input type="radio" name="q1" value="b" onchange="checkAnswers()"> b) Africa<br>
     <input type="radio" name="q1" value="c" onchange="checkAnswers()"> c) North America<br>
     <input type="radio" name="q1" value="d" onchange="checkAnswers()"> d) Europe<br>
     <br>

  2. What is the capital city of Canada?<br>
     <input type="radio" name="q2" value="a" onchange="checkAnswers()"> a) Ottawa<br>
     <input type="radio" name="q2" value="b" onchange="checkAnswers()"> b) Toronto<br>
     <input type="radio" name="q2" value="c" onchange="checkAnswers()"> c) Vancouver<br>
     <input type="radio" name="q2" value="d" onchange="checkAnswers()"> d) Montreal<br>
     <br>

  3. Which river is the longest in the world?<br>
     <input type="radio" name="q3" value="a" onchange="checkAnswers()"> a) Nile<br>
     <input type="radio" name="q3" value="b" onchange="checkAnswers()"> b) Amazon<br>
     <input type="radio" name="q3" value="c" onchange="checkAnswers()"> c) Mississippi<br>
     <input type="radio" name="q3" value="d" onchange="checkAnswers()"> d) Yangtze<br>
     <br>

  4. What is the largest desert in the world?<br>
     <input type="radio" name="q4" value="a" onchange="checkAnswers()"> a) Sahara<br>
     <input type="radio" name="q4" value="b" onchange="checkAnswers()"> b) Arabian<br>
     <input type="radio" name="q4" value="c" onchange="checkAnswers()"> c) Gobi<br>
     <input type="radio" name="q4" value="d" onchange="checkAnswers()"> d) Antarctica<br>
     <br>

  5. What is the capital city of Brazil?<br>
     <input type="radio" name="q5" value="a" onchange="checkAnswers()"> a) Rio de Janeiro<br>
     <input type="radio" name="q5" value="b" onchange="checkAnswers()"> b) Sao Paulo<br>
     <input type="radio" name="q5" value="c" onchange="checkAnswers()"> c) Brasilia<br>
     <input type="radio" name="q5" value="d" onchange="checkAnswers()"> d) Salvador<br>
     <br>

  6. Which country is known as the Land of the Rising Sun?<br>
     <input type="radio" name="q6" value="a" onchange="checkAnswers()"> a) China<br>
     <input type="radio" name="q6" value="b" onchange="checkAnswers()"> b) Japan<br>
     <input type="radio" name="q6" value="c" onchange="checkAnswers()"> c) India<br>
     <input type="radio" name="q6" value="d" onchange="checkAnswers()"> d) South Korea<br>
     <br>

  7. Which continent is home to the Amazon Rainforest?<br>
     <input type="radio" name="q7" value="a" onchange="checkAnswers()"> a) South America<br>
     <input type="radio" name="q7" value="b" onchange="checkAnswers()"> b) Africa<br>
     <input type="radio" name="q7" value="c" onchange="checkAnswers()"> c) Asia<br>
     <input type="radio" name="q7" value="d" onchange="checkAnswers()"> d) Australia<br>
     <br>

  8. What is the smallest country in the world?<br>
     <input type="radio" name="q8" value="a" onchange="checkAnswers()"> a) Monaco<br>
     <input type="radio" name="q8" value="b" onchange="checkAnswers()"> b) Vatican City<br>
     <input type="radio" name="q8" value="c" onchange="checkAnswers()"> c) San Marino<br>
     <input type="radio" name="q8" value="d" onchange="checkAnswers()"> d) Nauru<br>
     <br>

  9. Which mountain range stretches across the western part of South America?<br>
     <input type="radio" name="q9" value="a" onchange="checkAnswers()"> a) Himalayas<br>
     <input type="radio" name="q9" value="b" onchange="checkAnswers()"> b) Andes<br>
     <input type="radio" name="q9" value="c" onchange="checkAnswers()"> c) Alps<br>
     <input type="radio" name="q9" value="d" onchange="checkAnswers()"> d) Rocky Mountains<br>
     <br>

  10. What is the capital city of South Africa?<br>
      <input type="radio" name="q10" value="a" onchange="checkAnswers()"> a) Johannesburg<br>
      <input type="radio" name="q10" value="b" onchange="checkAnswers()"> b) Cape Town<br>
      <input type="radio" name="q10" value="c" onchange="checkAnswers()"> c) Pretoria<br>
      <input type="radio" name="q10" value="d" onchange="checkAnswers()"> d) Durban<br>
      <br>

  11. Which ocean is the largest by surface area?<br>
     <input type="radio" name="q11" value="a" onchange="checkAnswers()"> a) Pacific Ocean<br>
     <input type="radio" name="q11" value="b" onchange="checkAnswers()"> b) Atlantic Ocean<br>
     <input type="radio" name="q11" value="c" onchange="checkAnswers()"> c) Indian Ocean<br>
     <input type="radio" name="q11" value="d" onchange="checkAnswers()"> d) Southern Ocean<br>
     <br>

  12. What is the capital city of France?<br>
     <input type="radio" name="q12" value="a" onchange="checkAnswers()"> a) Paris<br>
     <input type="radio" name="q12" value="b" onchange="checkAnswers()"> b) London<br>
     <input type="radio" name="q12" value="c" onchange="checkAnswers()"> c) Rome<br>
     <input type="radio" name="q12" value="d" onchange="checkAnswers()"> d) Madrid<br>
     <br>

  13. Which mountain is the tallest above sea level?<br>
     <input type="radio" name="q13" value="a" onchange="checkAnswers()"> a) Mount Everest<br>
     <input type="radio" name="q13" value="b" onchange="checkAnswers()"> b) K2<br>
     <input type="radio" name="q13" value="c" onchange="checkAnswers()"> c) Kangchenjunga<br>
     <input type="radio" name="q13" value="d" onchange="checkAnswers()"> d) Lhotse<br>
     <br>

  14. What is the capital city of Australia?<br>
     <input type="radio" name="q14" value="a" onchange="checkAnswers()"> a) Sydney<br>
     <input type="radio" name="q14" value="b" onchange="checkAnswers()"> b) Melbourne<br>
     <input type="radio" name="q14" value="c" onchange="checkAnswers()"> c) Canberra<br>
     <input type="radio" name="q14" value="d" onchange="checkAnswers()"> d) Brisbane<br>
     <br>

  15. Which country is known as the "Land of the Rising Sun"?<br>
     <input type="radio" name="q15" value="a" onchange="checkAnswers()"> a) China<br>
     <input type="radio" name="q15" value="b" onchange="checkAnswers()"> b) Japan<br>
     <input type="radio" name="q15" value="c" onchange="checkAnswers()"> c) India<br>
     <input type="radio" name="q15" value="d" onchange="checkAnswers()"> d) South Korea<br>
     <br>

  16. Which continent is the driest in terms of rainfall?<br>
     <input type="radio" name="q16" value="a" onchange="checkAnswers()"> a) Africa<br>
     <input type="radio" name="q16" value="b" onchange="checkAnswers()"> b) Australia<br>
     <input type="radio" name="q16" value="c" onchange="checkAnswers()"> c) South America<br>
     <input type="radio" name="q16" value="d" onchange="checkAnswers()"> d) Antarctica<br>
     <br>

  17. What is the largest city by population in the United States?<br>
     <input type="radio" name="q17" value="a" onchange="checkAnswers()"> a) New York City<br>
     <input type="radio" name="q17" value="b" onchange="checkAnswers()"> b) Los Angeles<br>
     <input type="radio" name="q17" value="c" onchange="checkAnswers()"> c) Chicago<br>
     <input type="radio" name="q17" value="d" onchange="checkAnswers()"> d) Houston<br>
     <br>

  18. What is the highest waterfall in the world?<br>
     <input type="radio" name="q18" value="a" onchange="checkAnswers()"> a) Angel Falls<br>
     <input type="radio" name="q18" value="b" onchange="checkAnswers()"> b) Victoria Falls<br>
     <input type="radio" name="q18" value="c" onchange="checkAnswers()"> c) Niagara Falls<br>
     <input type="radio" name="q18" value="d" onchange="checkAnswers()"> d) Iguazu Falls<br>
     <br>

  19. Which country is located on the Iberian Peninsula?<br>
     <input type="radio" name="q19" value="a" onchange="checkAnswers()"> a) Italy<br>
     <input type="radio" name="q19" value="b" onchange="checkAnswers()"> b) Greece<br>
     <input type="radio" name="q19" value="c" onchange="checkAnswers()"> c) Spain<br>
     <input type="radio" name="q19" value="d" onchange="checkAnswers()"> d) Turkey<br>
     <br>

  20. What is the capital city of Argentina?<br>
      <input type="radio" name="q20" value="a" onchange="checkAnswers()"> a) Buenos Aires<br>
      <input type="radio" name="q20" value="b" onchange="checkAnswers()"> b) Santiago<br>
      <input type="radio" name="q20" value="c" onchange="checkAnswers()"> c) Lima<br>
      <input type="radio" name="q20" value="d" onchange="checkAnswers()"> d) Bogotá<br>
      <br>

  <center><input id="submitBtn" class="btn btn-info btn-lg" type="submit" name="submit" value="Submit" disabled></center>
</form>

  <?php endif; ?>
</div>
  <?php else : ?>
  <div class="container2">
    <center><h1>Please Login First</h1></center><br>
  </div>
  <?php endif; ?>

</body>
</html>