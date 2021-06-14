<?php
require_once 'controllers/database.php';
include 'common/header.php';

$productData = [];
$query = "SELECT * from categories";
$result = mysqli_query($conn, $query);

while ($category = mysqli_fetch_array($result)) {

    $catId = $category['id'];
    $query = "SELECT * FROM products WHERE category=$catId ORDER BY rand() LIMIT 4 ";
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
<style>
    .mystyle-products .product {
        margin: 14px;
        width: 30%;
    }

    .mystyle-products .product h3 {
        font-size: 26px;
    }

    .reviews-counter {
        font-size: 13px;
        margin-bottom: 12px;
        margin-top: 3px;
    }
</style>
<div class="container">
    <br>
    <br>
    <br>
    <br>
    <h3 class="text-center ">Our Categories</h3>
    <br>
    <ul class="mystyle-products">
        <?php
        foreach ($productData as $key => $data) {
        ?>

            <?php
            if (isset($data[0])){
            $product = $data[0];
            $url = str_replace('"', "'", strtolower($product['title']));
            $url = str_replace(' ', '-', $url);
            if ($product) {
            ?>
                <li class="product">
                    <a href="search.php?s=&p=lth&c=<?php echo $product['category_id']; ?>">
                        <div class="product-image-home" style="background-image:url('<?php echo $product['images']; ?>') ;">
                        </div>
                        <h3 class="text-center"> <?php echo $key; ?> (<?php echo count($data); ?>)</h3>
                    </a>
                </li>

                <br>
                <br>
        <?php
            }
        }
        }
        ?>
    </ul>
</div>
<br>
<br>