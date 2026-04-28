<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<style>
    #AddBannerModal .modal-dialog {
        min-width: 68% !important;
    }

    #AddBannerModal .modal-header {
        border-bottom: 1px solid #e8edf3;
        background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    }

    #AddBannerModal .modal-title small {
        color: #6c757d;
        font-size: 12px;
        font-weight: 500;
    }

    .banner-entry {
        border: 1px solid #e6ebf2;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 12px;
        background: #fbfcfe;
    }

    .banner-entry-title {
        font-size: 13px;
        font-weight: 700;
        color: #1f2d3d;
        margin-bottom: 10px;
    }

    .banner-actions-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .entry-remove-btn {
        border-radius: 6px;
    }

    .batch-tip {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 12px;
    }

    .select2-container--default .select2-selection--single {
        height: 38px;
        border-radius: 10px;
        background: #e6e6e6;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }
</style>
<div class="modal" id="AddBannerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="" name="AddBannerForm" id="AddBannerForm" class="needs-validation" novalidate=""
                enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div class="modal-header">
                    <h5 class="modal-title" id="GeneralBannerModalTitle">Add General Banner
                        <small class="d-block">Create one or many banners in one submit</small>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="banner-actions-bar">
                        <div class="batch-tip" id="BatchModeHint">
                            Tip: click "Add Another" for bulk entry.
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="AddAnotherBannerBtn" onclick="addBannerEntry()">
                            <i class="fa fa-plus"></i> Add Another
                        </button>
                    </div>
                    <div id="BannerEntriesContainer"></div>
                    <div class="d-none" id="BannerEntryTemplate">
                        <div class="banner-entry">
                            <div class="banner-entry-title">Banner Entry #__INDEX__</div>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label>Banner Type <small class="text-danger">*</small></label>
                                    <select class="form-control banner-type">
                                        <option value="">Select Type</option>
                                        <option value="custom-text">Custom Text</option>
                                        <option value="image-only">Image Only</option>
                                        <option value="pre-designed">Pre Designed</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Alignment <small class="text-danger">*</small></label>
                                    <select class="form-control banner-alignment">
                                        <option value="">Select Alignment</option>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                        <option value="center">Center</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Specialities <small class="text-danger">*</small></label>
                                    <select class="form-control banner-speciality">
                                        <option value="">Select Speciality</option>
                                        <?php foreach ($specialities as $record) { ?>
                                            <option value="<?= $record['UID'] ?>"><?= ucwords($record['Name']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Profile <small class="text-danger">*</small></label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input banner-profile" accept=".gif,.jpg,.jpeg,.png,.webp">
                                        <label class="custom-file-label">Choose file</label>
                                    </div>
                                    <small class="text-muted">Allowed formats: GIF, JPG, JPEG, PNG, WEBP</small>
                                    <div class="mt-2 current-banner-image"></div>
                                </div>
                            </div>
                            <div class="text-right mt-1 remove-entry-wrap d-none">
                                <button type="button" class="btn btn-outline-danger btn-sm entry-remove-btn" onclick="removeBannerEntry(this)">
                                    Remove Entry
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="mt-2 col-md-12" id="ajaxResponse"></div>
                </div>
                <div class="modal-footer">
                    <button style="border-radius: 5px;" type="button" class="btn btn-success btn-sm"
                        id="GeneralBannerSubmitBtn" onclick="AddBannerFormFunction()">Add Banner
                    </button>
                    <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm"
                        data-dismiss="modal">Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script>
    const ALLOWED_EXTENSIONS = ['gif', 'jpg', 'jpeg', 'png', 'webp'];
    const MAX_FILE_SIZE = 2 * 1024 * 1024;

    $(document).ready(function() {
        initializeBannerEntries();
    });

    function initializeBannerEntries() {
        const $container = $("#BannerEntriesContainer");
        if ($container.children().length === 0) {
            addBannerEntry();
        }
    }

    function renderEntryIndices() {
        $("#BannerEntriesContainer .banner-entry").each(function(idx) {
            $(this).find(".banner-entry-title").text("Banner Entry #" + (idx + 1));
            if (idx === 0) {
                $(this).find(".remove-entry-wrap").addClass("d-none");
            } else {
                $(this).find(".remove-entry-wrap").removeClass("d-none");
            }
        });
    }

    function updateFileName(input) {
        if (input.files && input.files.length > 0) {
            const fileName = input.files[0].name;
            const label = input.nextElementSibling;
            label.textContent = fileName;
            label.classList.add('selected');
        }
    }

    function addBannerEntry() {
        const $container = $("#BannerEntriesContainer");
        const index = $container.children().length + 1;
        let entryHtml = $("#BannerEntryTemplate").html();
        entryHtml = entryHtml.replace(/__INDEX__/g, index);
        const $entry = $(entryHtml);

        $entry.find(".banner-profile").on("change", function() {
            updateFileName(this);
        });

        $entry.find(".banner-speciality").select2({
            dropdownParent: $("#AddBannerModal"),
            width: '100%'
        });

        if ($container.children().length === 0) {
            $entry.find(".banner-type").attr("id", "type");
            $entry.find(".banner-alignment").attr("id", "alignment");
            $entry.find(".banner-speciality").attr("id", "speciality");
            $entry.find(".banner-profile").attr("id", "profile").attr("name", "profile");
            $entry.find(".current-banner-image").attr("id", "CurrentBannerImage");
        }

        $container.append($entry);
        renderEntryIndices();
    }

    function removeBannerEntry(btn) {
        $(btn).closest(".banner-entry").remove();
        renderEntryIndices();
    }

    function resetBannerFormForAdd() {
        const $form = $("#AddBannerModal form#AddBannerForm");
        $form[0].reset();
        $form.find("input#UID").val(0);
        $("#GeneralBannerModalTitle").html('Add General Banner<small class="d-block">Create one or many banners in one submit</small>');
        $("#GeneralBannerSubmitBtn").text("Add Banner");
        $("#AddAnotherBannerBtn").removeClass("d-none");
        $("#BatchModeHint").removeClass("d-none").text('Tip: click "Add Another" for bulk entry.');
        $("#AddBannerModal #ajaxResponse").html('');

        const $container = $("#BannerEntriesContainer");
        $container.html('');
        addBannerEntry();
    }

    function setBannerFormForUpdateMode() {
        $("#GeneralBannerModalTitle").html('Update General Banner<small class="d-block">Update selected banner details</small>');
        $("#GeneralBannerSubmitBtn").text("Update Banner");
        $("#AddAnotherBannerBtn").addClass("d-none");
        $("#BatchModeHint").removeClass("d-none").text('Image is optional for update.');
        const $container = $("#BannerEntriesContainer .banner-entry");
        if ($container.length > 1) {
            $container.slice(1).remove();
        }
        renderEntryIndices();
    }

    function validateEntry($entry, isUpdateMode) {
        const type = $entry.find(".banner-type").val();
        const alignment = $entry.find(".banner-alignment").val();
        const speciality = $entry.find(".banner-speciality").val();
        const fileInput = $entry.find(".banner-profile")[0];
        const file = fileInput ? fileInput.files[0] : null;

        if (!type) {
            return "Type Required!";
        }
        if (!alignment) {
            return "Alignment Required!";
        }
        if (!speciality) {
            return "Speciality Required!";
        }
        if (!file && !isUpdateMode) {
            return "Plz Select a File!";
        }
        if (file) {
            const fileExt = file.name.split('.').pop().toLowerCase();
            if (!ALLOWED_EXTENSIONS.includes(fileExt)) {
                return "Invalid file type. Allowed formats: " + ALLOWED_EXTENSIONS.join(', ');
            }
            if (file.size > MAX_FILE_SIZE) {
                return "File size exceeds 2MB limit";
            }
        }
        return "";
    }

    function createEntryFormData($entry, uid) {
        const formdata = new window.FormData();
        formdata.append("UID", uid);
        formdata.append("type", $entry.find(".banner-type").val());
        formdata.append("alignment", $entry.find(".banner-alignment").val());
        formdata.append("speciality", $entry.find(".banner-speciality").val());
        const fileInput = $entry.find(".banner-profile")[0];
        if (fileInput && fileInput.files && fileInput.files[0]) {
            formdata.append("profile", fileInput.files[0]);
        }
        return formdata;
    }

    function AddBannerFormFunction() {
        const UID = parseInt($("#AddBannerModal form#AddBannerForm input#UID").val() || 0, 10);
        const isUpdateMode = UID > 0;
        const $entries = $("#BannerEntriesContainer .banner-entry");

        if ($entries.length === 0) {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-3" role="alert"><strong>Error!</strong> No banner entry found.</div>');
            return false;
        }

        for (let i = 0; i < $entries.length; i++) {
            const $entry = $($entries[i]);
            const error = validateEntry($entry, isUpdateMode);
            if (error !== '') {
                $("#ajaxResponse").html('<div class="alert alert-danger mb-3" role="alert"><strong>Error!</strong> Entry #' + (i + 1) + ': ' + error + '</div>');
                return false;
            }
        }

        const $submitBtn = $("#GeneralBannerSubmitBtn");
        const originalText = $submitBtn.text();
        $submitBtn.prop("disabled", true).text("Submitting...");

        let successCount = 0;
        let failedMessage = "";
        const total = $entries.length;

        for (let i = 0; i < total; i++) {
            const $entry = $($entries[i]);
            const formdata = createEntryFormData($entry, isUpdateMode ? UID : 0);
            const response = AjaxUploadResponse("builder/submit_general_image", formdata);

            if (response.status === 'success') {
                successCount++;
                if (isUpdateMode) {
                    break;
                }
            } else {
                failedMessage = (response.message || response.msg || "Data Didnt Submitted Successfully...!");
                break;
            }
        }

        $submitBtn.prop("disabled", false).text(originalText);

        if (failedMessage !== "") {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-3" role="alert"><strong>Error!</strong> ' + failedMessage + '</div>');
            return false;
        }

        if (isUpdateMode) {
            $("#ajaxResponse").html('<div class="alert alert-success mb-3" role="alert"><strong>Success!</strong> Banner updated successfully.</div>');
            setTimeout(function() {
                location.reload();
            }, 500);
            return true;
        }

        $("#ajaxResponse").html('<div class="alert alert-success mb-3" role="alert"><strong>Success!</strong> ' + successCount + ' banner(s) added successfully.</div>');
        setTimeout(function() {
            location.reload();
        }, 700);
        return true;
    }
</script>
<script>
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
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