<?php 
include ("api/database.php");
session_start();

$scienceScore = null;

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

        $scienceScore = $userData["science"];
    }
}

if ($scienceScore === null) {
} else {
    header("Location: main.php");
    exit();
}

$answers = array("a", "c", "a", "c", "b", "b", "b", "a", "b", "b", "a", "a", "d", "a", "b", "d", "b", "c", "b", "c");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $submitted_answers = array($_POST["q1"], $_POST["q2"], $_POST["q3"], $_POST["q4"], $_POST["q5"], $_POST["q6"], $_POST["q7"], $_POST["q8"], $_POST["q9"], $_POST["q10"]);
    for ($i = 0; $i < count($answers); $i++) {
        if ($submitted_answers[$i] == $answers[$i]) {
            $score++;
        }
    }
    
    $scienceScore = $score;
    $userId = $_SESSION["user_id"];
    $query = "UPDATE users SET science = $scienceScore WHERE id = $userId";

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
<center><h1>Section 3: General Science</h1><br></center>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  1. What is the chemical symbol for oxygen?<br>
     <input type="radio" name="q1" value="a" onchange="checkAnswers()"> a) O<br>
     <input type="radio" name="q1" value="b" onchange="checkAnswers()"> b) Ox<br>
     <input type="radio" name="q1" value="c" onchange="checkAnswers()"> c) Oc<br>
     <input type="radio" name="q1" value="d" onchange="checkAnswers()"> d) Oz<br>
     <br>

  2. Which of the following is a renewable energy source?<br>
     <input type="radio" name="q2" value="a" onchange="checkAnswers()"> a) Coal<br>
     <input type="radio" name="q2" value="b" onchange="checkAnswers()"> b) Natural gas<br>
     <input type="radio" name="q2" value="c" onchange="checkAnswers()"> c) Solar power<br>
     <input type="radio" name="q2" value="d" onchange="checkAnswers()"> d) Petroleum<br>
     <br>

  3. What is the process by which plants make their food?<br>
     <input type="radio" name="q3" value="a" onchange="checkAnswers()"> a) Photosynthesis<br>
     <input type="radio" name="q3" value="b" onchange="checkAnswers()"> b) Respiration<br>
     <input type="radio" name="q3" value="c" onchange="checkAnswers()"> c) Fermentation<br>
     <input type="radio" name="q3" value="d" onchange="checkAnswers()"> d) Combustion<br>
     <br>

  4. What is the largest organ in the human body?<br>
     <input type="radio" name="q4" value="a" onchange="checkAnswers()"> a) Liver<br>
     <input type="radio" name="q4" value="b" onchange="checkAnswers()"> b) Brain<br>
     <input type="radio" name="q4" value="c" onchange="checkAnswers()"> c) Skin<br>
     <input type="radio" name="q4" value="d" onchange="checkAnswers()"> d) Heart<br>
     <br>

  5. What is the chemical formula for water?<br>
     <input type="radio" name="q5" value="a" onchange="checkAnswers()"> a) CO2<br>
     <input type="radio" name="q5" value="b" onchange="checkAnswers()"> b) H2O<br>
     <input type="radio" name="q5" value="c" onchange="checkAnswers()"> c) NaCl<br>
     <input type="radio" name="q5" value="d" onchange="checkAnswers()"> d) CH4<br>
     <br>

  6. What is the smallest bone in the human body?<br>
     <input type="radio" name="q6" value="a" onchange="checkAnswers()"> a) Femur<br>
     <input type="radio" name="q6" value="b" onchange="checkAnswers()"> b) Stapes<br>
     <input type="radio" name="q6" value="c" onchange="checkAnswers()"> c) Tibia<br>
     <input type="radio" name="q6" value="d" onchange="checkAnswers()"> d) Humerus<br>
     <br>

  7. What is the pH scale used to measure?<br>
     <input type="radio" name="q7" value="a" onchange="checkAnswers()"> a) Temperature<br>
     <input type="radio" name="q7" value="b" onchange="checkAnswers()"> b) Acidity<br>
     <input type="radio" name="q7" value="c" onchange="checkAnswers()"> c) Pressure<br>
     <input type="radio" name="q7" value="d" onchange="checkAnswers()"> d) Density<br>
     <br>

  8. What type of energy does a moving car have?<br>
     <input type="radio" name="q8" value="a" onchange="checkAnswers()"> a) Kinetic energy<br>
     <input type="radio" name="q8" value="b" onchange="checkAnswers()"> b) Potential energy<br>
     <input type="radio" name="q8" value="c" onchange="checkAnswers()"> c) Mechanical energy<br>
     <input type="radio" name="q8" value="d" onchange="checkAnswers()"> d) Thermal energy<br>
     <br>

  9. What is the chemical symbol for gold?<br>
     <input type="radio" name="q9" value="a" onchange="checkAnswers()"> a) Ag<br>
     <input type="radio" name="q9" value="b" onchange="checkAnswers()"> b) Au<br>
     <input type="radio" name="q9" value="c" onchange="checkAnswers()"> c) Fe<br>
     <input type="radio" name="q9" value="d" onchange="checkAnswers()"> d) Pb<br>
     <br>

  10. What is the primary function of red blood cells?<br>
      <input type="radio" name="q10" value="a" onchange="checkAnswers()"> a) Fighting infection<br>
      <input type="radio" name="q10" value="b" onchange="checkAnswers()"> b) Carrying oxygen<br>
      <input type="radio" name="q10" value="c" onchange="checkAnswers()"> c) Producing antibodies<br>
      <input type="radio" name="q10" value="d" onchange="checkAnswers()"> d) Forming blood clots<br>
      <br>

  11. What is the chemical symbol for sodium?<br>
     <input type="radio" name="q11" value="a" onchange="checkAnswers()"> a) So<br>
     <input type="radio" name="q11" value="b" onchange="checkAnswers()"> b) Na<br>
     <input type="radio" name="q11" value="c" onchange="checkAnswers()"> c) Sn<br>
     <input type="radio" name="q11" value="d" onchange="checkAnswers()"> d) Ni<br>
     <br>

  12. Which of the following is a non-metal element?<br>
     <input type="radio" name="q12" value="a" onchange="checkAnswers()"> a) Iron<br>
     <input type="radio" name="q12" value="b" onchange="checkAnswers()"> b) Copper<br>
     <input type="radio" name="q12" value="c" onchange="checkAnswers()"> c) Oxygen<br>
     <input type="radio" name="q12" value="d" onchange="checkAnswers()"> d) Silver<br>
     <br>

  13. What is the process by which plants release water vapor into the atmosphere?<br>
     <input type="radio" name="q13" value="a" onchange="checkAnswers()"> a) Respiration<br>
     <input type="radio" name="q13" value="b" onchange="checkAnswers()"> b) Condensation<br>
     <input type="radio" name="q13" value="c" onchange="checkAnswers()"> c) Transpiration<br>
     <input type="radio" name="q13" value="d" onchange="checkAnswers()"> d) Precipitation<br>
     <br>

  14. What is the smallest unit of an element that retains the properties of that element?<br>
     <input type="radio" name="q14" value="a" onchange="checkAnswers()"> a) Molecule<br>
     <input type="radio" name="q14" value="b" onchange="checkAnswers()"> b) Cell<br>
     <input type="radio" name="q14" value="c" onchange="checkAnswers()"> c) Atom<br>
     <input type="radio" name="q14" value="d" onchange="checkAnswers()"> d) Proton<br>
     <br>

  15. What is the chemical formula for carbon dioxide?<br>
     <input type="radio" name="q15" value="a" onchange="checkAnswers()"> a) CO<br>
     <input type="radio" name="q15" value="b" onchange="checkAnswers()"> b) CO3<br>
     <input type="radio" name="q15" value="c" onchange="checkAnswers()"> c) CO2<br>
     <input type="radio" name="q15" value="d" onchange="checkAnswers()"> d) C2O<br>
     <br>

  16. Which part of the human brain is responsible for regulating basic bodily functions such as breathing and heart rate?<br>
     <input type="radio" name="q16" value="a" onchange="checkAnswers()"> a) Cerebrum<br>
     <input type="radio" name="q16" value="b" onchange="checkAnswers()"> b) Cerebellum<br>
     <input type="radio" name="q16" value="c" onchange="checkAnswers()"> c) Brainstem<br>
     <input type="radio" name="q16" value="d" onchange="checkAnswers()"> d) Hypothalamus<br>
     <br>

  17. What is the process by which plants convert light energy into chemical energy?<br>
     <input type="radio" name="q17" value="a" onchange="checkAnswers()"> a) Respiration<br>
     <input type="radio" name="q17" value="b" onchange="checkAnswers()"> b) Transpiration<br>
     <input type="radio" name="q17" value="c" onchange="checkAnswers()"> c) Photosynthesis<br>
     <input type="radio" name="q17" value="d" onchange="checkAnswers()"> d) Fermentation<br>
     <br>

  18. What is the pH of pure water?<br>
     <input type="radio" name="q18" value="a" onchange="checkAnswers()"> a) 5<br>
     <input type="radio" name="q18" value="b" onchange="checkAnswers()"> b) 6<br>
     <input type="radio" name="q18" value="c" onchange="checkAnswers()"> c) 7<br>
     <input type="radio" name="q18" value="d" onchange="checkAnswers()"> d) 8<br>
     <br>

  19. What is the chemical symbol for potassium?<br>
     <input type="radio" name="q19" value="a" onchange="checkAnswers()"> a) Po<br>
     <input type="radio" name="q19" value="b" onchange="checkAnswers()"> b) Pa<br>
     <input type="radio" name="q19" value="c" onchange="checkAnswers()"> c) K<br>
     <input type="radio" name="q19" value="d" onchange="checkAnswers()"> d) Ke<br>
     <br>

  20. What is the primary function of white blood cells?<br>
      <input type="radio" name="q20" value="a" onchange="checkAnswers()"> a) Carrying oxygen<br>
      <input type="radio" name="q20" value="b" onchange="checkAnswers()"> b) Fighting infection<br>
      <input type="radio" name="q20" value="c" onchange="checkAnswers()"> c) Clotting blood<br>
      <input type="radio" name="q20" value="d" onchange="checkAnswers()"> d) Transmitting nerve impulses<br>
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