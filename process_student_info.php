<?php
// process_student_info.php

// Include your database connection code here
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_nt3101";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ... (You can include other common functions or configurations here)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["lastname"], $_POST["firstname"], $_POST["course"])) {
        $lastname = mysqli_real_escape_string($conn, $_POST["lastname"]);
        $firstname = mysqli_real_escape_string($conn, $_POST["firstname"]);
        $course = mysqli_real_escape_string($conn, $_POST["course"]);

        // Input validation (you may need to adjust these based on your requirements)
        if (empty($lastname) || empty($firstname) || empty($course)) {
            die("Please fill in all required fields.");
        }

        // Use prepared statements to prevent SQL injection
        $insert_query = "INSERT INTO tbstudinfo (lastname, firstname, course) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sss", $lastname, $firstname, $course);

        if ($insert_stmt->execute()) {
            // Redirect to the page where you want to display a success message or list of students
            header("Location: index.php");
            exit();
        } else {
            die("Error adding student information: " . $insert_stmt->error);
        }

        $insert_stmt->close();
    } else {
        // Handle other form submissions or data additions here
    }
}

$conn->close();
?>
