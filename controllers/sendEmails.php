<?php
require_once 'vendor/autoload.php';
//require __DIR__ . './../vendor/autoload.php';

// Create the Transport
$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
  ->setUsername('cybershopg5@gmail.com')
  ->setPassword('MiniProG5');

// Create the Mailer using your created Transport
$mailer = new Swift_Mailer($transport);

function sendVerificationEmail($userEmail, $token)
{
  $actual_link = str_replace('signup.php', 'verify-account.php', "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");

  global $mailer;
  $body = '<!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <title>Test mail</title>
      <style>
        .wrapper {
          padding: 20px;
          color: #444;
          font-size: 1.3em;
        }
        a {
          background: #592f80;
          text-decoration: none;
          padding: 8px 15px;
          border-radius: 5px;
          color: #fff;
        }
      </style>
    </head>

    <body>
      <div class="wrapper">
        <p>Thank you for signing up on our site. Please click on the link below to verify your account:.</p>
        <a href="' . $actual_link . '?token=' . $token . '">Verify Email!</a>
      </div>
    </body>

    </html>';

  // Create a message
  $message = (new Swift_Message('Verify your email'))
    ->setFrom('cybershopg5@gmail.com')
    ->setTo($userEmail)
    ->setBody($body, 'text/html');

  // Send the message
  $result = $mailer->send($message);

  if ($result > 0) {
    return true;
  } else {
    return false;
  }
}

function sendResetEmail($userEmail, $token)
{
  $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

  global $mailer;
  $body = '<!DOCTYPE html>
    <html lang="en">

    <head>
      <meta charset="UTF-8">
      <title>Test mail</title>
      <style>
        .wrapper {
          padding: 20px;
          color: #444;
          font-size: 1.3em;
        }
        a {
          background: #592f80;
          text-decoration: none;
          padding: 8px 15px;
          border-radius: 5px;
          color: #fff;
        }
      </style>
    </head>

    <body>
      <div class="wrapper">
        <p> Your Cyber Shop password can be reset by clicking the button below. If you did not request a new password, please ignore this email.</p>
        <a href="' . $actual_link . '?reset-token=' . $token . '"> Reset Password </a>
      </div>
    </body>

    </html>';

  // Create a message
  $message = (new Swift_Message('Reset your  Password'))
    ->setFrom('cybershopg5@gmail.com')
    ->setTo($userEmail)
    ->setBody($body, 'text/html');

  // Send the message
  $result = $mailer->send($message);

  if ($result > 0) {
    return true;
  } else {
    return false;
  }
}


function sendContactUsMail($customerName, $message, $email)
{
  global $mailer;

  $body = '<!DOCTYPE html>
  <html lang="en">
  
  <head>
    <meta charset="UTF-8">
    <title>Test mail</title>
    <style>
      .wrapper {
        padding: 20px;
        color: #444;
        font-size: 1.3em;
      }
  
      a {
        background: #592f80;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        color: #fff;
      }
    </style>
  </head>
  
  <body>
    <div class="wrapper">
      <h3>' . $customerName . ' is trying to Contact us,</h3>
      <p> ' . $message . '</p>
      <small>User Email : ' . $email . '</small>
    </div>
  </body>
  
  </html>';

  $body2 = '<!DOCTYPE html>
  <html lang="en">
  
  <head>
    <meta charset="UTF-8">
    <title>Test mail</title>
    <style>
      .wrapper {
        padding: 20px;
        color: #444;
        font-size: 1.3em;
      }
  
      a {
        background: #592f80;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        color: #fff;
      }
    </style>
  </head>
  
  <body>
    <div class="wrapper">
      <h3> Thanks ' . $customerName . ' for contacting us,</h3>
      <p> Your message has been successfully sent to the relevant sections. They will contact you as soon as possible. </p>
    </div>
  </body>
  
  </html>';

  // Create a message
  $message = (new Swift_Message('Contact Us Message'))
    ->setFrom('cybershopg5@gmail.com')
    ->setTo('cybershopg5@gmail.com')
    ->setBody($body, 'text/html');

  // Send the message
  $result = $mailer->send($message);

  $message2 = (new Swift_Message('Cyber Shop'))
    ->setFrom('cybershopg5@gmail.com')
    ->setTo($email)
    ->setBody($body2, 'text/html');

  // Send the message
  $mailer->send($message2);

  if ($result > 0) {
    return true;
  } else {
    return false;
  }
}
