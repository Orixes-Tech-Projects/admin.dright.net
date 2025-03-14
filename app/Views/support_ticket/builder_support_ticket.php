<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('SupportTicketFilters');
$CreatedDate='';
$Status='';
if (isset($SessionFilters['CreatedDate']) && $SessionFilters['CreatedDate'] != '') {
    $CreatedDate = $SessionFilters['CreatedDate'];
}if (isset($SessionFilters['Status']) && $SessionFilters['Status'] != '') {
    $Status = $SessionFilters['Status'];
}
?>
<div class="card">
    <div class="card-body">
        <h4>Builder Support Ticket
        </h4>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h5>Search Filters</h5>
                <hr>
                <form method="post" name="AllSupportTicketFilterForm" id="AllSupportTicketFilterForm"
                      onsubmit="SearchFilterFormSubmit('AllSupportTicketFilterForm');">
                    <div class="form-group">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label no-padding-right">Date:</label>
                                <input type="date" id="CreatedDate" name="CreatedDate" placeholder="Name"
                                       class="form-control "  value="<?=$CreatedDate;?>" data-validation-engine="validate[required]"
                                       data-errormessage="MAC Address is required"/>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-sm-4">Status:</label>
                                    <div class="col-sm-12">
                                        <select id="Status" name="Status" class="form-control"
                                                data-validation-engine="validate[required]">
                                            <option value="">Please Select</option>
                                            <option value="Open">Open</option>
                                            <option value="Close">Close</option>
                                           
                                               
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12" style="float: right">
                                 <span style="float: right;">
                                    <button class="btn btn-outline-primary" onclick="ClearAllFilter('SupportTicketFilters');"
                                            type="button">Clear</button>

                                <button class="btn btn-outline-success"
                                        onclick="SearchFilterFormSubmit('AllSupportTicketFilterForm');"
                                        type="button">Search!</button>
                                 </span>
                            </div>
                            <div class="mt-4" id="FilterResponse"></div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="frutis" class="table table-striped table-bordered">
            <thead>            <tr>
                <th>Sr No</th>
                <th>Profile</th>
                <th>Module</th>
                <th>Created Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th>Sr No</th>
                <th>Profile</th>
                <th>Module</th>

                <th>Created Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <div class="mt-4" id="Response"></div>

            </tfoot>
        </table>
    </div>
    <script>
        $(document).ready(function (){
            $('#frutis').DataTable({
                "scrollY": "800px",
                "scrollCollapse": true,
                "searching": true,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "lengthMenu": [[100, 500, 1000, -1], [100, 500, 1000, 'All']],
                "pageLength": 100,
                "autoWidth": true,
                "ajax": {
                    "url": "<?= $path ?>support-ticket/fetch-builder-task",
                    "type": "POST"
                }
            });});

    </script>

<script>
    function ReplyTicket(id) {
        location.href = "<?=$path?>support-ticket/builder_tickets_reply/" + id;
    }
    function SearchFilterFormSubmit(parent) {

        var data = $("form#" + parent).serialize();
        var rslt = AjaxResponse('support-ticket/builder_support_search_filter', data);
        if (rslt.status == 'success') {
            $("#AllSupportTicketFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }

    function ClearAllFilter(Session) {
        var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
        if (rslt.status == 'success') {
            $("#AllSupportTicketFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }
</script>
    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>

