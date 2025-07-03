<?php

use App\Models\Crud;

$Crud = new Crud();
if ($page == 'add-profile') {
    $AllPackages = $Crud->ListRecords('items');
}
?>
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
<div class="card">
    <div class="card-body">
        <h4 style="margin-bottom: 1rem;" class="card-title"><?= ((isset($PAGE['UID'])) ? 'Update' : 'Add New') ?>
            Profile</h4>
        <hr>
        <form method="post" name="ExtendedProfileForm" id="ExtendedProfileForm"
              enctype="multipart/form-data" onsubmit="ExtendedProfilesFormSubmit(); return false;">
            <input type="hidden" name="UID" id="UID" value="<?= ((isset($PAGE['UID'])) ? $PAGE['UID'] : '0') ?>">
            <div class="form-row">
                <div class="col-md-6 mb-3">
                    <label for="validationCustom04">FullName <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="Profile[FullName]" id="name"
                           placeholder="Full Name" value="<?= ((isset($PAGE['FullName'])) ? $PAGE['FullName'] : '') ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom04">Email <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="Profile[Email]" id="email"
                           placeholder="Email" value="<?= ((isset($PAGE['Email'])) ? $PAGE['Email'] : '') ?>"
                           required="">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom04">Contact No <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="Profile[ContactNo]" id="contact_no"
                           placeholder="Contact No"
                           value="<?= ((isset($PAGE['ContactNo'])) ? $PAGE['ContactNo'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-4">City<span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <select id="city" name="Profile[City]" class="form-control">
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
                        <label class="col-sm-12">DataBase Name <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <input <?= (($page == 'update-profile') ? 'readonly' : '') ?> type="text" id="database_name"
                                                                                          name="Profile[DatabaseName]"
                                                                                          placeholder="Database Name"
                                                                                          value="<?= ((isset($PAGE['DatabaseName'])) ? $PAGE['DatabaseName'] : '') ?>"
                                                                                          class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Sub Domain <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <input <?= (($page == 'update-profile') ? 'readonly' : '') ?> type="text" id="subdomain_url"
                                                                                          name="Profile[SubDomainUrl]"
                                                                                          placeholder="Sub Domain"
                                                                                          value="<?= ((isset($PAGE['SubDomainUrl'])) ? $PAGE['SubDomainUrl'] : '') ?>"
                                                                                          class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Pharma Name</label>
                        <div class="col-sm-12">
                            <input type="text" id="PharmaName" name="Profile[PharmaName]" placeholder="Pharma Name"
                                   value="<?= ((isset($PAGE['PharmaName'])) ? $PAGE['PharmaName'] : '') ?>"
                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Facebook Link</label>
                        <div class="col-sm-12">
                            <input type="text" id="FacebookUrl" name="Profile[FacebookUrl]" placeholder="Facebook Url"
                                   value="<?= ((isset($PAGE['FacebookUrl'])) ? $PAGE['FacebookUrl'] : '') ?>"
                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Deployment Date <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <input type="date"
                                   value="<?= ((isset($PAGE['DeploymentDate'])) ? $PAGE['DeploymentDate'] : '') ?>"
                                   id="deployment_date" name="Profile[DeploymentDate]" placeholder="Deployment Date"
                                   data-validation-engine="validate[required]" class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">SMS Credits </label>
                        <div class="col-sm-12">
                            <input type="text" id="SMSCredits" name="Profile[SMSCredits]" placeholder="SMS Credits"
                                   value="<?= ((isset($PAGE['SMSCredits'])) ? $PAGE['SMSCredits'] : '') ?>"
                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Sale Agent</label>
                        <div class="col-sm-12">
                            <input type="text" id="SaleAgent" name="Profile[SaleAgent]" placeholder="Sale Agent"
                                   value="<?= ((isset($PAGE['SaleAgent'])) ? $PAGE['SaleAgent'] : '') ?>"
                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <label>Address</label>
                    <input value="<?= ((isset($PAGE['Address'])) ? $PAGE['Address'] : '') ?>" type="text" id="Address"
                           name="Profile[Address]" placeholder="Address" class="form-control">
                </div>
            </div>
            <?php
            if ($page == 'add-profile') { ?>
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
                <div class="col-md-12 mt-2" id="ajaxResponse"></div>
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
                                <i class="feather feather-users mr-2"></i> <?= (($page == 'add-profile') ? 'Creating' : 'Updating') ?>
                                Your Digital Extended Profile</h5>
                            <p>Please wait while we save your details...</p>
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                            <span class="progress-percentage">0%</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-2">
                    <button style="float: right;" class="btn btn-primary" type="button"
                            onclick="ExtendedProfilesFormSubmit();"><?= (($page == 'add-profile') ? 'Add Profile' : 'Update Profile') ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>

    $(document).ready(function () {

        var currentPage = '<?=$page?>';
        if (currentPage === 'add-profile') {
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

    function ExtendedProfilesFormSubmit() {

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

            const FullName = $("form#ExtendedProfileForm input#name").val();
            const Email = $("form#ExtendedProfileForm input#email").val();
            const ContactNo = $("form#ExtendedProfileForm input#contact_no").val();
            const City = $("form#ExtendedProfileForm select#city").val();
            const DataBaseName = $("form#ExtendedProfileForm input#database_name").val();
            const SubDomain = $("form#ExtendedProfileForm input#subdomain_url").val();
            const DeploymentDate = $("form#ExtendedProfileForm input#deployment_date").val();

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
            if (DataBaseName == '') {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> DataBase Name Required </div>');
                return false;
            }
            if (SubDomain === '' || !isValidUrl(SubDomain)) {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Valid SubDomain Required </div>');
                return false;
            }
            if (DeploymentDate == '') {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Deployment Date Required </div>');
                return false;
            }
            if (CurrentPage == 'add-profile') {
                var Package = $("form#ExtendedProfileForm select#Package").val();
                var OriginalPrice = $("form#ExtendedProfileForm input#OriginalPrice").val();
                var Discount = $("form#ExtendedProfileForm input#Discount").val();
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

            $('.progress-fill').css('width', '60%');
            $('.progress-percentage').text('60%');

            const formdata = $("form#ExtendedProfileForm").serialize();
            const response = AjaxResponse("extended/submit_profile", formdata);
            if (response.status === 'success') {
                clearInterval(progressInterval);
                $('.progress-fill').css('width', '100%');
                $('.progress-percentage').text('100%');
                $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');

                if (CurrentPage === 'add-profile' && window.location.hostname !== 'localhost') {
                    const DomainResponse = AjaxResponse('Extended/CreateSubdomainsWorker', 'subdomain=' + response.subdomain);
                    if (DomainResponse.status == 'success') {
                        AjaxResponse('Extended/CreateDataBaseWorker', 'database=' + response.database);
                    }
                }

                setTimeout(function () {
                    $('.progress-modal').hide();
                    location.href = "<?=$path?>extended";
                }, 1500);

            } else {
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
            }
        }, 50); // Minimal delay to ensure modal renders
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
