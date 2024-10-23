
<?php
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$role = $_SESSION['role'];
?>



<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #000;">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Help Desk System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">

       
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php if ($role == 'admin'): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php"><i class="bi bi-person-circle"></i> Manage Users</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Reports
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="operator_job_report.php">Operator Job Report</a></li>
                            <li><a class="dropdown-item" href="technician_job_report.php">Technician Job Report</a></li>
                        </ul>
                    </li>
                    
                <?php endif; ?>

                <?php if ($role == 'office'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="offices.php"><i class="bi bi-building"></i> Manage Offices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="employees.php"><i class="bi bi-people"></i> Manage Employees</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="equipment.php"><i class="bi bi-laptop"></i> Manage Equipment</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-file-earmark-spreadsheet"></i> Reports
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="operator_job_report.php">Operator Job Report</a></li>
                            <li><a class="dropdown-item" href="technician_job_report.php">Technician Job Report</a></li>
                        </ul>
                    </li>

                <?php endif; ?>


                <?php if ($role == 'operator'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="log_call.php"><i class="bi bi-telephone-x-fill"></i> Log New Call</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_jobs.php"><i class="bi bi-telephone-inbound"></i> View All Jobs</a>
                    </li>
                <?php endif; ?>

                <?php if ($role == 'technician'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="view_jobs.php">View Assigned Jobs</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#"><i class="bi bi-person"></i> Welcome, <?php echo $_SESSION['username']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

