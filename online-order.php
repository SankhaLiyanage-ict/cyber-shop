<?php
require_once 'controllers/database.php';

if (!isset($_SESSION['send_gateway'])) {
    header('Location: index.php');
}

include 'common/header.php';
$return_url = str_replace('online-order.php', 'order-complete.php', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$cancel_url = str_replace('online-order.php', 'my-orders.php', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
$notify_url = str_replace('online-order.php', 'order-complete.php', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

?>

<form method="post" action="https://sandbox.payhere.lk/pay/checkout" style="display: none;">
    <input type="hidden" name="merchant_id" value="1217544">
    <input type="hidden" name="return_url" value="<?php echo $return_url; ?>">
    <input type="hidden" name="cancel_url" value="<?php echo $cancel_url; ?>">
    <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>">
    <br><br>Item Details<br>
    <input type="text" name="order_id" value="<?php echo $_SESSION['order_id']; ?>">
    <input type="text" name="items" value="<?php echo $_SESSION['order_items']; ?>"><br>
    <input type="text" name="currency" value="LKR">
    <input type="text" name="amount" value="<?php echo $_SESSION['order_amount']; ?>">
    <br><br>Customer Details<br>
    <input type="text" name="first_name" value="<?php echo $_SESSION['user_first_name']; ?>">
    <input type="text" name="last_name" value="<?php echo $_SESSION['user_second_name']; ?>"><br>
    <input type="text" name="email" value="<?php echo $_SESSION['email']; ?>">
    <input type="text" name="phone" value="<?php echo $_SESSION['user_mobile']; ?>"><br>
    <input type="text" name="address" value="<?php echo $_SESSION['user_address']; ?>">
    <input type="text" name="city" value="Colombo">
    <input type="hidden" name="country" value="Sri Lanka"><br><br>
</form>
<br>
<br>
<br>
<br>
<br>
<div class="form-group text-center">
    <small class="text-center">
        <strong> We will be redirecting you to our secure Payment Gateway in a few moments to make the payment for your order. </strong> </small>
    <br>
    <br>
    <span class="text-muted"><small> You will be redirect after <span id="spnSeconds">5</span> seconds. </small></span>
    <br>
</div>

<script>
    $(document).ready(function() {
        window.setInterval(function() {
            var iTimeRemaining = $("#spnSeconds").html();
            iTimeRemaining = eval(iTimeRemaining);
            if (iTimeRemaining == 0) {
                //location.href = "login.php";
                $('form').submit();
            } else {
                $("#spnSeconds").html(iTimeRemaining - 1);
            }
        }, 1000);
    });
</script>