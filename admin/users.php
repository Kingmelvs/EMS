<?php
include 'db_connect.php';
?>

<div class="container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-lg-12 text-right">
            <button class="btn btn-success btn-sm" id="new_user">
                <i class="fa fa-user-plus"></i> Add New User
            </button>
        </div>
    </div>

    <div class="row">
        <div class="card col-lg-12">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center" id="userTable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $type = array("", "Admin", "Staff");
                            $users = $conn->query("SELECT * FROM users ORDER BY name ASC");
                            if ($users->num_rows > 0) {
                                $i = 1;
                                while ($row = $users->fetch_assoc()):
                                    $status = $row['status'] == 1 ? 'Active' : 'Inactive';
                                    $badgeColor = $row['status'] == 1 ? 'badge-success' : 'badge-danger';
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo ucwords($row['name']); ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $type[$row['type']]; ?></td>
                                <td>
                                    <span class="badge <?php echo $badgeColor; ?> toggle-status" data-id="<?php echo $row['id']; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm edit_user" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm delete_user" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; } else { ?>
                            <tr>
                                <td colspan="6" class="text-center">No Users Found</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#userTable').DataTable();

    $('#new_user').click(function () {
        uni_modal('Add New User', 'manage_user.php');
    });

    $('.edit_user').click(function () {
        uni_modal('Edit User', 'manage_user.php?id=' + $(this).data('id') + '&edit_status=1');
    });

    $('.delete_user').click(function () {
        _conf('Are you sure you want to delete this user?', 'delete_user', [$(this).data('id')]);
    });

    $('.toggle-status').click(function () {
        let id = $(this).data('id');
        start_load();
        $.ajax({
            url: 'ajax.php?action=toggle_user_status',
            method: 'POST',
            data: { id: id },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast('User status updated', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert_toast('An error occurred', 'danger');
                }
            },
            error: function () {
                alert_toast('Unexpected error', 'danger');
            },
            complete: function () {
                end_load();
            }
        });
    });

    function delete_user(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_user',
            method: 'POST',
            data: { id: id },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast('User successfully deleted', 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert_toast('Failed to delete user', 'danger');
                }
            },
            error: function () {
                alert_toast('Unexpected error', 'danger');
            },
            complete: function () {
                end_load();
            }
        });
    }
</script>

<style>
    #new_user {
        margin-top: 10px;
        z-index: 1050;
    }

    .modal-content {
        margin-top: 10vh;
    }
</style>