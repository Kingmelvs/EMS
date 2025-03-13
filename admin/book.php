<?php
$to = "emssccp.co@gmail.com"; // Recipient's email address
$subject = "Test Email Subject"; // Subject of the email
$message = "This is a test email message."; // Email body
$headers = "From: sender@example.com\r\n"; // Sender's email address

// Send the email
if(mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully.";
} else {
    echo "Email sending failed.";
}
?>