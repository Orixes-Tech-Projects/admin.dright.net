<?php

use App\Models\Crud;

$Crud = new Crud();
$AllPackages = $Crud->ListRecords('items');
?>
<div class="modal" id="UpdateInvoiceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 60%;">
        <div class="modal-content">
            <form method="post" action="" name="UpdateInvoiceForm" id="UpdateInvoiceForm" class="needs-validation"
                  novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div style="border-bottom: 1px solid #dee2e6;" class="modal-header">
                    <h5 class="modal-title">Update Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div id="ProductTypeDiv" class="col-md-12 mb-3">
                            <label for="validationCustom02">Product Type</label>
                            <select onchange="UpdateHandleProductTypeChange('#UpdateInvoiceModal');"
                                    class="form-control" id="ProductType"
                                    name="ProductType">
                                <option value="">Select Product Type</option>
                                <option value="builder">ClinTa Web Builder</option>
                                <option value="extended">ClinTa Extended</option>
                            </select>
                        </div>
                        <div id="BuilderProductsDiv" class="col-md-6 mb-3">
                            <label for="validationCustom02">Builder Products</label>
                            <select onchange="LoadUpdateProductDetailsDropDown();" class="form-control"
                                    id="BuilderProducts"
                                    name="BuilderProducts">
                                <option value="">Select Product</option>
                                <option value="doctors">Doctors Website</option>
                                <option value="hospitals">Hospitals Website</option>
                            </select>
                        </div>
                        <div id="ExtendedProductsDiv" class="col-md-6 mb-3">
                            <label for="validationCustom02">Extended Products</label>
                            <select onchange="LoadUpdateProductDetailsDropDown();" class="form-control"
                                    id="ExtendedProducts"
                                    name="ExtendedProducts">
                                <option value="">Select Product</option>
                                <option value="hospitals">ClinTa Hospitals</option>
                                <option value="pharmacy">ClinTa Pharmacy</option>
                            </select>
                        </div>
                        <div id="ProductDetailsContainer" class="col-md-12"></div>
                        <div class="col-md-12 form-group mb-3">
                            <label>Package</label>
                            <select name="Package" id="Package" class="form-control">
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
                        <div class="col-md-12" id="UpdateInvoiceAjaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="UpdateInvoiceFormSubmit();">Save changes
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script>

    $(document).ready(function () {
        $("#UpdateInvoiceModal select#Package").select2({
            dropdownParent: $("#UpdateInvoiceModal")
        });
    });

    function LoadUpdateInvoiceModal(UID) {

        handlePackageChange('UpdateInvoiceModal', 'UpdateInvoiceForm');
        $('#UpdateInvoiceModal').modal('show');
        var response = AjaxResponse("invoice/get_record_invoice", "id=" + UID);
        if (response.status === 'success') {
            const record = response.record;

            $("#UpdateInvoiceForm")[0].reset();
            $("#UpdateInvoiceModal #UID").val(record.UID);
            $("#UpdateInvoiceModal #Package").val(record.PackageUID).trigger('change');
            $("#UpdateInvoiceModal #ProductType").val(record.ProductType).trigger('change');
            setTimeout(() => {
                if (record.ProductType === 'builder') {
                    $("#UpdateInvoiceModal #BuilderProducts").val(record.Product).trigger('change');
                    $("#UpdateInvoiceModal #ExtendedProductsDiv").addClass('d-none');
                } else if (record.ProductType === 'extended') {
                    $("#UpdateInvoiceModal #ExtendedProducts").val(record.Product).trigger('change');
                    $("#UpdateInvoiceModal #BuilderProductsDiv").addClass('d-none');
                }

                $("#UpdateInvoiceModal #OriginalPrice").val(record.OriginalPrice);
                $("#UpdateInvoiceModal #Discount").val(record.Discount);
                $("#UpdateInvoiceModal #Price").val(record.Price);

                if (record.Product) {
                    LoadUpdateProductDetailsDropDown(record.ProfileUID);
                }

            }, 500);

        }
    }

    function UpdateValidateInvoiceForm() {

        const form = document.getElementById('UpdateInvoiceForm');
        let isValid = true;

        $('.is-invalid').removeClass('is-invalid');
        $('.error-label').remove();

        const productType = $('#UpdateInvoiceModal #ProductType').val();
        if (!productType) {
            $('#UpdateInvoiceModal #ProductType').closest('.form-group, .mb-3').append(
                '<label class="error-label text-danger" style="display: block; margin-top: 2px; margin-bottom: 0px !important; font-size: 12px;">Please select a product type</label>'
            );
            isValid = false;
        }

        if (productType === 'builder') {
            const builderProduct = $('#UpdateInvoiceModal #BuilderProducts').val();
            if (!builderProduct) {
                $('#UpdateInvoiceModal #BuilderProducts').closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 2px; margin-bottom: 0px !important; font-size: 12px;">Please select a builder product</label>'
                );
                isValid = false;
            }
        } else if (productType === 'extended') {
            const extendedProduct = $('#UpdateInvoiceModal #ExtendedProducts').val();
            if (!extendedProduct) {
                $('#UpdateInvoiceModal #ExtendedProducts').closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 2px; margin-bottom: 0px !important; font-size: 12px;">Please select an extended product</label>'
                );
                isValid = false;
            }
        }

        const package = $('#UpdateInvoiceModal #Package').val();
        if (!package) {
            $('#UpdateInvoiceModal select#Package').closest('.form-group, .mb-3').append(
                '<label class="error-label text-danger" style="display: block; margin-top: 10px; margin-bottom: 0px !important; font-size: 12px;">Please select a package</label>'
            );
            isValid = false;
        }

        if (package != '') {
            const OriginalPrice = $('#UpdateInvoiceModal #OriginalPrice').val();
            if (!OriginalPrice || OriginalPrice <=0 ) {
                $('#UpdateInvoiceModal #OriginalPrice').closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 10px; margin-bottom: 0px !important; font-size: 12px;">Set package price</label>'
                );
                isValid = false;
            }
            const Discount = $('#UpdateInvoiceModal #Discount').val();
            if (!Discount) {
                $('#UpdateInvoiceModal #Discount').closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 10px; margin-bottom: 0px !important; font-size: 12px;">Set package discount</label>'
                );
                isValid = false;
            }
        }

        const profileSelect = $('#UpdateInvoiceModal #Profile');
        if (profileSelect.length && profileSelect.is(':visible')) {
            const profile = profileSelect.val();
            if (!profile) {
                profileSelect.addClass('is-invalid');
                profileSelect.closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 10px;margin-bottom: 0px !important; font-size: 12px;">Please select a profile</label>'
                );
                isValid = false;
            }
        }

        return isValid;
    }

    function UpdateInvoiceFormSubmit() {

        if (!UpdateValidateInvoiceForm()) {
            return false;
        }

        const submitBtn = $('button[onclick="UpdateInvoiceFormSubmit()"]');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        var formdata = $("form#UpdateInvoiceForm").serialize();
        var response = AjaxResponse("invoice/invoice_detail_form_submit", formdata);
        if (response.status === 'success') {
            $("#UpdateInvoiceAjaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 1500);
        } else {
            $("#UpdateInvoiceAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
            submitBtn.prop('disabled', false).html('Save changes');
        }
    }
</script>
<script>

    function UpdateHandleProductTypeChange(modalId) {

        const form = document.querySelector(`${modalId} form`);
        const productType = form.querySelector('#ProductType').value;
        const builderDiv = form.querySelector('#BuilderProductsDiv');
        const extendedDiv = form.querySelector('#ExtendedProductsDiv');
        const productTypeDiv = form.querySelector('#ProductTypeDiv');

        document.querySelectorAll('#UpdateInvoiceModal form#UpdateInvoiceForm #Product').forEach(select => {
            select.value = '';
        });

        if (productType === 'builder') {

            builderDiv.classList.remove('d-none');
            extendedDiv.classList.add('d-none');

            productTypeDiv.classList.remove('col-md-12');
            productTypeDiv.classList.add('col-md-6');

        } else if (productType === 'extended') {

            extendedDiv.classList.remove('d-none');
            builderDiv.classList.add('d-none');

            productTypeDiv.classList.remove('col-md-12');
            productTypeDiv.classList.add('col-md-6');
        } else {

            builderDiv.classList.add('d-none');
            extendedDiv.classList.add('d-none');

            productTypeDiv.classList.remove('col-md-6');
            productTypeDiv.classList.add('col-md-12');
        }
    }

    document.querySelector('#UpdateInvoiceModal #ProductType').addEventListener('change', () => {
        UpdateHandleProductTypeChange('#UpdateInvoiceModal');
    });

    function LoadUpdateProductDetailsDropDown(ID = 0) {
        let Product = '';
        const container = $("#UpdateInvoiceModal form#UpdateInvoiceForm div#ProductDetailsContainer");
        const modal = $("#UpdateInvoiceModal");

        container.empty().removeClass('mb-3');
        const ProductType = $("#UpdateInvoiceModal form#UpdateInvoiceForm select#ProductType").val();

        if (ProductType === 'builder') {
            Product = $("#UpdateInvoiceModal form#UpdateInvoiceForm select#BuilderProducts").val();
        } else if (ProductType === 'extended') {
            Product = $("#UpdateInvoiceModal form#UpdateInvoiceForm select#ExtendedProducts").val();
        }

        if (ProductType && Product) {
            container.html('<div style="background: #ffb6c169;padding: 5px;color: crimson;" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading Profiles...</div>');

            const response = AjaxResponse('invoice/load_invoice_product_customer_view', {
                ProductType: ProductType,
                Product: Product
            });

            if (response.status === 'success') {
                container.addClass('mb-3').html(response.page);

                const initSelect2AndSetValue = function (attempt = 0) {
                    const select = container.find('select#Profile');

                    // Stop trying after 10 attempts (~1 second total)
                    if (attempt > 10) {
                        console.warn('Profile select not found after 10 attempts');
                        return;
                    }

                    if (select.length) {
                        // Initialize Select2 if needed
                        if (select.hasClass('select2-hidden-accessible')) {
                            select.select2('destroy');
                        }
                        select.select2({
                            dropdownParent: modal,
                            width: '100%'
                        });

                        // Set value if ID provided
                        if (ID > 0) {
                            if (select.find('option[value="' + ID + '"]').length) {
                                select.val(ID).trigger('change');
                            } else {
                                console.warn('Profile ID not found in options');
                            }
                        }
                    } else {
                        // Try again on next frame
                        requestAnimationFrame(() => initSelect2AndSetValue(attempt + 1));
                    }
                };

                initSelect2AndSetValue();
            }
        }
    }
</script>