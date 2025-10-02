<div class="modal" id="UpdateMedicineModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 60%">
        <div class="modal-content">
            <form method="post" action="" name="UpdateMedicineForm" id="UpdateMedicineForm" class="needs-validation" novalidate enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Update Medicine</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-12 mb-3">
                            <label for="MedicineTitle">Name <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="MedicineTitle" name="Medicine[MedicineTitle]" placeholder="Enter medicine name" required>
                            <div class="invalid-feedback">Please enter the medicine name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="DosageForm">Formulation <small class="text-danger">*</small></label>
                            <input type="text" class="form-control" id="DosageForm" name="Medicine[DosageForm]" placeholder="Enter dosage form" required>
                            <div class="invalid-feedback">Please enter the formulation.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="Packing" style="width: 100%;">Strength & Unit <small class="text-danger">*</small>
                                <small style="float: right; color: red;">(e.g., 100 MG, 100 ML)</small>
                            </label>
                            <input type="text" class="form-control" id="Packing" name="Medicine[Packing]" placeholder="Enter strength and unit" required>
                            <div class="invalid-feedback">Please enter strength and unit.</div>
                        </div>
                        <div class="col-md-12" id="UpdateajaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="UpdateMedicineFormFunction()">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#UpdateMedicineModal').on('shown.bs.modal', function () {
        const form = document.getElementById('UpdateMedicineForm');
        form.classList.remove('was-validated');
        $('#UpdateajaxResponse').html('');
    });

    function UpdateMedicineFormFunction() {
        const form = document.getElementById('UpdateMedicineForm');

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const Data = $("form#UpdateMedicineForm").serialize();
        const response = AjaxResponse("medicine/submit_medicine_form", Data);
        if (response.status === 'success') {
            $("#UpdateajaxResponse").html('<div class="alert alert-success mb-4" role="alert"><strong>Success!</strong> ' + response.message + '</div>');
            setTimeout(() => location.reload(), 500);
        } else {
            $("#UpdateajaxResponse").html('<div class="alert alert-danger mb-4" role="alert"><strong>Error!</strong> ' + response.message + '</div>');
        }
    }
</script>
