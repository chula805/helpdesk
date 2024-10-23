<?php
session_start();
include 'config/db.php';

if ($_SESSION['role'] != 'technician') {
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['resolve_problem'])) {
    $call_id = $_POST['call_id'];
    $resolution = $_POST['resolution'];
    $time_taken = $_POST['time_taken'];

    $stmt = $conn->prepare("UPDATE problems SET resolution_description=?, time_taken=?, status='resolved' WHERE call_id=?");
    $stmt->bind_param("ssi", $resolution, $time_taken, $call_id);

    if ($stmt->execute()) {
        $success_message = "Problem resolved successfully!";
    } else {
        $error_message = "Error resolving problem!";
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
    <title>Resolve Problem</title>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>
    
    <div class="container mt-5">
        <h4>Resolve Problem</h4>

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
                <label>Resolution Details</label>
                <textarea name="resolution" class="form-control" required maxlength="1000"></textarea>
                
            </div>
            <div class="form-group">
                <label>Time Taken (in hours)</label>
                <input type="number" name="time_taken" class="form-control" required min="0.1" step="0.1">
               
            </div>
            <input type="hidden" name="call_id" value="<?php echo $_GET['call_id']; ?>">
            <button type="submit" name="resolve_problem" class="btn btn-success">Resolve</button>
        </form>

    </div>
</body>
</html>
