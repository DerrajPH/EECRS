<?php 
include ("api/database.php");
session_start();

$mathScore = null;

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

        $mathScore = $userData["math"];
    }
}

if ($mathScore === null) {
} else {
    header("Location: main.php");
    exit();
}

$answers = array("b", "d", "a", "b", "d", "b", "b", "d", "a", "c", "c", "c", "b", "b", "b", "b", "b", "c", "a", "b");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $submitted_answers = array($_POST["q1"], $_POST["q2"], $_POST["q3"], $_POST["q4"], $_POST["q5"], $_POST["q6"], $_POST["q7"], $_POST["q8"], $_POST["q9"], $_POST["q10"]);
    for ($i = 0; $i < count($answers); $i++) {
        if ($submitted_answers[$i] == $answers[$i]) {
            $score++;
        }
    }
    
    $mathScore = $score;
    $userId = $_SESSION["user_id"];
    $query = "UPDATE users SET math = $mathScore WHERE id = $userId";

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
<center><h1>Section 2: Mathematics</h1><br></center>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  1. Solve for x: 3x - 7 = 14 <br>
     <input type="radio" name="q1" value="a" onchange="checkAnswers()"> a) x = 5 <br>
     <input type="radio" name="q1" value="b" onchange="checkAnswers()"> b) x = 7 <br>
     <input type="radio" name="q1" value="c" onchange="checkAnswers()"> c) x = 21 <br>
     <input type="radio" name="q1" value="d" onchange="checkAnswers()"> d) x = 9 <br>
     <br>

  2. If a triangle has angles measuring 45°, 45°, and 90°, what type of triangle is it? <br>
     <input type="radio" name="q2" value="a" onchange="checkAnswers()"> a) Equilateral <br>
     <input type="radio" name="q2" value="b" onchange="checkAnswers()"> b) Scalene <br>
     <input type="radio" name="q2" value="c" onchange="checkAnswers()"> c) Isosceles <br>
     <input type="radio" name="q2" value="d" onchange="checkAnswers()"> d) Right <br>
     <br>

  3. What is the value of π (pi) rounded to two decimal places? <br>
     <input type="radio" name="q3" value="a" onchange="checkAnswers()"> a) 3.14 <br>
     <input type="radio" name="q3" value="b" onchange="checkAnswers()"> b) 3.141 <br>
     <input type="radio" name="q3" value="c" onchange="checkAnswers()"> c) 3.142 <br>
     <input type="radio" name="q3" value="d" onchange="checkAnswers()"> d) 3.1416 <br>
     <br>

  4. If a circle has a radius of 5 centimeters, what is its diameter? <br>
     <input type="radio" name="q4" value="a" onchange="checkAnswers()"> a) 5 cm <br>
     <input type="radio" name="q4" value="b" onchange="checkAnswers()"> b) 10 cm <br>
     <input type="radio" name="q4" value="c" onchange="checkAnswers()"> c) 15 cm <br>
     <input type="radio" name="q4" value="d" onchange="checkAnswers()"> d) 20 cm <br>
     <br>

  5. What is the next number in the sequence: 2, 4, 8, 16, ...? <br>
     <input type="radio" name="q5" value="a" onchange="checkAnswers()"> a) 20 <br>
     <input type="radio" name="q5" value="b" onchange="checkAnswers()"> b) 24 <br>
     <input type="radio" name="q5" value="c" onchange="checkAnswers()"> c) 32 <br>
     <input type="radio" name="q5" value="d" onchange="checkAnswers()"> d) 64 <br>
     <br>

  6. What is 25% of 80? <br>
     <input type="radio" name="q6" value="a" onchange="checkAnswers()"> a) 15 <br>
     <input type="radio" name="q6" value="b" onchange="checkAnswers()"> b) 20 <br>
     <input type="radio" name="q6" value="c" onchange="checkAnswers()"> c) 25 <br>
     <input type="radio" name="q6" value="d" onchange="checkAnswers()"> d) 30 <br>
     <br>

  7. Solve the equation for y: 2y + 3 = 11 <br>
     <input type="radio" name="q7" value="a" onchange="checkAnswers()"> a) y = 4 <br>
     <input type="radio" name="q7" value="b" onchange="checkAnswers()"> b) y = 5 <br>
     <input type="radio" name="q7" value="c" onchange="checkAnswers()"> c) y = 6 <br>
     <input type="radio" name="q7" value="d" onchange="checkAnswers()"> d) y = 7 <br>
     <br>

  8. If a rectangle has a length of 6 cm and a width of 4 cm, what is its perimeter? <br>
     <input type="radio" name="q8" value="a" onchange="checkAnswers()"> a) 10 cm <br>
     <input type="radio" name="q8" value="b" onchange="checkAnswers()"> b) 16 cm <br>
     <input type="radio" name="q8" value="c" onchange="checkAnswers()"> c) 20 cm <br>
     <input type="radio" name="q8" value="d" onchange="checkAnswers()"> d) 24 cm <br>
     <br>

  9. What is the square root of 144? <br>
     <input type="radio" name="q9" value="a" onchange="checkAnswers()"> a) 12 <br>
     <input type="radio" name="q9" value="b" onchange="checkAnswers()"> b) 14 <br>
     <input type="radio" name="q9" value="c" onchange="checkAnswers()"> c) 16 <br>
     <input type="radio" name="q9" value="d" onchange="checkAnswers()"> d) 18 <br>
     <br>

  10. If a car travels at an average speed of 60 kilometers per hour, how far will it travel in 3 hours? <br>
     <input type="radio" name="q10" value="a" onchange="checkAnswers()"> a) 120 km <br>
     <input type="radio" name="q10" value="b" onchange="checkAnswers()"> b) 150 km <br>
     <input type="radio" name="q10" value="c" onchange="checkAnswers()"> c) 180 km <br>
     <input type="radio" name="q10" value="d" onchange="checkAnswers()"> d) 200 km <br>
     <br>

  11. Solve for x: 5x + 3 = 18 <br>
     <input type="radio" name="q11" value="a" onchange="checkAnswers()"> a) x = 3 <br>
     <input type="radio" name="q11" value="b" onchange="checkAnswers()"> b) x = 5 <br>
     <input type="radio" name="q11" value="c" onchange="checkAnswers()"> c) x = 6 <br>
     <input type="radio" name="q11" value="d" onchange="checkAnswers()"> d) x = 7 <br>
     <br>

  12. If a quadrilateral has angles measuring 90°, 90°, 90°, and 90°, what type of quadrilateral is it? <br>
     <input type="radio" name="q12" value="a" onchange="checkAnswers()"> a) Parallelogram <br>
     <input type="radio" name="q12" value="b" onchange="checkAnswers()"> b) Rhombus <br>
     <input type="radio" name="q12" value="c" onchange="checkAnswers()"> c) Square <br>
     <input type="radio" name="q12" value="d" onchange="checkAnswers()"> d) Rectangle <br>
     <br>

  13. What is the value of π (pi) rounded to three decimal places? <br>
     <input type="radio" name="q13" value="a" onchange="checkAnswers()"> a) 3.141 <br>
     <input type="radio" name="q13" value="b" onchange="checkAnswers()"> b) 3.142 <br>
     <input type="radio" name="q13" value="c" onchange="checkAnswers()"> c) 3.143 <br>
     <input type="radio" name="q13" value="d" onchange="checkAnswers()"> d) 3.144 <br>
     <br>

  14. If a rectangle has a length of 12 inches and a width of 8 inches, what is its area? <br>
     <input type="radio" name="q14" value="a" onchange="checkAnswers()"> a) 80 sq in <br>
     <input type="radio" name="q14" value="b" onchange="checkAnswers()"> b) 96 sq in <br>
     <input type="radio" name="q14" value="c" onchange="checkAnswers()"> c) 104 sq in <br>
     <input type="radio" name="q14" value="d" onchange="checkAnswers()"> d) 120 sq in <br>
     <br>

  15. What is the next number in the sequence: 3, 9, 27, 81, ...? <br>
     <input type="radio" name="q15" value="a" onchange="checkAnswers()"> a) 162 <br>
     <input type="radio" name="q15" value="b" onchange="checkAnswers()"> b) 243 <br>
     <input type="radio" name="q15" value="c" onchange="checkAnswers()"> c) 324 <br>
     <input type="radio" name="q15" value="d" onchange="checkAnswers()"> d) 486 <br>
     <br>

  16. What is 40% of 250? <br>
     <input type="radio" name="q16" value="a" onchange="checkAnswers()"> a) 90 <br>
     <input type="radio" name="q16" value="b" onchange="checkAnswers()"> b) 100 <br>
     <input type="radio" name="q16" value="c" onchange="checkAnswers()"> c) 110 <br>
     <input type="radio" name="q16" value="d" onchange="checkAnswers()"> d) 120 <br>
     <br>

  17. Solve the equation for y: 4y - 7 = 17 <br>
     <input type="radio" name="q17" value="a" onchange="checkAnswers()"> a) y = 6 <br>
     <input type="radio" name="q17" value="b" onchange="checkAnswers()"> b) y = 6.5 <br>
     <input type="radio" name="q17" value="c" onchange="checkAnswers()"> c) y = 7 <br>
     <input type="radio" name="q17" value="d" onchange="checkAnswers()"> d) y = 7.5 <br>
     <br>

  18. If a square has a perimeter of 32 cm, what is the length of one side? <br>
     <input type="radio" name="q18" value="a" onchange="checkAnswers()"> a) 4 cm <br>
     <input type="radio" name="q18" value="b" onchange="checkAnswers()"> b) 6 cm <br>
     <input type="radio" name="q18" value="c" onchange="checkAnswers()"> c) 8 cm <br>
     <input type="radio" name="q18" value="d" onchange="checkAnswers()"> d) 10 cm <br>
     <br>

  19. What is the cube root of 64? <br>
     <input type="radio" name="q19" value="a" onchange="checkAnswers()"> a) 4 <br>
     <input type="radio" name="q19" value="b" onchange="checkAnswers()"> b) 6 <br>
     <input type="radio" name="q19" value="c" onchange="checkAnswers()"> c) 8 <br>
     <input type="radio" name="q19" value="d" onchange="checkAnswers()"> d) 10 <br>
     <br>

  20. If a train travels at an average speed of 75 kilometers per hour, how long will it take to travel 225 kilometers? <br>
     <input type="radio" name="q20" value="a" onchange="checkAnswers()"> a) 2 hours <br>
     <input type="radio" name="q20" value="b" onchange="checkAnswers()"> b) 3 hours <br>
     <input type="radio" name="q20" value="c" onchange="checkAnswers()"> c) 4 hours <br>
     <input type="radio" name="q20" value="d" onchange="checkAnswers()"> d) 5 hours <br>
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