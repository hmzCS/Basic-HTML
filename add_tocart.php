<?php
session_start();
include 'connection.php';
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['product_id'],$_POST['quantity']))
{
    $productID=intval($_POST['product_id']);
    $quantity=intval($_POST['quantity']);

    $stmt=$connection->prepare("SELECT stock FROM products WHERE id= ?");
    $stmt->bind_param("i",$productID);
    $stmt->execute();
    $stmt->bind_result($stock);
    $stmt->fetch();
    $stmt->close();

if ($quantity>$stock)
{
    $_SESSION['cart_error']="You cannot add more than the available ({$stock}) remaining.";
    echo $_SESSION['cart_error'];
//    header("Location: product.php?id=".$productID);
	header("Location: cart.php");
    exit();
}

if (!isset($_SESSION['cart'][$productID]))
{
    $_SESSION['cart'][$productID]=$quantity;
}
else
{
    if ($_SESSION['cart'][$productID]+$quantity>$stock)
    {
        $_SESSION['cart_error']="You cannot add more than the available stock remaining.";
//        header("Location: product.php?id=".$productID);
		header("Location: cart.php");
        exit();
    }
    $_SESSION['cart'][$productID]+=$quantity;
}
$_SESSION['cart_success']="Product added to cart!";
header("Location:cart.php");

}
?>
