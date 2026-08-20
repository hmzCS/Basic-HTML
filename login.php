<?php include('header.php'); ?>
<?php session_start();?>
<div class="container">
    <div class="mb-4 mt-4" style="background-color: #efefef; padding: 20px;">
        <?php
            if (isset($_SESSION['error'])) 
			{
                echo '<p style="color:red;">' . htmlspecialchars($_SESSION['error']) . '</p>';
                unset($_SESSION['error']);
            }
        ?>
        <form action="signin.php" method="post" id="signin">
            <div class="form-outline mb-4">
				<input type="email" name="email" id="emailInput" class="form-control" required>
                <label class="form-label" for="emailInput">Email</label>
            </div>

            <div class="form-outline mb-4">
				<input type="password" name="password" id="passwordInput" class="form-control" required>
                <label class="form-label" for="passwordInput">Password</label>
            </div>
            <input type="submit" class="btn btn-primary mb-2" value="Log In">
<!--			<p><a href = "forgotPassword.php">Forgot Password</a></p>-->
			<p>Not a Member?<a href="register.php">Register</a></p>
        </form>
    </div>
</div>
