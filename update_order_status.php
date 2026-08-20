<?php
session_start();
include 'connection.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1)
{
    header("Location:index.php");
    exit();
}

$orderId = $_POST['order_id'];
$newStatus = $_POST['order_status'];

$stmt = $connection->prepare("UPDATE orders SET order_status = ? WHERE id= ?");
$stmt->bind_param("si", $newStatus, $orderId);
$stmt->execute();
$stmt->close();

$_SESSION["order_update_success"]="Order updated succesfully";
header("Location: admin_orders.php");
exit();
?>
