<?php
session_start();
include 'connection.php';
include 'header.php';
if (!isset($_SESSION['user_email']) || $_SESSION['admin'] != 1)
    {
        header("Location: index.php");
        exit();
    }	
$searchEmail = isset($_GET['search_email']) ? trim($_GET['search_email']) : '';
$searchStatus = isset($_GET['search_status']) ? trim($_GET['search_status']) : '';
$searchStartDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$searchEndDate = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

$sql = "SELECT * FROM orders WHERE 1=1";
$params=[];
$types="";

if (!empty($searchEmail))
{
    $sql .=" AND user_email LIKE ?";
    $param[] = "%{$searchEmail}%";
    $types .="s";
}

if (!empty($searchStatus))
{
    $sql .=" AND order_status = ?";
    $param[] = $searchStatus;
    $types .="s";
}

if (!empty($searchStartDate) && !empty($searchEndDate))
{
    $sql .=" AND order_date BETWEEN ? AND ?";
    $param[] = $searchStartDate;
    $param[] = $searchEndDate;
    $types .="ss";
}

$stmt = $connection->prepare($sql);
if (!empty($params))
{
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$orders = $stmt->get_result();



?>

<div class="container mt-4">
    <h2>Admin - Manage Orders</h2>
	  <?php if(isset($_SESSION['admin_success'])):?>
	  <p class ="text-success"><?= $_SESSION['order_update_success'];
		  unset($_SESSION['order_update_success']);?></p>
  	  <?php endif;?>
    <form action="admin_orders.php" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <input type="text" name="search_email" class="form-control" placeholder="Search Email" value="<?= htmlspecialchars($searchEmail);?>">
            </div>
            <div class="col-md-2">
                <select name="search-status" class="form-control" placeholder="Search Email">
					<option value="">All statuses</option>
					<option value="Processing" <?= ($searchStatus == "Processing") ? 'selected': '';?>>Processing</option>
					<option value="Shipped" <?= ($searchStatus == "Shipped") ? 'selected': '';?>>Shipped</option>
					<option value="Delivered" <?= ($searchStatus == "Delivered") ? 'selected': '';?>>Delivered</option>
					<option value="Cancelled" <?= ($searchStatus == "Cancelled") ? 'selected': '';?>>Cancelled</option>
				</select>
            </div>
            <div class="col-md-2">
                <input type="date" name="start_date" class="form-control" value="<?=$searchStartDate;?>">
            </div>
            <div class="col-md-2">
                <input type="date" name="end_date" class="form-control" value="<?=$searchEndDate;?>">
            </div>
            <div class="col-md-3">
				<button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>	
    </form>
	
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Order Id</th>
				<th>User Email</th>
				<th>Date</th>
				<th>Total</th>
				<th>Status</th>
				<th>Update Status</th>
			</tr>
		</thead>
		<tbody>
			<?php while ($order = $orders->fetch_assoc()): ?>
				<tr>
					<td><?= $order['id']; ?></td>
					<td><?= htmlspecialchars($order['user_email']); ?></td>
					<td><?= $order['order_date']; ?></td>
					<td>£<?= number_format($order['total_price'], 2); ?></td>
					<td><?= htmlspecialchars($order['order_status']); ?></td>
					<td>
						<form action ="update_order_status.php" method="POST">
							<input  type="hidden" name="order_id" value="<?=$order['id'];?>">
						<select name="order_status" class="form-control">
							<option value="Processing" <?= ($order["order_status"] == "Processing") ? 'selected': '';?>>Processing</option>	
							<option value="Shipped" <?= ($order["order_status"] == "Shipped") ? 'selected': '';?>>Shipped</option>
							<option value="Delivered" <?= ($order["order_status"] == "Delivered") ? 'selected': '';?>>Delivered</option>
							<option value="Cancelled" <?= ($order["order_status"] == "Cancelled") ? 'selected': '';?>>Cancelled</option>
						</select>
							<button type="submit" class="btn btn-primary btn-sm mt-2">Update</button>
							</select>
						</form>
					</td>
				</tr>
			<?php endwhile; ?>

		</tbody>
	</table>
	<a href="admin.php" class="btn btn-secondary mb-5">Back to admin console</a>
</div>

<?php include 'footer.php'; ?>
