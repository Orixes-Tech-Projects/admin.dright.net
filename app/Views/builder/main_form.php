<br>
<?php

use App\Models\BuilderModel;

$speciality = '';
$qualification = '';
$department = '';
$pmdcno = '';
$patient_portal = '';
$telemedicine_id = '';
$short_desc = '';
$initatived_text = '';
$sponsor = '';
$initatived_logo = '';
$healthcarestatus = '';
$patientportal = '';
$theme = '';
$BuilderModel = new BuilderModel();
//print_r($page);exit();
if ($page == 'add-doctor') {

} else {
    $speciality = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'speciality');
    $qualification = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'qualification');
    $department = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'department');
    $pmdcno = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'pmdcno');
    $telemedicine_id = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'telemedicine_id');
    $short_desc = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'short_description');
    $initatived_text = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'initatived_text');
    $sponsor = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'sponsor');
    $initatived_logo = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'initatived_logo');
    $healthcarestatus = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'healthcare_status');
    $patient_portal = $BuilderModel->get_website_profile_meta_data_by_id_option($PAGE['UID'], 'patient_portal');
    $theme = $BuilderModel->get_profile_options_data_by_id_option($PAGE['UID'], 'theme');
}

//print_r($sponsor);exit();
?>
<div class="card">
    <div class="card-body">
        <h4 style="margin-bottom: 1rem;" class="card-title"><?= ((isset($PAGE['UID'])) ? 'Update' : 'Add New') ?>
            Doctor</h4>
        <hr>
        <form method="post" action="" name="AddDoctorForm" id="AddDoctorForm" class="needs-validation" novalidate=""
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
                    <label for="validationCustom04">Contact No</label>
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
                <div class="col-md-3 mb-3">
                    <label for="validationCustom02">Specialities</label>
                    <div class="col-sm-12">
                        <select id="specialities" name="speciality" class="form-control"
                                data-validation-engine="validate[required]">
                            <option value="">Please Select</option>
                            <?php foreach ($specialities as $record) { ?>
                                <option value="<?= $record['UID'] ?>" <?= (isset($speciality[0]['Value']) && $speciality[0]['Value'] == $record['UID']) ? 'selected' : '' ?>
                                ><?= ucwords($record['Name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="validationCustom02">Sponsors</label>
                    <div class="col-sm-12">
                        <select id="city" name="sponsor" class="form-control"
                                data-validation-engine="validate[required]">
                            <option value="">Please Select</option>
                            <?php foreach ($Sponsors as $record) { ?>
                                <option <?= ((isset($sponsor[0]['Description']) && $sponsor[0]['Description'] == $record['UID']) ? 'selected' : '') ?>
                                        value="<?= $record['UID'] ?>"><?= ucwords($record['Name']); ?></option>
                            <?php } ?>                                </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Qualification</label>
                        <div class="col-sm-12">
                            <input type="text" id="qualification" name="qualification" placeholder="Qualification"
                                   value="<?php if (is_array($qualification) && !empty($qualification)) { ?><?= isset($qualification[0]['Value']) ? $qualification[0]['Value'] : ''; ?><?php } ?>"
                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">PMDC No</label>
                        <div class="col-sm-12">
                            <input type="text" id="pmdcno" name="pmdcno" placeholder="PMDCNo"
                                   value="<?php if (is_array($pmdcno) && !empty($pmdcno)) { ?><?= isset($pmdcno[0]['Value']) ? $pmdcno[0]['Value'] : ''; ?><?php } ?>"
                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Department</label>
                        <div class="col-sm-12">
                            <input type="text" id="department" name="department" placeholder="Department"
                                   value="<?php if (is_array($department) && !empty($department)) { ?><?= isset($department[0]['Value']) ? $department[0]['Value'] : ''; ?><?php } ?>"

                                   class="form-control"/>
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
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">Admin Domain</label>
                        <div class="col-sm-12">
                            <input type="text" id="AdminDomain" name="AdminDomain" placeholder="Admin Domain"
                                   value="<?= ((isset($PAGE['AdminDomain'])) ? $PAGE['AdminDomain'] : '') ?>"

                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group row">
                        <label class="col-sm-12">e-Health Key</label>
                        <div class="col-sm-12">
                            <input type="text" id="telemedicine_id" name="telemedicine_id" placeholder="e-Health Key"
                                   value="<?php if (is_array($telemedicine_id) && !empty($telemedicine_id)) { ?><?= isset($telemedicine_id[0]['Value']) ? $telemedicine_id[0]['Value'] : ''; ?><?php } ?>"

                                   class="form-control"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">

                    <label for="validationCustom05">Profile</label>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="profile" name="profile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <?php if ($page != 'add-doctor') { ?>
                    <div class="col-md-4">
                        <img src="<?= load_image('pgsql|profiles|' . $PAGE['UID']) ?>" height="70">
                    </div>
                <?php } ?>
                <div class="col-md-4 mb-3">

                    <label for="validationCustom05">Initatived LOGO</label>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="initatived_logo" name="initatived_logo">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                <?php if ($page != 'add-doctor') { ?>
                    <div class="col-md-4">
                        <img src="<?= load_image_meta('pgsql|profile_metas|' . $PAGE['UID']) ?>" height="70">
                    </div>
                <?php } ?>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-12">Short Description</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="short_description" id="short_description"
                                      rows="6"><?php if (is_array($short_desc) && !empty($short_desc)) { ?> <?= isset($short_desc[0]['Value']) ? trim($short_desc[0]['Value']) : ''; ?><?php } ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-12">Initatived Text</label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="initatived_text" id="initatived_text"
                                      rows="6"><?php if (is_array($initatived_text) && !empty($initatived_text)) { ?><?= isset($initatived_text[0]['Value']) ? trim($initatived_text[0]['Value']) : ''; ?><?php } ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row mt-2">
                <div class="col-md-12">
                    <h4>Clinta HealthCare</h4>
                    <hr>
                </div>
                <div class="col-md-4">
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
                                <?php } ?>

                            >Show
                            </option>
                            <option value="0"
                                <?php if (is_array($healthcarestatus) && !empty($healthcarestatus)) { ?>
                                    <?= (isset($healthcarestatus[0]['Value']) && $healthcarestatus[0]['Value'] == '0') ? 'selected' : ''; ?>
                                <?php } ?>

                            >Hide
                            </option>

                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="col-sm-12">Theme Setting</label>
                    <div class="col-sm-12">
                        <select name="theme" id="theme" class="form-control">
                            <option value="0" <?= (is_array($theme) && !empty($theme) && isset($theme[0]['Description']) && $theme[0]['Description'] == '0') ? 'selected' : '' ?>>
                                Please Select
                            </option>
                            <option value="basic" <?= (is_array($theme) && !empty($theme) && isset($theme[0]['Description']) && $theme[0]['Description'] == 'basic') ? 'selected' : '' ?>>
                                Basic (Free)
                            </option>
                            <option value="deep-mind" <?= (is_array($theme) && !empty($theme) && isset($theme[0]['Description']) && $theme[0]['Description'] == 'deep-mind') ? 'selected' : '' ?>>
                                Premium (Paid)
                            </option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
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
            <div class="mt-4" id="ajaxResponse"></div>
            <div class="form-row">
                <div class="col-md-12">
                    <button style="float: right; border-radius: 5px;" class="btn btn-primary btn-sm" type="button"
                            onclick="AddDoctorFormFunction()">Update Profile
                    </button>
                </div>
        </form>

    </div>
</div>

</div>

<script>

    function AddDoctorFormFunction() {

        setTimeout(function () {
            $("#ajaxResponse").html('');
        }, 2000);

        var FullName = $("form#AddDoctorForm input#name").val();
        var Email = $("form#AddDoctorForm input#email").val();
        var Password = $("form#AddDoctorForm input#password").val();
        var ContactNo = $("form#AddDoctorForm input#ContactNo").val();
        var City = $("form#AddDoctorForm select#city").val();
        var SubDomain = $("form#AddDoctorForm input#sub_domain").val();

        if (FullName == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Full Name Required </div>');
            return false;
        }
        if (Email == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Email Required </div>');
            return false;
        }
        if (Password == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Password Required </div>');
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
        if (SubDomain == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> SubDomain Required </div>');
            return false;
        }

        var formdata = new window.FormData($("form#AddDoctorForm")[0]);
        var response = AjaxUploadResponse("builder/submit-doctor", formdata);
        if (response.status === 'success') {
            $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.href = "<?=$path?>builder/";
            }, 500);
        } else {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>
<script>
    (function () {
        'use strict';
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
</script>
<script src="<?= $template ?>assets/js/examples/form-validation.js"></script>
