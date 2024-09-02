<?php
require 'db_connect.php';

// Fetch roles for the dropdown
$roles = [];
$roleQuery = "SELECT role_id, role_name FROM roles";
$roleResult = $conn->query($roleQuery);
if ($roleResult && $roleResult->num_rows > 0) {
    while ($row = $roleResult->fetch_assoc()) {
        $roles[] = $row;
    }
}

// Fetch existing users
$users = [];
$userQuery = "SELECT u.user_id, u.full_name, u.email, u.status, r.role_name, u.role_id 
              FROM users u 
              JOIN roles r ON u.role_id = r.role_id";
$userResult = $conn->query($userQuery);
if ($userResult && $userResult->num_rows > 0) {
    while ($row = $userResult->fetch_assoc()) {
        $users[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            padding: 50px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background-color: #800000;
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            color: #fff;
        }

        h1, .table th, .table td {
            color: #ffc300;
        }

        .btn {
            background-color: #ffc300;
            color: #800000;
            border: none;
        }

        .btn:hover {
            background-color: #e0b000;
        }

        .form-control {
            background-color: #f2f2f2;
            border: none;
            color: #800000;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #ffc300;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Management</h1>
        <p>Manage user roles, permissions, and activities.</p>

        <!-- Add New User Form -->
        <h2>Add New User</h2>
        <form id="userForm" method="POST" action="add_user.php">
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name:</label>
                <input type="text" id="full_name" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="employee_no" class="form-label">Employee Number:</label>
                <input type="text" id="employee_no" name="employee_no" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Role:</label>
                <select id="role_id" name="role_id" class="form-control" required>
                    <?php foreach ($roles as $role): ?>
                        <option value="<?= $role['role_id'] ?>"><?= $role['role_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="submit" class="btn" value="Add User">
        </form>

        <!-- User List Table -->
        <h2>Existing Users</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['status']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editUser(<?php echo $user['user_id']; ?>, '<?php echo htmlspecialchars($user['full_name']); ?>', '<?php echo htmlspecialchars($user['email']); ?>', <?php echo $user['role_id']; ?>)">Edit</button>
                            <button class="btn btn-sm btn-warning" onclick="deactivateUser(<?php echo $user['user_id']; ?>)">Deactivate</button>
                            <button class="btn btn-sm btn-danger" onclick="deleteUser(<?php echo $user['user_id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to show the edit user modal and populate the fields
        function editUser(userId, fullName, email, roleId) {
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_full_name').value = fullName;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role_id').value = roleId;

            document.getElementById('editUserModal').style.display = 'block';
        }

        // Close the modal when the user clicks anywhere outside of the modal
        window.onclick = function(event) {
            var modal = document.getElementById('editUserModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Function to deactivate a user
        function deactivateUser(userId) {
            if (confirm('Are you sure you want to deactivate this user?')) {
                fetch('deactivate_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'user_id=' + userId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deactivated successfully!');
                        location.reload(); // Refresh the page to show updated user list
                    } else {
                        alert('Error deactivating user: ' + data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Function to delete a user
        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user?')) {
                fetch('delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'user_id=' + userId
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('User deleted successfully!');
                        location.reload(); // Refresh the page to show updated user list
                    } else {
                        alert('Error deleting user: ' + data.error);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>
