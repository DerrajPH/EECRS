<?php
include "api/database.php";

$username = $_GET['username'];

// Check if username exists in the database
$sql = "SELECT * FROM users WHERE username='$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Username already exists
    echo json_encode(array('available' => false, 'message' => 'Username already in use'));
} else {
    // Username available
    echo json_encode(array('available' => true, 'message' => 'Username available'));
}
?>
