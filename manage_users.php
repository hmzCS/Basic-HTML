<?php
session_start();
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['user_email']) || $_SESSION['admin'] != 1) {
    header("Location: index.php");
    exit();
}

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$roleFilter = isset($_GET['role']) ? intval($_GET['role']) : -1;

$sql = "SELECT email, firstName, surname, admin FROM users WHERE 1=1";
$params = [];
$types = "";

// Search by name or email
if (!empty($searchQuery)) {
    $sql .= " AND (firstName LIKE ? OR surname LIKE ? OR email LIKE ?)";
    $like = "%{$searchQuery}%";
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
    $types .= "sss";
}

// Filter by role
if ($roleFilter == 0 || $roleFilter == 1) {
    $sql .= " AND admin = ?";
    $params[] = $roleFilter;
    $types .= "i";
}

$sql .= " ORDER BY email ASC";

$stmt = $connection->prepare($sql);

if (!$stmt) {
    die("SQL ERROR: " . $connection->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$usersQuery = $stmt->get_result();
?>

<div class="container mt-4">
    <h2>Manage Users</h2>

    <?php if (isset($_SESSION['admin_success'])): ?>
        <p class="text-success"><?= $_SESSION['admin_success']; unset($_SESSION['admin_success']); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION['admin_error'])): ?>
        <p class="text-danger"><?= $_SESSION['admin_error']; unset($_SESSION['admin_error']); ?></p>
    <?php endif; ?>

    <form action="manage_users.php" method="GET" class="mb-4">
        <div class="row">

            <div class="col-md-4">
                <input type="search" name="search" class="form-control"
                       placeholder="Search by name or email"
                       value="<?= htmlspecialchars($searchQuery); ?>">
            </div>

            <div class="col-md-4">
                <select name="role" class="form-control">
                    <option value="-1">All Roles</option>
                    <option value="0" <?= ($roleFilter === 0) ? 'selected' : ''; ?>>User</option>
                    <option value="1" <?= ($roleFilter === 1) ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>

            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>

        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = $usersQuery->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['firstName'] . " " . $row['surname']); ?></td>
                        <td><?= ($row['admin'] == 1) ? "Admin" : "User"; ?></td>

                        <td>
                            <a href="edit_user.php?email=<?= urlencode($row['email']); ?>"
                               class="btn btn-warning btn-sm">Edit</a>

<!--
                            <a href="delete_user.php?email=<?= urlencode($row['email']); ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this user?')">
                               Delete
-->
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

        </table>
    </div>

    <a href="admin.php" class="btn btn-secondary mb-5">Back to admin console</a>
</div>

<?php include('footer.php'); ?>

