<?php
session_start();
include 'connection.php';

if(!isset($_SESSION['user_email'])){
    $_SESSION['update_error']="You need to be logged in to update your profile.";
    header("Location:account.php");
}

$currentEmail=$_SESSION['user_email'];
$updateType=isset($_POST['update_type'])?$_POST['update_type']:'profile_update';

if($updateType==="change_password")
{
    $currentPassword = isset($_POST['currentPassword'])? $_POST['currentPassword']:'';
    $newPassword = isset($_POST['newPassword'])? $_POST['newPassword']:'';
    $confirmPassword = isset($_POST['confirmPassword'])? $_POST['confirmPassword']:'';

    if(empty($currentPassword)||empty($newPassword)||empty($confirmPassword))
    {
        $_SESSION['update_error']="All password fields are required.";
        header("Location:acccount.php");
        exit();
    }
	if ($newPassword !== $confirmPassword){
		$_SESSION['update_error']="New passwords don't match.";
		header("Location:account.php");
		exit();
	}

	$stmt=$connection->prepare("SELECT password FROM users WHERE email=?");
	$stmt->bind_param("s", $currentEmail);
	$stmt->execute();
	$stmt->bind_result($storedPasswordHash);
	$stmt->fetch();
	$stmt->close();
	if(!$storedPasswordHash || !password_verify($currentPassword,$storedPasswordHash))
	{
		$_SESSION['update_error']="Current Password is incorrect.";
		header("Location:account.php");
		exit();
	}
	
	$newPasswordHash=password_hash($newPassword,PASSWORD_DEFAULT);
	$stmt=$connection->prepare("UPDATE users SET password = ? WHERE email=?");
	$stmt->bind_param("ss",$newPasswordHash,$currentEmail);

	if ($stmt->execute()){
		$_SESSION['update_success']="Password changed successfully.";
	}else{
		$_SESSION['update_error']="Failed to change password.";
	}
	$stmt->close();
	header("Location: account.php");
	exit();

}

$forename = isset($_POST['forename']) ? trim($_POST['forename']) : '';
$surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
$newEmail = isset($_POST['email']) ? trim($_POST['email']) : '';

if(empty($forename) || empty($surname) || empty($newEmail))
{
    $_SESSION['update_error'] = "All profile fields are required.";
    header("Location: account.php");
    exit();
}

if($newEmail !== $currentEmail)
{
    $stmt = $connection->prepare("SELECT email FROM users WHERE email=?");
    $stmt->bind_param("s", $newEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $emailExists = $result->num_rows > 0;
    $stmt->close();
}
if ($emailExists)
{
    $_SESSION['update_error']="That email already exists";
    header("Location: account.php");
    exit();
}

$stmt=$connection->prepare("UPDATE users SET firstName=?,surname=?, email=? WHERE email=?");
$stmt->bind_param("ssss", $forename,$surname,$newEmail,$currentEmail);

if ($stmt->execute())
{
    $_SESSION['user_forename']=$forename;
    $_SESSION['user_surname']=$surname;
    $_SESSION['user_email']=$newEmail;
    $_SESSION['update_success']="Profile Updated successfully";
}
else
{
    $_SESSION['update_error']="Failed to update profile details";
    $stmt->close();
    header("Location: account.php");
    exit();
}
$stmt->close();

if(!empty($_FILES['profilePic']['name']))
{
    $targetDir="images/profilePics/";

    if(!is_dir($targetDir))
    {
        mkdir($targetDir,0777,true);
    }
    $filename= preg_replace("/[^a-zA-Z0-9]/","_",$_SESSION['user_email']) . ".png";
    $targetFile=$targetDir . $filename;
    if(move_uploaded_file($_FILES['profilePic']['tmp_name'],$targetFile))
    {
        $_SESSION['profile_pic']= $targetFile;
        $_SESSION['update_success']="Profile Updated, including profile pciture";
    }
    else
    {
        $_SESSION['update_error']="Profile Details were updated, but profile pciture failed to upload";
    }
}
header("Location:account.php");
exit();


?>
