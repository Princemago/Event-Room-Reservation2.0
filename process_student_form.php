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
    $department_id = $_POST["department_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if studid exists in tbstudinfo
    $checkStudidSql = "SELECT studid FROM tbstudinfo WHERE studid = '$studid'";
    $result = $conn->query($checkStudidSql);

    if ($result->num_rows == 0) {
        // If studid doesn't exist, insert into tbstudinfo
        $insertStudInfoSql = "INSERT INTO tbstudinfo (studid) VALUES ('$studid')";
        if ($conn->query($insertStudInfoSql) !== TRUE) {
            echo "Error inserting into tbstudinfo: " . $conn->error;
            $conn->close();
            exit;
        }
    }

    // Insert data into tbstudent_acc table
    $insertStudAccSql = "INSERT INTO tbstudent_acc (studid, department_id, email, password) VALUES ('$studid', '$department_id', '$email', '$password')";

    if ($conn->query($insertStudAccSql) === TRUE) {
        // Display alert and redirect
        echo "<script>alert('New record created successfully'); window.location.href='student_login.php';</script>";
    } else {
        if (strpos($conn->error, 'FOREIGN KEY') !== false) {
            echo "<script>alert('Error: Foreign key constraint failure. The studid may not exist in tbstudinfo.')</script>";
        } else {
            echo "<script>alert('Error: " . $insertStudAccSql . "\\n" . $conn->error . "')</script>";
        }
    }
}

// Close the connection
$conn->close();
?>
