<?php
require_once 'controllers/database.php';
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
}

include 'common/header.php';

$userId = $_SESSION['id'];
$Orders = [];
$userQuery = "SELECT * FROM orders WHERE user_id=$userId ORDER BY id DESC";
$userData = mysqli_query($conn, $userQuery);

while ($order = mysqli_fetch_array($userData)) {
    $orderId = $order['id'];
    $ItemsQuery = "SELECT * FROM order_items WHERE order_id=$orderId";
    $ItemsData = [];
    $sss = mysqli_query($conn, $ItemsQuery);
    while ($Item = mysqli_fetch_array($sss)) {
        $ItemsData[] = $Item;
    }
    $order['products'] = $ItemsData;

    $Orders[] = $order;
    // echo '<pre>';
    // print_r($order);
}


?>

<div class="pd-wrap">
    <div class="container  pt-60">

        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="bg-white" style="padding: 0px 20px;">
                        <br>
                        <h4 class="text-center">My Orders</h4>
                        <br>
                        <?php

                        if ($Orders) {
                        ?>
                            <table class="table table-bordered">
                                <thead>
                                    <tr class="text-center">
                                        <td></td>
                                        <td>Status</td>
                                        <td>Order ID</td>
                                        <td>Payment Type</td>
                                        <td>Items</td>
                                        <td>Ordered On</td>
                                        <td>Amount</td>
                                        <td>Details</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $subTotal = 0;
                                    $loopIndex = 1;
                                    foreach ($Orders as $prod) {
                                        if ($prod['payment_method'] == 'cod' or ($prod['payment_method'] == 'online' and $prod['paypal_paid'])) {
                                    ?>
                                            <tr>
                                                <td width="50px" class="text-center"> <?php echo $loopIndex; ?> </td>
                                                <td width="200px" class="text-left">
                                                    <?php
                                                    if ($prod['status'] == 1) {
                                                        echo "<div class='text-success'><i class='ti-bag'></i> Order processing. </div>";
                                                    } elseif ($prod['status'] == 2) {
                                                        echo "<div class='text-success'><i class='ti-truck'></i> Order Shipped. </div>";
                                                    } elseif ($prod['status'] == 3) {
                                                        echo "<div class='text-success'><i class='ti-check'></i> Order Deliverd. </div>";
                                                    }
                                                    ?>
                                                </td>
                                                <td width=100px>
                                                    <div class="text-center">
                                                        <small class="product-title">#<?php echo $prod['id']; ?></small>
                                                    </div>
                                                </td>
                                                <td width=150px>
                                                    <div class="text-center">
                                                        <small class="product-title">
                                                            <?php
                                                            if ($prod['payment_method'] == 'cod') {
                                                                echo "<i class='ti-location-pin'></i> COD";
                                                            } else {
                                                                echo "<i class='ti-credit-card'></i> ONLINE";
                                                            }
                                                            ?>
                                                        </small>
                                                    </div>
                                                </td>

                                                <td width=50px>
                                                    <div class="text-center">
                                                        <small class="product-title"><?php echo count($prod['products']); ?></small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-center">
                                                        <small class="product-title"><?php echo date_format(date_create($prod['created_on']), "Y/m/d h:i:s A"); ?></small>
                                                    </div>
                                                </td>

                                                <td class="text-right product-price">
                                                    <?php echo number_format($prod['price'], 2); ?>
                                                </td>

                                                <td width="50" class="text-right product-price">
                                                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#orderDetails_<?php echo $prod['id']; ?>">
                                                        Details
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php
                                            $subTotal += $prod['price'];
                                            $loopIndex += 1;
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <br>
                        <?php

                        } else {
                        ?>
                            <br>
                            <br>
                            <div class="text-center">
                                <h5> You have not placed any orders yet </h5>
                                <a href="index.php" class="btn btn-outline-success">
                                    Continue Shopping
                                </a>
                            </div>
                            <br>
                            <br>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
foreach ($Orders as $order) {
    if ($order['payment_method'] == 'cod' or ($order['payment_method'] == 'online' and $order['paypal_paid'])) {
?>
        <div class="modal fade " id="orderDetails_<?php echo $order['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Order <?php echo $order['id']; ?> - Details</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
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
                                    $index = 1;
                                    $subTotal = 0;
                                    foreach ($order['products'] as $prod) {
                                    ?>
                                        <tr>
                                            <td width="50px" class="text-center"> <?php echo $index; ?> </td>
                                            <td width=150px>
                                                <div class="text-center">
                                                    <img class="product-image" src="../<?php echo $prod['images']; ?>" width="60px;">
                                                </div>
                                            </td>
                                            <td>
                                                <b class="product-title"><?php echo $prod['title']; ?></b>
                                                <br>
                                                <span style="margin-top: 5px; display: block;"><?php echo '<small> Rs. ' . number_format(($prod['price'] / $prod['qty']), 2); ?> X <?php echo $prod['qty'] . '</small>'; ?></span>
                                            </td>
                                            <td class="text-right product-price" width="150px" width="100px">
                                                <?php echo number_format($prod['price'], 2); ?>
                                            </td>
                                        </tr>
                                    <?php
                                        $subTotal += $prod['price'];
                                        $index += 1;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="4">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-left">
                                            Delivery Address
                                        </td>
                                        <td class="text-right">
                                            Total Items
                                        </td>
                                        <td class="text-right">
                                            <?php echo count($order['products']); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" rowspan="3" class="text-left">
                                            <?php echo nl2br($order['delivery_address']); ?>
                                        </td>
                                        <td class="text-right">
                                            Sub Total
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format($subTotal, 2); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="1" class="text-right">
                                            Delivery Charges
                                        </td>
                                        <td class="text-right">
                                            500.00
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="1" class="text-right">
                                            Net Amount
                                        </th>
                                        <th class="text-right">
                                            <?php echo number_format($subTotal + 500, 2); ?>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right">
                                            Payment Type
                                        </td>
                                        <td class="text-right">
                                            <?php
                                            if ($order['payment_method'] == 'cod') {
                                                echo "<i class='ti-location-pin'></i> COD";
                                            } else {
                                                echo "<i class='ti-credit-card'></i> ONLINE";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>