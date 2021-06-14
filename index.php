<?php
require_once 'controllers/database.php';
include 'common/header.php';

$productData = [];
$query = "SELECT * from categories";
$result = mysqli_query($conn, $query);

while ($category = mysqli_fetch_array($result)) {

	$catId = $category['id'];
	$query = "SELECT * FROM products WHERE category=$catId ORDER BY id DESC LIMIT 4 ";
	$result2 = mysqli_query($conn, $query);
	$products = [];

	if ($result2) {
		while ($product = mysqli_fetch_array($result2)) {

			$products[] = array(
				'id' => $product['id'],
				'title' => $product['title'],
				'description' => $product['description'],
				'price' => $product['price'],
				//'category' => $product['category_name'],
				'category_id' => $product['category'],
				'qty' => $product['qty'],
				'images' => $product['images']
			);
		}
	}

	$productData[$category['name']] = $products;
}

?>

<div class="container">
	<br>
	<br>
	<br>
	<br>
	<div class="row bg-white">
		<div class="col-md-6 text-center">
			<img alt="vp" class="top-banner-logo" src="logo/logo.png">
			<div class="site-moto">
				For Your Choice
			</div>
		</div>
		<div class="col-md-6">
			<div class="slidewrapper">
				<div class="slide" style="max-width:auto">
					<video width="100%" height="5%" autoplay loop muted>
						<source src="videos/maji11.mp4" type="video/mp4">
						<source src="videos/maji11.ogg" type="video/ogg">
						Your browser does not support the video tag
					</video>
				</div>
			</div>
		</div>

	</div>
</div>
<br>
<br>
<div class="container">

	<?php
	foreach ($productData as $key => $data) {
	?>
		<h3> <?php echo $key; ?> </h3>
		<hr>
		<ul class="mystyle-products">
			<?php
			foreach ($data as $product) {
				$url = str_replace('"', "'", strtolower($product['title']));
				$url = str_replace(' ', '-', $url);
			?>
				<li class="product">
					<a href="in-product.php?p=<?php echo $url; ?>&i=<?php echo $product['id']; ?>">
						<span class="onsale">Sale!</span>
						<div class="product-image-home" style="background-image:url('<?php echo $product['images']; ?>') ;">

						</div>
						<h3 class="text-center"> <?php echo $product['title']; ?> </h3>
						<div class="price">
							<!-- <del> <span class="amount">399.000 ₫</span> </del> -->
							<ins> <span class="amount">Rs. <?php echo number_format($product['price'], 2); ?> </span> </ins>
							<!-- <span class="sale-tag sale-tag-square">-33%</span> -->
						</div>
					</a>
					<a class="button add_to_cart_button product_type_simple" rel="nofollow" href="#">
						Mua ngay
					</a>
					<!-- <a href="#" class="btn btn-dark btn-circle btn-review" data-toggle="tooltip" data-placement="top" title="Quick View"><i class="ion ion-ios-move"></i></a> -->
				</li>

			<?php
			}
			?>
		</ul>
		<br>
		<br>
	<?php
	}
	?>
</div>

<?php include 'common/footer.php' ?>

</body>

</html>