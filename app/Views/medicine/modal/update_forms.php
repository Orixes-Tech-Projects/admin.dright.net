<div class="modal" id="UpdateFormsMedicineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <form method="post" action="" name="UpdateFormsMedicineForm" id="UpdateFormsMedicineForm"
                  class="needs-validation" novalidate enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="">

                <div class="modal-header">
                    <h5 class="modal-title">Update Medicine Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="EngName">Form Name (English) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="EngName" name="Forms[EngName]"
                                   placeholder="Enter English Name" required>
                            <div class="invalid-feedback">Please enter the English form name.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="Name">Form Name (Urdu) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Name" name="Forms[Name]"
                                   placeholder="Enter Urdu Name" dir="rtl" style="text-align: right;" required>
                            <div class="invalid-feedback">براہ کرم اردو فارم کا نام درج کریں۔</div>
                        </div>
                        <div class="col-md-12" id="UpdateajaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="UpdateFormsMedicineFormFunction()">Save
                        changes
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function UpdateFormsMedicineFormFunction() {

        const form = document.getElementById('UpdateFormsMedicineForm');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const Data = $("#UpdateFormsMedicineForm").serialize();
        const response = AjaxResponse("medicine/submit_medicine_forms", Data);
        if (response.status === 'success') {
            $("#UpdateajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 500);
        } else {
            $("#UpdateajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>