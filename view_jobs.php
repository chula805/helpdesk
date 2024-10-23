<?php
session_start();
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Fetch all jobs based on the user's role
if ($_SESSION['role'] == 'operator') {
    $result = $conn->query("SELECT h.*, eq.*, e.name AS employee_name FROM helpdesk_calls h, employees e, equipment eq WHERE h.caller_id = e.employee_id AND h.equipment_id = eq.equipment_id");
} else if ($_SESSION['role'] == 'technician') {
    $result = $conn->query("SELECT *, p.status AS problem_status FROM problems p, helpdesk_calls h, employees e, equipment eq WHERE p.call_id = h.call_id AND h.caller_id = e.employee_id AND h.equipment_id = eq.equipment_id AND technician_id = {$_SESSION['user_id']}");
}

$role = $_SESSION['role'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <title>View Jobs</title>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>
    
    <div class="container mt-5">
        <h4>View Jobs</h4>

            <?php if ($role == 'operator'): ?>
                
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Job ID</th>
                        <th>Caller</th>
                        <th>Description</th>
                        <th>Equipment</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['call_id']}</td>
                            <td>{$row['employee_name']}</td>
                            <td>{$row['problem_description']}</td>
                            <td>{$row['make']} {$row['model']} - {$row['serial_number']}</td>
                            <td>{$row['status']}</td>
                            <td>
                               <center>
                                <a href='assign_problem.php?call_id={$row['call_id']}' class='btn btn-primary btn-sm'>Assign Problem</a>
                                <a href='resolve_problem.php?call_id={$row['call_id']}' class='btn btn-success btn-sm'>Resolve</a>
                               </center>
                            </td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>

            <?php endif; ?>

            <?php if ($role == 'technician'): ?>
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Call ID</th>
                            <th>Caller</th>
                            <th>Description</th>
                            <th>Equipment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>{$row['problem_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['problem_description']}</td>
                                <td>{$row['make']} {$row['model']} - {$row['serial_number']}</td>
                                <td>{$row['problem_status']}</td>
                                <td>
                                   <center>
                                    <a href='resolve_problem.php?call_id={$row['problem_id']}' class='btn btn-success btn-sm'>Resolve</a>
                                   </center>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
    
            <?php endif; ?>
    </div>
</body>
</html>
