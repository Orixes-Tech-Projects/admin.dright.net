<div class="modal" id="LicenseFormModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="" name="LicenseForm" id="LicenseForm" class="needs-validation" novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div class="modal-header">
                    <h5 class="modal-title"> Pharmacy License Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row form-group">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label class="col-sm-12">License Code </label>
                                <div class="col-sm-12">
                                    <textarea type="text" id="LicenseCode" name="Pharmacy[LicenseCode]"
                                              placeholder="Address" class="form-control"
                                              data-validation-engine="validate[required]" readonly></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12"> Mac Address </label>
                                <div class="col-sm-12">
                                    <input type="text" id="MAC" name="Pharmacy[MAC]" placeholder="Mac Address"
                                           data-validation-engine="validate[required]" class="form-control" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label class="col-sm-12"> Expire Date *</label>
                                <div class="col-sm-12">
                                    <input type="date" id="ExpireDate" name="Pharmacy[ExpireDate]"
                                           placeholder="Expire Date" data-validation-engine="validate[required]"
                                           class="form-control" readonly/>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </form>
            <div class="mt-4" id="ajaxResponse"></div>
        </div>
    </div>
</div>
<script>
    function LoadLicense(id) {
        var Items = AjaxResponse("pharmacy/get-record", "id=" + id);
        $('#LicenseFormModal form#LicenseForm input#MAC').val(Items.record.MAC);
        $('#LicenseFormModal form#LicenseForm input#ExpireDate').val(Items.record.ExpireDate);
        $('#LicenseFormModal form#LicenseForm textarea#LicenseCode').val(Items.record.LicenseCode);
        $('#LicenseFormModal').modal('show');

    }
</script>