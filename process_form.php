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
    $empid = $_POST["empid"];
    $department_id = $_POST["department_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role_id = $_POST["role_id"];

    // Insert data into tbemp_acc table
    $sql = "INSERT INTO tbemp_acc (empid, department_id, email, password, role_id) VALUES ('$empid', '$department_id', '$email', '$password', '$role_id')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";

        // Fetch the inserted data from tbemp_acc
        $fetchSql = "SELECT * FROM tbemp_acc WHERE empid = '$empid'";
        $result = $conn->query($fetchSql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<br>Inserted Data:<br>";
                echo "empaccountId: " . $row["empaccountId"] . "<br>";
                echo "empid: " . $row["empid"] . "<br>";
                echo "department_id: " . $row["department_id"] . "<br>";
                echo "email: " . $row["email"] . "<br>";
                echo "password: " . $row["password"] . "<br>";
                echo "role_id: " . $row["role_id"] . "<br>";
            }
        } else {
            echo "No data found";
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
