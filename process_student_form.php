<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "finaldb3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $studid = $_POST["studid"];
    $department_id = $_POST["department_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Insert data into tbstudent_acc table
    $sql = "INSERT INTO tbstudent_acc (studid, department_id, email, password) VALUES ('$studid', '$department_id', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
