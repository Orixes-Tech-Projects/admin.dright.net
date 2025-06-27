<?php

use App\Models\Crud;

$Crud = new Crud();
$AllPackages = $Crud->ListRecords('items');
?>
<div class="modal" id="AddInvoiceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 60%;">
        <div class="modal-content">
            <form method="post" action="" name="AddInvoiceForm" id="AddInvoiceForm" class="needs-validation"
                  novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div style="border-bottom: 1px solid #dee2e6;" class="modal-header">
                    <h5 class="modal-title">Create Invoice</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div id="ProductTypeDiv" class="col-md-12 mb-3">
                            <label for="validationCustom02">Product Type</label>
                            <select onchange="handleProductTypeChange();" class="form-control" id="ProductType"
                                    name="ProductType">
                                <option value="">Select Product Type</option>
                                <option value="builder">ClinTa Web Builder</option>
                                <option value="extended">ClinTa Extended</option>
                            </select>
                        </div>
                        <div id="BuilderProductsDiv" class="col-md-6 mb-3">
                            <label for="validationCustom02">Builder Products</label>
                            <select onchange="LoadProductDetailsDropDown();" class="form-control" id="BuilderProducts"
                                    name="BuilderProducts">
                                <option value="">Select Product</option>
                                <option value="doctors">Doctors Website</option>
                                <option value="hospitals">Hospitals Website</option>
                            </select>
                        </div>
                        <div id="ExtendedProductsDiv" class="col-md-6 mb-3">
                            <label for="validationCustom02">Extended Products</label>
                            <select onchange="LoadProductDetailsDropDown();" class="form-control" id="ExtendedProducts"
                                    name="ExtendedProducts">
                                <option value="">Select Product</option>
                                <option value="hospitals">ClinTa Hospitals</option>
                                <option value="pharmacy">ClinTa Pharmacy</option>
                            </select>
                        </div>
                        <div id="ProductDetailsContainer" class="col-md-12"></div>
                        <div class="col-md-12 form-group">
                            <label>Package</label>
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
                        <div class="col-md-12" id="AddInvoiceAjaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="AddInvoiceFormSubmit();">Save changes
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
        $("#AddInvoiceModal select#Package").select2({
            dropdownParent: $("#AddInvoiceModal")
        });
    });

    function LoadAddInvoiceModal() {

        handlePackageChange('AddInvoiceModal', 'AddInvoiceForm');
        $('#AddInvoiceModal').modal('show');
    }

    function validateInvoiceForm() {

        const form = document.getElementById('AddInvoiceForm');
        let isValid = true;

        $('.is-invalid').removeClass('is-invalid');
        $('.error-label').remove();

        const productType = $('#AddInvoiceModal #ProductType').val();
        if (!productType) {
            $('#AddInvoiceModal #ProductType').closest('.form-group, .mb-3').append(
                '<label class="error-label text-danger" style="display: block; margin-top: 2px; margin-bottom: 0px !important; font-size: 12px;">Please select a product type</label>'
            );
            isValid = false;
        }

        if (productType === 'builder') {
            const builderProduct = $('#AddInvoiceModal #BuilderProducts').val();
            if (!builderProduct) {
                $('#AddInvoiceModal #BuilderProducts').closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 2px; margin-bottom: 0px !important; font-size: 12px;">Please select a builder product</label>'
                );
                isValid = false;
            }
        } else if (productType === 'extended') {
            const extendedProduct = $('#AddInvoiceModal #ExtendedProducts').val();
            if (!extendedProduct) {
                $('#AddInvoiceModal #ExtendedProducts').closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 2px; margin-bottom: 0px !important; font-size: 12px;">Please select an extended product</label>'
                );
                isValid = false;
            }
        }

        const package = $('#AddInvoiceModal #Package').val();
        if (!package) {
            $('#AddInvoiceModal select#Package').closest('.form-group, .mb-3').append(
                '<label class="error-label text-danger" style="display: block; margin-top: 10px; margin-bottom: 0px !important; font-size: 12px;">Please select a package</label>'
            );
            isValid = false;
        }

        if (package != '') {
            const OriginalPrice = $('#AddInvoiceModal #OriginalPrice').val();
            if (!OriginalPrice || OriginalPrice <=0 ) {
                $('#AddInvoiceModal #OriginalPrice').closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 10px; margin-bottom: 0px !important; font-size: 12px;">Set package price</label>'
                );
                isValid = false;
            }
            const Discount = $('#AddInvoiceModal #Discount').val();
            if (!Discount) {
                $('#AddInvoiceModal #Discount').closest('.form-group, .mb-3').append(
                    '<label class="error-label text-danger" style="display: block; margin-top: 10px; margin-bottom: 0px !important; font-size: 12px;">Set package discount</label>'
                );
                isValid = false;
            }
        }

        const profileSelect = $('#AddInvoiceModal #Profile');
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

    function AddInvoiceFormSubmit() {

        if (!validateInvoiceForm()) {
            return false;
        }

        const submitBtn = $('button[onclick="AddInvoiceFormSubmit()"]');
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        var formdata = $("form#AddInvoiceForm").serialize();
        var response = AjaxResponse("invoice/invoice_detail_form_submit", formdata);
        if (response.status === 'success') {
            $("#AddInvoiceAjaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 1500);
        } else {
            $("#AddInvoiceAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
            submitBtn.prop('disabled', false).html('Save changes');
        }
    }
</script>
<script>

    function handleProductTypeChange() {
        const productType = document.getElementById('ProductType').value;
        const builderDiv = document.getElementById('BuilderProductsDiv');
        const extendedDiv = document.getElementById('ExtendedProductsDiv');
        const productTypeDiv = document.getElementById('ProductTypeDiv');

        document.querySelectorAll('#AddInvoiceModal form#AddInvoiceForm #Product').forEach(select => {
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

    document.addEventListener('DOMContentLoaded', function () {

        const productTypeSelect = document.getElementById('ProductType');
        if (productTypeSelect) {

            productTypeSelect.addEventListener('change', handleProductTypeChange);
            handleProductTypeChange();
        }
    });

    function LoadProductDetailsDropDown() {
        let Product = '';
        const container = $("#AddInvoiceModal form#AddInvoiceForm div#ProductDetailsContainer");
        const modal = $("#AddInvoiceModal");

        container.empty();
        container.removeClass('mb-3');
        const ProductType = $("#AddInvoiceModal form#AddInvoiceForm select#ProductType").val();

        if (ProductType === 'builder') {
            Product = $("#AddInvoiceModal form#AddInvoiceForm select#BuilderProducts").val();
        } else if (ProductType === 'extended') {
            Product = $("#AddInvoiceModal form#AddInvoiceForm select#ExtendedProducts").val();
        }

        if (ProductType && Product) {
            container.html('<div style="background: #ffb6c169;padding: 5px;color: crimson;" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading Profiles...</div>');

            const response = AjaxResponse('invoice/load_invoice_product_customer_view', {
                ProductType: ProductType,
                Product: Product
            });

            if (response.status === 'success') {
                container.addClass('mb-3');
                container.html(response.page);

                const initSelect2 = function () {
                    const select = container.find('select#Profile');
                    if (select.length) {
                        if (select.hasClass('select2-hidden-accessible')) {
                            select.select2('destroy');
                        }
                        select.select2({
                            dropdownParent: modal,
                            width: '100%'
                        });
                    } else {
                        requestAnimationFrame(initSelect2);
                    }
                };
                initSelect2();
            }
        }
    }
</script>