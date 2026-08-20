<?php 
session_start();
include ('header.php');


	
?>

<?php

    if (isset($_SESSION['user_email'])):
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
					<form action="update_profile.php" method="post" enctype="multipart/form-data">
					<?php
					$emailFilename= preg_replace("/[^a-zA-Z0-9]/","_",$_SESSION['user_email']) . ".png";
					$profilePicPath="images/profilePics/".$emailFilename;
					$profilePic= file_exists($profilePicPath)? $profilePicPath:"images/profilePics/base.png";
					?>
					<img src="<?=htmlspecialchars($profilePic)?>" alt = "Profile Pic" class="img-thumbnail img-fluid" style="max-width:200px; max-height:200px">
					
					<div class="form-group mt-3">
						<label for="profilePic">Upload New Profile Picture:</label>
						<input type="file" name="profilePic" id="profilePic" class="form-control file">
					</div>
						
					<div class="form-group">
					  <label for="forename">First Name:</label>
					  <input type="text" name="forename" id="forename" class="form-control" value="<?php echo htmlspecialchars($_SESSION["user_forename"]); ?>" required>
					</div>
						
					<div class ="form-group">
						<label for ="surname">Surname:</label>
						<input type="text" name="surname" id="surname" class="form-control" value="<?=
						htmlspecialchars($_SESSION["user_surname"]);?>" required>
					</div>

					<div class ="form-group">
						<label for ="email">Email:</label>
						<input type="text" name="email" id="email" class="form-control" value="<?=
						htmlspecialchars($_SESSION["user_email"]);?>" required>
					</div>
					<button type="submit" class="btn btn-primary mt-3">Update Profile</button>						
				</form>
			  </div>
			</div>
			
			<div class="card mb-4">
				<div class="card-body">
					<h4 class="mb-3">Change Password</h4>
					<form action="update_profile.php" method="post">
						<input type="hidden" name="update_type" value="change_password">
						<div class="form-group">
							<label for="currentPassword">Current Password: </label>
							<input type="password" name="currentPassword" id="currentPassword" class="form-control" required>
						</div>

						<div class="form-group">
							<label for="newPassword">New Password: </label>
							<input type="password" name="newPassword" id="newPassword" class="form-control" required>
						</div>

						<div class="form-group">
							<label for="confirmPassword">Confirm Password: </label>
							<input type="password" name="confirmPassword" id="confirmPassword" class="form-control" required>
						</div>
						<button type="submit" class="btn btn-primary mt-3">change Passwords</button>
					</form>
				</div>
			</div>
			
			<div class="card">
			  <div class="card-body">
				<h4 class="mb-3">Your actions</h4>
				<div class="d-flex flex-wrap gap-3">
				  <a href="customersorders.php" class="btn btn-primary">View Your Orders</a>
				  <?php if ($_SESSION['admin'] == 1): ?>
					<a href="admin.php" class="btn btn-danger">Admin Console</a>
				  <?php endif; ?>
				  <a href="logout.php" class="btn btn-warning">Logout</a>
				</div>
			  </div>
			</div>

		</div>
	<?php else:?>

		<p class="text-center mt-4">You are not logged in. Please <a href="login.php">Log in</a>.</p>

	<?php endif;?>

<?php include ('footer.php')?>
