<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_nt3101";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $studid = $_POST["studid"];
    $password = $_POST["password"];

    // Check login credentials
    $checkLoginSql = "SELECT * FROM tbstudent_acc WHERE studid = '$studid' AND password = '$password'";
    $result = $conn->query($checkLoginSql);

    if ($result->num_rows == 1) {
        // Login successful
        session_start();
        $_SESSION["studid"] = $studid;
        header("Location: track_reservation.php");
        exit();
    } else {
        // Login failed
        echo "<script>
                alert('Invalid login credentials');
                window.location.href = 'student_login.php';
              </script>";
        exit();
    }
}

// Close the connection
$conn->close();
?>
