<?php
session_start();
include 'connection.php';
if (!isset($_SESSION['user_email'])|| $_SESSION['admin']!=1){
    header("Location: index.php");
    exit();
}
include 'header.php';
$product_id=isset($_GET['id']) ? intval ($_GET['id']) : null;
$stmt=$connection->prepare("SELECT products.*, categories.name AS category_name From products LEFT JOIN categories ON products.category_id = categories.id WHERE products.id= ?");
$stmt->bind_param("i",$product_id);
$stmt->execute();
$result = $stmt->get_result();
$product= $result->fetch_assoc();
$stmt->close();

if (!$product)
{
    header("Location: admin.php");
    exit();
}
$categoriesQuery= $connection->query("SELECT * FROM categories ORDER BY name ASC");
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['update_product']))
{
    $productName=trim($_POST['name']);
    $productPrice=trim($_POST['price']);
    $productStock=intval($_POST['stock']);
    $productDescription=trim($_POST['description']);
    $CategoryID=intval($_POST['category']);
	if (empty($productName) || empty($productPrice) || empty($productDescription) || $productStock<0 || $categoryID==0)
	{
		$_SESSION['admin_error']="All fields are required and stock cannot be negative";
		header("Location: edit_product.php?id=".$product_id);
		exit();
	}
$imageFileName=$product['image'];
if (!empty($_FILES['product_image']['name']))
{
    $targetDir="images/products/";
    if (!is_dir($targetDir)){
        mkdir($targetDir,0777,true);
    }
    $imageFileName=time()."-".basename($_FILES["product_image"]["name"]);
    $targetFile=$targetDir.$imageFileName;
    if (move_uploaded_file($_FILES["product_image"]["tmp_name"],$targetFile))
    	{
        	if(file_exists("images/products/" . $product['image'])){
            	unlink("images/products/" . $product['image']);
        	}
    	}
    else
    {
        $_SESSION['admin_error']="Error uploading new image.";
        header("Location: edit_product.php?id=" . $product_id);
        exit();
    }
}
	$stmt=$connection->prepare("UPDATE products SET name=?, price=?, stock=?, description=?, category_id=?, image=? WHERE id=?");
	$stmt->bind_param("sdisssi",$productName,$productPrice,$productStock,$productDescription,$categoryID,$imageFileName,$product_id);
	if ($stmt->execute())
	{
		$_SESSION['admin_success']="Product updated succesfully";
	}
	else
	{
		$_SESSION['admin_error']="Failed update product";
	}
	$stmt->close();
	header("Location: admin.php");
}
?>
<div class="container mt-4 mb-5 pb-5">
    <h2>Edit Products:</h2>
	
	<?php
	if(isset($_SESSION['admin_success'])):?>
		<p class="text-success"><?= $_SESSION['admin_success']; unset($_SESSION['admin_success']);?></p>
	<?php endif;?>

	<?php
	if(isset($_SESSION['admin_error'])):?>
		<p class="text-danger"><?= $_SESSION['admin_error']; unset($_SESSION['admin_error']);?></p>
	<?php endif;?>
    <div class="row">
       <div class="col-md-6">
            <img src="images/products/<?= htmlspecialchars($product['image']); ?>" class="img-fluid rounded shadow-sm" alt="<?= htmlspecialchars($product['name']); ?>" style="max-height: 400px; object-fit: contain;">
        </div> 
        <div class="col-md-6">
            <form action="edit_product.php?id=<?=$product_id;?>" method="post" enctype="multipart/form-data">
                <label for="name">Product Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="<?=htmlspecialchars($product['name']);?>" required>

                <label for="price">Price (£):</label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" value="<?=number_format($product['price'],2);?>" required>

                <label for="stock">Stock Quantity:</label>
                <input type="number" name="stock" id="stock" class="form-control" value="<?=htmlspecialchars($product['stock']);?>" required>

                <label for="description">Description:</label>
                <input type="textarea" name="description" id="description" class="form-control" value="<?=htmlspecialchars($product['description']);?>" required>
				
				<label for="category">Category:</label>
				
				<select name="category" id="category" class="form-control" required>
					<?php while ($category=$categoriesQuery->fetch_assoc()):?>
						<option value="<?= $category['id']?>" <?= $category['id']==$product['category_id']?'selected':'';?>>
							<?= htmlspecialchars($category['name']);?>
						</option>
					<?php endwhile;?>
				</select>
				<button type="submit" name="update_product" class="btn btn-primary mt-3">Update Product</button>
				<label for="productImage"> New Product Image </label>
				<input type="file" name="product_image" id="productImage" class="form-control">
            </form>
        </div>
    </div>
</div>
