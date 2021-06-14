<?php
require_once 'controllers/database.php';

if (!(isset($_GET['p']) and isset($_GET['i']) and $_GET['i'])) {
    header('Location: index.php');
}
$productId = $_GET['i'];

$query = "SELECT * from products WHERE id=$productId";
$result = mysqli_query($conn, $query);

$Product = mysqli_fetch_array($result);
$ProductCatId = $Product['category'];
$query = "SELECT * FROM products WHERE category=$ProductCatId ORDER BY rand() LIMIT 4 ";
$result2 = mysqli_query($conn, $query);
$RelatedProducts = [];

if ($result2) {
    while ($product = mysqli_fetch_array($result2)) {

        $RelatedProducts[] = array(
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

include 'common/header.php';

?>
<div class="pd-wrap">
    <div class="container bg-white pt-60">
        <div class="row">
            <div class="col-md-6">
                <div class="back-image" style="background-image: url('<?php echo $Product['images']; ?>');">

                </div>
            </div>
            <div class="col-md-6">
                <div class="product-dtl">
                    <div class="product-info">
                        <div class="product-name"><b><?php echo $Product['title']; ?></b></div>
                        <div class="reviews-counter">
                            <div class="rate">
                                <input type="radio" id="star5" name="rate" value="5" checked />
                                <label for="star5" title="text">5 stars</label>
                                <input type="radio" id="star4" name="rate" value="4" checked />
                                <label for="star4" title="text">4 stars</label>
                                <input type="radio" id="star3" name="rate" value="3" checked />
                                <label for="star3" title="text">3 stars</label>
                                <input type="radio" id="star2" name="rate" value="2" />
                                <label for="star2" title="text">2 stars</label>
                                <input type="radio" id="star1" name="rate" value="1" />
                                <label for="star1" title="text">1 star</label>
                            </div>
                            <span>3 Reviews</span>
                        </div>
                        <div class="product-price-discount">
                            <span>Rs. <?php echo number_format($Product['price'], 2); ?></span>
                            <!-- <span class="line-through">$29.00</span> -->
                        </div>
                    </div>
                    <div class="in-product-small-description">
                        <p>
                            <?php echo $Product['description']; ?>
                            <?php echo $Product['description']; ?>
                        </p>
                    </div>
                    <!-- <div class="row">
                        <div class="col-md-6">
                            <label for="size">Size</label>
                            <select id="size" name="size" class="form-control">
                                <option>S</option>
                                <option>M</option>
                                <option>L</option>
                                <option>XL</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="color">Color</label>
                            <select id="color" name="color" class="form-control">
                                <option>Blue</option>
                                <option>Green</option>
                                <option>Red</option>
                            </select>
                        </div>
                    </div> -->
                    <div class="product-count">
                        <label for="size">Quantity</label>
                        <form action="#" class="display-flex">
                            <div class="qtyminus">-</div>
                            <input type="text" name="quantity" id="quantity" value="1" class="qty" readonly>
                            <div class="qtyplus">+</div>
                            <span class="in-product-stock"> <?php echo $Product['qty'] ? $Product['qty'] . ' Available' : 'Out of Stock'; ?></span>
                        </form>
                        <br>

                        <?php
                        if (!isset($_SESSION['email'])) {
                            if ($Product['qty'] > 1) {
                        ?>
                                <a href="login.php?redir=<?php echo $Product['id']; ?>" class="btn btn-outline-success btn-lg">Buy Now</a>
                                <a href="login.php?redir=<?php echo $Product['id']; ?>" class="btn btn-success btn-lg">Add to Cart</a>
                            <?php
                            } else {
                            ?>
                                <a href="buy-now.php" class="btn btn-outline-success btn-lg disabled">Buy Now</a>
                            <?php
                            }
                        } else {
                            if ($Product['qty'] > 1) {
                            ?>
                                <a id="buyNow" data-href="add-to-cart.php" class="btn btn-outline-success btn-lg">Buy Now</a>
                                <a id="addToCart" data-href="add-to-cart.php" class="btn btn-success btn-lg">Add to Cart</a>
                            <?php
                            } else {
                            ?>
                                <a href="#" class="btn btn-outline-success btn-lg">Product Out of Stock</a>
                        <?php
                            }
                        }
                        ?>


                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="product-info-tabs">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">Description</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <?php echo nl2br($Product['description']); ?>
                </div>
            </div>
        </div>
        <br>
        <div class="product-info-tabs">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="description-tab" data-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="true">
                        Related Products
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <ul class="mystyle-products">
                        <?php
                        foreach ($RelatedProducts as $product) {
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
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        $(".qtyminus").on("click", function() {
            var now = $(".qty").val();
            if ($.isNumeric(now)) {
                if (parseInt(now) - 1 > 0) {
                    now--;
                }
                $(".qty").val(now);
            }
        })

        $(".qtyplus").on("click", function() {
            var now = $(".qty").val();
            var avalibleQty = parseInt('<?php echo $Product['qty']; ?>');
            if ($.isNumeric(now)) {
                console.log(now);
                console.log(avalibleQty);
                if (now <= avalibleQty - 1) {
                    $(".qty").val(parseInt(now) + 1);
                }

            }
        });

        $("#buyNow").on("click", function() {
            var quantity = $("#quantity").val();
            var productId = '<?php echo $product['id']; ?>';
            var productPrice = '<?php echo $product['price']; ?>';
            var url = $(this).attr('data-href');

            $.post(url, {
                productId: productId,
                quantity: quantity,
                productPrice: productPrice
            }, function(data) {
                if (data == 1) {
                    window.location.href = 'cart.php';
                }
                if (data == 2) {
                    alert("This Item Already added to Cart");
                    window.location.href = 'cart.php';
                }
            });
        });

        $("#addToCart").on("click", function() {
            var quantity = $("#quantity").val();
            var productId = '<?php echo $Product['id']; ?>';
            var productPrice = '<?php echo $Product['price']; ?>';
            var avalibleQty = '<?php echo $Product['qty']; ?>';
            var url = $(this).attr('data-href');

            $.post(url, {
                productId: productId,
                quantity: quantity,
                productPrice: productPrice,
                avalibleQty: avalibleQty
            }, function(data) {
                if (data == 1) {
                    window.location.reload();
                }
                if (data == 2) {
                    alert("This Item Already added to Cart");
                }
                if (data == 3) {
                    alert("Sorry. Our store does not have the number you need");
                    window.location.reload();
                }
                if (data == 4) {
                    window.location.href = 'login.php';
                }
            });
        });

    });
</script>


<?php include 'common/footer.php' ?>

</body>

</html>