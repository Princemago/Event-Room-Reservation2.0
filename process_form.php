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
    $empid = $_POST["empid"];
    $department_id = $_POST["department_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role_id = $_POST["role_id"];

    // Check if empid exists in tbempinfo
    $checkEmpidSql = "SELECT empid FROM tbempinfo WHERE empid = '$empid'";
    $result = $conn->query($checkEmpidSql);

    if ($result->num_rows == 0) {
        // If empid doesn't exist, insert into tbempinfo
        $insertEmpInfoSql = "INSERT INTO tbempinfo (empid) VALUES ('$empid')";
        if ($conn->query($insertEmpInfoSql) !== TRUE) {
            echo "Error inserting into tbempinfo: " . $conn->error;
            $conn->close();
            exit;
        }
    }

    // Insert data into tbemp_acc table
    $insertEmpAccSql = "INSERT INTO tbemp_acc (empid, department_id, email, password, role_id) VALUES ('$empid', '$department_id', '$email', '$password', '$role_id')";

    if ($conn->query($insertEmpAccSql) === TRUE) {
        // Display alert and redirect
        echo "<script>alert('New record created successfully'); window.location.href='employee_login.php';</script>";
    } else {
        if (strpos($conn->error, 'FOREIGN KEY') !== false) {
            echo "<script>alert('Error: Foreign key constraint failure. The empid may not exist in tbempinfo.')</script>";
        } else {
            echo "<script>alert('Error: " . $insertEmpAccSql . "\\n" . $conn->error . "')</script>";
        }
    }
}

// Close the connection
$conn->close();
?>
