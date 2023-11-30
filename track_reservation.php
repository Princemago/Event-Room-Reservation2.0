<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Reservation</title>
    <!-- Add Bootstrap CSS link -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Track Reservation</h1>
        <form action="" method="post">
            <div class="form-group">
                <label for="reservation_id">Reservation ID:</label>
                <input type="text" class="form-control" name="reservation_id" required>
            </div>
            <button type="submit" class="btn btn-primary">Track</button>
        </form>

        <?php
        // Include your database connection code here

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "db_nt3101";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["reservation_id"])) {
                $reservation_id = mysqli_real_escape_string($conn, $_POST["reservation_id"]);

                $select_query = "SELECT r.*, e.firstname as emp_name, s.firstname as stud_name, a.full_name as admin_name,
                        v.venue_name, d.department_name
                        FROM reservations r
                        LEFT JOIN tbempinfo e ON r.empid = e.empid
                        LEFT JOIN tbstudinfo s ON r.studid = s.studid
                        LEFT JOIN tb_admin a ON r.adminid = a.adminid
                        LEFT JOIN venues v ON r.venue_id = v.venue_id
                        LEFT JOIN departments d ON r.department_id = d.department_id
                        WHERE r.reservation_id = $reservation_id";

                $result = $conn->query($select_query);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    // Check if the user is a student or an employee
                    $isStudent = isset($row["stud_name"]);
                    $isEmployee = isset($row["emp_name"]);

                    if ($isStudent || $isEmployee) {
                        echo "<h2 class='mt-4'>Reservation Details</h2>";
                        echo "<p>Reservation ID: " . $row["reservation_id"] . "</p>";
                        
                        if ($isEmployee && $row["emp_name"] !== null) {
                            echo "<p>Employee Name: " . $row["emp_name"] . "</p>";
                        }

                        if ($isStudent && $row["stud_name"] !== null) {
                            echo "<p>Student Name: " . $row["stud_name"] . "</p>";
                        }

                        echo "<p>Venue Name: " . $row["venue_name"] . "</p>";
                        echo "<p>Department Name: " . $row["department_name"] . "</p>";
                        echo "<p>Start Time: " . $row["start_time"] . "</p>";
                        echo "<p>End Time: " . $row["end_time"] . "</p>";

                        if ($row["admin_name"] !== null) {
                            echo "<p>Admin Name: " . $row["admin_name"] . "</p>";
                        }
                        
                        echo "<p>Status: " . $row["status"] . "</p>";
                    } else {
                        echo "<p class='text-danger'>You are not authorized to view this reservation.</p>";
                    }
                } else {
                    echo "<p class='text-danger'>No reservation found with ID: $reservation_id</p>";
                }
            }
        }
        ?>

    </div>

    <!-- Bootstrap JS and Popper.js scripts (for certain Bootstrap features) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>