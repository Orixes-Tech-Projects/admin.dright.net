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

    .select2-container .select2-selection--single {
        height: 37px !important;
        border-radius: 10px !important;
        background: #e6e6e6 !important;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        height: 37px !important;
        padding: 5px 10px !important;
    }
</style>
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<?php

use App\Models\BuilderModel;
use App\Models\Crud;

$Crud = new Crud();
$BuilderModel = new BuilderModel();
$BuilderAllProfiles = $BuilderModel->BuilderAllProfiles();

$short_desc = $clinta_extended_profiles = $healthcarestatus = $theme = $patient_portal = '';
if($page != 'add-promotional-websites'){
    $patient_portal = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'patient_portal');
}
?>
<div class="card">
    <div class="card-body">
        <h6 style="margin-bottom: 1rem !important;"
            class="card-title"><?= ((isset($PAGE['UID'])) ? 'Update' : 'Add New') ?> Promotional Websites</h6>
        <hr style="margin-bottom: 1rem !important; margin-top: 0rem !important;">
        <form method="post" action="" name="AddPromotionalWebsitesForm" id="AddPromotionalWebsitesForm"
              class="needs-validation" novalidate=""
              enctype="multipart/form-data">
            <input type="hidden" name="UID" id="UID" value="<?= ((isset($PAGE['UID'])) ? $PAGE['UID'] : '0') ?>">
            <div class="form-row">
                <div class="col-md-4 mb-3">
                    <label for="validationCustom04">FullName <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="name" id="name"
                           placeholder="Full Name" value="<?= ((isset($PAGE['Name'])) ? $PAGE['Name'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom04">Email <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="email" id="email"
                           placeholder="Email" value="<?= ((isset($PAGE['Email'])) ? $PAGE['Email'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="validationCustom04">Password <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="password" id="password"
                           placeholder="Password" value="<?= ((isset($PAGE['Password'])) ? $PAGE['Password'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom04">ContactNo <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="ContactNo" id="ContactNo"
                           placeholder="Contact No"
                           value="<?= ((isset($PAGE['ContactNo'])) ? $PAGE['ContactNo'] : '') ?>"
                           required="">
                    <div class="invalid-feedback">
                        Please provide a valid .
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-sm-4">City <span class="text-danger">*</span></label>
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
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-sm-12">Sub Domain <span class="text-danger">*</span></label>
                        <div class="col-sm-12">
                            <input <?= (($page != 'add-promotional-websites') ? 'readonly' : '') ?> type="text"
                                                                                                    id="sub_domain"
                                                                                                    name="sub_domain"
                                                                                                    placeholder="Sub Domain"
                                                                                                    value="<?= ((isset($PAGE['SubDomain'])) ? $PAGE['SubDomain'] : '') ?>"
                                                                                                    class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-sm-12">Patient Portal <span class="text-danger">*</span></label>
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
                <div class="col-md-<?= (($page != 'add-promotional-websites') ? 4 : 4) ?> mb-3">
                    <label for="validationCustom05">Profile</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="profile" name="profile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <?php if ($page != 'add-promotional-websites') { ?>
                    <div class="col-md-3">
                        <img  src="<?= load_image('pgsql|profiles|' . $PAGE['UID']) ?>"
                             height="70">
                    </div>
                <?php } ?>

                <?php
                if ($page == 'add-promotional-websites') {
                    ?>
                    <div class="col-md-3">
                        <div class="form-group row">
                            <label class="col-sm-12">Profile Type <span class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select id="profile_type" name="profile_type" class="form-control"
                                        data-validation-engine="validate[required]">
                                    <option value="">Select Type</option>
                                    <option value="doctors">Doctors Website</option>
                                    <option value="hospitals">Hospital Website</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group row">
                            <label class="col-sm-12">Clone From Existing Profile <span
                                        class="text-danger">*</span></label>
                            <div class="col-sm-12">
                                <select id="copy_profile_id" name="copy_profile_id" class="form-control"
                                        data-validation-engine="validate[required]">
                                    <option value="">Select Profile</option>
                                    <?php
                                    foreach ($BuilderAllProfiles as $BAP) {
                                        echo '<option value="' . $BAP['UID'] . '">' . ucwords($BAP['Type']) . ' - ' . $BAP['Name'] . ' - ( ' . $BAP['SubDomain'] . ' )</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
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
                                <i class="feather feather-users mr-2"></i> <?= (($page == 'add-promotional-websites') ? 'Creating' : 'Updating') ?>
                                Your Digital Promotional Websites Profile</h5>
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
                            onclick="AddPromotionalWebsitesFormFunction()"><?= ((isset($PAGE['UID']) && $PAGE['UID'] != '' && $PAGE['UID'] > 0) ? 'Update' : 'Add') ?>
                        Promotional Websites
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script>

    $(document).ready(function () {
        $("select#copy_profile_id").select2();
    });

    function AddPromotionalWebsitesFormFunction() {

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

            const FullName = $("form#AddPromotionalWebsitesForm input#name").val();
            const Email = $("form#AddPromotionalWebsitesForm input#email").val();
            const Password = $("form#AddPromotionalWebsitesForm input#password").val();
            const ContactNo = $("form#AddPromotionalWebsitesForm input#ContactNo").val();
            const City = $("form#AddPromotionalWebsitesForm select#city").val();
            const SubDomain = $("form#AddPromotionalWebsitesForm input#sub_domain").val();
            const PatientPortal = $("form#AddPromotionalWebsitesForm select#patient_portal").val();

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

            if (PatientPortal == '') {
                clearInterval(progressInterval);
                $('.progress-modal').hide();
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Patient Portal Access Required </div>');
                return false;
            }

            if (CurrentPage == 'add-promotional-websites') {
                const ProfileType = $("form#AddPromotionalWebsitesForm select#profile_type").val();
                const CopyProfileID = $("form#AddPromotionalWebsitesForm select#copy_profile_id").val();

                if (ProfileType == '') {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Profile Type Required </div>');
                    return false;
                }
                if (CopyProfileID == '') {
                    clearInterval(progressInterval);
                    $('.progress-modal').hide();
                    $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Existing Profile Required </div>');
                    return false;
                }
            }

            const formdata = new window.FormData($("form#AddPromotionalWebsitesForm")[0]);
            const response = AjaxUploadResponse("builder/submit-promotional-websites", formdata);
            if (response.status === 'success') {
                clearInterval(progressInterval);
                $('.progress-fill').css('width', '100%');
                $('.progress-percentage').text('100%');
                $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');

                /** Send Request TO CPanel For Creating SubDomains */
                if (CurrentPage == 'add-promotional-websites' && response.subdomain) {
                    if (response.subdomain.endsWith('.clinta.biz')) {
                        AjaxResponse('Builder/CreateSubdomainsWorker', 'subdomain=' + response.subdomain);
                    }
                }

                setTimeout(function () {
                    $('.progress-modal').hide();
                    location.href = "<?=$path?>builder/promotional-websites";
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
