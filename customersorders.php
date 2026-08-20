<?php
session_start();
include 'connection.php';
include 'header.php';
if (!isset($_SESSION['user_email']))
    {
        header("Location: login.php");
        exit();
    }
$userEmail=$_SESSION['user_email'];
mysqli_report(MYSQLI_REPORT_ERROR|MYSQLI_REPORT_STRICT);
$orderStmt=$connection->prepare("SELECT * From orders WHERE user_email= ? ORDER BY order_date DESC");
$orderStmt->bind_param("s", $userEmail);
$orderStmt->execute();
$orders = $orderStmt->get_result();
$orderStmt->close();
?>		
<div class="container mt-4">
    <h2>My Orders</h2>
    <?php if ($orders->num_rows > 0): ?>
        <p>Orders found</p>
		<div class="table-responsive">
			<table class="table table-border">
				<thead>
					<tr>
						<th>Order Id</th>
						<th>Date</th>
						<th>Total (£)</th>
						<th>Status</th>
						<th>Details</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($order=$orders->fetch_assoc()):?>
					<tr>
						<td><?= $order['id']; ?></td>
						<td><?= $order['order_date']; ?></td>
						<td><?= number_format($order['total_price'],2); ?></td>
						<td><?= htmlspecialchars($order['order_status']); ?></td>
						<td><a href="order_details.php?id=<?=$order['id'];?>" class="btn btn-primary btn-sm">View</a></td>
					</tr>
					<?php	 endwhile;?>
				</tbody>
			</table>
			<a href="account.php" class="btn btn-secondary mb-5">Back to account</a>
		</div>
    <?php else: ?>
        <p class="text-muted">You have not placed any orders yet.</p>
    <?php endif;?>
</div>

<?php
include 'footer.php';
?>