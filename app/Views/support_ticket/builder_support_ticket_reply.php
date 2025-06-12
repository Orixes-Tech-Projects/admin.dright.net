<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .form-control {
        border-radius: 5px;
    }
</style>
<div class="card">
    <div class="card-header">
        <h2 style="font-size: 20px !important;" class="card-title mb-0"><b><?= $TicketData['ProfileName'] ?> - </b> <b
                    style="color: crimson;">#<?= $TicketData['UID'] ?> - <?= ucwords($TicketData['Module']) ?></b></h2>
    </div>
</div>
<div class="ks-content">
    <div class="ks-body ks-content-nav">
        <div class="ks-nav-body no-margin">
            <div class="page-content">
                <div class="row">
                    <div class="col-md-12" id="ListSection">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <i class="fa fa-ticket"> Ticket Information</i>
                                    </div>
                                    <div style="padding: 0.6rem;" class="card-body">
                                        <table id="frutis" class="table table-striped table-bordered">
                                            <tbody>
                                            <tr class="ks-first-place">
                                                <td style="height: 50px;" class="ks-info">
                                                    <strong>Ticket &raquo; #<?= $TicketData['UID'] ?> -
                                                        <?= ucwords($TicketData['Module']) ?></strong>
                                                    <?php
                                                    if ($TicketData['Status'] == 'Open') {
                                                        echo '<span  class="ks-ticket-status badge badge-success float-right">Open</span>';
                                                    } else {
                                                        echo '<span class="ks-ticket-status badge badge-danger float-right">Closed</span>';
                                                    } ?>
                                                </td>
                                            </tr>

                                            <tr class="ks-second-place">
                                                <td style="height: 50px;" class="ks-info">
                                                    <strong>Submitted ON </strong>
                                                    &raquo; <?= date("d M, Y h:i A", strtotime($TicketData['SystemDate'])) ?>
                                                </td>
                                            </tr>
                                            <tr class="ks-second-place">
                                                <td style="height: 50px;" class="ks-info">
                                                    <strong>Last Reply</strong>
                                                    &raquo; <?= date("d M, Y h:i A", strtotime($last_reply)) ?>
                                                </td>
                                            </tr>
                                            <tr class="ks-second-place">
                                                <td style="height: 50px;" class="ks-info">
                                                    <strong>Priority</strong>
                                                    &raquo; <?= ucwords($TicketData['Priority']) ?>
                                                </td>
                                            </tr>
                                            <tr class="ks-second-place">
                                                <td style="height: 50px;" class="ks-info">
                                                    <strong>Assign To</strong>
                                                    &raquo; <?= ((isset($TicketData['AssignedUser']) && $TicketData['AssignedUser'] != '' && $TicketData['AssignedUser'] != 0) ? ucwords($TicketData['AssignedUser']) : '-') ?>
                                                </td>
                                            </tr>
                                            <tr class="ks-second-place">
                                                <td style="height: 50px;" class="ks-info">
                                                    <strong>Assign By</strong>
                                                    &raquo; <?= ((isset($TicketData['AssignedBy']) && $TicketData['AssignedBy'] != '' && $TicketData['AssignedBy'] != 0) ? ucwords($TicketData['AssignedBy']) : '-') ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        Conversations
                                    </div>
                                    <div style="padding: 1rem;" class="card-body">
                                        <div class="accordion accordion-primary custom-accordion">
                                            <div class="accordion-row">
                                                <a href="#" class="accordion-header">
                                                    <span>Replies <small>( Click to View Ticket Conversation )</small></span>
                                                    <i class="accordion-status-icon close fa fa-chevron-up"></i>
                                                    <i class="accordion-status-icon open fa fa-chevron-down"></i>
                                                </a>
                                                <div class="accordion-body">
                                                    <div class="row" id="CommentsDiv"
                                                         style="max-height: 400px; overflow-y: auto;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if ($TicketData['Status'] == 'Open') {
                                        ?>
                                        <div class="card-footer">
                                            <form enctype="multipart/form-data" class="needs-validation" novalidate=""
                                                  name="BuilderTicketReplyForm" id="BuilderTicketReplyForm"
                                                  onsubmit="BuilderTicketReplyFormSubmit( 'BuilderTicketReplyForm' ); return false;">
                                                <input type="hidden" name="TaskID" id="TaskID" value="<?= $TicketID ?>">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="validationCustom04">Message</label>
                                                        <textarea id="message" class="form-control"
                                                                  name="message"></textarea>
                                                    </div>
                                                    <div class="col-md-12 col-lg-12 mt-2">
                                                        <label class="control-label">Attachments</label>
                                                        <input style="height: auto !important;" type="file"
                                                               class="form-control" name="Image" id="files">
                                                    </div>
                                                    <div class="col-md-12 mt-2" id="AjaxResult"></div>
                                                    <div class="col-md-12 mt-2">
                                                        <button style="float: right; border-radius: 5px;"
                                                                onclick="BuilderTicketReplyFormSubmit();"
                                                                class="btn btn-success btn-sm" type="button">Submit
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready(function () {
        $('#myTable').DataTable({
            "scrollY": "200px",
            "scrollCollapse": true
        });
        LoadTicketsComments();
    });
</script>
<script>
    function ShowInput(val) {

        if (val == 'input') {

            $("div#MessageRow").addClass('d-none');
            $("div#InputRow").removeClass('d-none');
        } else {
            $("div#MessageRow").removeClass('d-none');
            $("div#InputRow").addClass('d-none');
        }
    }

    function UpdateBuilderDeadLineFormSubmit() {
        var data = new window.FormData($("form#UpdateDeadLineForm")[0]);
        // console.log( data );
        // alert( data );
        rslt = AjaxUploadResponse("support-ticket/update-builder-deadline-form-submit", data);
        if (rslt.status == 'success') {

            setTimeout(function () {
                window.location.href = location.href;
            }, 2000);

        }
    }

    function LoadTicketsComments() {

        var TicketID = "<?=$TicketData['UID']?>";
        AjaxRequest("support-ticket/load_builder_tickets_comments", "TicketID=" + TicketID, "CommentsDiv");
    }

    function BuilderTicketReplyFormSubmit() {

        setTimeout(function () {
            $("#AjaxResult").html('');
        }, 2500);

        var Message = $("form#BuilderTicketReplyForm textarea#message").val();
        if (Message === '') {
            $("#AjaxResult").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Message Required! </div>');
            return false;
        }

        const fileInput = document.getElementById('files');
        const file = fileInput.files[0];
        const allowedExtensions = ['gif', 'jpg', 'jpeg', 'png', 'webp'];

        if (file) {
            const fileExt = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExt)) {
                $("#BuilderTicketReplyForm #AjaxResult").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Invalid file type. Allowed formats: ' + allowedExtensions.join(', ') + ' </div>');
                return false;
            }

            const maxSize = 1 * 1024 * 1024; // 1MB
            if (file.size > maxSize) {
                $("#BuilderTicketReplyForm #AjaxResult").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> File size exceeds 1MB limit</div>');
                return false;
            }
        }

        var data = new window.FormData($("form#BuilderTicketReplyForm")[0]);
        var rslt = AjaxUploadResponse("support-ticket/BuilderTicketReplyFormSubmit", data);
        if (rslt.status == 'success') {
            $("#AjaxResult").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + rslt.message + ' </div>');
            $("form#BuilderTicketReplyForm")[0].reset();
            setTimeout(function () {
                LoadTicketsComments();
            }, 2000);

        } else {
            $("#AjaxResult").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + rslt.message + ' </div>');
            return false;
        }
    }
</script>
<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>


