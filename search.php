<?php
session_start();
include 'connection.php';
include 'header.php';
$searchquery=isset($_GET['query'])? trim($_GET['query']) :'';
//echo $searchquery;
?>
<div class="container mt-4 mb-5">
    <h2>Search results for: <?= htmlspecialchars($searchquery);?></h2>
    <div class="row">
        <?php
        $sql = "SELECT products.*, categories.name AS category_name FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.name LIKE ? OR products.description LIKE ?";
        $stmt = $connection->prepare($sql);
		$likeQuery="%". $searchquery."%";
		$stmt->bind_param("ss",$likequery,$likeQuery );
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) 
			{?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="images/products/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top img-fluid mt-2" style="max-height: 200px; object-fit:contain;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="card-text">Price: <?php echo number_format($row['price']); ?></p>
                            <p class="card-text text-muted"><?php echo htmlspecialchars($row['category_name']); ?></p>
                            <p class="card-text <?php echo ($row['stock'] > 0) ? 'text-success' : 'text-danger'; ?>">
							<?= ($row['stock']>0)? "In Stock ({$row['stock']} available)" : 'Out of Stock' ;?>
							</p>
							<a href="product.php?id=<?= $row['id']; ?>" class="btn btn-primary">View Product</a>
                        </div>
                    </div>
                </div>
			<?php	}
			}
			else
			{
				echo '<p class="text-danger">No products found.</p>';
			}
			$stmt->close();
			$connection->close();
			?>
    </div>
</div>
<?php include('footer.php');?>