<div class="modal" id="UpdatePharmacyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="" name="UpdatePharmacyForm" id="UpdatePharmacyForm" class="needs-validation"
                  novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Update Pharmacy Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12">Full Name <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" id="name" name="Pharmacy[FullName]"
                                           placeholder="Profile Name" class="form-control"
                                           data-validation-engine="validate[required]"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12">Contact No <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" id="contact_no" name="Pharmacy[ContactNo]"
                                           placeholder="Contact No" class="form-control"
                                           data-validation-engine="validate[minSize[11]]" maxlength="11"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12">City <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <select id="city" name="Pharmacy[City]" class="form-control"
                                            data-validation-engine="validate[required]">
                                        <option value="">Please Select</option>
                                        <?php foreach ($cities as $record) { ?>
                                            <option value="<?= $record['UID'] ?>"
                                            ><?= ucwords($record['FullName']); ?></option>
                                        <?php } ?>                                </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12">Email</label>
                                <div class="col-sm-12">
                                    <input type="email" id="email" name="Pharmacy[Email]" placeholder="Email"
                                           class="form-control" data-validation-engine="validate[custom[email]]"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12"> Sale Agent <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" id="sale_agent" name="Pharmacy[SaleAgent]"
                                           placeholder="Sale Agent" class="form-control"
                                           data-validation-engine="validate[required]"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12">Deployment Date <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input readonly type="date" id="deployment_date" name="Pharmacy[DeploymentDate]"
                                           placeholder="Deployment Date" data-validation-engine="validate[required]"
                                           class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12">Expire Date</label>
                                <div class="col-sm-12">
                                    <input type="date" id="expire_date" name="Pharmacy[ExpireDate]"
                                           placeholder="Expire Date" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12"> Mac Address <span class="text-danger">*</span></label>
                                <div class="col-sm-12">
                                    <input type="text" id="mac_address" name="Pharmacy[MAC]" placeholder="Mac Address"
                                           data-validation-engine="validate[required]" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12">Address </label>
                                <div class="col-sm-12">
                                    <input type="text" id="address" name="Pharmacy[Address]" placeholder="Address"
                                           class="form-control" data-validation-engine="validate[required]"/>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 col-md-12" id="UpdateAjaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="UpdatePharmacyFormFunction()">Update Profile
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

    function UpdatePharmacy(id) {

        var Items = AjaxResponse("pharmacy/get-record", "id=" + id);

        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#UID').val(Items.record.UID);
        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#name').val(Items.record.FullName);
        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#email').val(Items.record.Email);
        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#contact_no').val(Items.record.ContactNo);
        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#address').val(Items.record.Address);
        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#sale_agent').val(Items.record.SaleAgent);
        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#deployment_date').val(Items.record.DeploymentDate);
        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#expire_date').val(Items.record.ExpireDate && Items.record.ExpireDate != '0000-00-00' ? Items.record.ExpireDate : '');
        $('#UpdatePharmacyModal form#UpdatePharmacyForm input#mac_address').val(Items.record.MAC);
        $('#UpdatePharmacyModal form#UpdatePharmacyForm select#city').val(Items.record.City);

        $('#UpdatePharmacyModal').modal('show');
    }

    function UpdatePharmacyFormFunction() {


        const FullName = $("form#UpdatePharmacyForm input#name").val();
        const ContactNo = $("form#UpdatePharmacyForm input#contact_no").val();
        const City = $("form#UpdatePharmacyForm select#city").val();
        const SaleAgent = $("form#UpdatePharmacyForm input#sale_agent").val();
        const DeploymentDate = $("form#UpdatePharmacyForm input#deployment_date").val();
        const MacAddress = $("form#UpdatePharmacyForm input#mac_address").val();

        if (FullName == '') {
            $("#UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Full Name Required </div>');
            return false;
        }
        if (ContactNo == '') {
            $("#UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Contact No Required </div>');
            return false;
        }
        if (City == '') {
            $("#UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> City Required </div>');
            return false;
        }
        if (SaleAgent == '') {
            $("#UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Sale Agent Required </div>');
            return false;
        }
        if (DeploymentDate == '') {
            $("#UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Deploymet Date Required </div>');
            return false;
        }
        if (MacAddress == '') {
            $("#UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Mac Address Required </div>');
            return false;
        }

        const formdata = $("form#UpdatePharmacyForm").serialize();
        const response = AjaxResponse("pharmacy/submit", formdata);
        if (response.status === 'success') {
            $("#UpdateAjaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 2500);
        } else {
            $("#UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>