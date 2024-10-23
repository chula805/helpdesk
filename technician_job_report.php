<?php
session_start();
include 'config/db.php';

// Check if user role
if ($_SESSION['role'] != 'office' && $_SESSION['role'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}

$result = $conn->query("SELECT *, p.status AS problem_status FROM problems p, helpdesk_calls h, employees e, equipment eq WHERE p.call_id = h.call_id AND h.caller_id = e.employee_id AND h.equipment_id = eq.equipment_id");

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">

    <title>Reports</title>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>
    
    <div class="container mt-5">
        <h4>Technician Job Report</h4>
        
        <div class="table-responsive">
            <table id="jobReportTable" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Call ID</th>
                        <th>Caller</th>
                        <th>Technician ID</th>
                        <th>Description</th>
                        <th>Equipment</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['problem_id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['technician_id']}</td>
                            <td>{$row['problem_description']}</td>
                            <td>{$row['make']} {$row['model']} - {$row['serial_number']}</td>
                            <td>{$row['problem_status']}</td>
                        </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS for Bootstrap 4 -->
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <!-- JSZip (required for Excel export) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- Buttons for Excel Export -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#jobReportTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: 'Export to Excel',
                    className: 'btn btn-success' 
                }
            ]
        });
    });
    </script>
</body>
</html>
