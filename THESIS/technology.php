<?php 
include ("api/database.php");
session_start();

$technologyScore = null;

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

        $technologyScore = $userData["technology"];
    }
}

if ($technologyScore === null) {
} else {
    header("Location: main.php");
    exit();
}

$answers = array("a", "c", "b", "d", "a", "c", "a", "a", "c", "d", "a", "c", "b", "a", "a", "c", "b", "b", "a", "b");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $score = 0;
    $submitted_answers = array($_POST["q1"], $_POST["q2"], $_POST["q3"], $_POST["q4"], $_POST["q5"], $_POST["q6"], $_POST["q7"], $_POST["q8"], $_POST["q9"], $_POST["q10"]);
    for ($i = 0; $i < count($answers); $i++) {
        if ($submitted_answers[$i] == $answers[$i]) {
            $score++;
        }
    }
    
    $technologyScore = $score;
    $userId = $_SESSION["user_id"];
    $query = "UPDATE users SET technology = $technologyScore WHERE id = $userId";

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
<center><h1>Section 6: Technology</h1><br></center>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  1. What does HTML stand for?<br>
     <input type="radio" name="q1" value="a" onchange="checkAnswers()"> a) HyperText Markup Language<br>
     <input type="radio" name="q1" value="b" onchange="checkAnswers()"> b) High Tech Markup Language<br>
     <input type="radio" name="q1" value="c" onchange="checkAnswers()"> c) Home Tool Markup Language<br>
     <input type="radio" name="q1" value="d" onchange="checkAnswers()"> d) Hyperlink Text Management Language<br>
     <br>

  2. Who is the founder of Tesla Motors?<br>
     <input type="radio" name="q2" value="a" onchange="checkAnswers()"> a) Jeff Bezos<br>
     <input type="radio" name="q2" value="b" onchange="checkAnswers()"> b) Bill Gates<br>
     <input type="radio" name="q2" value="c" onchange="checkAnswers()"> c) Elon Musk<br>
     <input type="radio" name="q2" value="d" onchange="checkAnswers()"> d) Mark Zuckerberg<br>
     <br>

  3. What is the main function of a CPU?<br>
     <input type="radio" name="q3" value="a" onchange="checkAnswers()"> a) Storage<br>
     <input type="radio" name="q3" value="b" onchange="checkAnswers()"> b) Processing<br>
     <input type="radio" name="q3" value="c" onchange="checkAnswers()"> c) Display<br>
     <input type="radio" name="q3" value="d" onchange="checkAnswers()"> d) Input<br>
     <br>

  4. Which social media platform is known for its disappearing photo and video messages?<br>
     <input type="radio" name="q4" value="a" onchange="checkAnswers()"> a) Facebook<br>
     <input type="radio" name="q4" value="b" onchange="checkAnswers()"> b) Twitter<br>
     <input type="radio" name="q4" value="c" onchange="checkAnswers()"> c) Instagram<br>
     <input type="radio" name="q4" value="d" onchange="checkAnswers()"> d) Snapchat<br>
     <br>

  5. What is the term used to describe the electronic storage of data in multiple independent servers?<br>
     <input type="radio" name="q5" value="a" onchange="checkAnswers()"> a) Cloud computing<br>
     <input type="radio" name="q5" value="b" onchange="checkAnswers()"> b) Big data<br>
     <input type="radio" name="q5" value="c" onchange="checkAnswers()"> c) Artificial intelligence<br>
     <input type="radio" name="q5" value="d" onchange="checkAnswers()"> d) Virtual reality<br>
     <br>

  6. Which programming language is commonly used for web development?<br>
     <input type="radio" name="q6" value="a" onchange="checkAnswers()"> a) Java<br>
     <input type="radio" name="q6" value="b" onchange="checkAnswers()"> b) Python<br>
     <input type="radio" name="q6" value="c" onchange="checkAnswers()"> c) HTML<br>
     <input type="radio" name="q6" value="d" onchange="checkAnswers()"> d) C++<br>
     <br>

  7. What is the term for software that is freely distributed and allows users to view, modify, and distribute its source code?<br>
     <input type="radio" name="q7" value="a" onchange="checkAnswers()"> a) Open source<br>
     <input type="radio" name="q7" value="b" onchange="checkAnswers()"> b) Closed source<br>
     <input type="radio" name="q7" value="c" onchange="checkAnswers()"> c) Proprietary<br>
     <input type="radio" name="q7" value="d" onchange="checkAnswers()"> d) Freeware<br>
     <br>

  8. What is the purpose of a firewall in computer networks?<br>
     <input type="radio" name="q8" value="a" onchange="checkAnswers()"> a) To prevent unauthorized access<br>
     <input type="radio" name="q8" value="b" onchange="checkAnswers()"> b) To enhance internet speed<br>
     <input type="radio" name="q8" value="c" onchange="checkAnswers()"> c) To store data securely<br>
     <input type="radio" name="q8" value="d" onchange="checkAnswers()"> d) To create virtual private networks<br>
     <br>

  9. What is the name of the organization that oversees the development of internet protocols and standards?<br>
     <input type="radio" name="q9" value="a" onchange="checkAnswers()"> a) W3C<br>
     <input type="radio" name="q9" value="b" onchange="checkAnswers()"> b) IEEE<br>
     <input type="radio" name="q9" value="c" onchange="checkAnswers()"> c) ICANN<br>
     <input type="radio" name="q9" value="d" onchange="checkAnswers()"> d) NSA<br>
     <br>

  10. What is the term used to describe the process of converting analog signals into digital signals?<br>
      <input type="radio" name="q10" value="a" onchange="checkAnswers()"> a) Encryption<br>
      <input type="radio" name="q10" value="b" onchange="checkAnswers()"> b) Decryption<br>
      <input type="radio" name="q10" value="c" onchange="checkAnswers()"> c) Modulation<br>
      <input type="radio" name="q10" value="d" onchange="checkAnswers()"> d) Digitization<br>
      <br>

  11. What does VPN stand for?<br>
     <input type="radio" name="q11" value="a" onchange="checkAnswers()"> a) Virtual Private Network<br>
     <input type="radio" name="q11" value="b" onchange="checkAnswers()"> b) Very Private Network<br>
     <input type="radio" name="q11" value="c" onchange="checkAnswers()"> c) Virtual Public Network<br>
     <input type="radio" name="q11" value="d" onchange="checkAnswers()"> d) Visible Personal Network<br>
     <br>

  12. Who is the CEO of Microsoft?<br>
     <input type="radio" name="q12" value="a" onchange="checkAnswers()"> a) Tim Cook<br>
     <input type="radio" name="q12" value="b" onchange="checkAnswers()"> b) Jeff Bezos<br>
     <input type="radio" name="q12" value="c" onchange="checkAnswers()"> c) Satya Nadella<br>
     <input type="radio" name="q12" value="d" onchange="checkAnswers()"> d) Sundar Pichai<br>
     <br>

  13. What is the primary function of RAM in a computer?<br>
     <input type="radio" name="q13" value="a" onchange="checkAnswers()"> a) Long-term storage<br>
     <input type="radio" name="q13" value="b" onchange="checkAnswers()"> b) Temporary storage<br>
     <input type="radio" name="q13" value="c" onchange="checkAnswers()"> c) Processing data<br>
     <input type="radio" name="q13" value="d" onchange="checkAnswers()"> d) Displaying graphics<br>
     <br>

  14. Which programming language is used for developing Android applications?<br>
     <input type="radio" name="q14" value="a" onchange="checkAnswers()"> a) Java<br>
     <input type="radio" name="q14" value="b" onchange="checkAnswers()"> b) Swift<br>
     <input type="radio" name="q14" value="c" onchange="checkAnswers()"> c) C#<br>
     <input type="radio" name="q14" value="d" onchange="checkAnswers()"> d) Python<br>
     <br>

  15. What is the term for a software program that replicates itself and spreads to other computers?<br>
     <input type="radio" name="q15" value="a" onchange="checkAnswers()"> a) Worm<br>
     <input type="radio" name="q15" value="b" onchange="checkAnswers()"> b) Trojan horse<br>
     <input type="radio" name="q15" value="c" onchange="checkAnswers()"> c) Virus<br>
     <input type="radio" name="q15" value="d" onchange="checkAnswers()"> d) Malware<br>
     <br>

  16. What is the term used to describe a small piece of code designed to enhance web functionality?<br>
     <input type="radio" name="q16" value="a" onchange="checkAnswers()"> a) Applet<br>
     <input type="radio" name="q16" value="b" onchange="checkAnswers()"> b) Widget<br>
     <input type="radio" name="q16" value="c" onchange="checkAnswers()"> c) Plugin<br>
     <input type="radio" name="q16" value="d" onchange="checkAnswers()"> d) Script<br>
     <br>

  17. What is the main purpose of an operating system?<br>
     <input type="radio" name="q17" value="a" onchange="checkAnswers()"> a) Running applications<br>
     <input type="radio" name="q17" value="b" onchange="checkAnswers()"> b) Managing hardware resources<br>
     <input type="radio" name="q17" value="c" onchange="checkAnswers()"> c) Creating documents<br>
     <input type="radio" name="q17" value="d" onchange="checkAnswers()"> d) Sending emails<br>
     <br>

  18. What is the name of the search engine developed by Microsoft?<br>
     <input type="radio" name="q18" value="a" onchange="checkAnswers()"> a) Google<br>
     <input type="radio" name="q18" value="b" onchange="checkAnswers()"> b) Bing<br>
     <input type="radio" name="q18" value="c" onchange="checkAnswers()"> c) Yahoo<br>
     <input type="radio" name="q18" value="d" onchange="checkAnswers()"> d) DuckDuckGo<br>
     <br>

  19. What is the term for a program that appears legitimate but performs malicious activities?<br>
     <input type="radio" name="q19" value="a" onchange="checkAnswers()"> a) Spyware<br>
     <input type="radio" name="q19" value="b" onchange="checkAnswers()"> b) Adware<br>
     <input type="radio" name="q19" value="c" onchange="checkAnswers()"> c) Scareware<br>
     <input type="radio" name="q19" value="d" onchange="checkAnswers()"> d) Ransomware<br>
     <br>

  20. What is the term for the process of hiding data within another file?<br>
      <input type="radio" name="q20" value="a" onchange="checkAnswers()"> a) Encryption<br>
      <input type="radio" name="q20" value="b" onchange="checkAnswers()"> b) Steganography<br>
      <input type="radio" name="q20" value="c" onchange="checkAnswers()"> c) Decryption<br>
      <input type="radio" name="q20" value="d" onchange="checkAnswers()"> d) Cryptography<br>
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