<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="card">
    <div class="card-body">
        <h6 class="card-title mb-0">View Ticket</h6>
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
                                    <div class="card-body">
                                        <table id="frutis" class="table table-striped table-bordered">
                                            <tbody>
                                            <tr class="ks-first-place">
                                                <td class="ks-info" style="padding-bottom: 5px !important; ">
                                                    <p style="margin-left: 100px;">
                                                        <strong>#<?= $TicketData['UID'] ?></strong>
                                                        -
                                                        <?php
                                                        if ($TicketData['Status'] == 'Open') {
                                                            echo '<span  class="ks-ticket-status badge badge-info float-right">Open</span>';
                                                        } else {
                                                            echo '<span class="ks-ticket-status badge badge-danger float-right">Closed</span>';
                                                        } ?>
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr class="ks-second-place">
                                                <td class="ks-info" style="padding-bottom: 5px !important; ">
                                                    <p style="margin-left: 100px;"><strong>Submitted</strong>
                                                        <br> <?= date("d M, Y h:i A", strtotime($TicketData['SystemDate'])) ?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr class="ks-second-place">
                                                <td class="ks-info" style="padding-bottom: 5px !important; ">
                                                    <p style="margin-left: 100px;"><strong>Last Reply</strong>
                                                        <br> <?= date("d M, Y h:i A", strtotime($last_reply)) ?>
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr class="ks-second-place">
                                                <td class="ks-info"
                                                    style="padding-bottom: 5px !important; margin-left: 100px;">
                                                    <p style="margin-left: 100px;"><strong>Priority</strong>
                                                        <br> <?= $TicketData['Priority'] ?></p>
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
                                        Replies
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="CommentsDiv" style=";max-height: 400px; overflow-y: auto;">

                                        </div>
                                    <div class="card-footer">
                                        <form enctype="multipart/form-data" class="needs-validation" novalidate=""
                                              name="BuilderTicketReplyForm" id="BuilderTicketReplyForm"
                                              onsubmit="BuilderTicketReplyFormSubmit( 'BuilderTicketReplyForm' ); return false;">
                                            <input type="hidden" name="TaskID" id="TaskID" value="<?= $TicketID ?>">


                                                <div class="form-row">
                                                    <div class="col-md-6">
                                                        <label for="validationCustom04">Message</label>
                                                        <textarea id="summernote" class="form-control"
                                                                  name="message"></textarea>
                                                    </div>

                                                            <div class="col-md-6 col-lg-6">
                                                                <label class="control-label">Attachments</label>
                                                                <input style="height: auto !important;" type="file"
                                                                       class="form-control" name="Image" id="files">
                                                            </div>

                                                    <div class="col-md-12" id="ImageFiles"></div>
                                                </div>
                                                <div class="row" style="margin-top: 0px !important;">
                                                    <div class="col-md-12" id="AjaxResult"></div>
                                                </div>
                                                <div class="row" style="margin-top: 0px !important;">
                                                    <div class="col-md-12">
                                                        <button style="float: right;"
                                                                onclick="BuilderTicketReplyFormSubmit();"
                                                                class="btn btn-success btn-sm" type="button">Submit
                                                        </button>
                                                    </div>
                                                </div>

                                        </form>
                                    </div>
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
            // alert( TicketID );

            AjaxRequest("support-ticket/load_builder_tickets_comments", "TicketID=" + TicketID, "CommentsDiv");
        }

        function BuilderTicketReplyFormSubmit() {
            var data = new window.FormData($("form#BuilderTicketReplyForm")[0]);
            // console.log( data );
            // alert( data );
            rslt = AjaxUploadResponse("support-ticket/BuilderTicketReplyFormSubmit", data);
            LoadTicketsComments();
            // if (rslt.status == 'success') {
            //
            //     setTimeout(function () {
            //         window.location.href = location.href;
            //     }, 2000);
            //
            // }
        }
    </script>
    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>


