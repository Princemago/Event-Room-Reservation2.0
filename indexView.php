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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle POST requests
    if (isset($_POST["delete_id"])) {
        $delete_id = $_POST["delete_id"];
        $delete_query = "DELETE FROM reservations WHERE reservation_id = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $delete_id);

        if ($delete_stmt->execute()) {
            $deleteMessage = "Record deleted successfully";
        } else {
            $deleteMessage = "Error deleting record: " . $delete_stmt->error;
        }

        $delete_stmt->close();
    } elseif (isset($_POST["approve_id"])) {
        // Handle approve request
        $approve_id = $_POST["approve_id"];
        $approve_query = "UPDATE reservations SET status = 'approved' WHERE reservation_id = ?";
        $approve_stmt = $conn->prepare($approve_query);
        $approve_stmt->bind_param("i", $approve_id);

        if ($approve_stmt->execute()) {
            $deleteMessage = "Reservation approved successfully";
        } else {
            $deleteMessage = "Error approving reservation: " . $approve_stmt->error;
        }

        $approve_stmt->close();
    } elseif (isset($_POST["disapprove_id"])) {
        // Handle disapprove request
        $disapprove_id = $_POST["disapprove_id"];
        $disapprove_query = "UPDATE reservations SET status = 'disapproved' WHERE reservation_id = ?";
        $disapprove_stmt = $conn->prepare($disapprove_query);
        $disapprove_stmt->bind_param("i", $disapprove_id);

        if ($disapprove_stmt->execute()) {
            $deleteMessage = "Reservation disapproved successfully";
        } else {
            $deleteMessage = "Error disapproving reservation: " . $disapprove_stmt->error;
        }

        $disapprove_stmt->close();
    } else {
        // Handle adding record
        $venueID = $_POST["venue_id"];
        $teacherSrcode = empty($_POST["teacher_srcode"]) ? null : $_POST["teacher_srcode"];
        $studentSrcode = empty($_POST["student_srcode"]) ? null : $_POST["student_srcode"];
        $departmentID = $_POST["department"];
        $dateAndTime = $_POST["date_and_time"];

        $sql = "INSERT INTO reservations (venue_id, teacher_id, student_id, department_id, start_time) 
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiis", $venueID, $teacherSrcode, $studentSrcode, $departmentID, $dateAndTime);

        if ($stmt->execute()) {
            $deleteMessage = "Record added successfully";
        } else {
            $deleteMessage = "Error adding record: " . $stmt->error;
        }

        $stmt->close();
    }
}

$select_query = "SELECT r.*, v.venue_name, t.teacher_name, s.student_name, d.department_name 
                 FROM reservations r
                 LEFT JOIN venues v ON r.venue_id = v.venue_id
                 LEFT JOIN tbempinfo t ON r.teacher_id = t.teacher_id
                 LEFT JOIN tbstudinfo s ON r.student_id = s.student_id
                 LEFT JOIN departments d ON r.department_id = d.department_id";
$result = $conn->query($select_query);

if (!$result) {
    die("Query failed: " . $conn->error);
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
            display: none;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <ul class="navigation">
            <li>
                <a href="indexView.php">
                    <i class="bi bi-house-door-fill"></i>
                    <span class="dashboard">Dashboard</span>
                </a>
            </li>

            <li class="logout">
                <a href="loginViewer.php">
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

            <table border="1" id="printTable">
                <tr>
                    <th>Venue</th>
                    <th>Teacher</th>
                    <th>Student</th>
                    <th>Department</th>
                    <th>Date and Time</th>
                    <th>Status</th>
                </tr>

                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["venue_name"] . "</td>";
                        echo "<td>" . $row["teacher_name"] . "</td>";
                        echo "<td>" . $row["student_name"] . "</td>";
                        echo "<td>" . $row["department_name"] . "</td>";
                        echo "<td>" . date('Y-m-d H:i:s', strtotime($row["start_time"])) . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
            </table>

            <!-- Place the button at the bottom of the table -->
            <button onclick="printTable()">Print Table</button>
        </div>

        <div id="hiddenContent">
            <!-- Your form content -->
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

        function printTable() {
            var printContent = document.getElementById("printTable");
            var originalContent = document.body.innerHTML;

            document.body.innerHTML = printContent.outerHTML;
            window.print();

            document.body.innerHTML = originalContent;
        }
    </script>
</body>

</html>
