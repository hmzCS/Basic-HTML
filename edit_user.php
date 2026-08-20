<?php 
session_start();
include ('header.php');

if (!isset($_SESSION['user_email'])|| $_SESSION['admin']!=1){
    header("Location: index.php");
    exit();
}


$email=isset($_GET['email']) ? $_GET['email'] : null;
echo $email;
$stmt=$connection->prepare("SELECT * From users WHERE email= ?");
$stmt->bind_param("s",$email);
$stmt->execute();
$result = $stmt->get_result();
$user= $result->fetch_assoc();
$stmt->close();
print_r($user);
?>

<?php

    if (isset($_GET['email'])):
?>
		<div class="container mt-4 mb-5">
			<h2 class="mb-3">Account Details</h2>

			<?php
				if (isset($_SESSION['update_success'])):?>
				<p class="text-success"><?= $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
			<?php endif; ?>

			<?php if (isset($_SESSION['update_error'])):?>
				<p class="text-danger"><?= $_SESSION['update_error']; unset($_SESSION['update_error']); ?></p>
			<?php endif; ?>
			
			<div class="card mb-4">
			  	<div class="card-body">
					<h4 class="mb-4">Update Profile</h4>
							<form action="update_profile_admin.php" method="post" enctype="multipart/form-data">
					<?php
					$emailFilename= preg_replace("/[^a-zA-Z0-9]/","_",$user['email']) . ".png";
					$profilePicPath="images/profilePics/".$emailFilename;
					$profilePic= file_exists($profilePicPath)? $profilePicPath:"images/profilePics/base.png";
					?>
					<img src="<?=htmlspecialchars($profilePic)?>" alt = "Profile Pic" class="img-thumbnail img-fluid" style="max-width:200px; max-height:200px">
					<input type="hidden" name="currentEmail" id="currentEmail" class="form-control file" value="<?=$email?>">
						
					<div class="form-group">
					  <label for="forename">First Name2:</label>
					  <input type="text" name="forename" id="forename" class="form-control" value="<?php echo htmlspecialchars($user['firstName']); ?>" disabled>
					</div>
						
					<div class ="form-group">
						<label for ="surname">Surname:</label>
						<input type="text" name="surname" id="surname" class="form-control" value="<?=
						htmlspecialchars($user['surname']);?>" disabled>
					</div>

					<div class ="form-group">
						<label for ="email">Email:</label>
						<input type="text" name="email" id="email" class="form-control" value="<?=
						htmlspecialchars($user['email']);?>" disabled>
					</div>
					<button type="submit" class="btn btn-primary mt-3">Make an admin</button>						
				</form>
			  </div>
			</div>
			
	
			
			<div class="card">
			  <div class="card-body">
				<h4 class="mb-3">Your actions</h4>
				<div class="d-flex flex-wrap gap-3">
				  <a href="customersorders.php" class="btn btn-primary">Back to admin account</a>
				  <a href="account.php" class="btn btn-warning">Logout</a>
				</div>
			  </div>
			</div>

		</div>
	<?php else:?>

		<p class="text-center mt-4">You are not logged in. Please <a href="login.php">Log in</a>.</p>

	<?php endif;?>

<?php include ('footer.php')?>

