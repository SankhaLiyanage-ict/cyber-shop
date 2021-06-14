<?php
require_once 'controllers/database.php';
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
}
$userId = $_SESSION['id'];

$userQuery = "SELECT * FROM users WHERE id=$userId LIMIT 1";
$userData = mysqli_query($conn, $userQuery);

$User = mysqli_fetch_array($userData);

$query = "SELECT products.*, cart.id as cart_id, cart.qty as cart_qty, cart.price as cart_price from cart INNER JOIN products ON cart.product_id = products.id WHERE user_id=$userId";
$result = mysqli_query($conn, $query);
$CartItems = [];

while ($product = mysqli_fetch_array($result)) {

    $CartItems[] = array(
        'product_id' => $product['id'],
        'cart_id' => $product['cart_id'],
        'title' => $product['title'],
        'description' => $product['description'],
        'price' => $product['price'],
        //'category' => $product['category_name'],
        'category_id' => $product['category'],
        'product_qty' => $product['qty'],
        'cart_qty' => $product['cart_qty'],
        'cart_price' => $product['cart_price'],
        'images' => $product['images']
    );
}

include 'common/header.php';
?>

<div class="pd-wrap">
    <div class="container  pt-60">

        <div class="row">
            <div class="col-md-8">
                <div class="col-md-12">
                    <div class="bg-white" style="padding: 0px 20px;">
                        <br>
                        <h4 class="text-center">My Cart</h4>
                        <br>
                        <?php

                        if ($CartItems) {
                        ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <td></td>
                                        <td>Image</td>
                                        <td>Product</td>
                                        <td>Amount</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subTotal = 0;
                                    $loopIndex = 1;
                                    foreach ($CartItems as $prod) {
                                    ?>
                                        <tr>
                                            <td width="50px" class="text-center"> <?php echo $loopIndex; ?> </td>
                                            <td width=150px>
                                                <div class="text-center">
                                                    <img class="product-image" src="../<?php echo $prod['images']; ?>" width="60px;">
                                                </div>
                                            </td>
                                            <td>
                                                <b class="product-title"><?php echo $prod['title']; ?></b>
                                                <div class="product-count">
                                                    <div class="display-flex">
                                                        <div class="qtyminus" data-id="<?php echo $prod['cart_id']; ?>" data-price="<?php echo $prod['price']; ?>" data-qty="<?php echo $prod['product_qty']; ?>">-</div>
                                                        <input type="text" name="quantity" value="<?php echo $prod['cart_qty']; ?>" class="qty" readonly>
                                                        <div class="qtyplus" data-id="<?php echo $prod['cart_id']; ?>" data-price="<?php echo $prod['price']; ?>" data-qty="<?php echo $prod['product_qty']; ?>">+</div>
                                                        <button class="btn btn-danger btn-sm remove-cart-item" data-id="<?php echo $prod['cart_id']; ?>" style="margin-left: 4px; padding: 0 7px;"> <i class="ti-trash"></i> </button>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="text-right product-price" width="150px" data-price="<?php echo $prod['price']; ?>" width="100px"> <?php echo number_format($prod['cart_price'], 2); ?> </td>
                                        </tr>
                                    <?php
                                        $subTotal += $prod['cart_price'];
                                        $loopIndex += 1;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            Total Items
                                        </td>
                                        <td class="text-right">
                                            <?php echo count($CartItems); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            Sub Total
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format($subTotal, 2); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            Delivery Charges
                                        </td>
                                        <td class="text-right">
                                            500.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-right">
                                            Net Amount
                                        </th>
                                        <th class="text-right">
                                            <?php echo number_format($subTotal + 500, 2); ?>
                                        </th>
                                    </tr>
                                </tbody>
                            </table>
                        <?php
                        } else {
                        ?>
                            <br>
                            <br>
                            <div class="text-center">
                                <h5>Your Cart is Empty </h5>
                                <a href="index.php" class="btn btn-outline-success">
                                    Continue Shopping
                                </a>
                            </div>
                            <br>
                            <br>
                        <?php
                        }
                        ?>
                        <br>
                    </div>
                </div>
            </div>
            <div class="col-md-4 bg-white" style="padding: 0px 30px;">
                <br>
                <h4 class="text-center">Checkout</h4>
                <form action="controllers/cartController.php" method="POST">
                    <!-- <input type="hidden" name="merchant_id" value="1">
                    <input type="hidden" name="return_url" value="1">
                    <input type="hidden" name="cancel_url" value="1">
                    <input type="hidden" name="notify_url" value="1">
                    <input type="hidden" name="city" value="1">
                    <input type="hidden" name="country" value="1">
                    <input type="hidden" name="order_id" value="1">
                    <input type="hidden" name="items" value="Cyber Shop Items">
                    <input type="hidden" name="currency" value="LKR">
                    <input type="hidden" name="amount" value="LKR"> -->
                    <input type="hidden" name="checkout_items" value="1">

                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="<?php echo $User['firstName']; ?>" required>
                    <br>
                    <label for="last_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo $User['lastName']; ?>" required>
                    <br>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" class="form-control" name="email" disabled id="email" placeholder="Email Address" value="<?php echo $User['email']; ?>" required>
                    <br>
                    <label for="phone" class="form-label">Mobile Number</label>
                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Mobile Number" value="<?php echo $User['mobile_no'] != '-' ? $User['mobile_no'] : ''; ?>" required>
                    <br>
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="<?php echo $User['delivery_address'] != '-' ? $User['delivery_address'] : ''; ?>" required>
                    <br>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_method_1" value="cod" checked>
                        <label class="form-check-label" for="payment_method_1">
                            Cash On Delivery
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_method_2" value="online">
                        <label class="form-check-label" for="payment_method_2">
                            Pay Online
                        </label>
                    </div>
                    <br>
                    <button type="submit" class="btn <?php echo $CartItems ? 'btn-success' : 'btn-secondary'; ?> col-12" <?php echo $CartItems ? '' : 'disabled'; ?>>
                        Checkout
                    </button>

                </form>
                <br>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $(".qtyminus").on("click", function() {
            var now = $(this).parent().find(".qty").val();
            var cartId = $(this).attr('data-id');
            var price = $(this).attr('data-price');
            var url = 'controllers/cartController.php';

            if ($.isNumeric(now)) {
                if (parseInt(now) - 1 > 0) {
                    now--;
                }
                $(this).parent().find(".qty").val(now);
                var nowCount = $(this).parent().find(".qty").val();

                $.post(url, {
                    cartItemQtyChange: 1,
                    cartId: cartId,
                    count: nowCount,
                    price: price
                }, function(data) {
                    if (data == 1) {
                        window.location.reload();
                    }
                    if (data == 2) {
                        window.location.href = 'login.php';
                    }
                });
            }
        })

        $(".qtyplus").on("click", function() {
            var now = $(this).parent().find(".qty").val();
            var cartId = $(this).attr('data-id');
            var price = $(this).attr('data-price');
            var avalibleQty = $(this).attr('data-qty');
            var url = 'controllers/cartController.php';

            if ($.isNumeric(now)) {
                var nowCount = parseInt($(this).parent().find(".qty").val()) + 1;

                if (now <= avalibleQty - 1) {

                    $(this).parent().find(".qty").val(nowCount);
                    $.post(url, {
                        cartItemQtyChange: 1,
                        cartId: cartId,
                        count: nowCount,
                        price: price
                    }, function(data) {
                        if (data == 1) {
                            window.location.reload();
                        }
                        if (data == 2) {
                            window.location.href = 'login.php';
                        }
                    });
                }
            }
        });

        $(".remove-cart-item").on("click", function() {
            var cartId = $(this).attr('data-id');
            var url = 'controllers/cartController.php';

            $.post(url, {
                removeCart: 1,
                cartId: cartId
            }, function(data) {
                if (data == 1) {
                    window.location.reload();
                }
                if (data == 2) {
                    window.location.href = 'login.php';
                }
            });
        });

    });
</script>