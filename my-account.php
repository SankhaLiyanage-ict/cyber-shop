<?php
require_once 'controllers/database.php';
if (!isset($_SESSION['id'])) {
    header('Location: index.php');
}

$uid = $_SESSION['id'];

// Edit user data
if (isset($_POST['edit-user-data'])) {
    $user_id = $_POST['user_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mobile_no = $_POST['mobile_no'];
    $delivery_address = $_POST['delivery_address'];

    $query = "UPDATE users SET firstName='$first_name', lastName='$last_name', mobile_no='$mobile_no', delivery_address='$delivery_address' WHERE id =$user_id";

    if ($conn->query($query) == FALSE) {
        echo "Error" . $sql . $conn->error;
        exit();
    }

    header("Location: my-account.php");
}

include 'common/header.php';

$userId = $_SESSION['id'];
$Orders = [];
$userQuery = "SELECT * FROM users WHERE id=$userId LIMIT 1";
$userData = mysqli_query($conn, $userQuery);
$user = mysqli_fetch_array($userData);

?>

<div class="pd-wrap">
    <div class="container  pt-60">

        <div class="bg-white" style="padding: 0px 20px;">
            <br>
            <h4 class="text-center">My Profile</h4>
            <br>
            <div class="profile-wapper">
                <form action="" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="user_id" value="<?php echo $uid; ?>">
                        <input type="hidden" name="edit-user-data" value="1">
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
                            <label for="delivery_address_<?php echo $user['id']; ?>" class="form-label">Delivery Address</label>
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
</div>