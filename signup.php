<?php
session_start();
include 'connection.php';

if (isset($_POST['email'], $_POST['forename'], $_POST['surname'], $_POST['password']))
{
    $hashedPassword = password_hash($_POST['password'],PASSWORD_DEFAULT);
	$stmt = $connection->prepare("INSERT INTO users(email,firstname,surname,password,admin) VALUES (?,?,?,?,'0')");
	$stmt->bind_param("ssss", $_POST['email'], $_POST['forename'], $_POST['surname'], $hashedPassword);
	if ($stmt->execute())
	{
    	$_SESSION['user_email']=$_POST['email'];
    	$_SESSION['user_forename']=$_POST['forename'];
    	$_SESSION['user_surname']=$_POST['surname'];
    	$_SESSION['admin']='0';
    	header('Location: account.php');
    	exit();
		
		
	}
	else
	{
    	if(strpos($stmt->error,"Duplicate entry")!==false)
    	{
        	$_SESSION['error']="The email address already exists. Please use a different e-mail to register";
    	}
    	else
		{
        	$_SESSION['error']="An unexpected error has occured. Please try again";
    	}
    	header('Location: register.php');
	}
}
else
{
    $_SESSION['error']="All field required";
    header('Location: register.php');
}
$stmt->close();
$connection->close();

?>
