<?php
include '../common/adminHeader.php';

$categoriesQuery = "SELECT * from categories";
$categoriesResult = mysqli_query($conn, $categoriesQuery);
$s = '';
$prod_cat = '';
$categories = [];

while ($category = mysqli_fetch_array($categoriesResult)) {

    $categories[] = array(
        'id' => $category['id'],
        'name' => $category['name']
    );
}

$query = "SELECT products.*, categories.name as category_name from products INNER JOIN categories ON categories.id = products.category";

if (isset($_GET['prod_category']) and $_GET['prod_category']) {
    $prod_cat = $_GET['prod_category'];
    $query = $query . " WHERE category = $prod_cat";
}

if (isset($_GET['s']) and $_GET['s']) {
    $s = $_GET['s'];
    if ($prod_cat) {
        $query = $query . " AND title LIKE '%$s%' OR description LIKE '%$s%'";
    } else {
        $query = $query . " WHERE title LIKE '%$s%' OR description LIKE '%$s%'";
    }
}

$query = $query . ' ORDER BY id DESC';


$result = mysqli_query($conn, $query);
// print_r($result);
// exit();
$products = [];
if ($result) {


    while ($product = mysqli_fetch_array($result)) {

        $products[] = array(
            'id' => $product['id'],
            'title' => $product['title'],
            'description' => $product['description'],
            'price' => $product['price'],
            'category' => $product['category_name'],
            'category_id' => $product['category'],
            'qty' => $product['qty'],
            'images' => $product['images']
        );
    }
}
?>

<button class="btn btn-success pull-right" id="createProduct">
    <i class="fa fa-plus"></i> Add Product
</button>
<h3>
    Products
</h3>
<br>
<form action="" method="GET" id="searchForm">

    <div class="col-md-12">
        <div class="row">
            <div class="col-md-9">
                <div class="input-group mb-3">
                    <span class="input-group-text"> Search Product </span>
                    <input type="text" class="form-control" name="s" value="<?php echo $s; ?>" aria-label="Text input with dropdown button">
                    <button class="btn btn-secondary" type="button" id="button-addon2">
                        <i class="fa fa-search"></i> Search
                    </button>

                </div>
            </div>
            <div class="col-md-3">
                <select class="form-control" name="prod_category" id="prod_category_filter" required>
                    <option value="0">All Categories</option>
                    <?php
                    foreach ($categories as $cat) {
                        $catId = $cat['id'];
                        $query = "SELECT count(id) FROM products WHERE category=$catId";
                        $result = mysqli_fetch_array(mysqli_query($conn, $query));
                        if ($prod_cat and $prod_cat == $cat['id']) {
                            echo "<option selected value='" . $cat['id'] . "'>" . $cat['name'] . ' (' . $result[0] . ")</option>";
                        } else {
                            echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . ' (' . $result[0] . ")</option>";
                        }
                    }
                    ?>
                </select>
            </div>

        </div>
    </div>
