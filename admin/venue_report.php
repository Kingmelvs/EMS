<?php include 'db_connect.php'; ?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white" >
            <h4 style="padding-top: 10px;"><i class="fa fa-building"></i> Venue Report</h4>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <!-- Filter Form -->
                    <form id="filter">
                        <div class="row form-group">
                            <!-- Venue Selection -->
                            <div class="col-md-4">
                                <label for="venue_id" class="control-label">Venue</label>
                                <select name="venue_id" id="venue_id" class="custom-select select2" required>
                                    <option value="" disabled selected>Select Venue</option>
                                    <?php 
                                    $venues = $conn->query("SELECT * FROM venue ORDER BY venue ASC");
                                    while($row = $venues->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo ucwords($row['venue']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Month Selection -->
                            <div class="col-md-3">
                                <label for="month" class="control-label">Month</label>
                                <input type="month" name="month" class="form-control" id="month" required>
                            </div>

                            <!-- Filter Button -->
                            <div class="col-md-2">
                                <label class="control-label d-block">&nbsp;</label>
                                <button class="btn btn-primary btn-block" type="submit">Filter</button>
                            </div>

                            <!-- Print Button -->
                            <div class="col-md-2">
                                <label class="control-label d-block">&nbsp;</label>
                                <button class="btn btn-success btn-block" id="print" type="button">
                                    <i class="fa fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                    </form>
                    <hr>

                    <!-- Report Section -->
                    <div id="printable">
                        <div id="onPrint" style="display: none;">
                            <p class="text-center font-weight-bold">Venue's Event List and Details</p>
                            <p>Venue: <span id="venue"></span></p>
                            <p>Month: <span id="month-field"></span></p>
                            <hr>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Event</th>
                                    <th class="text-center">Schedule</th>
                                    <th class="text-center">Event Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" class="text-center">Select Venue and Month to View Events</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<noscript>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        tr, td, th {
            border: 1px solid black;
        }
        .text-center {
            text-align: center;
        }
        h4 {
            padding-bottom: 10px;
        }
    </style>
</noscript>

<script>
    $(document).ready(function() {
        // Filter Form Submission
        $('#filter').submit(function(e) {
            e.preventDefault();
            start_load();
            $.ajax({
                url: 'ajax.php?action=get_venue_report',
                method: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp) {
                        resp = JSON.parse(resp);
                        // Update Venue Details
                        $('#venue').html(resp.venue.venue || '');
                        $('#month-field').html(resp.venue.month || '');

                        // Update Event Data
                        if (resp.data && Object.keys(resp.data).length > 0) {
                            let tbody = $('table tbody');
                            tbody.html('');
                            let i = 1;
                            Object.keys(resp.data).forEach(function(key) {
                                let item = resp.data[key];
                                tbody.append(`
                                    <tr class="item">
                                        <td class="text-center">${i++}</td>
                                        <td>${item.event}</td>
                                        <td>${item.sched}</td>
                                        <td>${item.etype}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            $('table tbody').html('<tr><td colspan="5" class="text-center">No Events Found</td></tr>');
                        }
                    }
                },
                error: function() {
                    alert_toast('An error occurred while fetching data', 'danger');
                },
                complete: function() {
                    end_load();
                }
            });
        });

        // Print Report
        $('#print').click(function() {
            if ($('table tbody').find('.item').length <= 0) {
                alert_toast('No Data to Print', 'warning');
                return false;
            }
            let nw = window.open("", "_blank", "width=900, height=600");
            nw.document.write($('noscript').html());
            nw.document.write($('#printable').html());
            nw.document.close();
            nw.print();
            setTimeout(function() {
                nw.close();
            }, 700);
        });
    });
</script>