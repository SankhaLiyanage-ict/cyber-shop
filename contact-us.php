<?php
include 'controllers/database.php';
include 'controllers/sendEmails.php';

if (isset($_POST['contact_frm'])) {

    $customerName = $_POST['customerName'];
    $message = $_POST['message'];
    $email = $_POST['email'];

    sendContactUsMail($customerName, $message, $email);
    $_SESSION['message_send'] = 1;

    header('Location: contact-us.php');
    exit();
}

$userName = '';
$userEmail = '';

if (isset($_SESSION['username'])) {
    $userName = $_SESSION['username'];
}
if (isset($_SESSION['email'])) {
    $userEmail = $_SESSION['email'];
}
include 'common/header.php';
?>
<style>
    .form-control {
        margin-bottom: 10px;
    }
</style>
<div class="container">
    <div class="row justify-content-center pt-5 mt-5 pb-5 ">
        <div class="col-md-7 bg-white" style="padding: 33px;">
            <h4 class="text-center">Contact us</h4>
            <hr class="bg-success">


            <?php
            if (isset($_SESSION['message_send'])) {
            ?>
                <div class="alert alert-success text-center" role="alert">
                    <b>Thank you! Your message has been successfully sent to the relevant sections. They will contact you as soon as possible.</b>
                </div>
            <?php
                unset($_SESSION['message_send']);
            } else {
            ?>
                <p class="pb-0 mb-0 text-center">Just get in contact with us. We are happy to answer your questions.</p>
                <p class="text-danger small pt-0 mt-0 text-center">* All fields are required</p>
                <br>
                <form method="POST">
                    <div class="row form-group">
                        <label for="name" class="col-form-label col-md-3">Name</label>
                        <div class="col-md-9">
                            <input type="text" name="name" id="name" class="form-control" value="<?php echo $userName ?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="email" class="col-form-label col-md-3">E-mail</label>
                        <div class="col-md-9">
                            <input type="hidden" name="email" id="email" class="form-control" value="<?php echo $userEmail ?>">
                            <input type="email" class="form-control " disabled value="<?php echo $userEmail ?>" required>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label for="message" class="col-form-label col-md-3">Message</label>
                        <div class="col-md-9">
                            <textarea name="message" id="message" class="form-control" rows="8" required></textarea>
                        </div>
                    </div>
                    <div class="text-right pt-3 mt-3">
                        <button type="submit" name="contact_frm" value="1" class="btn btn-success btn-block btn-send col-3">Send Message</button>
                    </div>
                </form>
            <?php
            }
            ?>

        </div>
    </div>
</div>

<script>
    $('form').on('submit', function() {
        $('[name="contact_frm"]').html('Sending...');
        setTimeout(function() {
            $('[name="contact_frm"]').attr('disabled', 'disabled');
        }, 300);
    });
</script>