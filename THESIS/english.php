<?php 
include ("api/database.php");
session_start();

$englishScore = null;

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

        $englishScore = $userData["english"];
    }
}

if ($englishScore === null) {
} else {
    header("Location: main.php");
    exit();
}

$answers = array("b", "b", "b", "b", "a", "b", "c", "b", "b", "b","b", "b", "c", "a", "a", "c", "c", "b", "b", "b");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $submitted_answers = array($_POST["q1"], $_POST["q2"], $_POST["q3"], $_POST["q4"], $_POST["q5"], $_POST["q6"], $_POST["q7"], $_POST["q8"], $_POST["q9"], $_POST["q10"]);
    for ($i = 0; $i < count($answers); $i++) {
        if ($submitted_answers[$i] == $answers[$i]) {
            $score++;
        }
    }
    
    $englishScore = $score;
    $userId = $_SESSION["user_id"];
    $query = "UPDATE users SET english = $englishScore WHERE id = $userId";

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
<center><h1>Section 1: English Proficiency</h1><br></center>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  1. Choose the correct form of the verb: "She _____ to the library every day."<br>
     <input type="radio" name="q1" value="a" onchange="checkAnswers()"> a) go <br>
     <input type="radio" name="q1" value="b" onchange="checkAnswers()"> b) goes <br>
     <input type="radio" name="q1" value="c" onchange="checkAnswers()"> c) going <br>
     <input type="radio" name="q1" value="d" onchange="checkAnswers()"> d) went <br>
     <br>

  2. Identify the correct spelling:<br>
     <input type="radio" name="q2" value="a" onchange="checkAnswers()"> a) Acomodation <br>
     <input type="radio" name="q2" value="b" onchange="checkAnswers()"> b) Accommodation <br>
     <input type="radio" name="q2" value="c" onchange="checkAnswers()"> c) Acommadation <br>
     <input type="radio" name="q2" value="d" onchange="checkAnswers()"> d) Acommodation <br>
     <br>

  3. Select the appropriate preposition: "We're going ____ vacation next week."<br>
     <input type="radio" name="q3" value="a" onchange="checkAnswers()"> a) in<br>
     <input type="radio" name="q3" value="b" onchange="checkAnswers()"> b) on<br>
     <input type="radio" name="q3" value="c" onchange="checkAnswers()"> c) at<br>
     <input type="radio" name="q3" value="d" onchange="checkAnswers()"> d) by<br>
     <br>

  4. Fill in the blank with the appropriate pronoun: "John and _____ went to the store."<br>
     <input type="radio" name="q4" value="a" onchange="checkAnswers()"> a) me<br>
     <input type="radio" name="q4" value="b" onchange="checkAnswers()"> b) I<br>
     <input type="radio" name="q4" value="c" onchange="checkAnswers()"> c) myself<br>
     <input type="radio" name="q4" value="d" onchange="checkAnswers()"> d) my<br>
     <br>

  5. Choose the correctly punctuated sentence:<br>
     <input type="radio" name="q5" value="a" onchange="checkAnswers()"> a) The cat sat on the mat, and then it slept.<br>
     <input type="radio" name="q5" value="b" onchange="checkAnswers()"> b) The cat sat on the mat and then it slept.<br>
     <input type="radio" name="q5" value="c" onchange="checkAnswers()"> c) The cat sat on the mat; and then it slept.<br>
     <input type="radio" name="q5" value="d" onchange="checkAnswers()"> d) The cat sat on the mat, and then, it slept.<br>
     <br>

  6. What is the synonym of "happy"?<br>
     <input type="radio" name="q6" value="a" onchange="checkAnswers()"> a) Sad<br>
     <input type="radio" name="q6" value="b" onchange="checkAnswers()"> b) Joyful<br>
     <input type="radio" name="q6" value="c" onchange="checkAnswers()"> c) Angry<br>
     <input type="radio" name="q6" value="d" onchange="checkAnswers()"> d) Upset<br>
     <br>

  7. Identify the part of speech of the word "quickly" in the sentence: "She ran quickly to catch the bus."<br>
     <input type="radio" name="q7" value="a" onchange="checkAnswers()"> a) Noun<br>
     <input type="radio" name="q7" value="b" onchange="checkAnswers()"> b) Verb<br>
     <input type="radio" name="q7" value="c" onchange="checkAnswers()"> c) Adverb<br>
     <input type="radio" name="q7" value="d" onchange="checkAnswers()"> d) Adjective<br>
     <br>

  8. Which sentence is in passive voice?<br>
     <input type="radio" name="q8" value="a" onchange="checkAnswers()"> a) The teacher praised the students.<br>
     <input type="radio" name="q8" value="b" onchange="checkAnswers()"> b) The students were praised by the teacher.<br>
     <input type="radio" name="q8" value="c" onchange="checkAnswers()"> c) The students praised the teacher.<br>
     <input type="radio" name="q8" value="d" onchange="checkAnswers()"> d) The teacher and the students praised each other.<br>
     <br>

  9. What is the comparative form of the adjective "good"?<br>
     <input type="radio" name="q9" value="a" onchange="checkAnswers()"> a) Gooder<br>
     <input type="radio" name="q9" value="b" onchange="checkAnswers()"> b) Better<br>
     <input type="radio" name="q9" value="c" onchange="checkAnswers()"> c) Best<br>
     <input type="radio" name="q9" value="d" onchange="checkAnswers()"> d) More good<br>
     <br>

  10. Choose the correct meaning of the idiom: "Break the ice."<br>
      <input type="radio" name="q10" value="a" onchange="checkAnswers()"> a) To melt ice<br>
      <input type="radio" name="q10" value="b" onchange="checkAnswers()"> b) To start a conversation in a social setting<br>
      <input type="radio" name="q10" value="c" onchange="checkAnswers()"> c) To freeze water<br>
      <input type="radio" name="q10" value="d" onchange="checkAnswers()"> d) To break a promise<br>
      <br>

  11. Choose the correct form of the verb: "He ____ to school every morning."<br>
      <input type="radio" name="q11" value="a" onchange="checkAnswers()"> a) go <br>
      <input type="radio" name="q11" value="b" onchange="checkAnswers()"> b) goes <br>
      <input type="radio" name="q11" value="c" onchange="checkAnswers()"> c) going <br>
      <input type="radio" name="q11" value="d" onchange="checkAnswers()"> d) went <br>
      <br>

  12. Identify the correct spelling:<br>
      <input type="radio" name="q12" value="a" onchange="checkAnswers()"> a) Acomodation <br>
      <input type="radio" name="q12" value="b" onchange="checkAnswers()"> b) Accommodation <br>
      <input type="radio" name="q12" value="c" onchange="checkAnswers()"> c) Acommadation <br>
      <input type="radio" name="q12" value="d" onchange="checkAnswers()"> d) Acommodation <br>
      <br>

  13. Select the appropriate preposition: "She arrived ____ the airport at 9 o'clock."<br>
      <input type="radio" name="q13" value="a" onchange="checkAnswers()"> a) in<br>
      <input type="radio" name="q13" value="b" onchange="checkAnswers()"> b) on<br>
      <input type="radio" name="q13" value="c" onchange="checkAnswers()"> c) at<br>
      <input type="radio" name="q13" value="d" onchange="checkAnswers()"> d) by<br>
      <br>

  14. Fill in the blank with the appropriate pronoun: "Mary gave _____ a present on her birthday."<br>
      <input type="radio" name="q14" value="a" onchange="checkAnswers()"> a) me<br>
      <input type="radio" name="q14" value="b" onchange="checkAnswers()"> b) I<br>
      <input type="radio" name="q14" value="c" onchange="checkAnswers()"> c) myself<br>
      <input type="radio" name="q14" value="d" onchange="checkAnswers()"> d) my<br>
      <br>

  15. Choose the correctly punctuated sentence:<br>
      <input type="radio" name="q15" value="a" onchange="checkAnswers()"> a) The sun rises in the east and sets in the west.<br>
      <input type="radio" name="q15" value="b" onchange="checkAnswers()"> b) The sun rises in the east, and sets in the west.<br>
      <input type="radio" name="q15" value="c" onchange="checkAnswers()"> c) The sun rises in the east and sets in the west.<br>
      <input type="radio" name="q15" value="d" onchange="checkAnswers()"> d) The sun rises, in the east and sets in the west.<br>
      <br>

  16. What is the antonym of "beautiful"?<br>
      <input type="radio" name="q16" value="a" onchange="checkAnswers()"> a) Pretty<br>
      <input type="radio" name="q16" value="b" onchange="checkAnswers()"> b) Lovely<br>
      <input type="radio" name="q16" value="c" onchange="checkAnswers()"> c) Ugly<br>
      <input type="radio" name="q16" value="d" onchange="checkAnswers()"> d) Gorgeous<br>
      <br>

  17. Identify the part of speech of the word "carefully" in the sentence: "He read the instructions carefully before starting."<br>
      <input type="radio" name="q17" value="a" onchange="checkAnswers()"> a) Noun<br>
      <input type="radio" name="q17" value="b" onchange="checkAnswers()"> b) Verb<br>
      <input type="radio" name="q17" value="c" onchange="checkAnswers()"> c) Adverb<br>
      <input type="radio" name="q17" value="d" onchange="checkAnswers()"> d) Adjective<br>
      <br>

  18. Which sentence is in passive voice?<br>
      <input type="radio" name="q18" value="a" onchange="checkAnswers()"> a) The cat chased the mouse.<br>
      <input type="radio" name="q18" value="b" onchange="checkAnswers()"> b) The mouse was chased by the cat.<br>
      <input type="radio" name="q18" value="c" onchange="checkAnswers()"> c) The mouse chased the cat.<br>
      <input type="radio" name="q18" value="d" onchange="checkAnswers()"> d) The cat and the mouse played together.<br>
      <br>

  19. What is the superlative form of the adjective "hot"?<br>
      <input type="radio" name="q19" value="a" onchange="checkAnswers()"> a) Hotter<br>
      <input type="radio" name="q19" value="b" onchange="checkAnswers()"> b) Hottest<br>
      <input type="radio" name="q19" value="c" onchange="checkAnswers()"> c) More hot<br>
      <input type="radio" name="q19" value="d" onchange="checkAnswers()"> d) Hotest<br>
      <br>

  20. Choose the correct meaning of the idiom: "Bite the bullet."<br>
      <input type="radio" name="q20" value="a" onchange="checkAnswers()"> a) To eat something hard<br>
      <input type="radio" name="q20" value="b" onchange="checkAnswers()"> b) To face a difficult situation with courage<br>
      <input type="radio" name="q20" value="c" onchange="checkAnswers()"> c) To avoid a problem<br>
      <input type="radio" name="q20" value="d" onchange="checkAnswers()"> d) To be injured by a bullet<br>
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