<?php
require_once 'controllers/database.php';
$onlineOrder = 0;
if (isset($_GET['order_id'])) {

    $onlineOrder = 1;
    $orderId = $_GET['order_id'];
    $query = "UPDATE orders SET paypal_paid=1 WHERE id =$orderId";

    if ($conn->query($query) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }
}

include 'common/header.php';

?>
<style>
    .background {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: linear-gradient(transparent, rgba(0, 0, 0, 0.5)), url("https://images.pexels.com/photos/4827/nature-forest-trees-fog.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=750&w=1260");
        background-size: cover;
        background-position: center;
    }

    .modalbox.success,
    .modalbox.error {
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        background: #fff;
        padding: 25px 25px 15px;
        text-align: center;
    }

    .modalbox.success.animate .icon,
    .modalbox.error.animate .icon {
        -webkit-animation: fall-in 0.75s;
        -moz-animation: fall-in 0.75s;
        -o-animation: fall-in 0.75s;
        animation: fall-in 0.75s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
    }

    .modalbox.success h1,
    .modalbox.error h1 {
        font-family: 'Montserrat', sans-serif;
    }

    .modalbox.success p,
    .modalbox.error p {
        font-family: 'Open Sans', sans-serif;
    }

    .modalbox.success button,
    .modalbox.error button,
    .modalbox.success button:active,
    .modalbox.error button:active,
    .modalbox.success button:focus,
    .modalbox.error button:focus {
        -webkit-transition: all 0.1s ease-in-out;
        transition: all 0.1s ease-in-out;
        -webkit-border-radius: 30px;
        -moz-border-radius: 30px;
        border-radius: 30px;
        margin-top: 15px;
        width: 80%;
        background: transparent;
        color: #4caf50;
        border-color: #4caf50;
        outline: none;
    }

    .modalbox.success button:hover,
    .modalbox.error button:hover,
    .modalbox.success button:active:hover,
    .modalbox.error button:active:hover,
    .modalbox.success button:focus:hover,
    .modalbox.error button:focus:hover {
        color: #fff;
        background: #4caf50;
        border-color: transparent;
    }

    .modalbox.success .icon,
    .modalbox.error .icon {
        position: relative;
        margin: 0 auto;
        margin-top: -75px;
        background: #4caf50;
        height: 100px;
        width: 100px;
        border-radius: 50%;
        padding: 22px;
    }

    .icon i {
        font-size: 57px;
        color: white;
    }

    .modalbox.success .icon span,
    .modalbox.error .icon span {
        position: absolute;
        font-size: 4em;
        color: #fff;
        text-align: center;
        padding-top: 20px;
    }

    .modalbox.error button,
    .modalbox.error button:active,
    .modalbox.error button:focus {
        color: #f44336;
        border-color: #f44336;
    }

    .modalbox.error button:hover,
    .modalbox.error button:active:hover,
    .modalbox.error button:focus:hover {
        color: #fff;
        background: #f44336;
    }

    .modalbox.error .icon {
        background: #f44336;
    }

    .modalbox.error .icon span {
        padding-top: 25px;
    }

    .center {
        float: none;
        margin-left: auto;
        margin-right: auto;
        /* stupid browser compat. smh */
        margin-top: 39vh;
    }

    .center .change {
        clear: both;
        display: block;
        font-size: 10px;
        color: #ccc;
        margin-top: 10px;
    }

    @-webkit-keyframes fall-in {
        0% {
            -ms-transform: scale(3, 3);
            -webkit-transform: scale(3, 3);
            transform: scale(3, 3);
            opacity: 0;
        }

        50% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
            opacity: 1;
        }

        60% {
            -ms-transform: scale(1.1, 1.1);
            -webkit-transform: scale(1.1, 1.1);
            transform: scale(1.1, 1.1);
        }

        100% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }

    @-moz-keyframes fall-in {
        0% {
            -ms-transform: scale(3, 3);
            -webkit-transform: scale(3, 3);
            transform: scale(3, 3);
            opacity: 0;
        }

        50% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
            opacity: 1;
        }

        60% {
            -ms-transform: scale(1.1, 1.1);
            -webkit-transform: scale(1.1, 1.1);
            transform: scale(1.1, 1.1);
        }

        100% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }

    @-o-keyframes fall-in {
        0% {
            -ms-transform: scale(3, 3);
            -webkit-transform: scale(3, 3);
            transform: scale(3, 3);
            opacity: 0;
        }

        50% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
            opacity: 1;
        }

        60% {
            -ms-transform: scale(1.1, 1.1);
            -webkit-transform: scale(1.1, 1.1);
            transform: scale(1.1, 1.1);
        }

        100% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }

    @-webkit-keyframes plunge {
        0% {
            margin-top: -100%;
        }

        100% {
            margin-top: 25%;
        }
    }

    @-moz-keyframes plunge {
        0% {
            margin-top: -100%;
        }

        100% {
            margin-top: 25%;
        }
    }

    @-o-keyframes plunge {
        0% {
            margin-top: -100%;
        }

        100% {
            margin-top: 25%;
        }
    }

    @-moz-keyframes fall-in {
        0% {
            -ms-transform: scale(3, 3);
            -webkit-transform: scale(3, 3);
            transform: scale(3, 3);
            opacity: 0;
        }

        50% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
            opacity: 1;
        }

        60% {
            -ms-transform: scale(1.1, 1.1);
            -webkit-transform: scale(1.1, 1.1);
            transform: scale(1.1, 1.1);
        }

        100% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }

    @-webkit-keyframes fall-in {
        0% {
            -ms-transform: scale(3, 3);
            -webkit-transform: scale(3, 3);
            transform: scale(3, 3);
            opacity: 0;
        }

        50% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
            opacity: 1;
        }

        60% {
            -ms-transform: scale(1.1, 1.1);
            -webkit-transform: scale(1.1, 1.1);
            transform: scale(1.1, 1.1);
        }

        100% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }

    @-o-keyframes fall-in {
        0% {
            -ms-transform: scale(3, 3);
            -webkit-transform: scale(3, 3);
            transform: scale(3, 3);
            opacity: 0;
        }

        50% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
            opacity: 1;
        }

        60% {
            -ms-transform: scale(1.1, 1.1);
            -webkit-transform: scale(1.1, 1.1);
            transform: scale(1.1, 1.1);
        }

        100% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }

    @keyframes fall-in {
        0% {
            -ms-transform: scale(3, 3);
            -webkit-transform: scale(3, 3);
            transform: scale(3, 3);
            opacity: 0;
        }

        50% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
            opacity: 1;
        }

        60% {
            -ms-transform: scale(1.1, 1.1);
            -webkit-transform: scale(1.1, 1.1);
            transform: scale(1.1, 1.1);
        }

        100% {
            -ms-transform: scale(1, 1);
            -webkit-transform: scale(1, 1);
            transform: scale(1, 1);
        }
    }

    @-moz-keyframes plunge {
        0% {
            margin-top: -100%;
        }

        100% {
            margin-top: 15%;
        }
    }

    @-webkit-keyframes plunge {
        0% {
            margin-top: -100%;
        }

        100% {
            margin-top: 15%;
        }
    }

    @-o-keyframes plunge {
        0% {
            margin-top: -100%;
        }

        100% {
            margin-top: 15%;
        }
    }

    @keyframes plunge {
        0% {
            margin-top: -100%;
        }

        100% {
            margin-top: 15%;
        }
    }
</style>

<div class="container">
    <div class="row">
        <div class="modalbox success col-sm-8 col-md-6 col-lg-5 center animate">
            <div class="icon">
                <i class="ti-check"></i>
            </div>
            <!--/.icon-->
            <br>
            <?php
            if ($onlineOrder) {
            ?>
                <h1> Payment Success</h1>
                <p>
                    <b>We received your order and payment successfully</b>
                    <br>
                    Your order will arrive at your doorstep within 7-10 working days of the week
                </p>
            <?php
            } else {
            ?>
                <h1> Order Confirmed</h1>
                <p>
                    <b>We received your order successfully</b>
                    <br>
                    Your order will arrive at your doorstep within 7-10 working days of the week
                </p>
            <?php
            }
            ?>

            <a href="my-orders.php" type="button" class="redo btn btn-outline-success">
                View My Orders
            </a>
            <br>
            <br>
        </div>
        <!--/.success-->
    </div>
</div>
<!--/.container-->