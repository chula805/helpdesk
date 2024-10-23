<?php
session_start();
include 'config/db.php';

if ($_SESSION['role'] != 'office') {
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['add_employee'])) {
    $office_id = $_POST['office_id'];
    $name = $_POST['name'];
    $contact_phone = $_POST['contact_phone'];
    $email = $_POST['email'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $job_title = $_POST['job_title'];
    $department = $_POST['department'];

    $stmt = $conn->prepare("INSERT INTO employees (office_id, name, contact_phone, email, start_date, end_date, job_title, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssss", $office_id, $name, $contact_phone, $email, $start_date, $end_date, $job_title, $department);

    if ($stmt->execute()) {
        $success_message = "Employee added successfully!";
    } else {
        $success_message = "Error adding employee!";
    }
}

if (isset($_GET['delete_employee'])) {
    $employee_id = $_GET['delete_employee'];
    $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
    $stmt->bind_param("i", $employee_id);
    if ($stmt->execute()) {
        $success_message = "Employee deleted successfully!";
    } else {
        $success_message = "Error deleting employee!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <title>Manage Employees</title>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>
    
    <div class="container mt-5">
        <h4>Manage Employees</h4>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success">
                <?= $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?= $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-end mb-4">
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="bi bi-plus-circle"></i> Add New Employee
            </button>
        </div>

 
        <table class="table table-bordered mb-5">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact Phone</th>
                    <th>Email</th>
                    <th>Office</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Job Title</th>
                    <th>Department</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT e.*, o.name as office_name 
                                        FROM employees e
                                        JOIN offices o ON e.office_id = o.office_id");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['contact_phone']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['office_name']}</td>
                        <td>{$row['start_date']}</td>
                        <td>{$row['end_date']}</td>
                        <td>{$row['job_title']}</td>
                        <td>{$row['department']}</td>
                        <td>
                            <center>
                                <a href='employees.php?delete_employee={$row['employee_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </center>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


     <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Office</label>
                                <select name="office_id" class="form-control" required>
                                    <?php
                                    $offices = $conn->query("SELECT * FROM offices");
                                    while ($row = $offices->fetch_assoc()) {
                                        echo "<option value='{$row['office_id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control" required pattern="[0-9]{10}">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Job Title</label>
                                <input type="text" name="job_title" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Department</label>
                                <input type="text" name="department" class="form-control" required>
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" name="add_employee" class="btn btn-primary">Add Employee</button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
