<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * from system_settings limit 1");
if($qry->num_rows > 0){
	foreach($qry->fetch_array() as $k => $val){
		$meta[$k] = $val;
	}
}
?>
<div class="container-fluid">
	<div class="card col-lg-12 shadow-lg p-4 rounded">
		<div class="card-body">
			<h4 class="text-center mb-4 text-primary"><i class="fa fa-cogs"></i> Manage System Settings</h4>
			<form action="" id="manage-settings">
				<div class="form-group">
					<label for="name" class="control-label">System Name</label>
					<input type="text" class="form-control" id="name" name="name" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="email" class="control-label">Email</label>
					<input type="email" class="form-control" id="email" name="email" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="contact" class="control-label">Contact</label>
					<input type="text" class="form-control" id="contact" name="contact" value="<?php echo isset($meta['contact']) ? $meta['contact'] : '' ?>" required>
				</div>
				<div class="form-group">
					<label for="about" class="control-label">About Content</label>
					<textarea name="about" class="form-control text-jqte" rows="5"><?php echo isset($meta['about_content']) ? $meta['about_content'] : '' ?></textarea>
				</div>
				<div class="form-group">
					<label for="img" class="control-label">Upload Cover Image</label>
					<input type="file" class="form-control" name="img" onchange="displayImg(this,$(this))">
					<div class="text-center mt-2">
						<img src="<?php echo isset($meta['cover_img']) ? 'assets/uploads/'.$meta['cover_img'] :'' ?>" alt="Cover Image" id="cimg" class="img-thumbnail shadow">
					</div>
				</div>
				<div class="text-center mt-4">
					<button class="btn btn-success px-5 py-2 rounded shadow"><i class="fa fa-save"></i> Save Settings</button>
				</div>
			</form>
		</div>
	</div>
</div>

<style>
	img#cimg {
		max-height: 200px;
		max-width: 100%;
		border-radius: 10px;
		transition: transform 0.3s ease-in-out;
	}
	img#cimg:hover {
		transform: scale(1.05);
	}
	.form-control {
		border-radius: 5px;
	}
	.btn-success:hover {
		background-color: #218838;
		transform: scale(1.05);
	}
</style>

<script>
function displayImg(input, _this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cimg').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$('.text-jqte').jqte();

$('#manage-settings').submit(function(e){
	e.preventDefault()
	start_load()
	$.ajax({
		url: 'ajax.php?action=save_settings',
		data: new FormData($(this)[0]),
		cache: false,
		contentType: false,
		processData: false,
		method: 'POST',
		type: 'POST',
		error: err => {
			console.log(err)
		},
		success: function(resp){
			if(resp == 1){
				alert_toast('Data successfully saved.','success')
				setTimeout(function(){
					location.reload()
				}, 1000)
			}
		}
	})
})
</script>