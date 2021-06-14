<?php
include 'controllers/database.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo 'Method not allowed';
}

if (!isset($_SESSION['id'])) {
    echo 4;
    exit();
}

// Add to Cart

if (isset($_POST['productId'])) {
    $productId = $_POST['productId'];
    //valibleQty = $_POST['avalibleQty'];
    $quantity = $_POST['quantity'];
    $productPrice = $_POST['productPrice'] * $quantity;
    $userId = $_SESSION['id'];

    $query = "SELECT * FROM cart WHERE user_id=$userId AND product_id=$productId";
    $result = mysqli_query($conn, $query);
    $Res = mysqli_fetch_array($result);

    if ($Res) {
        echo 2;
        exit();
    }

    $productQuery = "SELECT * FROM products WHERE id=$productId";
    $productQueryRes = mysqli_query($conn, $productQuery);
    $Product = mysqli_fetch_array($productQueryRes);

    if ($Product and ($Product['qty'] < $quantity)) {
        echo 3;
        exit();
    }

    $sql = "INSERT INTO cart (user_id, product_id, qty, price) VALUES ($userId, $productId, $quantity, '$productPrice')";
    if ($conn->query($sql) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    echo 1;
}

exit();
