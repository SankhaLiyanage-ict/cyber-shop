<?php
include 'controllers/authController.php';
include 'common/header.php';

?>

<div class="container">
  <div class="row">
    <div class="login-main-wapper">

      <div class="login-wapper auth">
        <div class="col-md-12 text-center">
          <img src="logo/logo.png" width="250px">
        </div>
        <h3 class="text-center form-title">Register</h3>
        <form action="signup.php" method="post">

          <?php if (count($errors) > 0) : ?>
            <div class="alert alert-danger">
              <?php foreach ($errors as $error) : ?>
                <li>
                  <?php echo $error; ?>
                </li>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="form-group mb-10">
            <label>First Name</label>
            <input type="text" name="firstname" required maxlength="20" class="form-control form-control-lg" value="<?php echo $firstname; ?>">
          </div>
          <div class="form-group mb-10">
            <label>Last Name</label>
            <input type="text" name="secondname" required maxlength="20" class="form-control form-control-lg" value="<?php echo $secondname; ?>">
          </div>
          <div class="form-group mb-10">
            <label>Email</label>
            <input type="email" name="email" required class="form-control form-control-lg" value="<?php echo $email; ?>">
          </div>
          <div class="form-group mb-10">
            <label>Password</label>
            <input type="password" id="password1" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" name="password" title="Password must contain at least 6 characters, including UPPER/lowercase and numbers" class="form-control form-control-lg" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : ''); if(this.checkValidity()) form.pwd2.pattern = RegExp.escape(this.value);">
          </div>
          <div class="form-group mb-10">
            <label>Confirm Password</label>
            <input type="password" id="password2" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}" name="passwordConf" title="Password must contain at least 6 characters, including UPPER/lowercase and numbers" class="form-control form-control-lg" onchange="this.setCustomValidity(this.validity.patternMismatch ? this.title : '');">
            <span id="password-validation"></span>
          </div>
          <br>
          <div class="form-group">
            <button type="submit" name="signup-btn" class="btn btn-lg btn-success btn-block col-12">Sign Up</button>
          </div>
        </form>
        <p class="register-text text-center">Already have an account? <a href="login.php">Login</a></p>
      </div>
    </div>
  </div>
</div>

<script>
  $('form').on('submit', function() {
    $('[type="submit"]').html("<i class='ti-reload'></i> Please Wait");
  });
</script>

</body>

</html>