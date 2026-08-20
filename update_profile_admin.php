<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['user_email'])){
    $_SESSION['update_error']="You need to be logged in to update your profile.";
    header("Location:account.php");
}

	$email= $_POST['currentEmail'];
	$stmt=$connection->prepare("UPDATE users SET admin = 1 WHERE email=?");
	$stmt->bind_param("s",$email);

	if ($stmt->execute()){
		$_SESSION['update_success']="User created as admin successfully.";
	}else{
		$_SESSION['update_error']="Failed to create user as admin.";
	}
	$stmt->close();
	header("Location: account.php");
	exit();




?>
