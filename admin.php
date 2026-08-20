<?php
session_start();
include 'connection.php';
if (!isset($_SESSION['user_email']) || $_SESSION['admin'] != 1){
    header('Location: index.php');
    exit();
}
include 'header.php';
?>
<div class="container mt-4 mb-5 pb-5">
    <h2>Admin Console</h2>
    <?php
    if(isset($_SESSION['admin_success'])):?>
        <p class="text-success"><?= $_SESSION['admin_success']; unset($_SESSION['admin_success']);?></p>
    <?php endif;?>
    <?php
    if(isset($_SESSION['admin_error'])):?>
        <p class="text-danger"><?= $_SESSION['admin_error']; unset($_SESSION['admin_error']);?></p>
    <?php endif;?>
    <div class="card mb-5">
        <div class="card-body">
            <h4 class="mb-3">Admin Actions</h4>
            <div class="d-flex flex-wrap gap-3">
                <a href="manage_products.php" class="btn btn-primary">Manage Products</a>
                <a href="add_product.php" class="btn btn-secondary">Add Products/Category</a>
                <a href="update_order_status.php" class="btn btn-warning">Update Orders</a>
				<a href="manage_users.php" class="btn btn-info">Manage Users</a>
            </div>
        </div>
    </div>
	<a href="account.php" class="btn btn-secondary mb-5">Back to account</a>
</div>
<?php include 'footer.php'?>
