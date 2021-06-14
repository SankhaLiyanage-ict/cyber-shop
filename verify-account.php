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
                if ($EmailVarificationSuccess) {
                ?>
                    <h3 class="text-center form-title" style="color: #26aa26;"> Email Address Verified</h3>
                    <p class="text-center register-text">
                        <h5 class="text-center">Thank you! Your Cyber ​​Shop account has been successfully verified. </h5>
                        <br>
                        <br>
                        <span class="text-muted"><small> You will be redirect to our Homepage after <span id="spnSeconds">5</span> seconds. </small></span>
                        <br>
                    </p>
                <?php
                } else {
                ?>
                    <h3 class="text-center form-title">Verify Email Address</h3>
                    <p class="text-center register-text">
                        We have sent the Verification link to your email address. Please open the Verification link to Verify your Cyber ​​Shop account.
                        <br>
                    </p>
                <?php
                }
                ?>

            </div>
        </div>
    </div>
</div>

</body>
<script>
    $(document).ready(function() {
        window.setInterval(function() {
            var iTimeRemaining = $("#spnSeconds").html();
            iTimeRemaining = eval(iTimeRemaining);
            if (iTimeRemaining == 0) {
                location.href = "index.php";
            } else {
                $("#spnSeconds").html(iTimeRemaining - 1);
            }
        }, 1000);
    });
</script>

</html>