</form>
<br>
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td> Id </td>
            <td> Image </td>
            <td class="text-center"> Title </td>
            <td class="text-center"> Category </td>
            <td class="text-center"> Price </td>
            <td class="text-center"> Actions </td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($products as $prod) {
        ?>
            <tr>
                <td width="50px" class="text-center"> <?php echo $prod['id']; ?> </td>
                <td width=200px>
                    <div class="text-center">
                        <img class="product-image" src="../<?php echo $prod['images']; ?>" width="100px;">
                    </div>
                </td>
                <td>
                    <textarea style="display: none;" class="prod_description_raw"><?php echo $prod['description']; ?></textarea>
                    <b class="product-title"><?php echo $prod['title']; ?></b>
                    <hr>
                    <div class="hide-overflow">
                        <small class="product-description">
                            <?php echo nl2br($prod['description']); ?><br>
                        </small>
                        <small class="show-options show-less"> Show less </small>
                    </div>
                    <small class="show-options show-more"> Show more </small>
                </td>
                <td class="text-center product-category" data-id="<?php echo $prod['category_id']; ?>"> <?php echo $prod['category']; ?> </td>
                <td class="text-right product-price" data-price="<?php echo $prod['price']; ?>" width="100px"> <?php echo number_format($prod['price'], 2); ?> </td>
                <td width="150px" class="text-right">
                    <input type="hidden" value="<?php echo $prod['qty'] ?>" class="product-qty">
                    <button class="btn btn-secondary btn-sm edit-product" data-id="<?php echo $prod['id']; ?>"> Edit</button>
                    <button class="btn btn-danger btn-sm remove-modal" data-id="<?php echo $prod['id']; ?>"> Remove</button>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<div class="modal fade" id="addProductMondal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="controller/adminController.php" enctype="multipart/form-data" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="create-product" value="1">
                        <label for="prod_title" class="form-label">Product Title</label>
                        <input type="text" class="form-control" name="prod_title" id="prod_title" placeholder="Product Title" required>
                        <br>
                        <label for="prod_category" class="form-label">Product Category</label>
                        <select class="form-control" name="prod_category" id="prod_category" required>
                            <?php
                            foreach ($categories as $cat) {
                                echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . "</option>";
                            }
                            ?>
                        </select>
                        <br>
                        <label for="prod_description" class="form-label">Product Description</label>
                        <textarea name="prod_description" class="form-control" id="prod_description" rows="10" required></textarea>
                        <br>
                        <label for="prod_image" class="form-label">Product Image</label>
                        <input type="file" name="prod_image" class="form-control" id="prod_image" accept="image/png, image/gif, image/jpeg" required></input>
                        <br>
                        <label for="prod_price" class="form-label">Product Price</label>
                        <input type="text" name="prod_price" class="form-control" id="prod_price" required></input>
                        <br>
                        <label for="prod_qty" class="form-label">Product Stock</label>
                        <input type="text" name="prod_qty" class="form-control" id="prod_qty" required></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> Create Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editProductMondal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="controller/adminController.php" enctype="multipart/form-data" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="edit-product" value="1">
                        <input type="hidden" name="product_id" id="product_id" value="">
                        <label for="prod_title_edit" class="form-label">Product Title</label>
                        <input type="text" class="form-control" name="prod_title" id="prod_title_edit" placeholder="Product Title" required>
                        <br>
                        <label for="prod_category_edit" class="form-label">Product Category</label>
                        <select class="form-control" name="prod_category" id="prod_category_edit" required>
                            <?php
                            foreach ($categories as $cat) {
                                echo "<option value='" . $cat['id'] . "'>" . $cat['name'] . "</option>";
                            }
                            ?>
                        </select>
                        <br>
                        <label for="prod_description_edit" class="form-label">Product Description</label>
                        <textarea name="prod_description" class="form-control" id="prod_description_edit" rows="10" required></textarea>
                        <br>
                        <div class="row">
                            <div class="col-md-4" id="uploaded_image">

                            </div>
                            <div class="col-md-8">

                                <label for="prod_image_edit" class="form-label">Product Image</label>
                                <input type="file" name="prod_image" class="form-control" id="prod_image_edit" accept="image/png, image/gif, image/jpeg"></input>
                            </div>
                        </div>
                        <br>
                        <label for="prod_price_edit" class="form-label">Product Price</label>
                        <input type="text" name="prod_price" class="form-control" id="prod_price_edit" required></input>
                        <br>
                        <label for="prod_qty_edit" class="form-label">Product Stock</label>
                        <input type="text" name="prod_qty" class="form-control" id="prod_qty_edit" required></input>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary"> Edit Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#createProduct").click(function() {
        $("#addProductMondal").modal('show');
    });

    $(".show-more").click(function() {
        $(this).parent().parent().find('div').removeClass('hide-overflow');
        $(this).hide();
    });

    $(".show-less").click(function() {
        $(this).parent().parent().find('div').addClass('hide-overflow');
        $(".show-more").show();
    });

    $(".edit-product").on('click', function() {
        var Parent = $(this).parent().parent();
        var product_id = $(this).attr('data-id');
        var product_title = Parent.find('.product-title').html();
        var product_category = Parent.find('.product-category').attr('data-id');
        var product_description = Parent.find('.prod_description_raw').val();
        var product_image = Parent.find('.product-image').attr('src');
        var product_price = Parent.find('.product-price').attr('data-price');
        var product_stock = Parent.find('.product-qty').val();

        // console.log(product_id);
        // console.log(product_title);
        // console.log(product_category);
        // console.log(product_description);
        // console.log(product_image);
        // console.log(product_price);
        // console.log(product_stock);

        $("#prod_title_edit").val(product_title);
        $("#prod_category_edit").val(product_category);
        $("#prod_description_edit").val(product_description);
        $("#uploaded_image").html("<img src='" + product_image + "' width='50px'> ");
        $("#prod_price_edit").val(product_price);
        $("#prod_qty_edit").val(product_stock);
        $("#product_id").val(product_id);

        $("#editProductMondal").modal('show');
    });

    $("#prod_category_filter").on('change', function() {
        $('#searchForm').submit();
    });
</script>
<?php include '../common/adminFooter.php'; ?>