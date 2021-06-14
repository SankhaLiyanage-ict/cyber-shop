<?php include 'controllers/authController.php';

if (isset($_SESSION['id'])) {
    header('location: index.php');
}

if (isset($_GET['redir']) and $_GET['redir']) {
    $_SESSION['redir'] = $_GET['redir'];
}

include 'common/header.php';

?>

<style>

</style>

<div class="container">
    <div class="row">
        <div class="login-main-wapper">
            <div class="login-wapper">
                <div class="col-md-12 text-center">
                    <img src="logo/logo.png" width="250px">
                </div>
                <h3 class="text-center form-title">Login</h3>
                <form action="login.php" method="POST">

                    <?php if (count($errors) > 0) : ?>
                        <div class="alert alert-danger text-center">
                            <?php foreach ($errors as $error) : ?>
                                <?php echo $error; ?><br>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-group mb-10">
                        <label>Email</label>
                        <input type="text" name="email" class="form-control form-control-lg" value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control form-control-lg">
                        <p class="text-left"><a href="fogot-password.php"><small>Forgot password?</small></a></p>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="login-btn" class="btn btn-success col-12">Login</button>
                    </div>
                </form>
                <p class="text-center register-text">Don't yet have an account? <a href="signup.php">Sign up</a></p>
            </div>
        </div>
    </div>
</div>
</body>

</html>