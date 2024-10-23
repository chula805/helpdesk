<?php
session_start();
include 'config/db.php';

if ($_SESSION['role'] != 'operator') {
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['log_call'])) {
    $caller_id = $_POST['caller_id'];
    $operator_id = $_SESSION['user_id'];
    $problem_description = $_POST['problem_description'];
    $equipment_id = $_POST['equipment_id'];
    $operating_system = $_POST['operating_system'];
    $software_used = $_POST['software_used'];

    $stmt = $conn->prepare("INSERT INTO helpdesk_calls (caller_id, operator_id, problem_description, equipment_id, operating_system, software_used) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $caller_id, $operator_id, $problem_description, $equipment_id, $operating_system, $software_used);

    if ($stmt->execute()) {
        $success_message = "Call logged successfully!";
    } else {
        $error_message = "Error logging the call!";
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
    <title>Log Helpdesk Call</title>
</head>
<body>

    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>
    
    <div class="container mt-5">
        <h4>New Helpdesk Call Log</h4>

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


        <form method="POST">
            <div class="form-group">
                <label>Caller</label>
                <select name="caller_id" class="form-control">
                    <!-- Populate employees from database -->
                    <?php
                    $result = $conn->query("SELECT * FROM employees");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['employee_id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Problem Description</label>
                <textarea name="problem_description" class="form-control" required maxlength="500" minlength="5"></textarea>
            </div>
            <div class="form-group">
                <label>Equipment</label>
                <select name="equipment_id" class="form-control">
                    <!-- Populate equipment from database -->
                    <?php
                    $result = $conn->query("SELECT * FROM equipment");
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='{$row['equipment_id']}'>({$row['make']} {$row['model']}) - {$row['serial_number']} </option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Operating System</label>
                <input type="text" name="operating_system" class="form-control">
            </div>
            <div class="form-group">
                <label>Software Used</label>
                <input type="text" name="software_used" class="form-control">
            </div>
            

            <div class="d-flex justify-content-center">
                <button type="submit" name="log_call" class="btn btn-primary"> <i class="bi bi-plus-circle"></i> Log Call</button>
            </div>
        </form>
    </div>
</body>
</html>
