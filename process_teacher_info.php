
<?php
// process_teacher_info.php

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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate and sanitize inputs
    $lastname = filter_input(INPUT_POST, "lastname", FILTER_SANITIZE_STRING);
    $firstname = filter_input(INPUT_POST, "firstname", FILTER_SANITIZE_STRING);
    $department = filter_input(INPUT_POST, "department", FILTER_SANITIZE_STRING);

    if ($lastname && $firstname && $department) {
        // Using prepared statements to prevent SQL injection
        $insert_query = "INSERT INTO tbempinfo (lastname, firstname, department) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("sss", $lastname, $firstname, $department);

        if ($insert_stmt->execute()) {
            // Redirect to the page where you want to display a success message or list of teachers
            header("Location: index.php");
            exit();
        } else {
            die("Error adding teacher information: " . $insert_stmt->error);
        }

        $insert_stmt->close();
    } else {
        // Handle invalid inputs or other form submissions here
    }
}

// Fetch department options dynamically from the database
$departmentOptions = fetchDropdownOptions($conn, "departments", "department_id", "department_name");

$conn->close();

function fetchDropdownOptions($conn, $tableName, $idColumn, $nameColumn)
{
    $options = array();
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
    <title>Add Teacher Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            margin-top: 50px;
        }

        .cardcontainer {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        form {
            max-width: 400px;
            margin: auto;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-content">
            <div class="cardcontainer">
                <h3 class="text-center">Add Teacher Information</h3>
                <hr>

                <form action="process_teacher_info.php" method="post">
                    <div class="form-group">
                        <label for="lastname">Last Name:</label>
                        <input type="text" class="form-control" name="lastname" required>
                    </div>

                    <div class="form-group">
                        <label for="firstname">First Name:</label>
                        <input type="text" class="form-control" name="firstname" required>
                    </div>

                    <div class="form-group">
                        <label for="department">Department:</label>
                        <select class="form-control" name="department" required>
                            <option value="CICS">CICS</option>
                            <option value="CAS">CAS</option>
                            <option value="CABE">CABE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
