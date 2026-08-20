<?php
session_start();
include 'connection.php';
include 'header.php';
$product_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$stmt = $connection->prepare("SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) 
{
    header('Location: products.php');
    exit();
}
$stmt->close();
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <img src="images/products/<?= htmlspecialchars($product['image']); ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($product['name']); ?>" style="max-height: 400px; object-fit: contain;">
        </div> 
        <div class="col-md-6">
            <h2><?= htmlspecialchars($product['name']); ?></h2>
            <p class="text-muted"><?= htmlspecialchars($product['category_name']); ?></p>
            <h4 class="text-primary">$<?= number_format($product['price'],2); ?></h4>
            <p><?= htmlspecialchars($product['description']); ?></p>
            <p class="<?= ($product['stock']>0)?'text-success':'text-danger'; ?>">
                <?= ($product['stock']>0)?'In stock ('.$product['stock'].') available':'Out of stock'; ?>
            </p>
			<?php if ($product['stock']> 0):?>
					<form action = "add_tocart.php" method = "POST">
						<input type="hidden" name="product_id" value="<?php echo $product['id']?>">
						<div class="form-group">
							<input type="number" name="quantity" id="quantity" class="form-control w-25" min="1" max="<?php echo $product['stock'];?>" value="1" required>
						</div>
						<button type="submit" class="btn btn-success mt-3">
							<i class="fa-solid fa-cart-plus"></i>
							Add to cart
						</button>
					</form>
			<?php else: ?>
					<button class="btn btn-secondary" disabled>Out of stock</button>
			<?php endif;?>
			
        </div>
    </div>
</div>
	