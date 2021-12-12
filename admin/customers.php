<?php
include '../common/adminHeader.php';
$userQuery = "SELECT * from users WHERE role = 'ROLE_USER' AND status=1";
$userQueryResult = mysqli_query($conn, $userQuery);
$Users = [];

while ($usersData = mysqli_fetch_array($userQueryResult)) {
    $uid = $usersData['id'];
    $ordersQ = mysqli_query($conn, "SELECT * FROM orders WHERE user_id =$uid AND (paypal_paid=1 OR payment_method='cod')");
    $UserOrders = [];

    while ($orderData = mysqli_fetch_array($ordersQ)) {
        $UserOrders[] = $orderData;
    }

    $usersData['user_orders'] = $UserOrders;
    $Users[] = $usersData;
}
?>

<h3>
    Customers
</h3>
<br>

<table class="table table-bordered">
    <thead>
        <tr class="text-center">
            <td> </td>
            <td> Name </td>
            <td> Email </td>
            <td> Orders</td>
            <td> Joined On</td>
            <td> Verified</td>
            <td> Actions </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $loopIndex = 1;
        foreach ($Users as $user) {
            $uid = $user['id'];
        ?>
            <tr>
                <td width="50px" class="text-center"> <?php echo $loopIndex; ?> </td>
                <td> <?php echo $user['firstName'] . ' ' . $user['lastName']; ?> </td>
                <td> <?php echo $user['email']; ?> </td>
                <td class="text-center"> <?php echo count($user['user_orders']); ?> </td>
                <td class="text-center"> <?php echo date_format(date_create($user['created_on']), "Y-m-d") . '<br>'; ?> </td>
                <td class="text-center"> <?php echo $user['verified'] ? '<i class="ti-check-box text-success"></i>' : '<i class="ti-close"></i>'; ?> </td>
                <td width="200px" class="text-right">

                    <a href="customer-orders.php?uid=<?php echo $uid; ?>" class="btn btn-outline-primary btn-sm"> Orders</a>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#customerDetails_<?php echo $user['id']; ?>"> Edit</button>
                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#removeUserModal_<?php echo $user['id']; ?>"> Remove</button>
                </td>
            </tr>
        <?php
            $loopIndex++;
        }
        ?>
    </tbody>
</table>



<?php
$loopIndex = 1;
foreach ($Users as $user) {
    $uid = $user['id'];
?>

    <div class="modal fade " id="customerDetails_<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?php echo $user['firstName'] . ' ' . $user['lastName']; ?> - Details</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="controller/adminController.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                        <input type="hidden" name="edit-user-data" value="1">
                        <input type="hidden" name="redir" value="customers.php">
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name </label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['firstName']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name </label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['lastName']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address </label>
                            <input type="email" class="form-control" id="email" name="email" <?php echo $user['verified'] ? 'disabled' : ''; ?> value="<?php echo $user['email']; ?>" required>
                            <?php echo $user['verified'] ? '<div id="emailHelp" class="form-text">Varified Email can not be edited.</div>' : ''; ?>
                        </div>
                        <div class="mb-3">
                            <label for="mobile_no" class="form-label">Mobile No </label>
                            <input type="text" class="form-control" id="mobile_no" name="mobile_no" value="<?php echo $user['mobile_no']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_address" class="form-label">Delivery Address</label>
                            <textarea class="form-control" id="delivery_address" rows="3" name="delivery_address"><?php echo $user['delivery_address']; ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeUserModal_<?php echo $user['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Remove <?php echo $user['firstName']; ?> </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="controller/adminController.php" method="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <h5 class="text-danger text-center"> Are you sure to remove <?php echo $user['firstName'] . ' ' . $user['lastName']; ?>'s Account </h5>
                            <div class="text-center">This will remove all <?php echo $user['firstName']; ?>'s data in System.</div>
                            <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                            <input type="hidden" name="remove-user" value="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger"> Remove </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
    $loopIndex++;
}
?>

<?php include '../common/adminFooter.php'; ?>