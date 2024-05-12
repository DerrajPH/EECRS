<?php 
include ("api/database.php");
session_start();

$critical_thinkingScore = null;

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

        $critical_thinkingScore = $userData["critical_thinking"];
    }
}

if ($critical_thinkingScore === null) {
} else {
    header("Location: main.php");
    exit();
}

$answers = array("a", "a", "b", "a", "b", "d", "b", "a", "c", "b", "a", "c", "b", "a", "a", "c", "b", "b", "a", "b");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $submitted_answers = array($_POST["q1"], $_POST["q2"], $_POST["q3"], $_POST["q4"], $_POST["q5"], $_POST["q6"], $_POST["q7"], $_POST["q8"], $_POST["q9"], $_POST["q10"]);
    for ($i = 0; $i < count($answers); $i++) {
        if ($submitted_answers[$i] == $answers[$i]) {
            $score++;
        }
    }
    
    $critical_thinkingScore = $score;
    $userId = $_SESSION["user_id"];
    $query = "UPDATE users SET critical_thinking = $critical_thinkingScore WHERE id = $userId";

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
<center><h1>Section 4: Critical Thinking</h1><br></center>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  1. If all cats are mammals, and all mammals are animals, what can you conclude about cats?<br>
     <input type="radio" name="q1" value="a" onchange="checkAnswers()"> a) All cats are animals.<br>
     <input type="radio" name="q1" value="b" onchange="checkAnswers()"> b) All animals are cats.<br>
     <input type="radio" name="q1" value="c" onchange="checkAnswers()"> c) Some animals are cats.<br>
     <input type="radio" name="q1" value="d" onchange="checkAnswers()"> d) Cats are not animals.<br>
     <br>

  2. "All humans are mortal. Socrates is human. Therefore, Socrates is mortal." This argument is an example of:<br>
     <input type="radio" name="q2" value="a" onchange="checkAnswers()"> a) Deductive reasoning<br>
     <input type="radio" name="q2" value="b" onchange="checkAnswers()"> b) Inductive reasoning<br>
     <input type="radio" name="q2" value="c" onchange="checkAnswers()"> c) Abductive reasoning<br>
     <input type="radio" name="q2" value="d" onchange="checkAnswers()"> d) None of the above<br>
     <br>

  3. If a box contains 8 red balls, 5 blue balls, and 3 green balls, what is the probability of randomly selecting a blue ball?<br>
     <input type="radio" name="q3" value="a" onchange="checkAnswers()"> a) 3/16<br>
     <input type="radio" name="q3" value="b" onchange="checkAnswers()"> b) 5/16<br>
     <input type="radio" name="q3" value="c" onchange="checkAnswers()"> c) 3/8<br>
     <input type="radio" name="q3" value="d" onchange="checkAnswers()"> d) 5/8<br>
     <br>

  4. What fallacy is committed in the following statement: "You shouldn't listen to her argument about the environment because she drives a gas-guzzling SUV"?<br>
     <input type="radio" name="q4" value="a" onchange="checkAnswers()"> a) Ad hominem<br>
     <input type="radio" name="q4" value="b" onchange="checkAnswers()"> b) Slippery slope<br>
     <input type="radio" name="q4" value="c" onchange="checkAnswers()"> c) Straw man<br>
     <input type="radio" name="q4" value="d" onchange="checkAnswers()"> d) False dilemma<br>
     <br>

  5. If today is Wednesday, and it is two days before the day after the day before Thursday, what day is it?<br>
     <input type="radio" name="q5" value="a" onchange="checkAnswers()"> a) Monday<br>
     <input type="radio" name="q5" value="b" onchange="checkAnswers()"> b) Tuesday<br>
     <input type="radio" name="q5" value="c" onchange="checkAnswers()"> c) Thursday<br>
     <input type="radio" name="q5" value="d" onchange="checkAnswers()"> d) Friday<br>
     <br>

  6. What is the next number in the sequence: 3, 6, 12, 24, ...?<br>
     <input type="radio" name="q6" value="a" onchange="checkAnswers()"> a) 36<br>
     <input type="radio" name="q6" value="b" onchange="checkAnswers()"> b) 48<br>
     <input type="radio" name="q6" value="c" onchange="checkAnswers()"> c) 60<br>
     <input type="radio" name="q6" value="d" onchange="checkAnswers()"> d) 72<br>
     <br>

  7. What is the sum of the interior angles of a triangle?<br>
     <input type="radio" name="q7" value="a" onchange="checkAnswers()"> a) 90 degrees<br>
     <input type="radio" name="q7" value="b" onchange="checkAnswers()"> b) 180 degrees<br>
     <input type="radio" name="q7" value="c" onchange="checkAnswers()"> c) 270 degrees<br>
     <input type="radio" name="q7" value="d" onchange="checkAnswers()"> d) 360 degrees<br>
     <br>

  8. If a square has an area of 25 square meters, what is the length of one side?<br>
     <input type="radio" name="q8" value="a" onchange="checkAnswers()"> a) 5 meters<br>
     <input type="radio" name="q8" value="b" onchange="checkAnswers()"> b) 10 meters<br>
     <input type="radio" name="q8" value="c" onchange="checkAnswers()"> c) 15 meters<br>
     <input type="radio" name="q8" value="d" onchange="checkAnswers()"> d) 20 meters<br>
     <br>

  9. Which is the largest planet in our solar system?<br>
     <input type="radio" name="q9" value="a" onchange="checkAnswers()"> a) Earth<br>
     <input type="radio" name="q9" value="b" onchange="checkAnswers()"> b) Mars<br>
     <input type="radio" name="q9" value="c" onchange="checkAnswers()"> c) Jupiter<br>
     <input type="radio" name="q9" value="d" onchange="checkAnswers()"> d) Saturn<br>
     <br>

  10. What is the chemical symbol for silver?<br>
      <input type="radio" name="q10" value="a" onchange="checkAnswers()"> a) Si<br>
      <input type="radio" name="q10" value="b" onchange="checkAnswers()"> b) Ag<br>
      <input type="radio" name="q10" value="c" onchange="checkAnswers()"> c) Au<br>
      <input type="radio" name="q10" value="d" onchange="checkAnswers()"> d) Pt<br>
      <br>

  11. If all squares are rectangles, and all rectangles are polygons, what can you conclude about squares?<br>
     <input type="radio" name="q11" value="a" onchange="checkAnswers()"> a) All squares are polygons.<br>
     <input type="radio" name="q11" value="b" onchange="checkAnswers()"> b) All polygons are squares.<br>
     <input type="radio" name="q11" value="c" onchange="checkAnswers()"> c) Some polygons are squares.<br>
     <input type="radio" name="q11" value="d" onchange="checkAnswers()"> d) Squares are not polygons.<br>
     <br>

  12. "All birds have feathers. Penguins are birds. Therefore, penguins have feathers." This argument is an example of:<br>
     <input type="radio" name="q12" value="a" onchange="checkAnswers()"> a) Deductive reasoning<br>
     <input type="radio" name="q12" value="b" onchange="checkAnswers()"> b) Inductive reasoning<br>
     <input type="radio" name="q12" value="c" onchange="checkAnswers()"> c) Abductive reasoning<br>
     <input type="radio" name="q12" value="d" onchange="checkAnswers()"> d) None of the above<br>
     <br>

  13. If a bag contains 6 red marbles, 4 blue marbles, and 2 green marbles, what is the probability of randomly selecting a green marble?<br>
     <input type="radio" name="q13" value="a" onchange="checkAnswers()"> a) 1/6<br>
     <input type="radio" name="q13" value="b" onchange="checkAnswers()"> b) 1/4<br>
     <input type="radio" name="q13" value="c" onchange="checkAnswers()"> c) 1/3<br>
     <input type="radio" name="q13" value="d" onchange="checkAnswers()"> d) 1/2<br>
     <br>

  14. What fallacy is committed in the following statement: "You shouldn't trust his opinion on politics; he failed his history class."<br>
     <input type="radio" name="q14" value="a" onchange="checkAnswers()"> a) Ad hominem<br>
     <input type="radio" name="q14" value="b" onchange="checkAnswers()"> b) False cause<br>
     <input type="radio" name="q14" value="c" onchange="checkAnswers()"> c) Hasty generalization<br>
     <input type="radio" name="q14" value="d" onchange="checkAnswers()"> d) Appeal to authority<br>
     <br>

  15. If today is Sunday, and it is three days before the day after the day before Tuesday, what day is it?<br>
     <input type="radio" name="q15" value="a" onchange="checkAnswers()"> a) Thursday<br>
     <input type="radio" name="q15" value="b" onchange="checkAnswers()"> b) Friday<br>
     <input type="radio" name="q15" value="c" onchange="checkAnswers()"> c) Saturday<br>
     <input type="radio" name="q15" value="d" onchange="checkAnswers()"> d) Sunday<br>
     <br>

  16. What is the next number in the sequence: 1, 4, 9, 16, ...?<br>
     <input type="radio" name="q16" value="a" onchange="checkAnswers()"> a) 25<br>
     <input type="radio" name="q16" value="b" onchange="checkAnswers()"> b) 36<br>
     <input type="radio" name="q16" value="c" onchange="checkAnswers()"> c) 49<br>
     <input type="radio" name="q16" value="d" onchange="checkAnswers()"> d) 64<br>
     <br>

  17. What is the sum of the interior angles of a pentagon?<br>
     <input type="radio" name="q17" value="a" onchange="checkAnswers()"> a) 360 degrees<br>
     <input type="radio" name="q17" value="b" onchange="checkAnswers()"> b) 540 degrees<br>
     <input type="radio" name="q17" value="c" onchange="checkAnswers()"> c) 720 degrees<br>
     <input type="radio" name="q17" value="d" onchange="checkAnswers()"> d) 900 degrees<br>
     <br>

  18. If a circle has an area of 36π square meters, what is its radius?<br>
     <input type="radio" name="q18" value="a" onchange="checkAnswers()"> a) 3 meters<br>
     <input type="radio" name="q18" value="b" onchange="checkAnswers()"> b) 6 meters<br>
     <input type="radio" name="q18" value="c" onchange="checkAnswers()"> c) 9 meters<br>
     <input type="radio" name="q18" value="d" onchange="checkAnswers()"> d) 12 meters<br>
     <br>

  19. Which is the smallest planet in our solar system?<br>
     <input type="radio" name="q19" value="a" onchange="checkAnswers()"> a) Earth<br>
     <input type="radio" name="q19" value="b" onchange="checkAnswers()"> b) Mercury<br>
     <input type="radio" name="q19" value="c" onchange="checkAnswers()"> c) Venus<br>
     <input type="radio" name="q19" value="d" onchange="checkAnswers()"> d) Mars<br>
     <br>

  20. What is the chemical symbol for hydrogen?<br>
      <input type="radio" name="q20" value="a" onchange="checkAnswers()"> a) Hg<br>
      <input type="radio" name="q20" value="b" onchange="checkAnswers()"> b) Hy<br>
      <input type="radio" name="q20" value="c" onchange="checkAnswers()"> c) H<br>
      <input type="radio" name="q20" value="d" onchange="checkAnswers()"> d) He<br>
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