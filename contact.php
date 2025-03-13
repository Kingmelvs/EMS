<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['send'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($subject) && !empty($message)) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'emssccp.co@gmail.com';
            $mail->Password = 'hftw syqy igfr gxur';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom($email, $name);
            $mail->addAddress('emssccp.co@gmail.com', 'Event Management');

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = "
                <h3>Contact Form Submission</h3>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Phone:</strong> {$phone}</p>
                <p><strong>Message:</strong><br>{$message}</p>
            ";

            $mail->send();
            $msg = "<p class='alert alert-success'>Message sent successfully!</p>";
        } catch (Exception $e) {
            $error = "<p class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</p>";
        }
    } else {
        $error = "<p class='alert alert-warning'>Please fill in all required fields.</p>";
    }
}
?>

<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end mb-4" style="background: #0000002e;">
                <h1 class="text-uppercase text-white font-weight-bold">Contact Us</h1>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<section class="page-section">
    <div class="contact-box">
        <h2 class="text-center">💬 Contact Us Anytime</h2>
        <p class="text-center text-muted">We're here to help you 24/7! Drop us a message.</p>

        <div class="row mb-4">
            <div class="form-group col-lg-6">
                <input type="text" name="name" class="form-control glowing-input" placeholder="Your Name*" required>
            </div>
            <div class="form-group col-lg-6">
                <input type="email" name="email" class="form-control glowing-input" placeholder="Email Address*" required>
            </div>
            <div class="form-group col-lg-6">
                <input type="text" name="phone" class="form-control glowing-input" placeholder="Phone Number*" maxlength="11" required>
            </div>
            <div class="form-group col-lg-6">
                <input type="text" name="subject" class="form-control glowing-input" placeholder="Subject*" required>
            </div>
            <div class="col-lg-12">
                <div class="form-group">
                    <textarea name="message" class="form-control glowing-input" rows="5" placeholder="Type your message..." required></textarea>
                </div>
            </div>
            <div class="col-lg-12 text-center">
                <button type="submit" id="sendButton" class="btn btn-glow">
                    <span class="btn-text">🚀 Send Message</span>
                    <span class="spinner-border spinner-border-sm d-none" id="loadingSpinner"></span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Glassmorphism Clean Background -->
<style>
.page-section {
    background: linear-gradient(135deg, rgb(29, 12, 12), #3c3c3c);
    padding: 60px 0;
}

/* Glass Contact Box */
.contact-box {
    background: linear-gradient(135deg, rgb(29, 12, 12), #3c3c3c);
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    color: #f5f5f5;
    border: 1px solid rgba(255, 255, 255, 0.1);
}



/* Glowing Input */
.glowing-input {
    border: 2px solid #d1d1d1;
    border-radius: 5px;
    padding: 12px;
    transition: all 0.3s;
}

.glowing-input:focus {
    border-color: #42f5b6;
    box-shadow: 0 0 20px rgba(66, 245, 182, 0.5);
}

/* Glassmorphism Button */
.btn-glow {
    background: #3498db;
    color: white;
    padding: 15px 30px;
    border: none;
    border-radius: 50px;
    transition: all 0.3s;
    box-shadow: 0 0 30px rgba(52, 152, 219, 0.5);
}

.btn-glow:hover {
    background: #2980b9;
    transform: scale(1.05);
    box-shadow: 0 0 40px rgba(52, 152, 219, 0.8);
}

/* Fade In Animation */
@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(30px); }
    100% { opacity: 1; transform: translateY(0); }
}

/* Success Button */
.btn-success {
    background: #42f5b6;
    box-shadow: 0 0 40px rgba(66, 245, 182, 0.8);
}

/* Spinner */
#loadingSpinner {
    transition: all 0.3s;
}

/* Heading */
.contact-box h2 {
    color: #3498db;
    font-weight: bold;
}

/* Glass Effect */
.contact-box {
    border: 1px solid rgba(255, 255, 255, 0.15);
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.1);
}
</style>

<script>
document.getElementById("sendButton").addEventListener("click", function(event) {
    event.preventDefault();
    var button = this;
    var text = button.querySelector(".btn-text");
    var spinner = document.getElementById("loadingSpinner");

    button.disabled = true;
    spinner.classList.remove("d-none");
    text.textContent = "Sending...";

    setTimeout(function() {
        text.textContent = "✅ Message Sent!";
        spinner.classList.add("d-none");
        button.classList.remove("btn-glow");
        button.classList.add("btn-success");
        button.disabled = false;
    }, 3000);
});
</script>
