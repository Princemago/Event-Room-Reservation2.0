<?php
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studid = $_POST["studid"];
    $department_id = $_POST["department_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "INSERT INTO tbstudent_acc (studid, department_id, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $studid, $department_id, $email, $password);
        if ($stmt->execute()) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare statement failed: " . $conn->error;
    }
}

$conn->close();
?>