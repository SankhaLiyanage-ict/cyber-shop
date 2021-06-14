<?php
require_once 'controllers/database.php';
include 'common/header.php';

$productData = [];
$query = "SELECT * from categories";
$result = mysqli_query($conn, $query);
$s = '';
$c = 0;
$p = 'lth';

while ($category = mysqli_fetch_array($result)) {

    $catId = $category['id'];
    $query = "SELECT * FROM products WHERE category=$catId ";
    $result2 = mysqli_query($conn, $query);
    $products = [];

    if ($result2) {
        while ($product = mysqli_fetch_array($result2)) {

            $products[] = array(
                'id' => $product['id'],
                'category_id' => $product['category']
            );
        }
    }

    $productData[$category['name']] = $products;
}

$productQuery = "SELECT *,CONVERT(price,UNSIGNED INTEGER) AS num FROM products ";

if (isset($_GET['c']) and $_GET['c']) {
    $c = $_GET['c'];
    $productQuery = $productQuery . " WHERE category=$c ";
}

if (isset($_GET['s']) and $_GET['s']) {
    $s = $_GET['s'];
    if ($c) {
        $productQuery = $productQuery . " AND (title LIKE '%$s%' OR description LIKE '%$s%') ";
    } else {
        $productQuery = $productQuery . " WHERE title LIKE '%$s%' OR description LIKE '%$s%' ";
    }
}

if (isset($_GET['p']) and $_GET['p']) {
    $p = $_GET['p'];

    if ($p == 'htl') {
        $productQuery = $productQuery . " ORDER BY num DESC";
    } else {
        $productQuery = $productQuery . " ORDER BY num ASC";
    }
}



$productQueryResult = mysqli_query($conn, $productQuery);
$Products = [];

if ($result2) {
    while ($product = mysqli_fetch_array($productQueryResult)) {

        $Products[] = array(
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
?>
<style>
    .form-check {
        margin-left: 15px;
    }

    .in-product-small-description {
        height: 39px;
        overflow: hidden;
        padding-right: 33px;
        font-size: 12px;
        color: #676767;
    }

    .reviews-counter {
        font-size: 13px;
        margin-bottom: 10px;
        margin-top: 3px;
    }

    tr {
        transition: 0.3s;
        cursor: pointer;
    }

    tr:hover {
        transition: 0.3s;
        box-shadow: 1px 1px 8px #bdbdbd;
    }

    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f4f4f4;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #f4f4f4;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #f4f4f4;
    }
</style>
<div class="container">
    <br>
    <br>
    <br>
    <div class="row">
        <div class="col-sm-3 pl-0 pr-0">
            <br>
            <div class="bg-white" style="padding: 13px 12px;margin-top: -7px;">
                <form action="">
                    <br>
                    <input type="hidden" name="s" value="<?php echo  $s; ?>">
                    <h5 class="search-cat-nav">Order By</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="p" value="lth" id="flexRadioDefault1" <?php echo $p == 'lth' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="flexRadioDefault1">
                            Price Low to High
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="p" value="htl" id="flexRadioDefault2" <?php echo $p == 'htl' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="flexRadioDefault2">
                            Price High to Low
                        </label>
                    </div>
                    <br>
                    <h5 class="search-cat-nav">Category</h5>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" value="0" name="c" <?php echo $c ? '' : 'checked'; ?> id="flexCheckDefault">
                        <label class="form-check-label" for="flexCheckDefault">
                            All Categories
                        </label>
                    </div>
                    <?php
                    foreach ($productData as $key => $data) {
                        if ($data) {
                    ?>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="c" <?php echo $c == $data[0]['category_id'] ? 'checked' : ''; ?> id="cat_<?php echo $data[0]['category_id']; ?>" value="<?php echo $data[0]['category_id']; ?>">
                                <label class="form-check-label" for="cat_<?php echo $data[0]['category_id']; ?>">
                                    <?php echo $key . ' (' . count($data) . ')'; ?>
                                </label>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <br>
                    <div class="search-cat-nav" style="border: 0;">
                        <button class="btn btn-outline-success col-12">
                            Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-sm-9 search-result-page">
            <div>
                <?php
                if (!$Products) {
                ?>
                    <br>
                    <div class="<?php echo !$Products ? 'bg-white mt--6' : ''; ?>">
                        <br>
                        <img src="logo/no-result.png">
                        <h5 class="text-center"> We're sorry. We were not able to find match. </h5>
                        <p class="text-center"> Try changing Category and Keyword </p>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                <?php
                }
                ?>

                <table class="table">
                    <?php
                    $loopdd = 1;
                    foreach ($Products as $key => $data) {
                        $url = str_replace('"', "'", strtolower($data['title']));
                        $url = str_replace(' ', '-', $url);
                        if ($loopdd == 1) {
                            echo "<tr><td></td></tr>";
                        }
                    ?>
                        <tr class="">
                            <td width="250px" class="p-35 bg-white">
                                <a href="in-product.php?p=<?php echo $url; ?>&i=<?php echo $data['id']; ?>">
                                    <div class="search-product-image" style="background-image: url('<?php echo $data['images']; ?>');">

                                    </div>
                                </a>
                            </td>
                            <td class="bg-white">
                                <a href="in-product.php?p=<?php echo $url; ?>&i=<?php echo $data['id']; ?>">
                                    <h5 class="product-search-title"><?php echo $data['title']; ?></h5>
                                    <div class="reviews-counter">
                                        <div class="rate">
                                            <input type="radio" id="star_<?php echo $data['id']; ?>_5" name="rate" value="5" checked />
                                            <label for="star_<?php echo $data['id'] ?>_5" title="text">5 stars</label>
                                            <input type="radio" id="star_<?php echo $data['id']; ?>_4" name="rate" value="4" checked />
                                            <label style="color: #ffc700;" for="star_<?php echo $data['id'] ?>_4" title="text">4 stars</label>
                                            <input type="radio" id="star_<?php echo $data['id']; ?>_3" name="rate" value="3" checked />
                                            <label style="color: #ffc700;" for="star_<?php echo $data['id'] ?>_3" title="text">3 stars</label>
                                            <input type="radio" id="star_<?php echo $data['id']; ?>_2" name="rate" value="2" />
                                            <label style="color: #ffc700;" for="star_<?php echo $data['id'] ?>_2" title="text">2 stars</label>
                                            <input type="radio" id="star_<?php echo $data['id']; ?>_1" name="rate" value="1" />
                                            <label style="color: #ffc700;" for="star_<?php echo $data['id'] ?>_1" title="text">1 star</label>
                                        </div>
                                        <span>4 Reviews</span>
                                    </div>
                                    <div class="in-product-small-description">
                                        <p>
                                            <?php echo $data['description']; ?>
                                        </p>
                                    </div>
                                    <div class="product-price-discount">
                                        <span> <b class="search-price">Rs. <?php echo number_format($data['price'], 2); ?></b></span>
                                        <!-- <span class="line-through">$29.00</span> -->
                                    </div>
                                </a>
                            </td>
                        </tr>
                    <?php
                        $loopdd += 1;
                    }
                    ?>
                </table>
            </div>
        </div>
    </div>
    <br>
</div>

<script>
    var header = $("#guide-template");
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= window.innerHeight) {
            header.addClass("fixed");
        } else {
            header.removeClass("fixed");
        }
    });
</script>