<?php include 'db_connect.php'; ?>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<?php
if (isset($_GET['id'])) {
    $booking = $conn->query("SELECT * FROM venue_booking WHERE id = " . $_GET['id']);
    foreach ($booking->fetch_array() as $k => $v) {
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="manage-book">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
        <div class="form-group">
            <label for="" class="control-label">Venue</label>
            <select name="venue_id" id="" class="custom-select select2">
                <option></option>
                <?php 
                $venue = $conn->query("SELECT * FROM venue ORDER BY venue ASC");
                while ($row = $venue->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id']; ?>" <?php echo isset($venue_id) && $venue_id == $row['id'] ? 'selected' : ''; ?>>
                    <?php echo ucwords($row['venue']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Full Name</label>
            <input type="text" class="form-control" name="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Address</label>
            <textarea cols="30" rows="2" required name="address" class="form-control"><?php echo isset($address) ? $address : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Email</label>
            <input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Contact #</label>
            <input type="text" class="form-control" name="contact" value="<?php echo isset($contact) ? $contact : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Duration</label>
            <select name="duration" class="custom-select" required>
                <option value=""></option>
                <?php for ($i = 1; $i <= 24; $i++): ?>
                <option value="<?php echo $i . ' Hour'; ?>" <?php echo isset($duration) && $duration == $i . ' Hour' ? 'selected' : ''; ?>>
                    <?php echo $i . ' Hour'; ?>
                </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Desired Event Schedule</label>
            <!-- Date and Time Picker -->
            <input type="date" class="form-control datetimepicker" name="schedule" value="<?php echo isset($schedule) ? $schedule :'' ?>" required>

        </div>
        <div class="form-group">
            <label for="" class="control-label">Status</label>
            <select name="status" id="" class="custom-select">
                <option value="0" <?php echo isset($status) && $status == 0 ? "selected" : ''; ?>>For Verification</option>
                <option value="1" <?php echo isset($status) && $status == 1 ? "selected" : ''; ?>>Confirmed</option>
                <option value="2" <?php echo isset($status) && $status == 2 ? "selected" : ''; ?>>Cancelled</option>
            </select>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        // Initialize DateTime Picker
        $('#datetimepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today", // Disable past dates
            time_24hr: true // Use 24-hour format
        });

        // Form submission handler
        $('#manage-book').submit(function(e){
            e.preventDefault();
            start_load();
            
            var form = $(this);
            var status = form.find('[name="status"]').val();
            var formData = form.serialize();
            
            // Debug logs
            console.log('Status value:', status);
            console.log('Form data being sent:', formData);
            
            $.ajax({
                url: 'ajax.php?action=save_book',
                method: 'POST',
                data: formData,
                success: function(resp){
                    console.log('Server response:', resp);
                    if(resp == 1){
                        var statusText = '';
                        if(status == '0') statusText = "For Verification";
                        else if(status == '1') statusText = "Confirmed";
                        else if(status == '2') statusText = "Cancelled";
                        
                        alert_toast("Booking status updated to " + statusText, 'success');
                        setTimeout(function(){
                            $('#uni_modal').modal('hide');
                            end_load();
                            window.location.href = 'index.php?page=booking';
                        }, 1500);
                    } else {
                        alert_toast("An error occurred", 'danger');
                        end_load();
                    }
                },
                error: function(xhr, status, error) {
                    alert_toast("An error occurred: " + error, 'danger');
                    end_load();
                }
            });
        });

        // Initialize select2
        $('.select2').select2({
            placeholder: "Please select here",
            width: "100%"
        });
    });
</script>

