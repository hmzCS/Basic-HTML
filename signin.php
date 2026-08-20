<?php
session_start();
include 'connection.php';
$email = trim($_POST['email']);
$password = trim($_POST['password']);

if ($email && $password)
{
    $stmt = $connection->prepare("SELECT * From users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
	if ($user=$result->fetch_assoc())
	{
    if (password_verify($password,$user['password']))
    {
        $_SESSION['user_email'] = $user['email'];
		$_SESSION['user_forename'] = $user['firstName'];
		$_SESSION['user_surname'] = $user['surname'];
		$_SESSION['admin'] = $user['admin'];
		header("Location: account.php");
		exit();
    }
	else
	{
		$_SESSION['error']="Invalid Password. Please try again";
	}
	}
	else
	{
		$_SESSION['error']="No user found with this email address";
	}

}
else
{
    $_SESSION['error'] = "Please enter both e-mail and password";
}
$stmt->close();
$connection->close();
header("location: login.php");
exit();

?>