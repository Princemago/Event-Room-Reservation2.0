<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Account Form</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Student Account Form</h2>
    <form action="trackLogin.php" method="post">
    <form action="process_student_form.php" method="post">
        <div class="form-group">
            <label for="studid">Student ID:</label>
            <input type="text" class="form-control" name="studid" required>
        </div>

        <div class="form-group">
            <label for="department_id">Department:</label>
            <select class="form-control" name="department_id">
                <option value="1">CAS</option>
                <option value="2">CABE</option>
                <option value="3">CICS</option>
                <!-- Add more options as needed -->
            </select>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" required>
        </div>

        <button type="submit" class="btn btn-primary" >Submit</button>
    </form>
</form>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>