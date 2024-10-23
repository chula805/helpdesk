<?php
session_start();
include 'config/db.php';

if ($_SESSION['role'] != 'office') {
    header('Location: dashboard.php');
    exit;
}

if (isset($_POST['add_equipment'])) {
    $office_id = $_POST['office_id'];
    $serial_number = $_POST['serial_number'];
    $equipment_type = $_POST['equipment_type'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $manufacturer = $_POST['manufacturer'];
    $warranty_expiry_date = $_POST['warranty_expiry_date'];
    $software_licence_number = $_POST['software_licence_number'];
    $software_type = $_POST['software_type'];

    $stmt = $conn->prepare("INSERT INTO equipment (office_id, serial_number, equipment_type, make, model, manufacturer, warranty_expiry_date, software_licence_number, software_type) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssssss", $office_id, $serial_number, $equipment_type, $make, $model, $manufacturer, $warranty_expiry_date, $software_licence_number, $software_type);

    if ($stmt->execute()) {
        $success_message = "Equipment added successfully!";
    } else {
        $success_message = "Error adding equipment!";
    }
}

// Delete equipment
if (isset($_GET['delete_equipment'])) {
    $equipment_id = $_GET['delete_equipment'];
    $stmt = $conn->prepare("DELETE FROM equipment WHERE equipment_id = ?");
    $stmt->bind_param("i", $equipment_id);
    if ($stmt->execute()) {
        $success_message = "Equipment deleted successfully!";
    } else {
        $success_message = "Error deleting equipment!";
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
    <title>Manage Equipment</title>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>
    
    <div class="container mt-5">
        <h4>Manage Equipment</h4>

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
    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addEquipmentModal">
        <i class="bi bi-plus-circle"></i> Add New Equipment
    </button>
</div>

      

        <table class="table table-bordered mb-5">
            <thead>
                <tr>
                    <th>Serial Number</th>
                    <th>Equipment Type</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Manufacturer</th>
                    <th>Warranty Expiry</th>
                    <th>Software Licence</th>
                    <th>Office</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT e.*, o.name as office_name 
                                        FROM equipment e
                                        JOIN offices o ON e.office_id = o.office_id");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['serial_number']}</td>
                        <td>{$row['equipment_type']}</td>
                        <td>{$row['make']}</td>
                        <td>{$row['model']}</td>
                        <td>{$row['manufacturer']}</td>
                        <td>{$row['warranty_expiry_date']}</td>
                        <td>{$row['software_licence_number']}</td>
                        <td>{$row['office_name']}</td>
                        <td>
                            <a href='equipment.php?delete_equipment={$row['equipment_id']}' class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>


      <div class="modal fade" id="addEquipmentModal" tabindex="-1" aria-labelledby="addEquipmentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEquipmentModalLabel">Add New Equipment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="form-group">
                                <label>Office</label>
                                <select name="office_id" class="form-control" required>
                                    <option value="">Select Office</option>
                                    <?php
                                    $offices = $conn->query("SELECT * FROM offices");
                                    while ($row = $offices->fetch_assoc()) {
                                        echo "<option value='{$row['office_id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Serial Number</label>
                                <input type="text" name="serial_number" class="form-control" required pattern="[A-Za-z0-9]+" title="Serial number must be alphanumeric">
                            </div>
                            <div class="form-group">
                                <label>Equipment Type</label>
                                <select name="equipment_type" class="form-control" required>
                                    <option value="">Select Equipment Type</option>
                                    <option value="Hardware">Hardware</option>
                                    <option value="Software">Software</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Make</label>
                                <input type="text" name="make" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Model</label>
                                <input type="text" name="model" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Manufacturer</label>
                                <input type="text" name="manufacturer" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Warranty Expiry Date</label>
                                <input type="date" name="warranty_expiry_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Software Licence Number</label>
                                <input type="text" name="software_licence_number" class="form-control" required pattern="[A-Za-z0-9]+" title="Licence number must be alphanumeric">
                            </div>
                            <div class="form-group">
                                <label>Software Type</label>
                                <input type="text" name="software_type" class="form-control">
                            </div>

                            <div class="d-flex justify-content-center">
                                <button type="submit" name="add_equipment" class="btn btn-primary">Add Equipment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
