<?php

include '../common/adminHeader.php';
$ord_status = 0;
$payment_type = 0;
$dates = '';
$order_by = 0;

$Orders = [];
$userQuery = "SELECT orders.*, users.firstName, users.lastName FROM orders INNER JOIN users ON orders.user_id = users.id ";

if (isset($_GET['ord_status']) and $_GET['ord_status']) {
    $ord_status = $_GET['ord_status'];
    $userQuery = $userQuery . " WHERE orders.status=$ord_status ";
}

if (isset($_GET['payment_type']) and $_GET['payment_type']) {
    $payment_type = $_GET['payment_type'];
    if ($ord_status) {
        $userQuery = $userQuery . " AND orders.payment_method='$payment_type' ";
    } else {
        $userQuery = $userQuery . " WHERE orders.payment_method='$payment_type' ";
    }
}

if (isset($_GET['dates']) and $_GET['dates']) {
    $dates = $_GET['dates'];
    $datesArray = explode('-', $dates);
    $sDate = date_format(date_create($datesArray[0]), 'Y-m-d');
    $eDate = date_format(date_create($datesArray[1]), 'Y-m-d');

    if ($ord_status or $payment_type) {
        $userQuery = $userQuery . " AND orders.created_on between '$sDate 00:00:00' and '$eDate 23:59:59' ";
    } else {
        $userQuery = $userQuery . " WHERE orders.created_on between '$sDate 00:00:00' and '$eDate 23:59:59' ";
    }
}

if (isset($_GET['order_by']) and $_GET['order_by']) {
    $order_by = $_GET['order_by'];

    if ($order_by == 1) {
        $userQuery = $userQuery . " ORDER BY orders.id ASC";
    }
    if ($order_by == 2) {
        $userQuery = $userQuery . " ORDER BY orders.price ASC";
    }
    if ($order_by == 3) {
        $userQuery = $userQuery . " ORDER BY orders.price DESC";
    }
    if ($order_by == 4) {
        $userQuery = $userQuery . " ORDER BY orders.created_on DESC";
    }
    if ($order_by == 5) {
        $userQuery = $userQuery . " ORDER BY orders.created_on ASC";
    }
} else {
    $userQuery = $userQuery . " ORDER BY orders.id DESC";
}


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
}
?>

<h3>
    <span id="totalSales" style="float: right; font-size: 24px;"> </span>
    Orders
</h3>
<br>

<form method="GET" id="filterForm">
    <div class="row">
        <div class="col-md-3">
            Order Status
            <select class="form-control" name="ord_status">
                <option value="0" <?php echo $ord_status == 0 ? 'selected' : ''; ?>> All Orders </option>
                <option value="1" <?php echo $ord_status == 1 ? 'selected' : ''; ?>> Dispatching Orders </option>
                <option value="2" <?php echo $ord_status == 2 ? 'selected' : ''; ?>> Delivering Orders </option>
                <option value="3" <?php echo $ord_status == 3 ? 'selected' : ''; ?>> Completed Orders </option>
            </select>
        </div>
        <div class="col-md-3">
            Payment Method
            <select class="form-control" name="payment_type">
                <option value="0" <?php echo $payment_type == 0 ? 'selected' : ''; ?>> All Types </option>
                <option value="cod" <?php echo $payment_type == 'cod' ? 'selected' : ''; ?>> Cash On Delivery </option>
                <option value="online" <?php echo $payment_type == 'online' ? 'selected' : ''; ?>> Online Paid </option>
            </select>
        </div>
        <div class="col-md-3">
            Filter by Dates
            <input type="text" name="dates" value="<?php echo $dates ? $dates : ''; ?>" class="form-control">
        </div>
        <div class="col-md-3">
            Order By
            <select class="form-control" name="order_by">
                <option value="0" <?php echo $order_by == 0 ? 'selected' : ''; ?>> Order Id - DESC </option>
                <option value="1" <?php echo $order_by == 1 ? 'selected' : ''; ?>> Order Id - ASC </option>
                <option value="2" <?php echo $order_by == 2 ? 'selected' : ''; ?>> Order Price - ASC </option>
                <option value="3" <?php echo $order_by == 3 ? 'selected' : ''; ?>> Order Price - DESC </option>
                <option value="4" <?php echo $order_by == 4 ? 'selected' : ''; ?>> Order Date - ASC </option>
                <option value="5" <?php echo $order_by == 5 ? 'selected' : ''; ?>> Order Date - DESC </option>
            </select>
        </div>
    </div>
</form>
<br>
<br>
<table class="table table-bordered">
    <thead>
        <tr class="text-center">
            <td> Order </td>
            <td> Status </td>
            <td> Order User </td>
            <td> Items </td>
            <td> Payment </td>
            <td> Order On </td>
            <td> Price </td>
            <td> Actions </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $subTotalA = 0;
        $loopIndex = 1;
        foreach ($Orders as $prod) {
            if ($prod['payment_method'] == 'cod' or ($prod['payment_method'] == 'online' and $prod['paypal_paid'])) {
        ?>
                <tr>
                    <td width=30px>
                        <div class="text-center">
                            <b class="product-title">#<?php echo $prod['id']; ?></b>
                        </div>
                    </td>
                    <td width="150px" class="text-left">
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

                    <td width=200px>
                        <?php
                        echo "<i class='ti-user'></i> " . $prod['firstName'] . ' ' . $prod['lastName'];
                        ?>
                    </td>



                    <td width=50px>
                        <div class="text-center">
                            <small class="product-title"><?php echo count($prod['products']); ?></small>
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
                    <td>
                        <div class="text-center">
                            <small class="product-title">
                                <?php echo date_format(date_create($prod['created_on']), "Y-m-d") . '<br>'; ?>
                                <?php echo '<small>' . date_format(date_create($prod['created_on']), "h:i:s A") . '</small>'; ?>
                            </small>
                        </div>
                    </td>

                    <td class="text-right product-price">
                        <?php echo number_format($prod['price'], 2); ?>
                    </td>

                    <td width="50" class="text-right product-price">
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#orderDetails_<?php echo $prod['id']; ?>">
                            Details
                        </button>
                    </td>
                </tr>
        <?php
                $subTotalA += $prod['price'];
                $loopIndex += 1;
            }
        }
        ?>
    </tbody>
</table>

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
                                    <tr>
                                        <td colspan="2">
                                            Order Status
                                        </td>
                                        <td>
                                            <select class="form-control update-order-status">
                                                <option value="1" <?php echo ($order['status'] == 2 or $order['status'] == 3) ? 'disabled' : ''; ?>> Order processing. </option>
                                                <option value="2" <?php echo ($order['status'] == 3) ? 'disabled' : ''; ?>> Order Shipped. </option>
                                                <option value="3"> Order Deliverd. </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn btn-success col-12 update-status" data-oid="<?php echo $order['id']; ?>">
                                                Update
                                            </button>
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


<script>
    $(document).ready(function() {
        var totalSales = '<?php echo number_format($subTotalA, 2); ?>';
        $('#totalSales').html('Rs. ' + totalSales);
        $('input[name="dates"]').daterangepicker();

        $("#filterForm input, #filterForm select").on('change', function(e) {
            $("#filterForm").submit();
        });

        $(".update-status").click(function() {
            var selected_status = $(this).parent().prev('td').find('.update-order-status').val();
            var order_id = $(this).attr('data-oid');

            $.post(window.location.pathname, {
                selected_status: selected_status,
                order_id: order_id
            }, function(d) {
                if (d == 1) {
                    window.location.reload();
                }
            });

        });

    });
</script>

<?php include '../common/adminFooter.php'; ?>