<?php
include '../common/adminHeader.php';

$ProductsQuery = "SELECT * FROM products";
$ProductsData = mysqli_query($conn, $ProductsQuery);
$Products = mysqli_fetch_array($ProductsData);

$OrdersQuery = "SELECT DATE_FORMAT(created_on, '%M-%d') as dddate FROM orders GROUP By dddate";
$OrdersData = mysqli_query($conn, $OrdersQuery);

$days = [];
$daySales = [];

while ($orders = mysqli_fetch_array($OrdersData)) {
    $days[] = $orders['dddate'];
    $fdate = date_format(date_create($orders['dddate']), 'Y-m-d');
    $OrdersCountQuery = "SELECT count(id) as cc FROM orders WHERE created_on LIKE '$fdate%'";

    $OrdersCountData = mysqli_query($conn, $OrdersCountQuery);
    while ($count = mysqli_fetch_array($OrdersCountData)) {
        $daySales[] = (int)$count['cc'];
    }
}

$chartADates = json_encode($days);
$chartAValues = json_encode($daySales);




$CatQuery = "SELECT * FROM categories;";
$CatData = mysqli_query($conn, $CatQuery);

$cats = [];
$catsSales = [];

while ($ccat = mysqli_fetch_array($CatData)) {
    $cats[] = $ccat['name'];
    $ccid = $ccat['id'];
    $catsOrdersCount = "SELECT count(id) as cc FROM order_items WHERE category_id =$ccid GROUP By category_id";

    $catsOrdersCountData = mysqli_query($conn, $catsOrdersCount);
    if ($catsOrdersCountData) {
        while ($ccount = mysqli_fetch_array($catsOrdersCountData)) {
            $catsSales[] = $ccount['cc'];
        }
    } else {
        $catsSales[] = 0;
    }
}

$chartBDates = json_encode($cats);
$chartBValues = json_encode($catsSales);


$CatQuery = "SELECT * FROM categories;";
$CatData = mysqli_query($conn, $CatQuery);

$ordTypes = ["Online Orders", "COD Orders"];
$ordTypesSales = [];


$onlineQQ = "SELECT count(id) as cc FROM orders WHERE payment_method = 'online'";
$onlineQQResult = mysqli_query($conn, $onlineQQ);
$onlineQQResultArray  = mysqli_fetch_array($onlineQQResult);
$ordTypesSales[] = $onlineQQResultArray['cc'];

$codQQ = "SELECT count(id) as cc FROM orders WHERE payment_method = 'cod'";
$codQQResult = mysqli_query($conn, $codQQ);
$codQQResultArray  = mysqli_fetch_array($codQQResult);
$ordTypesSales[] = $codQQResultArray['cc'];

$chartCDates = json_encode($ordTypes);
$chartCValues = json_encode($ordTypesSales);

?>
<link rel="stylesheet" href="../css/admin-dashboard.css" charset="utf-8" />

<div class="">
    <div class="container-fluid">
        <h2> Admin Dashboard </h2>
        <br>
        <?php exit(); ?>
        <!-- Area Chart Example-->
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-area-chart"></i> Daily Sales Chart</div>
            <div class="card-body">
                <canvas id="myAreaChart" width="100%" height="30"></canvas>
            </div>
            <div class="card-footer small text-muted">Updated at <?php echo date("h:i A"); ?></div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <!-- Example Bar Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-bar-chart"></i> Category Wise Sales </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 my-auto">
                                <canvas id="myBarChart" width="100" height="50"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer small text-muted">Updated at <?php echo date("h:i A"); ?></div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Example Pie Chart Card-->
                <div class="card mb-3">
                    <div class="card-header">
                        <i class="fa fa-pie-chart"></i> Order Type </div>
                    <div class="card-body">
                        <canvas id="myPieChart" width="100%" height="100"></canvas>
                    </div>
                    <div class="card-footer small text-muted">Updated at <?php echo date("h:i A"); ?></div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            $(document).ready(function() {

                var ctx = document.getElementById("myAreaChart");
                var myLineChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?php echo $chartADates; ?>,
                        datasets: [{
                            label: "Orders",
                            lineTension: 0.3,
                            backgroundColor: "rgba(2,117,216,0.2)",
                            borderColor: "rgba(2,117,216,1)",
                            pointRadius: 5,
                            pointBackgroundColor: "rgba(2,117,216,1)",
                            pointBorderColor: "rgba(255,255,255,0.8)",
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: "rgba(2,117,216,1)",
                            pointHitRadius: 20,
                            pointBorderWidth: 2,
                            data: <?php echo $chartAValues; ?>,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'date'
                                },
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    maxTicksLimit: 7
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    max: 40000,
                                    maxTicksLimit: 5
                                },
                                gridLines: {
                                    color: "rgba(0, 0, 0, .125)",
                                }
                            }],
                        },
                        legend: {
                            display: false
                        }
                    }
                });


                // -- Bar Chart Example
                var ctx = document.getElementById("myBarChart");
                var myLineChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?php echo $chartBDates; ?>,
                        datasets: [{
                            label: "Category",
                            backgroundColor: "rgba(2,117,216,1)",
                            borderColor: "rgba(2,117,216,1)",
                            data: <?php echo $chartBValues; ?>,
                        }],
                    },
                    options: {
                        scales: {
                            xAxes: [{
                                time: {
                                    unit: 'month'
                                },
                                gridLines: {
                                    display: false
                                },
                                ticks: {
                                    maxTicksLimit: 6
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    min: 0,
                                    max: 15000,
                                    maxTicksLimit: 5
                                },
                                gridLines: {
                                    display: true
                                }
                            }],
                        },
                        legend: {
                            display: false
                        }
                    }
                });


                // -- Pie Chart Example
                var ctx = document.getElementById("myPieChart");
                var myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo $chartCDates; ?>,
                        datasets: [{
                            data: <?php echo $chartCValues; ?>,
                            backgroundColor: ['#ffc107', '#28a745', '#ffc107', '#28a745'],
                        }],
                    },
                });
            });
        </script>
        <?php include '../common/adminFooter.php'; ?>