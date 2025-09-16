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
    <div class="card-header">
        <h5 style="margin-bottom: 0px; padding: 10px;" class="card-title"><?= $ProfileData['Name'] ?> <b>(Scripts)</b>
        </h5>
    </div>
    <div style="padding: 1rem !important;" class="card-body">
        <form method="post" action="" name="BuilderProfilesScriptForm" id="BuilderProfilesScriptForm"
              class="needs-validation" novalidate=""
              enctype="multipart/form-data">
            <input type="hidden" name="ProfileUID" id="ProfileUID" value="<?= $ProfileData['UID'] ?>">
            <input type="hidden" name="UID" id="UID" value="0">
            <div class="row">
                <div class="col-md-12">
                    <div class="accordion accordion-primary custom-accordion">
                        <div class="accordion-row open">
                            <a href="#" class="accordion-header">
                                <span>Add Script Details</span>
                                <i class="accordion-status-icon close fa fa-chevron-up"></i>
                                <i class="accordion-status-icon open fa fa-chevron-down"></i>
                            </a>
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-12" id="ajaxResponse"></div>
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom04">Title <small
                                                    class="text-danger">*</small></label>
                                        <input type="text" class="form-control" name="title" id="title"
                                               placeholder="Script Title">
                                    </div>
                                    <div class="col-md-12">
                                        <label for="validationCustom04">Script <small
                                                    class="text-danger">*</small></label>
                                        <textarea style="min-height: 200px;" class="form-control" name="script"
                                                  id="script"
                                                  placeholder="Script"></textarea>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <button onclick="BuilderProfileScriptFormSubmit();"
                                                style="float: right; border-radius: 5px;" type="button"
                                                class="btn btn-sm  btn-primary">Add Script
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-row">
                            <a href="#" class="accordion-header">
                                <span>View All Scripts</span>
                                <i class="accordion-status-icon close fa fa-chevron-up"></i>
                                <i class="accordion-status-icon open fa fa-chevron-down"></i>
                            </a>
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-12" id="BuilderProfileScriptRecords"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {
        LoadBuilderProfilesScripts();
    }, 100);

    function BuilderProfileScriptFormSubmit() {
        const UID = $("input#UID").val();
        const Title = $("input#title").val().trim();
        const Script = $("textarea#script").val().trim();

        if (Title == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Title Required </div>');
            return false;
        }
        if (Script == '') {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Script Required </div>');
            return false;
        }

        const confirmMessage = UID > 0 ? "Do you want to update the script?" : "Do you want to add script?";
        if (confirm(confirmMessage)) {
            const Data = $("form#BuilderProfilesScriptForm").serialize();
            const response = AjaxResponse("builder/profiles_script_form_submit", Data);

            if (response.status === 'success') {
                $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');

                setTimeout(function () {
                    LoadBuilderProfilesScripts();
                    $("#ajaxResponse").html('');
                    $("input#UID").val("0");
                    $("input#title").val("");
                    $("textarea#script").val("");

                    const submitButton = $('button[onclick="BuilderProfileScriptFormSubmit();"]');
                    submitButton.text("Add Script");
                    submitButton.removeClass("btn-danger").addClass("btn-primary");

                }, 1500);
            } else {
                $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
            }
        }
    }

    function LoadBuilderProfilesScripts() {

        const ProfileUID = $("input#ProfileUID").val();
        if (ProfileUID > 0) {
            const ScriptRecords = AjaxResponse('builder/get_all_profiles_scripts', 'ProfileUID=' + ProfileUID);
            if (ScriptRecords.status == 'success') {
                $("#BuilderProfileScriptRecords").html(ScriptRecords.page);
            } else {
                $("#BuilderProfileScriptRecords").html('<div class="alert alert-danger text-center font-weight-bold">No Record Available!</div>');
            }
        }
    }

    function UpdateProfileScriptRecord(ScriptUID) {
        const Record = AjaxResponse('builder/get_profile_script_record_by_id', 'ScriptUID=' + ScriptUID);
        if (Record != '' && Record != null) {

            $("input#UID").val(Record.UID);
            $("input#title").val(Record.Title);
            $("textarea#script").val(Record.Script);

            const accordionRow = $(".accordion-row").first();
            if (!accordionRow.hasClass("open")) {
                accordionRow.addClass("open");
            }

            const submitButton = $('button[onclick="BuilderProfileScriptFormSubmit();"]');
            submitButton.text("Update Script");
            submitButton.removeClass("btn-primary").addClass("btn-danger");

            $('html, body').animate({
                scrollTop: accordionRow.offset().top - 100
            }, 500);
        }
    }

    function DeleteProfileScriptRecord(ScriptUID) {
        if (confirm("Do you want to remove script?")) {
            const Response = AjaxResponse('builder/remove_profile_script', 'ScriptUID=' + ScriptUID);
            if (Response.status == 'success') {
                setTimeout(function () {
                    LoadBuilderProfilesScripts();
                }, 500)
            }
        }
    }

</script>