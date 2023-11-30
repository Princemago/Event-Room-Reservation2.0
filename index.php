<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_nt3101";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$deleteMessage = "";

$employeeNames = fetchDropdownOptions($conn, "tbempinfo", "empid", "firstname");
$studentNames = fetchDropdownOptions($conn, "tbstudinfo", "studid", "firstname");
$venueNames = fetchDropdownOptions($conn, "venues", "venue_id", "venue_name");
$departmentNames = fetchDropdownOptions($conn, "departments", "department_id", "department_name");
$adminNames = fetchDropdownOptions($conn, "tb_admin", "adminid", "full_name");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST["empid"], $_POST["studid"], $_POST["venue_id"], $_POST["department_id"], $_POST["start_time"], $_POST["end_time"], $_POST["admin_id"])
    ) {
        $empid = mysqli_real_escape_string($conn, $_POST["empid"]);
        $studid = mysqli_real_escape_string($conn, $_POST["studid"]);
        $venue_id = mysqli_real_escape_string($conn, $_POST["venue_id"]);
        $department_id = mysqli_real_escape_string($conn, $_POST["department_id"]);
        $start_time = mysqli_real_escape_string($conn, $_POST["start_time"]);
        $end_time = mysqli_real_escape_string($conn, $_POST["end_time"]);
        $admin_id = mysqli_real_escape_string($conn, $_POST["admin_id"]);

        // Input validation (you may need to adjust these based on your requirements)
        if (empty($venue_id) || empty($department_id) || empty($start_time) || empty($end_time) || empty($admin_id)) {
            die("Please fill in all required fields.");
        }

        // Determine if empid or studid should be used based on the form submission
        if (!empty($empid)) {
            $studid = null; // Set student id to null
        } elseif (!empty($studid)) {
            $empid = null; // Set employee id to null
        } else {
            die("Please select either an employee or a student.");
        }

        // Perform additional validation if needed

        // Convert datetime-local format to MySQL datetime format
        $start_time = date("Y-m-d H:i:s", strtotime($start_time));
        $end_time = date("Y-m-d H:i:s", strtotime($end_time));

        // Use prepared statements to prevent SQL injection
        $insert_query = "INSERT INTO reservations (empid, studid, venue_id, department_id, start_time, end_time, adminid, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending')";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("iiissss", $empid, $studid, $venue_id, $department_id, $start_time, $end_time, $admin_id);

        if ($insert_stmt->execute()) {
            // Set a message for successful insertion
            $deleteMessage = "Reservation added successfully";
        } else {
            $deleteMessage = "Error adding reservation: " . $insert_stmt->error;
        }

        $insert_stmt->close();
    } elseif (isset($_POST["delete_id"])) {
        $delete_id = mysqli_real_escape_string($conn, $_POST["delete_id"]);

        // Prepare and execute the DELETE query
        $delete_query = "DELETE FROM reservations WHERE reservation_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $delete_id);

        if ($delete_stmt->execute()) {
            // Set a message for successful deletion
            $deleteMessage = "Reservation deleted successfully";
        } else {
            $deleteMessage = "Error deleting reservation: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } elseif (isset($_POST["approve_id"])) {
        $approve_id = mysqli_real_escape_string($conn, $_POST["approve_id"]);

        // Prepare and execute the UPDATE query to set status to 'Approved'
        $approve_query = "UPDATE reservations SET status = 'Approved' WHERE reservation_id = ?";
        $approve_stmt = $conn->prepare($approve_query);
        $approve_stmt->bind_param("i", $approve_id);

        if ($approve_stmt->execute()) {
            // Set a message for successful approval
            $deleteMessage = "Reservation approved successfully";
        } else {
            $deleteMessage = "Error approving reservation: " . $approve_stmt->error;
        }

        $approve_stmt->close();
    } elseif (isset($_POST["disapprove_id"])) {
        $disapprove_id = mysqli_real_escape_string($conn, $_POST["disapprove_id"]);

        // Prepare and execute the UPDATE query to set status to 'Disapproved'
        $disapprove_query = "UPDATE reservations SET status = 'Disapproved' WHERE reservation_id = ?";
        $disapprove_stmt = $conn->prepare($disapprove_query);
        $disapprove_stmt->bind_param("i", $disapprove_id);

        if ($disapprove_stmt->execute()) {
            // Set a message for successful disapproval
            $deleteMessage = "Reservation disapproved successfully";
        } else {
            $deleteMessage = "Error disapproving reservation: " . $disapprove_stmt->error;
        }

        $disapprove_stmt->close();
    } else {
        // Handle other form submissions or data additions here
    }
}

$select_query = "SELECT r.*, e.firstname as emp_name, s.firstname as stud_name, a.full_name as admin_name,
                 v.venue_name, d.department_name
                 FROM reservations r
                 LEFT JOIN tbempinfo e ON r.empid = e.empid
                 LEFT JOIN tbstudinfo s ON r.studid = s.studid
                 LEFT JOIN tb_admin a ON r.adminid = a.adminid
                 LEFT JOIN venues v ON r.venue_id = v.venue_id
                 LEFT JOIN departments d ON r.department_id = d.department_id";

