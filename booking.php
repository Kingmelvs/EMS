<div class="container-fluid">
	<form action="" method="POST" id="manage-book">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id :'' ?>">
		<input type="hidden" name="venue_id" value="<?php echo isset($_GET['venue_id']) ? $_GET['venue_id'] :'' ?>">

		<div class="form-group">
			<label for="" class="control-label">Full Name</label>
			<input type="text" class="form-control" name="name" value="<?php echo isset($name) ? $name :'' ?>" required>
		</div>

		<div class="form-group">
			<label for="" class="control-label">Address</label>
			<textarea cols="30" rows="2" required name="address" class="form-control"><?php echo isset($address) ? $address :'' ?></textarea>
		</div>

		<div class="form-group">
			<label for="" class="control-label">Email</label>
			<input type="email" class="form-control" name="email" value="<?php echo isset($email) ? $email :'' ?>" required>
		</div>

		<div class="form-group">
			<label for="" class="control-label">Contact #</label>
			<input type="text" class="form-control" name="contact" pattern="[0-9\+\-\s]+" title="Please enter a valid phone number" value="<?php echo isset($contact) ? $contact :'' ?>" required>
		</div>

		<!-- ✅ ADD EVENT TYPE DROPDOWN -->
		<div class="form-group">
			<label for="event_type" class="control-label">Type of Event</label>
			<select name="event_type" class="form-control" required>
				<option value="" disabled selected>Select Type of Event</option>
				<option value="Birthday Party" <?php echo isset($event_type) && $event_type == 'Birthday Party' ? 'selected' : '' ?>>🎂 Birthday Party</option>
				<option value="Wedding Reception" <?php echo isset($event_type) && $event_type == 'Wedding Reception' ? 'selected' : '' ?>>💍 Wedding Reception</option>
				<option value="Seminar/Workshop" <?php echo isset($event_type) && $event_type == 'Seminar/Workshop' ? 'selected' : '' ?>>📢 Seminar/Workshop</option>
				<option value="Corporate Event" <?php echo isset($event_type) && $event_type == 'Corporate Event' ? 'selected' : '' ?>>💼 Corporate Event</option>
				<option value="Concert/Show" <?php echo isset($event_type) && $event_type == 'Concert/Show' ? 'selected' : '' ?>>🎤 Concert/Show</option>
				<option value="Family Gathering" <?php echo isset($event_type) && $event_type == 'Family Gathering' ? 'selected' : '' ?>>👨‍👩‍👧‍👦 Family Gathering</option>
				<option value="Graduation" <?php echo isset($event_type) && $event_type == 'Graduation' ? 'selected' : '' ?>>🎓 Graduation</option>
				<option value="Others" <?php echo isset($event_type) && $event_type == 'Others' ? 'selected' : '' ?>>📝 Others</option>
			</select>
		</div>

		<div class="form-group">
			<label for="duration">Select Duration:</label>
			<select name="duration" id="duration" class="form-control">
				<?php 
					for($i = 1; $i <= 24; $i++):
				?>
				<option value="<?php echo $i ?>" <?php echo isset($duration) && $duration == $i ? 'selected' : '' ?>>
					<?php echo $i ?> Hour(s)
				</option>
				<?php endfor; ?>
			</select>
		</div>

		<div class="form-group">
			<label for="" class="control-label">Desired Event Schedule</label>
			<input type="date" class="form-control datetimepicker" name="schedule" value="<?php echo isset($schedule) ? $schedule :'' ?>" required>
		</div>
	</form>
</div>

<script>
	$('#manage-book').submit(function(e){
		e.preventDefault();
		start_load();
		$('#msg').html('');

		var formData = new FormData($(this)[0]);
		$.ajax({
			url: 'admin/ajax.php?action=save_book',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			success: function(resp){
				if(resp == 1){
					alert_toast("Booking Request Sent.", 'success');
					setTimeout(function(){
						end_load();
						uni_modal("", "book_msg.php");
					}, 1500);
				} else {
					alert_toast("An error occurred. Please try again.", 'danger');
					end_load();
				}
			},
			error: function(xhr, status, error) {
				alert_toast("An error occurred: " + error, 'danger');
				end_load();
			}
		});
	});
</script>
