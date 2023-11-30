<?php
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $empid = $_POST["empid"];
    $department_id = $_POST["department_id"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role_id = $_POST["role_id"];

    // Check if empid exists in tbempinfo
    $check_sql = "SELECT empid FROM tbempinfo WHERE empid = ?";
    $check_stmt = $conn->prepare($check_sql);

    if ($check_stmt) {
        $check_stmt->bind_param("s", $empid);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            // empid exists in tbempinfo, proceed with insertion
            $check_stmt->close();

            $insert_sql = "INSERT INTO tbemp_acc (empid, department_id, email, password, role_id) VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);

            if ($insert_stmt) {
                $insert_stmt->bind_param("sssss", $empid, $department_id, $email, $password, $role_id);
                if ($insert_stmt->execute()) {
                    // Display alert and redirect
                    echo "<script>alert('New record created successfully'); window.location.href='employee_login.php';</script>";
                } else {
                    echo "Error: " . $insert_stmt->error;
                }
                $insert_stmt->close();
            } else {
                echo "Prepare statement for insertion failed: " . $conn->error;
            }
        } else {
            echo "Error: empid does not exist in tbempinfo";
        }
    } else {
        echo "Prepare statement for checking empid failed: " . $conn->error;
    }

    $conn->close();
}
?>
