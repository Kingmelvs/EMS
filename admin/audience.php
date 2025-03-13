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
                        <h4><i class="fa fa-users"></i> Event Audience List</h4>
                        <button class="btn btn-success btn-sm" id="new_register">
                            <i class="fa fa-plus"></i> New
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover text-center">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Event Information</th>
                                    <th>Audience Information</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $registering = $conn->query("SELECT a.*, e.event, e.payment_type, e.type, e.amount, e.schedule 
                                                           FROM audience a 
                                                           INNER JOIN events e ON e.id = a.event_id");
                                while ($row = $registering->fetch_assoc()):
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <p><b><?php echo ucwords($row['event']); ?></b></p>
                                        <small>Schedule:
                                            <b><?php echo date("M d, Y h:i A", strtotime($row['schedule'])); ?></b></small><br>
                                        <small>Type:
                                            <b><?php echo $row['type'] == 1 ? "Public Event" : "Private Event"; ?></b></small><br>
                                        <small>Fee:
                                            <b><?php echo $row['payment_type'] == 1 ? "Free" : number_format($row['amount'], 2); ?></b></small>
                                    </td>
                                    <td>
                                        <p><b><?php echo ucwords($row['name']); ?></b></p>
                                        <small>Email: <?php echo htmlspecialchars($row['email']); ?></small><br>
                                        <small>Contact: <?php echo htmlspecialchars($row['contact']); ?></small><br>
                                        <small>Address: <?php echo htmlspecialchars($row['address']); ?></small><br>
                                        <small>Payment:
                                            <b><?php echo $row['payment_type'] == 1 ? "N/A" : ($row['payment_status'] == 1 ? "Paid" : "Unpaid"); ?></b></small>
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
                                        <button class="btn btn-warning btn-sm edit_register"
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

$('#new_register').click(function() {
    uni_modal("New Entry", "manage_register.php");
});

$('.edit_register').click(function() {
    uni_modal("Manage Register Details", "manage_register.php?id=" + $(this).data('id'));
});


</script>