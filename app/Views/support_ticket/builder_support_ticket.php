<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<?php
$session = session();
$SessionFilters = $session->get('SupportTicketFilters');
$CreatedDate = '';
$Status = '';
if (isset($SessionFilters['CreatedDate']) && $SessionFilters['CreatedDate'] != '') {
    $CreatedDate = $SessionFilters['CreatedDate'];
}
if (isset($SessionFilters['Status']) && $SessionFilters['Status'] != '') {
    $Status = $SessionFilters['Status'];
}
?>
<style>
    .form-control {
        border-radius: 5px;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="accordion accordion-primary custom-accordion">
            <div class="accordion-row <?= ((isset($SessionFilters) && $SessionFilters != '' && count($SessionFilters) > 0) ? 'open' : '') ?>">
                <a href="#" class="accordion-header">
                    <span>Search Filters <small>( Click to Search Records From Filter )</small></span>
                    <i class="accordion-status-icon close fa fa-chevron-up"></i>
                    <i class="accordion-status-icon open fa fa-chevron-down"></i>
                </a>
                <div class="accordion-body">
                    <form method="post" name="AllSupportTicketFilterForm" id="AllSupportTicketFilterForm"
                          onsubmit="SearchFilterFormSubmit('AllSupportTicketFilterForm');">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-control-label no-padding-right">Date:</label>
                                    <input type="date" id="CreatedDate" name="CreatedDate" placeholder="Name"
                                           class="form-control " value="<?= $CreatedDate; ?>"
                                           data-validation-engine="validate[required]"
                                           data-errormessage="MAC Address is required"/>
                                </div>
                                <div class="col-md-3">
                                    <label>Status:</label>
                                    <select id="Status" name="Status" class="form-control"
                                            data-validation-engine="validate[required]">
                                        <option value="">Please Select</option>
                                        <option <?= (($Status == 'Open') ? 'selected' : '') ?> value="Open">Open
                                        </option>
                                        <option <?= (($Status == 'Close') ? 'selected' : '') ?> value="Close">Close
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6" style="margin-top: 33px;">
                                    <button style="border-radius: 5px;" class="btn btn-outline-success btn-sm"
                                            onclick="SearchFilterFormSubmit('AllSupportTicketFilterForm');"
                                            type="button">Search!
                                    </button>
                                    <button style="border-radius: 5px;" class="btn btn-outline-danger btn-sm"
                                            onclick="ClearAllFilter('SupportTicketFilters');"
                                            type="button">Clear
                                    </button>
                                </div>
                                <div class="mt-2 col-md-12" id="FilterResponse"></div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header">
        <h4>List Of All Builder Support Ticket</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="frutis" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1" width="5%">#</th>
                    <th data-priority="2">Profile</th>
                    <th data-priority="3">Module</th>
                    <th data-priority="7" width="15%">Created Date</th>
                    <th data-priority="6" width="15%">Assigned To</th>
                    <th data-priority="8" width="10%">Priority</th>
                    <th data-priority="4" width="10%">Status</th>
                    <th data-priority="5" width="12%">Action</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo view('support_ticket/modal/assignee-modal'); ?>
<script>
    $(document).ready(function () {
        $('#frutis').DataTable({
            "scrollCollapse": true,
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
            "pageLength": 25,
            "autoWidth": true,
            "ajax": {
                "url": "<?= $path ?>support-ticket/fetch-builder-task",
                "type": "POST"
            }
        });
    });
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

