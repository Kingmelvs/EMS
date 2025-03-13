<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4><i class="fa fa-map-marker-alt"></i> List of Venue</h4>
                        <a class="btn btn-success btn-sm" href="index.php?page=manage_venue" id="new_venue">
                            <i class="fa fa-plus"></i> New Entry
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover text-center">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Venue</th>
                                    <th>Address</th>
                                    <th>Description</th>
                                    <th>Rate</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $venue = $conn->query("SELECT * FROM venue");
                                while($row = $venue->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><b><?php echo ucwords($row['venue']); ?></b></td>
                                    <td><?php echo $row['address']; ?></td>
                                    <td><p class="text-truncate" style="max-width: 300px;"><?php echo $row['description']; ?></p></td>
                                    <td class="text-right">₱<?php echo number_format($row['rate'], 2); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning text-white edit_venue" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete_venue" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    $('.edit_venue').click(function() {
        location.href = "index.php?page=manage_venue&id=" + $(this).data('id');
    });

    $('.delete_venue').click(function() {
        const id = $(this).data('id');
        if (confirm("Are you sure to delete this venue?")) {
            delete_venue(id);
        }
    });
});

function delete_venue(id) {
    $.ajax({
        url: 'ajax.php?action=delete_venue',
        method: 'POST',
        data: { id: id },
        success: function(response) {
            if (response == 1) {
                alert('Venue successfully deleted');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            } else {
                alert('Error deleting venue');
            }
        },
        error: function() {
            alert('An error occurred while processing the request.');
        }
    });
}
</script>