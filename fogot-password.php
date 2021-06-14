<?php include 'controllers/authController.php';
include 'common/header.php';
?>

<div class="container">
    <div class="row">
        <div class="login-main-wapper">
            <div class="login-wapper">
                <div class="col-md-12 text-center">
                    <img src="logo/logo.png" width="250px">
                </div>
                <br>
                <?php
                if (isset($_SESSION['reset_link_send']) and $_SESSION['reset_link_send']) {
                ?>
                    <h3 class="text-center form-title"> Reset Link has been sent to your email address. </h3>
                    <p class="text-center">
                        <form action="fogot-password.php" method="POST">
                            <div class="form-group text-center">
                                <small class="text-center">
                                    The link to change your password has been sent to your email address. Please check your mail. </small>
                                <br>
                                <br>
                            </div>
                        </form>
                    </p>
                <?php
                } elseif ($changePassword and $changePasswordToken) {
                ?>
                    <h3 class="text-center form-title"> Change Password</h3>
                    <p class="text-center">

                        <?php
                        if ($resetPasswordNotMatch) {
                            echo "<div class='alert alert-danger text-center'> Passwords Not Match </div>";
                        }
                        ?>

                        <form action="fogot-password.php" method="POST">
                            <div class="form-group text-center">
                                <small class="text-center"> Please Enter your New Password</small>
                                <br>
                                <br>
                            </div>
                            <div class="form-group">
                                <input type="password" id="password1" placeholder="Enter New Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" name="password1" title="Password must contain at least 6 characters, including UPPER/lowercase and numbers" class="form-control form-control-lg text-center" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : ''); if(this.checkValidity()) form.pwd2.pattern = RegExp.escape(this.value);">

                            </div>
                            <br>
                            <div class="form-group">
                                <input type="password" id="password2" required placeholder="Repeat New Password" name="password2" title="Password must contain at least 6 characters, including UPPER/lowercase and numbers" class="form-control form-control-lg text-center">
                                <input type="hidden" name="token" value="<?php echo $changePasswordToken; ?>">
                            </div>
                            <br>
                            <div class="form-group">
                                <button type="submit" id="ChangePassword" name="confirm-reset-btn" class="btn btn-success col-12"> Change Password </button>
                            </div>
                        </form>
                    </p>
                <?php
                } elseif ($passwordChangeSuccess) {
                ?>
                    <h3 class="text-center form-title"> Your password was successfully changed. </h3>
                    <p class="text-center">
                        <form action="fogot-password.php" method="POST">
                            <div class="form-group text-center">
                                <small class="text-center">
                                    You can sign in to your account with a new password from the Sign In page now.</small>
                                <br>
                                <br>
                                <span class="text-muted"><small> You will be redirect to our Homepage after <span id="spnSeconds">5</span> seconds. </small></span>
                                <br>
                            </div>
                        </form>
                    </p>
                <?php
                } else {
                ?>
                    <h3 class="text-center form-title"> Reset Password</h3>
                    <p class="text-center">

                        <?php
                        if ($passwordResetNoEmail) {
                            echo "<div class='alert alert-danger text-center'> No account could be found at this email address </div>";
                        }
                        ?>

                        <form action="fogot-password.php" method="POST">
                            <div class="form-group">
                                <small class="text-center"> Please Enter your Email address to reset your password </small>
                                <br>
                                <br>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email" class="form-control form-control-lg text-center" value="<?php echo $resetEmail ? $resetEmail : ''; ?>" placeholder="Email Address" required>
                            </div>
                            <br>
                            <div class="form-group">
                                <button type="submit" name="reset-btn" class="btn btn-success col-12"> Send Reset Link </button>
                            </div>
                        </form>
                    </p>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // $('form').on('submit', function() {
        //     $('button').attr('disabled', 'disabled');
        // });

        window.setInterval(function() {
            var iTimeRemaining = $("#spnSeconds").html();
            iTimeRemaining = eval(iTimeRemaining);
            if (iTimeRemaining == 0) {
                location.href = "login.php";
            } else {
                $("#spnSeconds").html(iTimeRemaining - 1);
            }
        }, 1000);
    });
</script>