$result = $conn->query($select_query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

function fetchDropdownOptions($conn, $tableName, $idColumn, $nameColumn)
{
    $options = array();

    // Add an empty option
    $options[''] = 'Select'; // You can customize the text for the empty option

    $query = "SELECT $idColumn, $nameColumn FROM $tableName";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options[$row[$idColumn]] = $row[$nameColumn];
        }
    }

    return $options;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style1.css">
    <style>
        #hiddenContent {
            display: none; /* Initially hide the content */
        }

        #hiddenContent form {
            max-width: 400px;
            margin: auto;
        }

        #hiddenContent label {
            display: block;
            margin-bottom: 8px;
        }

        #hiddenContent input,
        #hiddenContent select {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <ul class="navigation">
            <li>
                <a href="index.php">
                    <i class="bi bi-house-door-fill"></i>
                    <span class="dashboard">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" onclick="toggleContent()">
                    <i class="bi bi-person-workspace"></i>
                    <span class="dashboard">Manage</span>
                </a>
            </li>
            <li>
                <a href="process_teacher_info.php" onclick="toggleContent()">
                    <i class="bi bi-person-workspace"></i>
                    <span class="dashboard">Add Teacher Info</span>
                </a>
            </li>
            <li>
                <a href="studentregistration_form.php" onclick="toggleContent()">
                    <i class="bi bi-person-workspace"></i>
                    <span class="dashboard">Add Student Info</span>
                </a>
            </li>
            <li class="logout">
                <a href="login.php">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="dashboard">Logout</span>
                </a>
            </li>
        </ul>
    </div>  
    <div class="main-content">
        <div class="cardcontainer">
            <h3>Welcome Back! Manage schedule and check the vacant rooms.</h3>
            <hr>
            <h3>Reservation List</h3>

            <!-- Display deleteMessage if set -->
            <?php if ($deleteMessage): ?>
                <p><?php echo $deleteMessage; ?></p>
            <?php endif; ?>

            <table border="1">
                <tr>
                    <th>Reservation ID</th>
                    <th>Employee Name</th>
                    <th>Student Name</th>
                    <th>Venue Name</th>
                    <th>Department Name</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Admin Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

                <?php
                // Display data in the table
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["reservation_id"] . "</td>";
                    echo "<td>" . ($row["emp_name"] ? $row["emp_name"] : "N/A") . "</td>";
                    echo "<td>" . ($row["stud_name"] ? $row["stud_name"] : "N/A") . "</td>";
                    echo "<td>" . $row["venue_name"] . "</td>";
                    echo "<td>" . $row["department_name"] . "</td>";
                    echo "<td>" . $row["start_time"] . "</td>";
                    echo "<td>" . $row["end_time"] . "</td>";
                    echo "<td>" . ($row["admin_name"] ? $row["admin_name"] : "N/A") . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td>";
                    echo "<form action='index.php' method='post'>";
                    echo "<input type='hidden' name='delete_id' value='" . $row["reservation_id"] . "'>";
                    echo "<input type='submit' value='Delete' onclick='return confirm(\"Are you sure?\")'>";
                    echo "</form>";

                    // Modify the form action and hidden field names for approve and disapprove
                    echo "<form action='' method='post'>";
                    echo "<input type='hidden' name='approve_id' value='" . $row["reservation_id"] . "'>";
                    echo "<input type='submit' value='Approve'>";
                    echo "</form>";

                    echo "<form action='' method='post'>";
                    echo "<input type='hidden' name='disapprove_id' value='" . $row["reservation_id"] . "'>";
                    echo "<input type='submit' value='Disapprove'>";
                    echo "</form>";

                    echo "</td>";
                    echo "</tr>";
                }

                // Display message if no records found
                if ($result->num_rows === 0) {
                    echo "<tr><td colspan='10'>No records found</td></tr>";
                }
                ?>
            </table>
        </div>

        <div id="hiddenContent">
            <h3>Reservation Form</h3>
            <form action="" method="post">
                <!-- Use dropdowns for Employee ID, Student ID, Venue ID, Department ID, and Admin ID -->
                <label for="empid">Employee:</label>
                <select name="empid" >
                    <?php
                    foreach ($employeeNames as $empId => $empName) {
                        echo "<option value='$empId'>$empName</option>";
                    }
                    ?>
                </select>

                <label for="studid">Student:</label>
                <select name="studid" >
                    <?php
                    foreach ($studentNames as $studId => $studName) {
                        echo "<option value='$studId'>$studName</option>";
                    }
                    ?>
                </select>

                <label for="venue_id">Venue:</label>
                <select name="venue_id" required>
                    <?php
                    foreach ($venueNames as $venueId => $venueName) {
                        echo "<option value='$venueId'>$venueName</option>";
                    }
                    ?>
                </select>

                <label for="department_id">Department:</label>
                <select name="department_id" required>
                    <?php
                    foreach ($departmentNames as $departmentId => $departmentName) {
                        echo "<option value='$departmentId'>$departmentName</option>";
                    }
                    ?>
                </select>

                <label for="admin_id">Admin:</label>
                <select name="admin_id" required>
                    <?php
                    foreach ($adminNames as $adminId => $adminName) {
                        echo "<option value='$adminId'>$adminName</option>";
                    }
                    ?>
                </select>

                <label for="start_time">Start Time:</label>
                <input type="datetime-local" name="start_time" required>

                <label for="end_time">End Time:</label>
                <input type="datetime-local" name="end_time" required>

                <input type="submit" value="Submit">
                
            </form>
        </div>
        
    </div>
                         
    <script>
        function toggleContent() {
            var content = document.getElementById("hiddenContent");
            if (content.style.display === "none") {
                content.style.display = "block";
            } else {
                content.style.display = "none";
            }
        }
    </script>
</body>
</html>
