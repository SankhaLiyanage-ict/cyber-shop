<?php
require_once 'controllers/database.php';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <img alt="vp" class="top-nav-logo" src="logo/logo.png">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- <ul class="navbar-nav mx-auto"> -->
            <!-- <ul class="navbar-nav me-auto mb-2 mb-lg-0"> -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['REQUEST_URI']) == 'index.php' ? 'active' : '' ?>" aria-current="page" href="index.php">
                        <i class="ti-home"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['REQUEST_URI']) == 'categories.php' ? 'active' : '' ?>" href="categories.php">
                        <i class="ti-mobile"></i> Categories
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav mx-auto">
                <?php
                $searchQQ = '';
                $searchPP = 'lth';
                $searchCC = 0;
                if (isset($_GET['s']) and $_GET['s']) {
                    $searchQQ = $_GET['s'];
                }
                if (isset($_GET['p']) and $_GET['p']) {
                    $searchPP = $_GET['p'];
                }
                if (isset($_GET['c']) and $_GET['c']) {
                    $searchCC = $_GET['c'];
                }
                ?>
                <li class="nav-item">
                    <div class="nav-search-div">
                        <form action="search.php">
                            <span>Search </span>
                            <input type="search" name="s" value="<?php echo $searchQQ; ?>" class="form-control">
                            <input type="hidden" name="p" value="<?php echo $searchPP ?>" checked>
                            <input type="hidden" name="c" value="<?php echo $searchCC ?>" checked>
                            <i class="ti-search"></i>
                        </form>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php
                if (isset($_SESSION['email'])) {
                    $userId = $_SESSION['id'];
                    $cartQuery = "SELECT count(id) as cc FROM cart WHERE user_id=$userId";
                    $cartData = mysqli_fetch_array(mysqli_query($conn, $cartQuery));
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="ti-shopping-cart"></i>
                            Cart <span class="cart-qty"><?php echo $cartData['cc']; ?></span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti-user"></i> <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <?php
                            if (isset($_SESSION['id']) and $_SESSION['role'] == 'ROLE_ADMIN') {
                                echo '<li><a class="dropdown-item" href="../admin/index.php"> Admin </a></li>';
                            }
                            ?>
                            <li><a class="dropdown-item" href="my-orders.php"> My Orders </a></li>
                            <li><a class="dropdown-item" href="my-account.php"> My Profile </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="logout.php">
                                    <i class="ti-back-left"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php
                } else {
                ?>
                    <li class="nav-item">
                        <a class="btn btn-outline-success" href="signup.php" tabindex="-1" style="margin-right: 6px;">
                            Register
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success" href="login.php" tabindex="-1">
                            Login
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>
</nav>
<!-- 
<header>
    <div class="headerlogo">
        <a href="index.php">
            <img alt="vp" src="images/Logo.png">
        </a>
    </div>
    <nav class="navwrapper">
        <ul class="navbar">

            <li><a href="index.php"> Home </a> </li>
            <li><a href="planning.php"> Gallery </a> </li>
            <button class="vendorDrop">
                <li><a href="displayServ.php">Phones</a></li>
            </button>
            <div class="dropdowncontent">
                <a href="#">Link 1</a>
                <a href="#">Link 2</a>
                <a href="#">Link 3</a>
            </div>
            <li><a href="venues.php"> TV </a> </li>
            <li><a href="wemen.php"> Printers </a> </li>
            <li><a href="reviews.php"> Reviews </a> </li>
            <li><a href="contactus.php"> Contact Us </a> </li>
            <li><a href="aboutus.php"> About Us </a> </li>
            <li>
                <div class="login">
                    <ul>
                        <li>
                            <a href="userAccount.php" style="position: relative;text-transform: none;" title="Go to user account">
                                <?php if (isset($_SESSION['username'])) { ?> Hey <strong><?php echo $_SESSION['username']; ?></strong>
                            </a>
                            <a href="verify-user/logout.php" style="text-transform: none;">Logout</a>
                        <?php } else { ?>
                        </li>
                        <li><a href="verify-user/login.php">Log In</a></li>
                        <li><a href="verify-user/signup.php">Sign Up</a></li>
                    </ul>
                <?php } ?>
                </div>
            </li>
        </ul>
    </nav>
</header> -->