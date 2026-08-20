<?php
session_start();
include 'connection.php';
include 'header.php';
if (!isset($_SESSION['user_email']) || $_SESSION['admin'] != 1)
    {
        header("Location: index.php");
        exit();
    }
$searchQuery=isset($_GET['search']) ? trim($_GET['search']) : '';
$minPrice=isset($_GET['min_price']) ? (float)($_GET['min_price']) : 0;
$maxPrice=isset($_GET['max_price']) ? (float)($_GET['max_price']) : 100000000;
$categoryFilter=isset($_GET['category']) ? intval($_GET['category']) : 0;

$categoriesQuery=$connection->query("SELECT * from categories order by name ASC");

$sql = "SELECT products.*, categories.name AS category_name FROM products
LEFT JOIN categories ON products.category_id = categories.id
WHERE price BETWEEN ? AND ?";

$params = [ $minPrice, $maxPrice ];
$types = "dd";

if (!empty($searchQuery))
{
    $sql .= " AND (products.name LIKE ? OR products.description LIKE ?)";
    $likeQuery = "%{$searchQuery}%";
    $params[] = $likeQuery;
    $params[] = $likeQuery;
    $types .= "ss";
}

if ($categoryFilter > 0)
{
    $sql .= " AND products.category_id=?";
    $params[] = $categoryFilter;
    $types .= "i";
}

$sql .= " Order By created_at DESC";
$stmt = $connection->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$productsQuery = $stmt->get_result();

$sql .= " Order By created_at DESC";
?>

<div class = "container mt-4">
	<h2>Manage Products</h2>
	<?php if (isset($_SESSION['admin_success'])):?>
		<p class="text-success"><?= $_SESSION['admin_success']; unset($_SESSION['admin_success']);?>
	<?php endif?>
			
	<?php if (isset($_SESSION['admin_error'])):?>
		<p class="text-danger"><?= $_SESSION['admin_error']; unset($_SESSION['admin_error']);?>
	<?php endif?>
	<form action="manage_products.php" method="GET" class="mb-4">
		<div class="row">
			<div class = "col-md-4">
				<input type="search" name="search" class="form-control" placeholder="search by name or description" value = "<?= htmlspecialchars($searchQuery);?>">
			</div>
			<div class = "col-md-2">
				<input type="number" name="min_price" class="form-control" placeholder="Min Price £" min="0" value = "<?= $minPrice;?>">
			</div>
			<div class = "col-md-2">
				<input type="number" name="max_price" class="form-control" placeholder="Max Price £" min="0" value = "<?= $maxPrice;?>">
			</div>
			<div class = "col-md-2">
			<select name = "category" class="form-control">
				<option value="0">All Categories</option>
				<?php while ($category = $categoriesQuery->fetch_assoc()):?>
					<option value="<?= $category['id']?>" <?= ($categoryFilter==$category['id']) ? 'selected':'';?>>
						<?= htmlspecialchars($category['name']);?>
					</option>
				<?php endwhile;?>
			</select>
		</div>
			<div class ="col-md-2">
				<button type = "submit" class = "btn btn-primary w-100">Filter</button>
			</div>
		</div>
	</form>
	<div class="table-responsive">
	  <table class="table table-bordered">
		<thead class="thead-light">
		  <tr>
			<th>ID</th>
			<th>Image</th>
			<th>Name</th>
			<th>Category</th>
			<th>Price (£)</th>
			<th>Stock</th>
			<th>Description</th>
			<th>Action</th
		</thead>
		<tbody>
		<?php while ($row=$productsQuery->fetch_assoc()):?>
			<td><?= $row['id'];?></td>
			<td>
				<img src = "images/products/<?=htmlspecialchars($row['image']);?>"
				style="width:80px; height:80px; object-fit:contain; padding: 5px; background-color:#fff;">
			</td>
			<td><?= htmlspecialchars($row['name'])?></td>
			<td><?= htmlspecialchars($row['category_name'])?></td>
			<td>£<?=number_format($row['price'], 2)?></td>
			<td><?= htmlspecialchars($row['stock'])?></td>
			<td><?= htmlspecialchars($row['description'])?></td>
			<td>
				<a href="edit_product.php?id=<?= $row['id'];?>"
				class ="btn btn-warning btn-sm">Edit</a>
				<a href="delete_product.php?id=<?= $row['id'];?>"
				class ="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product')">Delete</a>
			</td>
			</tr>
		<?php endwhile;?>
		</tbody>
	  </table>
	</div>
	<a href="admin.php" class="btn btn-secondary mb-5">Back to admin console</a>
</div>
<?php include ('footer.php')?>