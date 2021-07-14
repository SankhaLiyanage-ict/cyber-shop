<?php
require_once 'database.php';
require_once 'sendEmails.php';

$firstname = "";
$secondname = "";
$email = "";
$errors = [];
$EmailVarificationSuccess = 0;
$passwordResetNoEmail = 0;
$resetLinkSend = 0;
$resetEmail = '';
$changePasswordToken = '';
$changePassword = 0;
$resetPasswordNotMatch = 0;
$passwordChangeSuccess = 0;

if (isset($_SESSION['verified']) and isset($_SESSION['email'])) {
    if (!isset($_GET['token'])) {
        header('location: index.php');
        exit();
    }
}

// SIGN UP USER
if (isset($_POST['signup-btn'])) {

    if (empty($_POST['firstname'])) {
        $errors['firstname'] = 'First Name is required';
    }

    if (empty($_POST['secondname'])) {
        $errors['secondname'] = 'Second Name is required';
    }

    if (empty($_POST['email'])) {
        $errors['email'] = 'Email is required';
    }

    if (is_numeric($_POST['firstname'])) {
        $errors['firstname'] = 'Invalid First Name';
    }

    if (is_numeric($_POST['secondname'])) {
        $errors['secondname'] = 'Invalid Second Name';
    }

    if (empty($_POST['password'])) {
        $errors['password'] = 'Password required';
    }

    if (isset($_POST['password']) && $_POST['password'] !== $_POST['passwordConf']) {
        $errors['passwordConf'] = 'The two passwords do not match';
    }



    $firstname = $_POST['firstname'];
    $secondname = $_POST['secondname'];
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // generate unique token
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); //encrypt password
    $role = 'ROLE_USER';
    $verified = 0;
    $status = 1;

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);


    if (mysqli_num_rows($result) > 0) {
        $errors['email'] = "Email already exists";
    }

    if (count($errors) === 0) {
        $sql = "INSERT INTO users (firstName, lastName, email, password, role, status, verified, delivery_address, mobile_no, postal_code, token ) 
                VALUES ('$firstname','$secondname','$email','$password','$role',$status, $verified, '-', '-', '-', '$token')";

        if ($conn->query($sql) == TRUE) {

            // TO DO: send verification email to user
            $host_url = "http://g10-allocation-system.000webhostapp.com/sendMail.php?email=$email&token=$token";        
            //$host_url = "https://esystems.space/sendMail.php?email=$email&token=$token";        
            
            $response = file_get_contents($host_url);

            //sendVerificationEmail($email, $token);
            $_SESSION['tmp_email'] = $email;

            header('location: verify-account.php');
        } else {
            $_SESSION['error_msg'] = "Database error: Could not register user";
            echo 'Database error: Could not register user <br>';
            echo "Error: " . $sql . "<br>" . $conn->error;
            exit();
        }
    }
}

// LOGIN
if (isset($_POST['login-btn'])) {

    if (empty($_POST['email'])) {
        $errors['email'] = 'Email Required';
    }
    if (empty($_POST['password'])) {
        $errors['password'] = 'Password Required';
    }

    $username = $_POST['email'];
    $password = $_POST['password'];

    if (count($errors) === 0) {

        $query = "SELECT * FROM users WHERE email=? LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) { // if password matches
                $stmt->close();
                if ($user['status']) {
                    if ($user['verified']) {
                        $_SESSION['id'] = $user['id'];
                        $_SESSION['username'] = $user['firstName'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['verified'] = $user['verified'];
                        $_SESSION['message'] = 'You are logged in!';
                        $_SESSION['type'] = 'alert-success';
                        $_SESSION['role'] = $user['role'];

                        if (isset($_SESSION['redir']) and $_SESSION['redir']) {
                            $pid = $_SESSION['redir'];
                            $query = "SELECT title from products WHERE id=$pid";
                            $result = mysqli_query($conn, $query);

                            $Product = mysqli_fetch_array($result);
                            $url = str_replace('"', "'", strtolower($Product['title']));
                            $url = str_replace(' ', '-', $url);
                            unset($_SESSION['redir']);
                            header("location: ../in-product.php?p=$url&i=$pid");
                        } else {
                            if ($user['role'] == 'ROLE_ADMIN') {
                                header('location: admin/index.php');
                            } else {
                                header('location: index.php');
                            }
                        }
                    } else {
                        $_SESSION['tmp_email'] = $user['email'];
                        header('location: verify-account.php');
                    }
                    exit();
                } else {
                    $errors['login_fail'] = "Account Removed.";
                }
            } else {

                // if password does not match
                $errors['login_fail'] = "Email or Password is incorrect";
            }
        } else {
            $_SESSION['message'] = "Database error. Login failed!";
            $_SESSION['type'] = "alert-danger";
        }
    }
}

// Email Verification
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $sql = "SELECT * FROM users WHERE token='$token' AND verified=0 LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $query = "UPDATE users SET verified=1 WHERE token='$token'";

        if (mysqli_query($conn, $query)) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $user['firstName'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['verified'] = true;
            $_SESSION['message'] = "Your email address has been verified successfully";
            $_SESSION['type'] = 'alert-success';
            $_SESSION['tmp_email'] = '';
            $_SESSION['varifiled_now'] = true;
            $EmailVarificationSuccess = 1;
        }
    } else {
        header('location: index.php');
        exit();
    }
}

// Password Reset
if (isset($_POST['reset-btn'])) {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));

    $sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $query = "UPDATE users SET token='$token' WHERE email='$email'";

        if (mysqli_query($conn, $query)) {

            //sendResetEmail($email, $token);
            $host_url = "http://g10-allocation-system.000webhostapp.com/sendMail.php?email=$email&token=$token&pwd_reset=1";        
           //$host_url = "http://esystems.space/sendMail.php?email=$email&token=$token&pwd_reset=1";        
            $response = file_get_contents($host_url);

            $_SESSION['reset_link_send'] = 1;
            header('location: fogot-password.php');
        }
    } else {
        $passwordResetNoEmail = 1;
        $resetEmail = $email;
    }
}

// Reset Link verify
if (isset($_GET['reset-token'])) {
    $token = $_GET['reset-token'];
    $sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['reset_link_send'] = 0;
        $changePassword = 1;
        $changePasswordToken = $token;
    } else {
        header('location: index.php');
        exit();
    }
}

// Change Password
if (isset($_POST['confirm-reset-btn'])) {
    $password1 = $_POST['password1'];
    $password2 = $_POST['password2'];
    $token = $_POST['token'];

    if ($password1 == $password2) {

        $password = password_hash($password1, PASSWORD_DEFAULT); //encrypt password
        $query = "UPDATE users SET password='$password' WHERE token='$token'";
        if (mysqli_query($conn, $query)) {
            $passwordChangeSuccess = 1;
            $_SESSION = array();
            session_destroy();
        }
    } else {

        $resetPasswordNotMatch = 1;
        $changePassword = 1;
        $changePasswordToken = $token;
    }
}

