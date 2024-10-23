<?php
session_start();
include 'config/db.php';

// Check if user is an office
if ($_SESSION['role'] != 'office') {
    header('Location: dashboard.php');
    exit;
}

// Add new office
if (isset($_POST['add_office'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact_phone = $_POST['contact_phone'];
    $specialisation = $_POST['specialisation'];

    $stmt = $conn->prepare("INSERT INTO offices (name, address, contact_phone, specialisation) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $address, $contact_phone, $specialisation);
    
    if ($stmt->execute()) {
        $success_message = "Office added successfully!";
    } else {
        $error_message = "Error adding office!";
    }
}

// Delete office
if (isset($_GET['delete_office'])) {
    $office_id = $_GET['delete_office'];
    $stmt = $conn->prepare("DELETE FROM offices WHERE office_id = ?");
    $stmt->bind_param("i", $office_id);
    if ($stmt->execute()) {
        $success_message = "Office delete successfully!";
    } else {
        $error_message = "Error deleting office!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css">
    <title>Manage Offices</title>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <h4>Manage Offices</h4>

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

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-warning mt-2 mb-3" data-bs-toggle="modal" data-bs-target="#addOfficeModal">
                <i class="bi bi-plus-circle"></i> Add New Office
            </button>
        </div>

        <table class="table table-bordered mb-5">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Contact Phone</th>
                    <th>Specialisation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM offices");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['address']}</td>
                        <td>{$row['contact_phone']}</td>
                        <td>{$row['specialisation']}</td>
                        <td>
                            <center>
                            <a href='offices.php?delete_office={$row['office_id']}' class='btn btn-danger btn-sm'>
                                <i class='bi bi-trash'></i> Delete
                            </a>
                            </center>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


    
        <!-- Modal -->
        <div class="modal fade" id="addOfficeModal" tabindex="-1" aria-labelledby="addOfficeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOfficeModalLabel">Add New Office</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Add new office form inside modal -->
                        <form method="POST">
                            <div class="form-group mb-3">
                                <label>Office Name</label>
                                <input type="text" name="name" class="form-control" required maxlength="100" minlength="2">
                            </div>
                            <div class="form-group mb-3">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" required maxlength="255">
                            </div>
                            <div class="form-group mb-3">
                                <label>Contact Phone</label>
                                <input type="text" name="contact_phone" class="form-control" required pattern="[0-9]{10}">
                            </div>
                            <div class="form-group mb-3">
                                <label>Specialisation</label>
                                <input type="text" name="specialisation" class="form-control" required maxlength="100">
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" name="add_office" class="btn btn-primary">Add Office</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>


    
</body>
</html>
