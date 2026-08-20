<?php
session_start();
include 'header.php';
if (empty($_SESSION['cart']))
{
	header("location: cart.php");
	exit();
}
include 'connection.php';
if(!isset($_SESSION['user_email']))
{
	$_SESSION['error']="You must be logged in to proceed to checkout";
	header("location: login.php");
	exit();
}
?>
<div class="container">
    <h2>Checkout</h2>
    <h4>Billing information</h4>
    <form action="mock-payment.php" method="POST">
        <div class="form-group">
            <label for="full_name">Full Name:</label>
            <input type="text" name="full_name" id="full_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="address">Shipping Address:</label>
            <input type="text" name="address" id="address" class="form-control" required>
        </div>
        <h4 class="mt-4">Order Summary</h4>
        <ul class="list-group mb-3">
			<?php
			$totalPrice=0;
			foreach($_SESSION['cart'] as $productId=>$qty)
				{
					$stmt = $connection->prepare("SELECT * FROM products WHERE id=?");
					$stmt->bind_param("i",$productId);
					$stmt->execute();
					$product=$stmt->get_result()->fetch_assoc();
					$subtotal=$product['price']*$qty;
					$totalPrice+=$subtotal;
			?>
					<li class="list-group-item d-flex justify-content-between">
						<?= htmlspecialchars($product['name']);?> (x<?= $qty?>)
						<strong>£<?= number_format($subtotal,2);?></strong>
					</li>
			<?php } 
			
			?>
        </ul>
		<h4>Total: £ <?= number_format($totalPrice,2);?></h4>
		<button type="submit" class="btn btn-primary">Proceed to payment</button>
    </form>
</div>

<?php include 'footer.php'; ?>
