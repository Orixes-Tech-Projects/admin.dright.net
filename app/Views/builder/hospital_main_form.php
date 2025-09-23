<style>
    /* Progress Modal (Overlay) */
    .progress-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(3px);
    }

    /* Progress Content Box */
    .progress-content {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        text-align: center;
        max-width: 400px;
        width: 90%;
    }

    /* Circular Spinner */
    .progress-circular {
        width: 50px;
        height: 50px;
        animation: rotate 2s linear infinite;
    }

    .progress-path {
        stroke: #4CAF50;
        stroke-dasharray: 150, 200;
        stroke-dashoffset: 0;
        animation: dash 1.5s ease-in-out infinite;
    }

    /* Progress Bar */
    .progress-bar {
        height: 8px;
        background: #f0f0f0;
        border-radius: 10px;
        margin: 15px 0;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50, #8BC34A);
        width: 0%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    /* Animations */
    @keyframes rotate {
        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes dash {
        0% {
            stroke-dasharray: 1, 200;
            stroke-dashoffset: 0;
        }
        50% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -35;
        }
        100% {
            stroke-dasharray: 89, 200;
            stroke-dashoffset: -124;
        }
    }

    /* Text Styling */
    .progress-info h5 {
        color: #333;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .progress-info p {
        color: #777;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .progress-percentage {
        font-size: 14px;
        color: #4CAF50;
        font-weight: 600;
    }
</style>
<?php

use App\Models\BuilderModel;
use App\Models\Crud;

$Crud = new Crud();
$BuilderModel = new BuilderModel();

$short_desc = $clinta_extended_profiles = $healthcarestatus = $theme = $patient_portal = '';
if ($page == 'add-hospital') {
    $AllPackages = $Crud->ListRecords('items');
} else {
    $short_desc = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'short_description');
    $clinta_extended_profiles = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'clinta_extended_profiles');
    $healthcarestatus = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'healthcare_status');
    $theme = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'theme');
    $patient_portal = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'patient_portal');

    $PrescriptionSegment = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'prescription_module');
    $PrescriptionPricingType = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'prescription_pricing_type');
    $PrescriptionPrice = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'prescription_price');

    $OPDInvoicing = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'opd_invoicing');
    $OPDPricingType = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'opd_pricing_type');
    $OPDInvoicingPrice = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'opd_invoice_price');
}
?>
<div class="card">
    <div class="card-body">
        <h6 style="margin-bottom: 1rem !important;"
            class="card-title"><?= ((isset($PAGE['UID'])) ? 'Update' : 'Add New') ?> Hospital</h6>
        <hr style="margin-bottom: 1rem !important; margin-top: 0rem !important;">
        <form method="post" action="" name="AddHospitalForm" id="AddHospitalForm" class="needs-validation" novalidate=""
              enctype="multipart/form-data">
            <input type="hidden" name="UID" id="UID" value="<?= ((isset($PAGE['UID'])) ? $PAGE['UID'] : '0') ?>">
            <div class="form-row">
                <div class="col-md-3 mb-3">
                    <label for="validationCustom04">FullName</label>
                    <input type="text" class="form-control" name="name" id="name"
                           placeholder="Full Name" value="<?= ((isset($PAGE['Name'])) ? $PAGE['Name'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom04">Email</label>
                    <input type="text" class="form-control" name="email" id="email"
                           placeholder="Email" value="<?= ((isset($PAGE['Email'])) ? $PAGE['Email'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom04">Password</label>
                    <input type="text" class="form-control" name="password" id="password"
                           placeholder="Password" value="<?= ((isset($PAGE['Password'])) ? $PAGE['Password'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom04">ContactNo</label>
                    <input type="text" class="form-control" name="ContactNo" id="ContactNo"
                           placeholder="Contact No"
                           value="<?= ((isset($PAGE['ContactNo'])) ? $PAGE['ContactNo'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-4">City:</label>
                        <div class="col-sm-12">
                            <select id="city" name="city" class="form-control"
                                    data-validation-engine="validate[required]">
                                <option value="">Please Select</option>
                                <?php foreach ($Cities as $record) { ?>
                                    <option value="<?= $record['UID'] ?>" <?= (isset($PAGE['City']) && $PAGE['City'] == $record['UID']) ? 'selected' : '' ?>
                                    ><?= ucwords($record['FullName']); ?></option>
                                <?php } ?>                                </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Sub Domain</label>
                        <div class="col-sm-12">
                            <input type="text" id="sub_domain" name="sub_domain" placeholder="Sub Domain"
                                   value="<?= ((isset($PAGE['SubDomain'])) ? $PAGE['SubDomain'] : '') ?>"
                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-<?=(($page != 'add-hospital')? '4' : '6')?> mb-3">
                    <label for="validationCustom05">Profile</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="profile" name="profile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <?php if ($page != 'add-hospital') { ?>
                    <div class="col-md-2">
                        <img src="<?= load_image('pgsql|profiles|' . $PAGE['UID']) ?>" height="70">
                    </div>
                <?php } ?>

                <div class="col-md-12">
                    <div class="form-group row">
                        <label class="col-sm-12">Short Description</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="short_description" id="short_description"
                                      rows="6"><?php if (is_array($short_desc) && !empty($short_desc)) { ?><?= isset($short_desc[0]['Value']) ? $short_desc[0]['Value'] : ''; ?><?php } ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <h4>Prescription Segment</h4>
                    <hr>
                </div>
                <div class="col-md-12" id="prescription-status-col">
                    <label>Status <small class="text-danger">*</small></label>
                    <select name="prescription_module" id="prescription_module" class="form-control">
                        <option <?= ((isset($PrescriptionSegment[0]['Description']) && $PrescriptionSegment[0]['Description'] == 0) ? 'selected' : '') ?>
                                value="0">De Activate
                        </option>
                        <option <?= ((isset($PrescriptionSegment[0]['Description']) && $PrescriptionSegment[0]['Description'] == 1) ? 'selected' : '') ?>
                                value="1">Activate
                        </option>
                    </select>
                </div>
                <div class="col-md-6 d-none" id="prescription-pricing-type-col">
                    <label>Pricing Type <small class="text-danger">*</small></label>
                    <select name="prescription_pricing_type" id="prescription_pricing_type" class="form-control">
                        <option value="">Select Option</option>
                        <option <?= ((isset($PrescriptionPricingType[0]['Description']) && $PrescriptionPricingType[0]['Description'] == 'with-subscription') ? 'selected' : '') ?>
                                value="with-subscription">With Subscription
                        </option>
                        <option <?= ((isset($PrescriptionPricingType[0]['Description']) && $PrescriptionPricingType[0]['Description'] == 'per-prescription') ? 'selected' : '') ?>
                                value="per-prescription">Per Prescription
                        </option>
                    </select>
                </div>
                <div class="col-md-4 d-none" id="prescription-price-col">
                    <label>Per Prescription Price <small class="text-danger">*</small></label>
                    <input value="<?= ((isset($PrescriptionPrice[0]['Description']) && $PrescriptionPrice[0]['Description'] != '') ? $PrescriptionPrice[0]['Description'] : 0) ?>"
                           type="text" name="prescription_price" id="prescription_price" class="form-control"
                           oninput="
                             let v = this.value;
                             v = v.replace(/[^0-9.]/g, '');
                             if (v.startsWith('.')) v = '0' + v;
                             v = v.replace(/(\..*?)\..*/g, '$1');
                             v = v.replace(/^0+(?!\.)/, '');
                             this.value = v;
                           ">
                </div>
            </div>
            <div class="row mt-4 mb-4">
                <div class="col-md-12">
                    <h4>OPD Invoicing Segment </h4>
                    <hr>
                </div>
                <div class="col-md-12" id="opd-status-col">
                    <label>Status <small class="text-danger">*</small></label>
                    <select name="opd_invoicing" id="opd_invoicing" class="form-control">
                        <option <?= ((isset($OPDInvoicing[0]['Description']) && $OPDInvoicing[0]['Description'] == 0) ? 'selected' : '') ?>
                                value="0">De Activate
                        </option>
                        <option <?= ((isset($OPDInvoicing[0]['Description']) && $OPDInvoicing[0]['Description'] == 1) ? 'selected' : '') ?>
                                value="1">Activate
                        </option>
                    </select>
                </div>
                <div class="col-md-6 d-none" id="opd-pricing-type-col">
                    <label>Pricing Type <small class="text-danger">*</small></label>
                    <select name="opd_pricing_type" id="opd_pricing_type" class="form-control">
                        <option value="">Select Option</option>
                        <option <?= ((isset($OPDPricingType[0]['Description']) && $OPDPricingType[0]['Description'] == 'with-subscription') ? 'selected' : '') ?>
                                value="with-subscription">With Subscription
                        </option>
                        <option <?= ((isset($OPDPricingType[0]['Description']) && $OPDPricingType[0]['Description'] == 'per-invoice') ? 'selected' : '') ?>
                                value="per-invoice">Per Invoice
                        </option>
                    </select>
                </div>
                <div class="col-md-4 d-none" id="opd-price-col">
                    <label>Per Invoice Price <small class="text-danger">*</small></label>
                    <input type="text" name="opd_invoice_price" id="opd_invoice_price" class="form-control"
                           value="<?= ((isset($OPDInvoicingPrice[0]['Description']) && $OPDInvoicingPrice[0]['Description'] != '') ? $OPDInvoicingPrice[0]['Description'] : 0) ?>"
                           oninput="
                             let v = this.value;
                             v = v.replace(/[^0-9.]/g, '');
                             if (v.startsWith('.')) v = '0' + v;
                             v = v.replace(/(\..*?)\..*/g, '$1');
                             v = v.replace(/^0+(?!\.)/, '');
                             this.value = v;
                           ">
                </div>
            </div>

            <?php
            if ($page == 'add-hospital') { ?>
                <div class="form-row mt-4">
                    <div class="col-md-12">
                        <h4>Subscription Details</h4>
                        <hr>
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
                        <label for="validationCustom01">Original Price <span style="color: crimson;">*</span></label>
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
                </div>
            <?php } ?>
            <div class="form-row">
                <div class="col-md-12">
                    <h3>ClinTa Extended</h3>
                    <hr style="margin-bottom: 1rem !important; margin-top: 0rem !important;">
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-4">Profiles:</label>
                        <div class="col-sm-12">
                            <select id="clinta_extended_profiles" name="clinta_extended_profiles" class="form-control"
                                    data-validation-engine="validate[required]">
                                <option value="">Please Select</option>
                                <!--                                --><?php
                                //                                foreach( $extended_profiles as $PF ){
                                //                                    echo'<option value="'.$PF['UID'].'" '. if (is_array($healthcarestatus) && !empty($healthcarestatus)) {
                                //                                    ( ( isset( $clinta_extended_profiles ) && $clinta_extended_profiles[0]['Value'] == $PF['UID'] )? 'selected' : ''  )     } .' >'.$PF['FullName'].'</option>';
                                //                                }?>
                                <?php
                                foreach ($extended_profiles as $PF) {
                                    $selected = (is_array($clinta_extended_profiles) && !empty($clinta_extended_profiles) && isset($clinta_extended_profiles[0]['Value']) && $clinta_extended_profiles[0]['Value'] == $PF['UID']) ? 'selected' : '';
                                    echo '<option value="' . $PF['UID'] . '" ' . $selected . '>' . $PF['FullName'] . '</option>';
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Add-ons</label>
                        <div class="col-sm-12">
                            <select name="healthcare_status" id="healthcare_status" class="form-control">
                                <option value=""

                                    <?php if (is_array($healthcarestatus) && !empty($healthcarestatus)) { ?>
                                        <?= (isset($healthcarestatus[0]['Value']) && $healthcarestatus[0]['Value'] == '') ? 'selected' : ''; ?>
                                    <?php } ?>

                                >Please Select
                                </option>
                                <option value="1"
                                    <?php if (is_array($healthcarestatus) && !empty($healthcarestatus)) { ?>
                                        <?= (isset($healthcarestatus[0]['Value']) && $healthcarestatus[0]['Value'] == '1') ? 'selected' : ''; ?>
                                    <?php } ?>>Show
                                </option>
                                <option value="0"
                                    <?php if (is_array($healthcarestatus) && !empty($healthcarestatus)) { ?>
                                        <?= (isset($healthcarestatus[0]['Value']) && $healthcarestatus[0]['Value'] == '0') ? 'selected' : ''; ?>
                                    <?php } ?>>Hide
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Theme Setting</label>
                        <div class="col-sm-12">
                            <select name="theme" id="theme" class="form-control">
                                <option value="0" <?= (is_array($theme) && !empty($theme) && isset($theme[0]['Description']) && $theme[0]['Description'] == '0') ? 'selected' : '' ?>>
                                    Please Select
                                </option>
                                <option value="basic" <?= (is_array($theme) && !empty($theme) && isset($theme[0]['Description']) && $theme[0]['Description'] == 'basic') ? 'selected' : '' ?>>
                                    Basic (Free)
                                </option>
                                <option value="mist" <?= (is_array($theme) && !empty($theme) && isset($theme[0]['Description']) && $theme[0]['Description'] == 'mist') ? 'selected' : '' ?>>
                                    Premium (Paid)
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Patient Portal</label>
                        <div class="col-sm-12">
                            <select name="patient_portal" id="patient_portal" class="form-control">
                                <option value=""<?= (is_array($patient_portal) && !empty($patient_portal) && isset($patient_portal[0]['Value']) && $patient_portal[0]['Value'] == '') ? 'selected' : '' ?>>
                                    Please Select
                                </option>
                                <option value="1"<?= (is_array($patient_portal) && !empty($patient_portal) && isset($patient_portal[0]['Value']) && $patient_portal[0]['Value'] == '1') ? 'selected' : '' ?>>
                                    Yes
                                </option>
                                <option value="0"<?= (is_array($patient_portal) && !empty($patient_portal) && isset($patient_portal[0]['Value']) && $patient_portal[0]['Value'] == '0') ? 'selected' : '' ?>>
                                    No
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="progress-modal col-md-12" style="display: none;">
                    <div class="progress-content">
                        <div class="progress-loader">
                            <svg class="progress-circular" viewBox="25 25 50 50">
                                <circle class="progress-path" cx="50" cy="50" r="20" fill="none" stroke-width="4"
                                        stroke-miterlimit="10"/>
                            </svg>
                        </div>
                        <div class="progress-info">
                            <h5>
                                <i class="feather feather-users mr-2"></i> <?= (($page == 'add-hospital') ? 'Creating' : 'Updating') ?>
                                Your Digital Hospital Profile</h5>
                            <p>Please wait while we save your details...</p>
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                            <span class="progress-percentage">0%</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-4" id="ajaxResponse"></div>
                <div class="col-md-12">
                    <button style="float: right; border-radius: 5px !important;" class="btn btn-primary btn-sm"
                            type="button"
                            onclick="AddHospitalFormFunction()"><?= ((isset($PAGE['UID']) && $PAGE['UID'] != '' && $PAGE['UID'] > 0) ? 'Update' : 'Add') ?>
                        Hospital
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    $(document).ready(function () {
        var currentPage = '<?=$page?>';
        if (currentPage === 'add-hospital') {
            handlePackageChange();
        }
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

    function AddHospitalFormFunction() {

        $('.progress-modal').css('display', 'flex').hide().fadeIn(50);

        let progress = 20;
        $('.progress-fill').css('width', '0%');
        $('.progress-percentage').text('0%');

        const progressInterval = setInterval(() => {
            progress += 1;
            $('.progress-fill').css('width', `${progress}%`);
            $('.progress-percentage').text(`${progress}%`);

            if (progress >= 90) {
                clearInterval(progressInterval);
            }
        }, 50);


        setTimeout(() => {

            const CurrentPage = "<?=$page?>";
            const Host = "<?=$_SERVER['HTTP_HOST']?>";

            const FullName = $("form#AddHospitalForm input#name").val();
            const Email = $("form#AddHospitalForm input#email").val();
            const Password = $("form#AddHospitalForm input#password").val();
            const ContactNo = $("form#AddHospitalForm input#ContactNo").val();
            const City = $("form#AddHospitalForm select#city").val();
            const SubDomain = $("form#AddHospitalForm input#sub_domain").val();

            const PrescriptionSegmentStatus = $("form#AddHospitalForm select#prescription_module").val();
            const OPDInvoicingSegmentStatus = $("form#AddHospitalForm select#opd_invoicing").val();

            if (FullName == '') {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Full Name Required </div>');
                return false;
            }
            if (Email == '') {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Email Required </div>');
                return false;
            }
            if (Password == '') {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Password Required </div>');
                return false;
            }
            if (ContactNo == '') {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Contact No Required </div>');
                return false;
            }
            if (City == '') {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> City Required </div>');
                return false;
            }
            if (SubDomain == '' || !isValidUrl(SubDomain)) {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Valid SubDomain Required </div>');
                return false;
            }

            if (PrescriptionSegmentStatus == 1) {
                const PrescriptionPricingType = $("form#AddHospitalForm select#prescription_pricing_type").val();
                const PrescriptionPrice = $("form#AddHospitalForm input#prescription_price").val().trim();

                if (PrescriptionPricingType == '') {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Prescription Pricing Type Required </div>');
                    return false;
                }

                if (PrescriptionPricingType == 'per-prescription' && PrescriptionPrice == '') {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Per Prescription Price Required </div>');
                    return false;
                }
            }

            if (OPDInvoicingSegmentStatus == 1) {
                const OpdInvoicingPricingType = $("form#AddHospitalForm select#opd_pricing_type").val();
                const OpdInvoicingPrice = $("form#AddHospitalForm input#opd_invoice_price").val().trim();

                if (OpdInvoicingPricingType == '') {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> OPD Invoicing Pricing Type Required </div>');
                    return false;
                }

                if (OpdInvoicingPricingType == 'per-invoice' && OpdInvoicingPrice == '') {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Per Invoice Price Required </div>');
                    return false;
                }
            }

            if (CurrentPage == 'add-hospital') {
                const Package = $("form#AddHospitalForm select#Package").val();
                const OriginalPrice = $("form#AddHospitalForm input#OriginalPrice").val();
                const Discount = $("form#AddHospitalForm input#Discount").val();
                if (Package == '') {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Subscription Package Required </div>');
                    return false;
                }

                if (OriginalPrice == '' || OriginalPrice <= 0) {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Package Price Required </div>');
                    return false;
                }

                if (Discount == '' || Discount < 0) {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Invalid discount </div>');
                    return false;
                }
            }

            const formdata = new window.FormData($("form#AddHospitalForm")[0]);
            const response = AjaxUploadResponse("builder/submit-hospital", formdata);
            if (response.status === 'success') {
                clearInterval(progressInterval);
                $('.progress-fill').css('width', '100%');
                $('.progress-percentage').text('100%');
                $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');

                /** Send Request TO CPanel For Creating SubDomains */
                if (CurrentPage == 'add-hospital' && response.subdomain && Host != 'localhost') {
                    if (response.subdomain.endsWith('.clinta.biz')) {
                        AjaxResponse('Builder/CreateSubdomainsWorker', 'subdomain=' + response.subdomain);
                    }
                }

                setTimeout(function () {
                    $('.progress-modal').hide();
                    location.href = "<?=$path?>builder/hospital";
                }, 1500);

            } else {
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
            }

        }, 50);
    }

    function isValidUrl(urlString, options = {}) {

        if (!/^https?:\/\//i.test(urlString)) {
            urlString = 'https://' + urlString;
        }

        try {
            const url = new URL(urlString);

            if (!/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/i.test(url.hostname)) {
                return false;
            }

            if (options.allowedDomains) {
                const allowed = options.allowedDomains.some(domain =>
                    url.hostname === domain ||
                    url.hostname.endsWith('.' + domain)
                );
                if (!allowed) return false;
            }

            return true;
        } catch (_) {
            return false;
        }
    }
</script>
<script>
    $(document).ready(function () {
        $('#prescription_module, #prescription_pricing_type').on('change', togglePrescriptionDetailsFields);
        $('#opd_invoicing, #opd_pricing_type').on('change', toggleOPDFields);
        togglePrescriptionDetailsFields();
        toggleOPDFields();
    });

    function togglePrescriptionDetailsFields() {
        const status = $('#prescription_module').val();
        const pricingType = $('#prescription_pricing_type').val();

        if (status === '1') {
            $('#prescription-pricing-type-col').removeClass('d-none');
            $('#prescription-status-col').removeClass('col-md-12').addClass('col-md-6');
            if (pricingType === 'per-prescription') {
                $('#prescription-price-col').removeClass('d-none');
                $('#prescription-status-col').removeClass('col-md-6').addClass('col-md-4');
                $('#prescription-pricing-type-col').removeClass('col-md-6').addClass('col-md-4');
            } else {
                $('#prescription-price-col').addClass('d-none');
                $('#prescription-status-col').removeClass('col-md-4').addClass('col-md-6');
                $('#prescription-pricing-type-col').removeClass('col-md-4').addClass('col-md-6');
            }

        } else {
            $('#prescription-pricing-type-col').addClass('d-none');
            $('#prescription-price-col').addClass('d-none');
            $('#prescription-status-col').removeClass('col-md-6 col-md-4').addClass('col-md-12');

            $("#prescription_pricing_type").val('');
            $("#prescription_price").val(0);
        }
    }

    function toggleOPDFields() {

        const status = $('#opd_invoicing').val();
        const pricingType = $('#opd_pricing_type').val();

        if (status === '1') {

            $('#opd-pricing-type-col').removeClass('d-none');
            $('#opd-status-col').removeClass('col-md-12 col-md-6 col-md-4').addClass('col-md-6');
            $('#opd-pricing-type-col').removeClass('col-md-4 col-md-12').addClass('col-md-6');

            if (pricingType === 'per-invoice') {
                $('#opd-price-col').removeClass('d-none');
                $('#opd-status-col').removeClass('col-md-6').addClass('col-md-4');
                $('#opd-pricing-type-col').removeClass('col-md-6').addClass('col-md-4');
                $('#opd-price-col').removeClass('col-md-6 col-md-12').addClass('col-md-4');
            } else {

                $('#opd-price-col').addClass('d-none');
                $('#opd-status-col').removeClass('col-md-4').addClass('col-md-6');
                $('#opd-pricing-type-col').removeClass('col-md-4').addClass('col-md-6');
            }
        } else {

            $('#opd-pricing-type-col').addClass('d-none');
            $('#opd-price-col').addClass('d-none');
            $('#opd-status-col').removeClass('col-md-6 col-md-4').addClass('col-md-12');

            $('#opd_pricing_type').val('');
            $('#opd_invoice_price').val(0);
        }
    }

</script>