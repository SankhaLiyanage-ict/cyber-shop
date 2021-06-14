<?php
require_once '../controllers/database.php';
if (!isset($_SESSION['id']) or $_SESSION['role'] != 'ROLE_ADMIN') {
    header('Location: ../index.php');
}
?>
<html>

<head>
    <meta charset="utf-8">
    <title>Cyber Shop Admin Panel</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <title> CyberShop Admin </title>
    <link rel="stylesheet" type="text/css" href="../css/themify-icons.css">
    <link rel="icon" href="../favicon.ico">
    <link rel="stylesheet" href="../css/admin.css" charset="utf-8" />

    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">
            <img alt="vp" class="top-nav-logo" src="../logo/logo.png">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti-user"></i> <?php echo $_SESSION['username']; ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="/admin/index.php">Admin</a></li>
                    <li><a class="dropdown-item" href="../my-orders.php">My Orders</a></li>
                    <li><a class="dropdown-item" href="../my-account.php">My Profile</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item" href="../logout.php">
                            <i class="ti-back-left"></i> Logout
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<body class="admin">
    <nav class="admin__sidepanel">
        <ul>
            <li class="<?php echo basename($_SERVER['REQUEST_URI']) == 'index.php' ? 'active-admin-nav' : '' ?>">
                <a href="index.php">
                    <i class="fa fa-file-text-o"></i>
                    Dashboard
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['REQUEST_URI']) == 'products.php' ? 'active-admin-nav' : '' ?> ">
                <a href="products.php">
                    <i class="fa fa-file-text-o"></i>
                    Products
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['REQUEST_URI']) == 'categories.php' ? 'active-admin-nav' : '' ?> ">
                <a href="categories.php">
                    <i class="fa fa-pencil-square-o"></i>
                    Categories
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['REQUEST_URI']) == 'orders.php' ? 'active-admin-nav' : '' ?> ">
                <a href="orders.php">
                    <i class="fa fa-times-circle-o"></i>
                    Orders
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['REQUEST_URI']) == 'customers.php' ? 'active-admin-nav' : '' ?> ">
                <a href="customers.php">
                    <i class="fa fa-plus-square-o"></i>
                    Customers
                </a>
            </li>
            <li class="<?php echo basename($_SERVER['REQUEST_URI']) == 'admins.php' ? 'active-admin-nav' : '' ?> ">
                <a href="admins.php">
                    <i class="fa fa-plus-square-o"></i>
                    Admins
                </a>
            </li>
        </ul>
    </nav>

    <main class="admin__main">