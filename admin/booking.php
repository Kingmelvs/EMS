<?php 
include('db_connect.php');
require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '../vendor/phpmailer/phpmailer/src/Exception.php';
require '../vendor/phpmailer/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendBookingConfirmation($customerEmail, $bookingDetails) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'emssccp.co@gmail.com';
        $mail->Password   = 'htue utjn rjtl sdkx';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465; 

        $mail->setFrom('emssccp.co@gmail.com', 'Event Management');
        $mail->addAddress($customerEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation';
        $mail->Body = "<p>Hello,</p><p>Your booking has been confirmed:</p><p>$bookingDetails</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>

<div class="container-fluid">
    <style>
    input[type=checkbox] {
        transform: scale(1.5);
        padding: 10px;
    }
    </style>

    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>
        <div class="row">
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <b>Venue Booking List</b>
                        <button class="btn btn-success btn-sm" id="new_book">
                            <i class="fa fa-plus"></i> New
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover text-center">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Booking Information</th>
                                    <th>Customer Information</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
								$i = 1;
								$booking = $conn->query("SELECT b.*,v.venue from venue_booking b inner join venue v on v.id = b.venue_id");
								while($row=$booking->fetch_assoc()):
									
								?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <p>Venue: <b><?php echo ucwords($row['venue']); ?></b></p>
                                        <p><small>Schedule:
                                                <b><?php echo date("M d, Y h:i A", strtotime($row['datetime'])); ?></b></small>
                                        </p>
                                        <p><small>Duration: <?php echo ucwords($row['duration']); ?></small></p>
                                    </td>
                                    <td>
                                        <p>Booked by: <b><?php echo ucwords($row['name']); ?></b></p>
                                        <p><small>Email: <b><?php echo $row['email']; ?></b></small></p>
                                        <p><small>Contact: <b><?php echo $row['contact']; ?></b></small></p>
                                        <p><small>Address: <b><?php echo ucwords($row['address']); ?></b></small></p>
                                    </td>
                                    <td>
                                        <?php if ($row['status'] == 0): ?>
                                        <span class="badge badge-secondary">For Verification</span>
                                        <?php elseif ($row['status'] == 1): ?>
                                        <span class="badge badge-primary">Confirmed</span>
                                        <?php elseif ($row['status'] == 2): ?>
                                        <span class="badge badge-danger">Cancelled</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm edit_book"
                                            data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>
</div>

<style>
td {
    vertical-align: middle !important;
}

.table-hover tbody tr:hover {
    background-color: #f1f1f1;
}

.card {
    border: none;
    border-radius: 10px;
}

.card-header {
    border-radius: 0;
    margin-top: -40px;

}

.btn-sm i {
    margin-right: 5px;
}
</style>

<script>
$(document).ready(function() {
    $('table').dataTable();
});

$('#new_book').click(function() {
    uni_modal("New Entry", "manage_booking.php");
});

$('.edit_book').click(function() {
    uni_modal("Manage Book Details", "manage_booking.php?id=" + $(this).data('id'));
});
</script>