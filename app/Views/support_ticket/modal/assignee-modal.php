<?php

use App\Models\Crud;

$Crud = new Crud();
?>
<div class="modal" id="TicketAssigneeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 50% !important;">
        <div class="modal-content">
            <form method="post" action="" name="TicketAssigneeForm" id="TicketAssigneeForm"
                  class="needs-validation" novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="TicketUID" id="TicketUID" value="0">
                <div style="border-bottom: 1px solid #dee2e6;" class="modal-header">
                    <h5 class="modal-title">Assign Ticket <span style="color: crimson; font-weight: bold;"
                                                                id="TicketDetails"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="validationCustom02">Assign To</label>
                            <select class="form-control" id="assign_to" name="assign_to">
                                <option value="">Select User</option>
                                <?php
                                $SupportUsers = $Crud->ListRecords('system_users', array('AccessLevel' => 'support_department'), array('FullName' => 'ASC'));
                                foreach ($SupportUsers as $SU) {
                                    echo '<option value="' . $SU['UID'] . '">' . $SU['FullName'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mt-2 col-md-12" id="ajaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="border-radius: 5px;" type="button" class="btn btn-success btn-sm"
                            onclick="TicketAssigneeFormFunction()">Submit Record
                    </button>
                    <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm"
                            data-dismiss="modal">Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function LoadTicketAssigneeModal(TicketID, TicketDetails, AssignedUserID) {

        $('#TicketAssigneeModal form#TicketAssigneeForm input#TicketUID').val(TicketID);
        $("#TicketAssigneeModal h5 span#TicketDetails").html(TicketDetails);

        if (AssignedUserID > 0) {
            $('#TicketAssigneeModal form#TicketAssigneeForm select#assign_to').val(AssignedUserID);
        }

        $('#TicketAssigneeModal').modal('show');
    }

    function TicketAssigneeFormFunction() {

        setTimeout(function () {
            $("#TicketAssigneeModal #ajaxResponse").html('');
        }, 2500);

        const AssignTo = $("#TicketAssigneeModal form#TicketAssigneeForm select#assign_to").val();

        if (AssignTo == '') {
            $("#TicketAssigneeModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Assignee User Required!</div>');
            return false;
        }

        var data = $("form#TicketAssigneeForm").serialize();
        var response = AjaxResponse("support-ticket/submit-ticket-assignee", data);

        if (response.status === 'success') {
            $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 500);
        } else {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>