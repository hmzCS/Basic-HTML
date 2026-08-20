	<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>ByteMart.com</title>
<link rel = "stylesheet" href="css/bootstrap.min.css">
<script src="css/bootstrap.bundle.min.js"></script>
<link rel = "styesheet" href="css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel = "stylesheet" href="css/style.css">
<?php include("connection.php");?>
	
	
</head>

<body>
	<nav class="navbar navbar-expand-lg bg-info sticky-top" style= "background-color: #c2e2f7">
		<div class="container-fluid">
			<a class ="navbar-brand" href="index.php"> <img src="images/Logo.png" alt="Company Logo" style="width:auto; height:auto; max-height: 100px"></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class = "navbar-nav me-auto mb-2 mb-lg-0">
				<li class = "nav-item">
					<a class = "nav-link active" aria-current="page" href="index.php">Home</a>
				</li>
				<li class = "nav-item">	
					<a class = "nav-link" href="products.php">Products</a	>
				</li>
				</ul>
				<ul class = "navbar-nav ms-auto mb-2 mb-lg-0 d-flex flex-row align-items-center me-3">
					<li class= "nav-item">
						<a href="cart.php" class="nav-link me-2">
							<i class ="fa-solid fa-basket-shopping fs-3"></i>
						</a>
					<li class= "nav-item">
						<a href="account.php" class="nav-link">
							<i class ="fa-solid fa-user fs-3"></i>
						</a>
					</li>
				</ul>
				<form class= "d-flex" role="search" action="search.php" method="GET">
					<input class="Form-control me-2" type="search" name="query" placeholder="search" aria-label="search" required>
					<button class= "btn btn-outline-dark" type= "submit">search</button>
				</form>
			</div>
		</div>
	</nav>