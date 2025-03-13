<?php 
include('db_connect.php');
session_start();

$meta = [];

if (isset($_GET['id'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $meta = $result->fetch_assoc();
    } else {
        echo "<script>alert('User not found'); window.location='user_list.php';</script>";
        exit;
    }
}
?>
<div class="container-fluid">
    <div id="msg"></div>
    
    <form action="update_profile.php" id="manage-user" method="POST" enctype="multipart/form-data" autocomplete="on">  
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">

        <div class="form-group">
            <label for="photo">Upload Photo</label>
            <input type="file" name="photo" id="photo" class="form-control">
            <small class="text-muted">Supported formats: JPG, PNG. Max size: 2MB</small>
            <img 
                src="<?php echo isset($meta['photo']) && file_exists($meta['photo']) 
                    ? $meta['photo'] 
                    : 'uploads/photos/default.jpg'; ?>" 
                alt="Photo" 
                class="img-thumbnail mt-2" 
                style="width: 100px; height: 100px;"
            >
        </div>

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" 
                   value="<?php echo isset($meta['name']) ? htmlspecialchars($meta['name']) : '' ?>" 
                   autocomplete="name" required>
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" 
                   value="<?php echo isset($meta['username']) ? htmlspecialchars($meta['username']) : '' ?>" 
                   autocomplete="username" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" 
                   autocomplete="new-password">
            <?php if (isset($meta['id'])): ?>
            <small><i>Leave this blank if you don't want to change the password.</i></small>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="type">User Type</label>
            <select name="type" id="type" class="custom-select" autocomplete="off" 
                <?php echo ($_SESSION['login_type'] == 2) ? 'disabled' : ''; ?>>
                <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Staff</option>
                <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status">User Status</label>
            <select name="status" id="status" class="custom-select" autocomplete="off">
                <option value="1" <?php echo isset($meta['status']) && $meta['status'] == 1 ? 'selected' : '' ?>>Active</option>
                <option value="0" <?php echo isset($meta['status']) && $meta['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

    </form>
</div>

<script>
    $('#manage-user').submit(function(e) {
        e.preventDefault();
        start_load();
        
        var formData = new FormData(this);
        
        $.ajax({
            url: 'ajax.php?action=save_user',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully saved", 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    const msg = resp == 2 
                        ? 'Username already exists' 
                        : 'Error saving data';
                    $('#msg').html(`<div class="alert alert-danger">${msg}</div>`);
                }
            },
            error: function() {
                $('#msg').html('<div class="alert alert-danger">An unexpected error occurred</div>');
            },
            complete: function() {
                end_load();
            }
        });
    });
</script>