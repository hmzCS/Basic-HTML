<?php
session_start();
include 'connection.php';
if(!isset($_SESSION['cart']) || empty($_SESSION['cart']))
{
    header("Location:cart.php");
    exit();
}

if(!isset($_SESSION['user_email']))
{
    header("Location:login.php");
    exit();
}

$userEmail = $_SESSION['user_email'];
$orderTotal = 0;
$orderStatus = "Processing";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if($connection->connect_error)
{
    die("Database connection failed ". $connection->connect_error);
}

$orderStmt = $connection->prepare("INSERT INTO orders (user_email, total_price, order_status, order_date) VALUES (?, ?, ?, NOW())");
if (!$orderStmt){
    die("Error preparing statement: ". $connection->error);
}
$orderStmt->bind_param("sds", $userEmail, $orderTotal, $orderStatus);
$orderStmt->execute();
$orderId = $orderStmt->insert_id;
$orderStmt->close();

foreach($_SESSION['cart'] as $productId=>$qty)
{
    $stmt = $connection->prepare("SELECT price, stock FROM products WHERE id=?");
    if (!$stmt)
    {
        die ("Error preparing product query". $connection->error);
    }

    $stmt->bind_param("i",$productId);
    $stmt->execute();
    $stmt->bind_result($productPrice,$stock);
    $stmt->fetch();
    $stmt->close();

	if ($qty>$stock)
	{
		$_SESSION['cart'][$productId]=$stock;
		$_SESSION['cart_error']="Stock updated. Only {$stock} available";
		header("Location:cart.php");
		exit();
	}

	$subTotal = $productPrice * $qty;
	$orderTotal += $subTotal;
	$orderItemStmt = $connection->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES(?,?,?,?)");
	if (!$orderItemStmt)
	{
		die("Error preparing order item query". $connection->error);
	}

	$orderItemStmt->bind_param("iiid", $orderId, $productId, $qty, $productPrice);
	$orderItemStmt->execute();
	$orderItemStmt->close();

	$stockStmt = $connection->prepare("UPDATE products SET stock = stock - ? WHERE id=?");
	if (!$stockStmt)
	{
		die("Error preparing update query". $connection->error);
	}
	$stockStmt->bind_param("ii",$qty,$productId);
	$stockStmt->execute();
	$stockStmt->close();
}

$updateOrderStmt = $connection->prepare("UPDATE orders SET total_price = ? WHERE id=?");
if (!$updateOrderStmt)
{
	die("Error preparing order total update query". $connection->error);
}
$updateOrderStmt->bind_param("di", $orderTotal, $orderId);
$updateOrderStmt->execute();
$updateOrderStmt->close();

unset($_SESSION['cart']);
$_SESSION['payment_status']="success";
$_SESSION['payment_message']="Your order has been placed successfully";
header("Location:mock_payment_success.php");
exit();
 	

?>