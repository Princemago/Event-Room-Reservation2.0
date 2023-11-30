<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Account Form</title>

  <!-- Add Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      max-width: 400px;
      margin: 0 auto;
      margin-top: 50px;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
    }

    form {
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
      margin-bottom: 20px;
    }

    button {
      width: 100%;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Employee Account Information</h2>

  <form action="employee_form.php" method="post">
    <div class="form-group">
      <label for="empid">Employee ID:</label>
      <input type="text" class="form-control" name="empid" required>
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

    <div class="form-group">
      <label for="role_id">Role:</label>
      <select class="form-control" name="role_id">
        <option value="1">Admin</option>
        <option value="2">Teacher</option>
        <!-- Add more options as needed -->
      </select>
    </div>

    <div class="text-center">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>

<!-- Add Bootstrap JS and Popper.js (if needed) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
