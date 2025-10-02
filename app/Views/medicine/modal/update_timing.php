<div class="modal" id="UpdateTimingMedicineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <form method="post" action="" name="UpdateTimingMedicineForm" id="UpdateTimingMedicineForm"
                  class="needs-validation" novalidate enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Update Timing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="EngNameUpdate">Timing Name (English) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="EngName" name="timing[EngName]"
                                   placeholder="Enter English Name" required>
                            <div class="invalid-feedback">Please enter the English name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="NameUpdate">Timing Name (Urdu) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="Name" name="timing[Name]"
                                   placeholder="Enter Urdu Name" dir="rtl" style="text-align: right;" required>
                            <div class="invalid-feedback">براہ کرم اردو نام درج کریں۔</div>
                        </div>
                        <div class="col-md-12" id="UpdateajaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="UpdateTimingMedicineFormFunction()">Save
                        changes
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function UpdateTimingMedicineFormFunction() {

        const form = document.getElementById('UpdateTimingMedicineForm');

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const Data = $("#UpdateTimingMedicineForm").serialize();
        const response = AjaxResponse("medicine/submit_medicine_timing", Data);
        if (response.status === 'success') {
            $("#UpdateajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"><strong>Success!</strong> ' + response.message + '</div>');
            setTimeout(() => location.reload(), 500);
        } else {
            $("#UpdateajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"><strong>Error!</strong> ' + response.message + '</div>');
        }
    }
</script>
