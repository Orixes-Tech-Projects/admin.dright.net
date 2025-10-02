<div class="modal" id="UpdateTakeTypeMedicineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 60%;">
        <div class="modal-content">
            <form method="post" action="" name="UpdateTakeTypeMedicineForm" id="UpdateTakeTypeMedicineForm"
                  class="needs-validation" novalidate enctype="multipart/form-data">

                <input type="hidden" name="UID" id="UID" value="">

                <div class="modal-header">
                    <h5 class="modal-title">Update Take Type</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="TakeTypeEng">English <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="TakeTypeEng" name="TakeType[TakeTypeEng]"
                                   placeholder="Enter English Type" required>
                            <div class="invalid-feedback">Please enter the English type.</div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="TakeType">Urdu <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="TakeType" name="TakeType[TakeType]"
                                   placeholder="Enter Urdu Type" required dir="rtl" style="text-align: right;">
                            <div class="invalid-feedback">براہ کرم اردو ٹائپ درج کریں۔</div>
                        </div>
                        <div class="col-md-12" id="UpdateajaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="UpdateTakeTypeMedicineFormFunction()">Save
                        changes
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

    function UpdateTakeTypeMedicineFormFunction() {
        const form = document.getElementById('UpdateTakeTypeMedicineForm');

        // Check validation
        if (form.checkValidity() === false) {
            form.classList.add('was-validated');
            return;
        }

        const Data = $("#UpdateTakeTypeMedicineForm").serialize();
        const response = AjaxResponse("medicine/submit_medicine_take_type_form", Data);

        if (response.status === 'success') {
            $("#UpdateajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"><strong>Success!</strong> ' + response.message + '</div>');
            setTimeout(function () {
                location.reload();
            }, 500);
        } else {
            $("#UpdateajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"><strong>Error!</strong> ' + response.message + '</div>');
        }
    }
</script>
