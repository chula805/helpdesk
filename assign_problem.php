<?php
session_start();
include 'config/db.php';

if ($_SESSION['role'] != 'operator') {
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['assign_problem'])) {
    $call_id = $_POST['call_id'];
    $technician_id = $_POST['technician_id'];
    $problem_type = $_POST['problem_type'];

    $stmt = $conn->prepare("INSERT INTO problems (call_id, technician_id, problem_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $call_id, $technician_id, $problem_type);

    if ($stmt->execute()) {
        $conn->query("UPDATE helpdesk_calls SET status='closed' WHERE call_id=$call_id");
        $success_message = "Problem assigned successfully!";
    } else {
        $error_message = "Error assigning problem!";
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
    <title>Assign Problem</title>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>
    
    <div class="container mt-5">
        <h4>Assign Problem</h4>

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
                <label>Technician</label>
                <select name="technician_id" class="form-control">
                    <?php
                    $techs = $conn->query("SELECT * FROM users WHERE role='technician'");
                    while ($row = $techs->fetch_assoc()) {
                        echo "<option value='{$row['user_id']}'>{$row['full_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Problem Type</label>
                <input type="text" name="problem_type" class="form-control" required maxlength="500" minlength="3">
            </div>
            <input type="hidden" name="call_id" value="<?php echo $_GET['call_id']; ?>">

            <div class="d-flex justify-content-center">
                <button type="submit" name="assign_problem" class="btn btn-primary">Assign</button>
            </div>

        </form>
    </div>
</body>
</html>
