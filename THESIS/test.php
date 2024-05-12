<?php 
include ("api/database.php");
session_start();

// Function to recommend courses based on the user's highest score
function recommendCourses($highestScore) {
    // Define courses based on score thresholds for each field
    $courses = array(
        "Engineering" => array(
            "Civil Engineering" => 55,
            "Electrical Engineering" => 55,
            "Computer Engineering" => 55,
            "Mechanical Engineering" => 55,
            "Chemical Engineering" => 55,
            "Industrial Engineering" => 55,
            "Aerospace Engineering" => 55,
            "Biomedical Engineering" => 55,
            "Environmental Engineering" => 55,
            "Petroleum Engineering" => 55,
            "Materials Engineering" => 55,
            "Nuclear Engineering" => 55,
        ),
        "Computer Science" => array(
            "Computer Science" => 60,
            "Data Science" => 60,
            "Information Technology" => 60,
            "Software Engineering" => 60,
            "Cybersecurity" => 60,
            "Artificial Intelligence" => 60,
            "Machine Learning" => 60,
            "Web Development" => 60,
            "Game Development" => 60,
            "Mobile App Development" => 60,
        ),
        "Health Sciences" => array(
            "Bachelor of Science in Nursing" => 55,
            "Bachelor of Science in Pharmacy" => 55,
            "Bachelor of Science in Medical Technology" => 55,
            "Medicine" => 55,
            "Dentistry" => 55,
            "Physical Therapy" => 55,
            "Occupational Therapy" => 55,
            "Public Health" => 55,
            "Nutrition and Dietetics" => 55,
            "Healthcare Administration" => 55,
            "Biomedical Science" => 55,
        ),
        "Agriculture" => array(
            "Agricultural Technology" => 50,
            "Agriculture" => 50,
            "Agribusiness Management" => 50,
            "Animal Science" => 50,
            "Crop Science" => 50,
            "Horticulture" => 50,
            "Agricultural Economics" => 50,
            "Agricultural Education" => 50,
            "Food Science and Technology" => 50,
            "Environmental Science" => 50,
        ),
        "Science" => array(
            "Biology" => 50,
            "Chemistry" => 50,
            "Physics" => 50,
            "Mathematics" => 50,
            "Environmental Science" => 50,
            "Geology" => 50,
            "Astrophysics" => 50,
            "Statistics" => 50,
            "Biochemistry" => 50,
            "Biotechnology" => 50,
        ),
        "Education" => array(
            "Education" => 45,
            "Early Childhood Education" => 45,
            "Elementary Education" => 45,
            "Secondary Education" => 45,
            "Special Education" => 45,
            "Educational Leadership" => 45,
            "Curriculum and Instruction" => 45,
            "Educational Technology" => 45,
            "TESOL (Teaching English to Speakers of Other Languages)" => 45,
        ),
        "Internet Technology" => array(
            "Web Development" => 60,
            "Information Technology" => 60,
            "Cybersecurity" => 60,
            "Artificial Intelligence" => 60,
            "Machine Learning" => 60,
            "Game Development" => 60,
            "Mobile App Development" => 60,
            "Network Administration" => 60,
            "Cloud Computing" => 60,
            "Digital Marketing" => 60,
        ),
    );

    // Determine the recommended courses based on the highest score
    $recommendedCourses = array();
    foreach ($courses as $field => $fieldCourses) {
        foreach ($fieldCourses as $course => $threshold) {
            if ($highestScore >= $threshold) {
                $recommendedCourses[] = $course;
            }
        }
    }

    return $recommendedCourses;
}

// Fetch highest score achieved by the user among the subjects
$highestScore = 0;
$subjects = array("english", "math", "science", "critical_thinking", "geography", "technology");
foreach ($subjects as $subject) {
    $query = "SELECT $subject FROM users WHERE id = {$_SESSION['user_id']}";
    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $score = $row[$subject];
        if ($score !== null && $score > $highestScore) {
            $highestScore = $score;
        }
    }
}

// Recommend courses based on the highest score
$recommendedCourses = recommendCourses($highestScore);

// Output recommended courses
echo "Recommended Courses:\n";
foreach ($recommendedCourses as $course) {
    echo "- $course\n";
}

// Rest of your code
?>
