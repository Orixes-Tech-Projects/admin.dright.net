<?php

use App\Models\Crud;

$Crud = new Crud();
$AllPackages = $Crud->ListRecords('items');
?>
<div class="modal" id="AddPharmacyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form method="post" action="" name="AddPharmacyForm" id="AddPharmacyForm" class="needs-validation"
                  novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div class="modal-header">
                    <h5 class="modal-title">Add Pharmacy Detail</h5>
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
                                    <input type="text" id="name" name="Pharmacy[FullName]" placeholder="Profile Name"
                                           class="form-control" data-validation-engine="validate[required]"/>
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
                                <label class="col-sm-12">City: <span class="text-danger">*</span></label>
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
                                    <input type="date" id="deployment_date" name="Pharmacy[DeploymentDate]"
                                           placeholder="Deployment Date" data-validation-engine="validate[required]"
                                           class="form-control"/>
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
                                <label class="col-sm-12">Address</label>
                                <div class="col-sm-12">
                                    <input type="text" id="address" name="Pharmacy[Address]" placeholder="Address"
                                           class="form-control" data-validation-engine="validate[required]"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Package <span style="color: crimson;">*</span></label>
                            <select name="Package"
                                    id="Package" class="form-control">
                                <option value="">Select Package</option>
                                <?php
                                foreach ($AllPackages as $AP) {
                                    $String = $AP['Code'] . ' - ' . $AP['Name'];
                                    echo '<option value="' . $AP['UID'] . '">' . $String . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div id="OrgPriceDiv" class="col-md-4 mb-3">
                            <label for="validationCustom01">Original Price <span
                                        style="color: crimson;">*</span></label>
                            <input oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="Enter Package Price" type="text" class="form-control"
                                   id="OriginalPrice"
                                   name="OriginalPrice">
                        </div>
                        <div id="DiscountDiv" class="col-md-4 mb-3">
                            <label for="validationCustom01">Discount (%) <span
                                        style="color: crimson;">*</span></label>
                            <input value="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="Enter Package Discount" type="text" class="form-control"
                                   id="Discount"
                                   name="Discount">
                        </div>
                        <div id="PriceDiv" class="col-md-4 mb-3">
                            <label for="validationCustom01">Price After Discount <span
                                        style="color: crimson;">*</span></label>
                            <input readonly oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                   placeholder="Price After Discount" type="text" class="form-control" id="Price"
                                   name="Price">
                        </div>
                        <div class="mt-3 col-md-12" id="ajaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="AddPharmacyFormFunction()">Add Profile
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
        handlePackageChange();
    });

    function handlePackageChange() {

        const $packageSelect = $(`#Package`);
        const $originalPrice = $(`#OriginalPrice`);
        const $discount = $(`#Discount`);
        const $price = $(`#Price`);

        $(`#OrgPriceDiv, #DiscountDiv, #PriceDiv`).hide();

        $packageSelect.change(function () {
            if ($(this).val() !== "") {

                $(`#OrgPriceDiv, #DiscountDiv, #PriceDiv`).show();

                const PackageID = $(this).val();
                const PackageRecord = AjaxResponse("support-ticket/get-record-items", "id=" + PackageID);
                if (PackageRecord.record != '') {
                    $(`input#OriginalPrice`).val(PackageRecord.record.OriginalPrice);
                    $(`input#Discount`).val(PackageRecord.record.Discount);
                    $(`input#Price`).val(PackageRecord.record.Price);
                }

            } else {
                // Hide and reset fields
                $(`#OrgPriceDiv, #DiscountDiv, #PriceDiv`).hide();
                $originalPrice.val("");
                $discount.val("0");
                $price.val("");
            }
        });

        $originalPrice.add($discount).on('input', function () {
            const originalPrice = parseFloat($originalPrice.val()) || 0;
            const discount = parseFloat($discount.val()) || 0;
            const discountedPrice = originalPrice - (originalPrice * (discount / 100));
            $price.val(Math.round(discountedPrice));
        });
    }


    function AddPharmacyFormFunction() {

        const FullName = $("form#AddPharmacyForm input#name").val();
        const ContactNo = $("form#AddPharmacyForm input#contact_no").val();
        const City = $("form#AddPharmacyForm select#city").val();
        const SaleAgent = $("form#AddPharmacyForm input#sale_agent").val();
        const DeploymentDate = $("form#AddPharmacyForm input#deployment_date").val();
        const MacAddress = $("form#AddPharmacyForm input#mac_address").val();
        const Package = $("form#AddPharmacyForm select#Package").val();
        const OriginalPrice = $("form#AddPharmacyForm input#OriginalPrice").val();
        const Discount = $("form#AddPharmacyForm input#Discount").val();

        if (FullName == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Full Name Required </div>');
            return false;
        }
        if (ContactNo == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Contact No Required </div>');
            return false;
        }
        if (City == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> City Required </div>');
            return false;
        }
        if (SaleAgent == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Sale Agent Required </div>');
            return false;
        }
        if (DeploymentDate == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Deploymet Date Required </div>');
            return false;
        }
        if (MacAddress == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Mac Address Required </div>');
            return false;
        }

        if (Package == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Subscription Package Required </div>');
            return false;
        }

        if (OriginalPrice == '' || OriginalPrice <= 0) {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Package Price Required </div>');
            return false;
        }
        if (Discount == '' || Discount < 0) {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Invalid discount </div>');
            return false;
        }

        const formdata = $("form#AddPharmacyForm").serialize();
        const response = AjaxResponse("pharmacy/submit", formdata);
        if (response.status === 'success') {
            $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 2500);
        } else {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>