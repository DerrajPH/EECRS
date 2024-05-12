<?php 
include ("api/database.php");
session_start();

// Function to recommend a field based on the user's total scores
function recommendField($totalScores) {
    // Define courses based on score thresholds for each field
    $courses = array(
        "Civil Engineering" => 55,
        "Electrical Engineering" => 55,
        "Computer Engineering" => 55,
        "Computer Science" => 60,
        "Data Science" => 60,
        "Information Technology" => 60,
        "Agricultural Technology" => 50,
        "Electro-Mechanical Technology" => 50,
        "Electronics Technology" => 50,
        "Agriculture" => 50,
        "Marine Biology" => 50,
        "Chemistry" => 50,
        "Secondary Education (major in Science)" => 45,
        "Social Work" => 45,
        "Technical-Vocational Teacher" => 45,
        "Bachelor of Science in Nursing" => 55,
        "Bachelor of Science in Pharmacy" => 55,
        "Bachelor of Science in Medical Technology" => 55
    );
    // $courses = array(
    //     "Engineering" => array(
    //         "Civil Engineering" => 55,
    //         "Electrical Engineering" => 55,
    //         "Computer Engineering" => 55,
    //         "Mechanical Engineering" => 55,
    //         "Chemical Engineering" => 55,
    //         "Industrial Engineering" => 55,
    //         "Aerospace Engineering" => 55,
    //         "Biomedical Engineering" => 55,
    //         "Environmental Engineering" => 55,
    //         "Petroleum Engineering" => 55,
    //         "Materials Engineering" => 55,
    //         "Nuclear Engineering" => 55,
    //     ),
    //     "Computer Science" => array(
    //         "Computer Science" => 60,
    //         "Data Science" => 60,
    //         "Information Technology" => 60,
    //         "Software Engineering" => 60,
    //         "Cybersecurity" => 60,
    //         "Artificial Intelligence" => 60,
    //         "Machine Learning" => 60,
    //         "Web Development" => 60,
    //         "Game Development" => 60,
    //         "Mobile App Development" => 60,
    //     ),
    //     "Health Sciences" => array(
    //         "Bachelor of Science in Nursing" => 55,
    //         "Bachelor of Science in Pharmacy" => 55,
    //         "Bachelor of Science in Medical Technology" => 55,
    //         "Medicine" => 55,
    //         "Dentistry" => 55,
    //         "Physical Therapy" => 55,
    //         "Occupational Therapy" => 55,
    //         "Public Health" => 55,
    //         "Nutrition and Dietetics" => 55,
    //         "Healthcare Administration" => 55,
    //         "Biomedical Science" => 55,
    //     ),
    //     "Agriculture" => array(
    //         "Agricultural Technology" => 50,
    //         "Agriculture" => 50,
    //         "Agribusiness Management" => 50,
    //         "Animal Science" => 50,
    //         "Crop Science" => 50,
    //         "Horticulture" => 50,
    //         "Agricultural Economics" => 50,
    //         "Agricultural Education" => 50,
    //         "Food Science and Technology" => 50,
    //         "Environmental Science" => 50,
    //     ),
    //     "Science" => array(
    //         "Biology" => 50,
    //         "Chemistry" => 50,
    //         "Physics" => 50,
    //         "Mathematics" => 50,
    //         "Environmental Science" => 50,
    //         "Geology" => 50,
    //         "Astrophysics" => 50,
    //         "Statistics" => 50,
    //         "Biochemistry" => 50,
    //         "Biotechnology" => 50,
    //     ),
    //     "Education" => array(
    //         "Education" => 45,
    //         "Early Childhood Education" => 45,
    //         "Elementary Education" => 45,
    //         "Secondary Education" => 45,
    //         "Special Education" => 45,
    //         "Educational Leadership" => 45,
    //         "Curriculum and Instruction" => 45,
    //         "Educational Technology" => 45,
    //         "TESOL (Teaching English to Speakers of Other Languages)" => 45,
    //     ),
    //     "Social Work" => array(
    //         "Social Work" => 45,
    //         "Human Services" => 45,
    //         "Counseling" => 45,
    //         "Community Development" => 45,
    //         "Public Administration" => 45,
    //         "Nonprofit Management" => 45,
    //         "Criminal Justice" => 45,
    //         "Sociology" => 45,
    //         "Anthropology" => 45,
    //         "Political Science" => 45,
    //     ),
    // );

    // Determine the recommended courses based on total score
    $recommendedCourses = array();
    foreach ($courses as $course => $threshold) {
        if ($totalScores >= $threshold) {
            $recommendedCourses[] = $course;
        }
    }

    return $recommendedCourses;
}

    // Check if all examinations are answered
    $allExamsAnswered = true;
    $subjects = array("english", "math", "science", "critical_thinking", "geography", "technology");
    foreach ($subjects as $subject) {
        $query = "SELECT $subject FROM users WHERE id = {$_SESSION['user_id']}";
        $result = $conn->query($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row[$subject] === null) { // Check if score is null
                $allExamsAnswered = false;
                break;
            }
        } else {
            $allExamsAnswered = false;
            break;
        }
    }

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
    }
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
        <center>
            <h1>Examinations</h1><br>
            <div class="row">
                <?php
                $subjects = array(
                    "english" => array("English", "english.png", "english.php"),
                    "math" => array("Mathematics", "math.png", "math.php"),
                    "science" => array("General Science", "science.png", "science.php"),
                    "critical_thinking" => array("Critical Thinking", "critical_thinking.png", "critical_thinking.php"),
                    "geography" => array("Geography", "geography.png", "geography.php"),
                    "technology" => array("Technology", "technology.png", "technology.php")
                );

                // Fetch the user's scores from the database
                $userScores = array();
                foreach ($subjects as $subject => $info) {
                    $query = "SELECT $subject FROM users WHERE id = {$_SESSION['user_id']}";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $score = $row[$subject];
                        if ($score !== null) { // Check if score is not null
                            $userScores[$subject] = $score;
                        } else {
                            $allExamsAnswered = false; // If any score is null, all exams are not answered
                        }
                    } else {
                        $allExamsAnswered = false; // If any query fails, all exams are not answered
                    }
                }

                if (!isset($allExamsAnswered)) {
                    $allExamsAnswered = true; // If all queries succeed and no scores are null, all exams are answered
                }

                foreach ($subjects as $subject => list($subjectName, $imagePath, $subjectLink)) : ?>
                    <div class="col-sm-2">
                        <div class="thumbnail"><br>
                            <a href="<?php echo $subjectLink; ?>">
                                <p style="color:black;"><?php echo $subjectName; ?></p>
                                <img src="<?php echo $imagePath; ?>" style="width:100%">
                                <div class="caption">
                                    <?php 
                                    $query = "SELECT $subject FROM users WHERE id = {$_SESSION['user_id']}";
                                    $result = $conn->query($query);

                                    if ($result && $result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $score = $row[$subject];
                                        if ($score !== null) { // Check if score is not null
                                            echo "<p>$score/20</p>";
                                        }
                                    }
                                    ?>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php if (!$allExamsAnswered) : ?>
                    <!-- <div class="col-sm-12">
                        <h2>Please finish all the examinations first</h2>
                    </div> -->
                <?php else : ?>
                    <?php
                        // Recommend courses based on the user's scores
                        $totalScore = array_sum($userScores);
                        $recommendedCourses = recommendField($totalScore);
                        if (!empty($recommendedCourses)) {
                            echo "<div class='col-sm-12'><h2>Recommended Courses:</h2><ul>";
                            $userId = $_SESSION["user_id"];
                            $query = "UPDATE users SET course = ? WHERE id = ?";
                            $statement = $conn->prepare($query);
                            foreach ($recommendedCourses as $course) {
                                echo "<li>$course</li>";
                                $statement->bind_param('si', $course, $userId);
                                $statement->execute();
                            }
                            echo "</ul></div>";
                        } else {
                            echo "<div class='col-sm-12'><h2>Passing Score must be 50 and above.</h2></div>";
                        }
                    ?>
                <?php endif; ?>
            </div>
        </center>
    </div>
<?php else : ?>
    <div class="container2">
        <center><h1>Please Login First</h1></center><br>
    </div>
<?php endif; ?>

</body>
</html>
