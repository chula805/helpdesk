<?php
session_start();
include 'config/db.php';

// Check if user is admin
if ($_SESSION['role'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}

// Add new user
if (isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $full_name = $_POST['full_name'];
    $contact_phone = $_POST['contact_phone'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, full_name, contact_phone, email) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $password, $role, $full_name, $contact_phone, $email);

    if ($stmt->execute()) {
        $success_message = "User added successfully!";
    } else {
        $error_message = "Error deleting user!";
    }
}

// Delete user
if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $success_message = "User delete successfully!";
    } else {
        $error_message = "Error deleting user!";
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
    <title>Manage Users</title>
</head>
<body>
    <!-- Include the Navbar -->
    <?php include 'nav.php'; ?>
    
    <div class="container mt-5">
        <h4>Manage Users</h4>

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
            <button type="button" class="btn btn-warning mt-2 mb-3" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-circle"></i> Add New User
            </button>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM users");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['username']}</td>
                        <td>{$row['full_name']}</td>
                        <td>{$row['role']}</td>
                        <td>{$row['contact_phone']}</td>
                        <td>{$row['email']}</td>
                        <td>
                            <center>
                            <a href='manage_users.php?delete_user={$row['user_id']}' class='btn btn-danger btn-sm'>Delete</a>
                            </center>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>



     <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required pattern="^[a-zA-Z0-9_]{3,15}$" title="Username should be 3-15 characters long and contain only letters, numbers, and underscores.">
                        </div>
                        
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8" title="Password must be at least 8 characters long.">
                        </div>
                        
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">Select Role</option>
                                <option value="operator">Operator</option>
                                <option value="technician">Technician</option>
                                <option value="office">Office Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" class="form-control" required minlength="3" maxlength="50" title="Full name should be between 3 and 50 characters long.">
                        </div>
                        
                        <div class="form-group">
                            <label>Contact Phone</label>
                            <input type="text" name="contact_phone" class="form-control" required pattern="^\+?[0-9]{10}$" title="Enter a valid phone number. ">
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="d-flex justify-content-center">
                            <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>

        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script>
            document.querySelector('form').addEventListener('submit', function(event) {
                let password = document.querySelector('input[name="password"]').value;
                if (password.length < 8) {
                    alert("Password must be at least 8 characters long.");
                    event.preventDefault();  // Stop form submission
                }
                
                let contactPhone = document.querySelector('input[name="contact_phone"]').value;
                let phonePattern = /^\+?[0-9]{10}$/;
                if (!phonePattern.test(contactPhone)) {
                    alert("Please enter a valid phone number.");
                    event.preventDefault();  // Stop form submission
                }
            });
        </script>

</body>
</html>
