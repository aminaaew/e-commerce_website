<?php
// Database connection setup
try {
    $pdo = new PDO("mysql:host=localhost;dbname=app_db", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Handle form submission to add a user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
    $stmt->execute([
        ':email' => $email,
        ':password' => $hashed_password,
        ':role' => $role
    ]);

    header("Location: index.php?msg=User added successfully&type=success");
    exit;
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([':id' => $delete_id]);

    header("Location: index.php?msg=User deleted successfully&type=success");
    exit;
}

// Handle edit operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $id = intval($_POST['id']);
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Update with a new password if provided
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET email = :email, password = :password, role = :role WHERE id = :id");
        $stmt->execute([
            ':email' => $email,
            ':password' => $hashed_password,
            ':role' => $role,
            ':id' => $id
        ]);
    } else {
        // Update without changing the password
        $stmt = $pdo->prepare("UPDATE users SET email = :email, role = :role WHERE id = :id");
        $stmt->execute([
            ':email' => $email,
            ':role' => $role,
            ':id' => $id
        ]);
    }

    header("Location: index.php?msg=User updated successfully&type=success");
    exit;
}

// Fetch all users from the "users" table
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container pt-4">
        <h1 class="text-center">Admin Dashboard</h1>

        <!-- Notification Messages -->
        <?php if (isset($_GET['msg'])) : ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['type'] ?? 'info'); ?> alert-dismissible fade show">
                <?= htmlspecialchars($_GET['msg']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Add User Form -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Add New User</h5>
                <form method="POST" action="index.php">
                    <input type="hidden" name="add_user" value="1">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select name="role" id="role" class="form-select" required>
                            <option value="" disabled selected>Select a role</option>
                            <option value="admin">Admin</option>
                            <option value="guest">Guest</option>
                            <option value="editor">Editor</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Add User</button>
                </form>
            </div>
        </div>

        <!-- User Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Password (Hashed)</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']); ?></td>
                        <td><?= htmlspecialchars($user['email']); ?></td>
                        <td><?= htmlspecialchars($user['password']); ?></td>
                        <td><?= htmlspecialchars($user['role']); ?></td>
                        <td class="text-center">
                            <!-- Edit Modal Trigger -->
                            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#editModal<?= $user['id']; ?>">
                                <i class="bi bi-pencil-square"></i> Edit
                            </button>
                            <!-- Delete Link -->
                            <a href="index.php?delete_id=<?= htmlspecialchars($user['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal<?= $user['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $user['id']; ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="index.php">
                                <input type="hidden" name="edit_user" value="1">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']); ?>">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $user['id']; ?>">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="email<?= $user['id']; ?>" class="form-label">Email</label>
                                            <input type="email" name="email" id="email<?= $user['id']; ?>" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password<?= $user['id']; ?>" class="form-label">New Password (leave blank to keep current password)</label>
                                            <input type="password" name="password" id="password<?= $user['id']; ?>" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label for="role<?= $user['id']; ?>" class="form-label">Role</label>
                                            <select name="role" id="role<?= $user['id']; ?>" class="form-select" required>
                                                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                                <option value="guest" <?= $user['role'] === 'guest' ? 'selected' : ''; ?>>Guest</option>
                                                <option value="editor" <?= $user['role'] === 'editor' ? 'selected' : ''; ?>>Editor</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
