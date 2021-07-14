<?php
include '../common/adminHeader.php';

$userQuery = "SELECT * from users WHERE role = 'ROLE_ADMIN' AND status=1";
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

<button class="btn btn-success pull-right" data-bs-toggle="modal" data-bs-target="#newAdmin">
    <i class="fa fa-plus"></i> Add New Admin
</button>
<h3>
    Admins
</h3>
<br>
<table class="table table-bordered">
    <thead>
        <tr class="text-center">
            <td> </td>
            <td> Name </td>
            <td> Email </td>
            <td> Joined On</td>
            <td> Actions </td>
        </tr>
    </thead>
    <tbody>
        <?php
        $loopIndex = 1;
        foreach ($Users as $user) {
            $uid = $user['id'];
            if ($uid != $_SESSION['id']) {
        ?>
                <tr>
                    <td width="50px" class="text-center"> <?php echo $loopIndex; ?> </td>
                    <td> <?php echo $user['firstName'] . ' ' . $user['lastName']; ?> </td>
                    <td> <?php echo $user['email']; ?> </td>
                    <td class="text-center"> <?php echo date_format(date_create($user['created_on']), "Y-m-d") . '<br>'; ?> </td>
                    <td width="200px" class="text-right">
                        <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#customerDetails_<?php echo $user['id']; ?>"> Edit</button>
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#removeUserModal_<?php echo $user['id']; ?>"> Remove</button>
                    </td>
                </tr>
        <?php
                $loopIndex++;
            }
        }
        ?>
    </tbody>
</table>

<div class="modal fade" id="newAdmin" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Admin</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="controller/adminController.php" method="POST">
                <div class="modal-body">
                    <div id="errors">

                    </div>
                    <input type="hidden" name="create_new_admin" value="1">
                    <div class="mb-3">
                        <label for="new_first_name" class="form-label">First Name </label>
                        <input type="text" class="form-control" id="new_first_name" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_last_name" class="form-label">Last Name </label>
                        <input type="text" class="form-control" id="new_last_name" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_email" class="form-label">Email Address </label>
                        <input type="email" class="form-control" id="new_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_1" class="form-label">Password </label>
                        <input type="password" class="form-control" id="new_password_1" name="password_1" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_2" class="form-label">Password Repeat</label>
                        <input type="text" class="form-control" id="new_password_2" rows="3" name="password_2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>


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
                        <input type="hidden" name="redir" value="admins.php">
                        <div class="mb-3">
                            <label for="first_name_<?php echo $user['id']; ?>" class="form-label">First Name </label>
                            <input type="text" class="form-control" id="first_name<?php echo $user['id']; ?>" name="first_name" value="<?php echo $user['firstName']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name_<?php echo $user['id']; ?>" class="form-label">Last Name </label>
                            <input type="text" class="form-control" id="last_name_<?php echo $user['id']; ?>" name="last_name" value="<?php echo $user['lastName']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email_<?php echo $user['id']; ?>" class="form-label">Email Address </label>
                            <input type="email" class="form-control" id="email_<?php echo $user['id']; ?>" name="email" <?php echo $user['verified'] ? 'disabled' : ''; ?> value="<?php echo $user['email']; ?>" required>
                            <?php echo $user['verified'] ? '<div id="emailHelp" class="form-text">Varified Email can not be edited.</div>' : ''; ?>
                        </div>
                        <div class="mb-3">
                            <label for="mobile_no_<?php echo $user['id']; ?>" class="form-label">Mobile No </label>
                            <input type="text" class="form-control" id="mobile_no_<?php echo $user['id']; ?>" name="mobile_no" value="<?php echo $user['mobile_no']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="delivery_address_<?php echo $user['id']; ?>" class="form-label">Address</label>
                            <textarea class="form-control" id="delivery_address_<?php echo $user['id']; ?>" rows="3" name="delivery_address"><?php echo $user['delivery_address']; ?></textarea>
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
<script>
    $(document).ready(function() {
        $("#newAdmin form").on('submit', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            $.ajax({
                type: 'POST',
                url: url,
                data: $('#newAdmin form').serialize(),
                success: function(response) {
                    if (response == 1) {
                        $("#errors").html('<div class="alert alert-success alert-dismissible fade show" role="alert"> New Admin Created Successfully <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        setTimeout(function() {
                            window.location.reload();
                        }, 500)

                    } else {
                        $("#errors").html(response);
                    }
                }
            });
        });
    });
</script>
<?php include '../common/adminFooter.php'; ?>