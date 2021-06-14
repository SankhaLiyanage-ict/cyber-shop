<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo 'Method not allowed';
}

if (!isset($_SESSION['id'])) {
    echo 2;
    exit();
}

// Remove form Cart

if (isset($_POST['removeCart'])) {
    $cartId = $_POST['cartId'];

    $sql = "DELETE FROM cart WHERE id = '$cartId'";

    if ($conn->query($sql) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    echo 1;
    exit();
}

// Plus and Minus Cart Item

if (isset($_POST['cartItemQtyChange'])) {
    $cartId = $_POST['cartId'];
    $count = $_POST['count'];
    $price = $_POST['price'] * $count;

    $query = "UPDATE cart SET qty ='$count', price='$price' WHERE id = '" . $cartId . "'";

    if ($conn->query($query) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    echo 1;
    exit();
}

// Checkout Items

if (isset($_POST['checkout_items'])) {


    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];
    $userId = $_SESSION['id'];
    $userEmail = $_SESSION['email'];

    $query = "UPDATE users SET firstName ='$first_name', lastName='$last_name', mobile_no='$phone', delivery_address='$address' WHERE id =$userId";

    if ($conn->query($query) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    $cartQuery = "SELECT sum(price) as cart_amount from cart WHERE user_id=$userId";
    $cartQueryResult = mysqli_query($conn, $cartQuery);

    $CartAmount = mysqli_fetch_array($cartQueryResult)['cart_amount'];

    if (!$CartAmount) {
        header('Location: ../index.php');
        exit();
    }

    $paypal_email = '-';
    $paypal_paid = 0;
    $cod_paid = 1;

    if ($payment_method == 'online') {
        $paypal_email = $userEmail;
        $paypal_paid = 0;
        $cod_paid = 0;
    }

    $orderCreateQuery = "INSERT INTO orders (user_id, price, delivery_address, payment_method, paypal_email, paypal_paid, cod_paid, delivered, status) 
    VALUES ('$userId','$CartAmount','$address','$payment_method','$paypal_email', $paypal_paid, $cod_paid, 0, 1)";

    if ($conn->query($orderCreateQuery) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    $lastOrderQuery = "SELECT * from orders WHERE user_id=$userId ORDER BY id DESC LIMIT 1";
    $lastOrderQueryResult = mysqli_fetch_array(mysqli_query($conn, $lastOrderQuery));
    $lastOrderId = $lastOrderQueryResult['id'];

    $CartItemsQuery = "SELECT products.*, cart.id as cart_id, cart.qty as cart_qty, cart.price as cart_price from cart INNER JOIN products ON cart.product_id = products.id WHERE user_id=$userId";
    $CartItemsQueryResult = mysqli_query($conn, $CartItemsQuery);
    $CartItems = [];
    $orderItems = [];

    while ($product = mysqli_fetch_array($CartItemsQueryResult)) {
        $title = $product['title'];
        $description = $product['description'];
        $cart_qty = $product['cart_qty'];
        $cart_price = $product['cart_price'];
        $images = $product['images'];
        $category_id = $product['category'];
        $orderItems[] = $product['title'];

        $orderQuery = "INSERT INTO order_items (order_id, title, description, price, images, category_id, qty, status) 
    VALUES ($lastOrderId,'$title','$description','$cart_price', '$images', $category_id , $cart_qty, 1)";

        if ($conn->query($orderQuery) == FALSE) {
            echo "Error" . $sql . $conn->error;
            exit();
        }

        $productNewQty = $product['qty'] - $product['cart_qty'];
        $pid = $product['id'];

        $productQtyUpdate = "UPDATE products SET qty ='$productNewQty' WHERE id =$pid";

        if ($conn->query($productQtyUpdate) == FALSE) {
            echo "Error" . $sql . $conn->error;
            exit();
        }
    }

    $userCartRemoveQuery = "DELETE FROM cart WHERE user_id = '$userId'";

    if ($conn->query($userCartRemoveQuery) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    if ($payment_method == 'online') {

        $userDetailsQuery = "SELECT * from users WHERE id=$userId LIMIT 1";
        $userDetailsResult = mysqli_fetch_array(mysqli_query($conn, $userDetailsQuery));

        $_SESSION['order_id'] = $lastOrderId;
        $_SESSION['send_gateway'] = 1;
        $_SESSION['order_items'] = implode(',', $orderItems);
        $_SESSION['order_amount'] = $lastOrderQueryResult['price'];
        $_SESSION['user_first_name'] = $userDetailsResult['firstName'];
        $_SESSION['user_second_name'] = $userDetailsResult['lastName'];
        $_SESSION['email'] = $userDetailsResult['email'];
        $_SESSION['user_mobile'] = $userDetailsResult['mobile_no'];
        $_SESSION['user_address'] = $userDetailsResult['delivery_address'];

        header('Location: ../online-order.php');
    }

    if ($payment_method == 'cod') {
        header('Location: ../order-complete.php');
    }
}
