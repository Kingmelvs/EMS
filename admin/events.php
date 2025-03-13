<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4><i class="fa fa-calendar-alt"></i> List of Events</h4>
                        <a class="btn btn-success btn-sm" href="index.php?page=manage_event" id="new_event">
                            <i class="fa fa-plus"></i> New Entry
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover text-center">
                            <colgroup>
                                <col width="5%">
                                <col width="20%">
                                <col width="15%">
                                <col width="30%">
                                <col width="15%">
                            </colgroup>
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Venue</th>
                                    <th>Event Info</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $events = $conn->query("SELECT e.*, v.venue FROM events e LEFT JOIN venue v ON v.id = e.venue_id ORDER BY e.id ASC");
                                if ($events && $events->num_rows > 0):
                                    while ($row = $events->fetch_assoc()):
                                        $desc = htmlspecialchars(strip_tags($row['description']));
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><b><?php echo ucwords($row['venue']); ?></b></td>
                                    <td>
                                        <b><?php echo ucwords($row['event']); ?></b><br>
                                        <small><i class="fa fa-calendar"></i> Type: <?php echo $row['type'] == 1 ? "Public Event" : "Private Event"; ?></small><br>
                                        <small><i class="fa fa-money-bill"></i> Fee: <?php echo $row['payment_type'] == 1 ? "Free" : number_format($row['amount'], 2); ?></small>
                                    </td>
                                    <td><p class="text-truncate" style="max-width: 300px;"><?php echo $desc; ?></p></td>
                                    <td>
                                        <button class="btn btn-sm btn-info view_event" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning text-white edit_event" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete_event" data-id="<?php echo $row['id']; ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endwhile; else: ?>
                                <tr><td colspan="5" class="text-center">No events found</td></tr>
                                <?php endif; ?>
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
    $(document).ready(function(){
        $('table').dataTable();
    });

    $('.view_event').click(function(){
        location.href = "index.php?page=view_event&id=" + $(this).attr('data-id');
    });

    $('.edit_event').click(function(){
        location.href = "index.php?page=manage_event&id=" + $(this).attr('data-id');
    });

    $('.delete_event').click(function(){
        _conf("Are you sure to delete this event?", "delete_event", [$(this).attr('data-id')]);
    });

    function delete_event($id){
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_event',
            method: 'POST',
            data: {id: $id},
            success: function(resp){
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("Failed to delete event", 'danger');
                }
            },
            error: function(){
                alert_toast("An unexpected error occurred", 'danger');
            },
            complete: function(){
                end_load();
            }
        });
    }
</script>