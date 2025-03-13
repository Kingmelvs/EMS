<?php include 'db_connect.php'; ?>
<div class="container-fluid">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 style="padding-top: 10px;"><i class="fa fa-users"></i> Event Audience Report</h4>
            </div>
            <div class="card-body">
                <div class="col-md-12">
                    <!-- Filter Form -->
                    <form id="filter">
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label for="event_id" class="control-label">Event</label>
                                <select name="event_id" id="event_id" class="custom-select select2" required>
                                    <option value="" disabled selected>Select Event</option>
                                    <?php 
					                $event = $conn->query("SELECT * FROM events order by event asc");
                                    while($row = $event->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo ucwords($row['event']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label class="control-label d-block">&nbsp;</label>
                                <button class="btn btn-primary btn-block" type="submit">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                            </div>

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
                            <p class="text-center font-weight-bold">Audience List and Details</p>
                            <p>Event: <span id="ename"></span></p>
                            <p>Venue: <span id="evenue"></span></p>
                            <hr>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Contact</th>
                                    <th class="text-center">Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><th colspan="5" class="text-center text-danger">Select Event First.</th></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #onPrint{
        display: none;
    }
</style>

<noscript>
    <style>
        table{
            width:100%;
            border-collapse: collapse;
        }
        tr, td, th{
            border: 1px solid black;
        }
        .text-center{
            text-align:center;
        }
        p{
            font-weight: 600
        }
    </style>
</noscript>

<script>
    $('#filter').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=get_audience_report',
            method:'POST',
            data:{event_id:$('#event_id').val()},
            success:function(resp){
                if(resp){
                    resp = JSON.parse(resp)
                    if(!!resp.event){
                        $('#ename').html(resp.event.event)
                        $('#evenue').html(resp.event.venue)
                    }
                    if(!!resp.data && Object.keys(resp.data).length > 0){
                        $('table tbody').html('')
                        var i = 1;
                        Object.keys(resp.data).map(k=>{
                            var tr = $('<tr class="item"></tr>')
                            tr.append('<td class="text-center">'+(i++)+'</td>')
                            tr.append('<td class="">'+resp.data[k].name+'</td>')
                            tr.append('<td class="">'+resp.data[k].email+'</td>')
                            tr.append('<td class="">'+resp.data[k].contact+'</td>')
                            tr.append('<td class="">'+resp.data[k].pstatus+'</td>')
                            $('table tbody').append(tr)
                        })
                    }else{
                        $('table tbody').html('<tr><th colspan="5" class="text-center">No Data Available.</th></tr>')
                    }
                }
            },
            complete:function(){
                end_load()
            }
        })
    })

    $('#print').click(function(){
        if($('table tbody').find('.item').length <= 0){
            alert_toast("No Data to Print",'warning')
            return false;
        }
        var nw= window.open("","_blank","width=900,height=600")
        nw.document.write($('noscript').html())
        nw.document.write($('#printable').html())
        nw.document.close()
        nw.print()
        setTimeout(function(){
            nw.close()
        },700)
    })
</script>