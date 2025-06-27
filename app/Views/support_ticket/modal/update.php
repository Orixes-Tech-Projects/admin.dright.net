<div class="modal" id="UpdatePackageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 50% !important;">
        <div class="modal-content">
            <form method="post" name="UpdatePackageForm" id="UpdatePackageForm" enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div style="border-bottom: 1px solid #dee2e6;" class="modal-header">
                    <h5 class="modal-title">Update Package</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-8 mb-3">
                            <label for="validationCustom01">Name <span style="color: crimson;">*</span></label>
                            <input placeholder="Enter Package Name" type="text" class="form-control" id="Name"
                                   name="Item[Name]" required>
                            <div class="invalid-feedback">Please provide a package name.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Code <span style="color: crimson;">*</span></label>
                            <input placeholder="Enter Package Code" type="text" class="form-control" id="Code"
                                   name="Item[Code]" required>
                            <div class="invalid-feedback">Please provide a package code.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Original Price <span
                                        style="color: crimson;">*</span></label>
                            <input oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="Enter Package Price" type="text" class="form-control" id="OriginalPrice"
                                   name="Item[OriginalPrice]" required>
                            <div class="invalid-feedback">Please provide a valid price.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Discount (%) <span style="color: crimson;">*</span></label>
                            <input value="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="Enter Package Discount" type="text" class="form-control" id="Discount"
                                   name="Item[Discount]" required>
                            <div class="invalid-feedback">Please provide a valid discount.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom01">Price After Discount <span style="color: crimson;">*</span></label>
                            <input readonly oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="Price After Discount" type="text" class="form-control" id="Price"
                                   name="Item[Price]" required>
                            <div class="invalid-feedback">Please provide a valid price.</div>
                        </div>
                        <div id="UpdatePackageTypeDiv" class="col-md-12 mb-3">
                            <label for="validationCustom02">Type <span style="color: crimson;">*</span></label>
                            <select onchange="LoadNoOfMonthsDiv(this.value, 'update');" class="form-control" id="Type"
                                    name="Item[Type]" required>
                                <option value="">Select Type</option>
                                <option value="monthly">Monthly</option>
                                <option value="annually">Annually</option>
                            </select>
                            <div class="invalid-feedback">Please select a package type.</div>
                        </div>
                        <div id="UpdateNoOfMonthsDiv" class="col-md-12 d-none mb-3">
                            <label for="validationCustom02">No Of Months <span style="color: crimson;">*</span></label>
                            <input oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="Enter No Of Months" type="text" class="form-control" id="NoOfMonths"
                                   name="Item[NoOfMonths]">
                            <div class="invalid-feedback">Please provide number of months.</div>
                        </div>
                        <div class="col-md-12 mt-2" id="UpdatePackageResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="UpdatePackageFormSubmit()">Save changes
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>

    function LoadPackageUpdateModal(id) {

        var Items = AjaxResponse("support-ticket/get-record-items", "id=" + id);

        $('#UpdatePackageModal form#UpdatePackageForm input#UID').val(Items.record.UID);
        $('#UpdatePackageModal form#UpdatePackageForm input#Name').val(Items.record.Name);
        $('#UpdatePackageModal form#UpdatePackageForm input#Code').val(Items.record.Code);
        $('#UpdatePackageModal form#UpdatePackageForm select#Type').val(Items.record.Type);
        $('#UpdatePackageModal form#UpdatePackageForm input#OriginalPrice').val(Items.record.OriginalPrice);
        $('#UpdatePackageModal form#UpdatePackageForm input#Discount').val(Items.record.Discount);
        $('#UpdatePackageModal form#UpdatePackageForm input#Price').val(Items.record.Price);
        $('#UpdatePackageModal form#UpdatePackageForm input#NoOfMonths').val(Items.record.NoOfMonths);

        setTimeout(function () {
            LoadNoOfMonthsDiv(Items.record.Type, 'update');
        });

        $('#UpdatePackageModal').modal('show');
    }

    function UpdatePackageFormSubmit() {

        const form = $('#UpdatePackageModal form#UpdatePackageForm')[0];
        const packageType = $('#UpdatePackageModal form#UpdatePackageForm select#Type').val();
        if (packageType === 'monthly') {
            $('#UpdatePackageModal form#UpdatePackageForm input#NoOfMonths').attr('required', 'required');
        } else {
            $('#UpdatePackageModal form#UpdatePackageForm input#NoOfMonths').removeAttr('required');
        }

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return false;
        }

        const price = $('#UpdatePackageModal form#UpdatePackageForm input#Price').val();
        if (price <= 0) {
            $("#UpdatePackageResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Original Price must be greater than 0 </div>');
            return false;
        }

        if (packageType === 'monthly') {
            const NoOfMonths = $('#UpdatePackageModal form#UpdatePackageForm input#NoOfMonths').val();
            if (NoOfMonths <= 0 || NoOfMonths > 11) {
                $("#UpdatePackageResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> No of months must be greater than 0 or lesss than 12 </div>');
                return false;
            }
        }

        var formdata = $("form#UpdatePackageForm").serialize();
        $("#UpdatePackageResponse").html('<div class="alert alert-info mb-4" style="margin: 10px;" role="alert">Processing your request...</div>');

        var response = AjaxResponse("support-ticket/submit-item", formdata);
        if (response.status === 'success') {
            $("#UpdatePackageResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 2000);
        } else {
            $("#UpdatePackageResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>