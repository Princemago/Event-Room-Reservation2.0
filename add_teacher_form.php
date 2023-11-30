<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee Account Form</title>

  <!-- Add Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
  <h2 class="text-center mb-4">Employee Account Information</h2>

  <form action="process_form.php" method="post">
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
