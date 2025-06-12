<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<div class="modal" id="AddIndividualBannerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 60% !important;">
        <div class="modal-content">
            <form method="post" action="" name="AddIndividualBannerForm" id="AddIndividualBannerForm" class="needs-validation" novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="ProfileUID" id="ProfileUID" value="0">
                <div style="border-bottom: 1px solid #dee2e6;" class="modal-header">
                    <h5 class="modal-title">Add Individual Banner</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Alignment</label>
                            <select class="form-control" id="alignment" name="alignment">
                                <option value="">Select Alignment</option>
                                <option value="left">Left</option>
                                <option value="right">Right</option>
                                <option value="center">Center</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Color</label>
                            <select class="form-control" id="color" name="color">
                                <option value="">Select Color</option>
                                <option value="light">Light</option>
                                <option value="dark">Dark</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="validationCustom02">Specialities</label>
                            <select class="form-control" id="speciality" name="speciality">
                                <option value="">Select Speciality</option>
                                <?php foreach ($specialities as $record) { ?>
                                    <option value="<?= $record['UID'] ?>"><?= ucwords($record['Name']); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="validationCustom05">Profile</label>
                            <div class="custom-file">
                                <input onchange="updateFileName(this)" type="file" class="custom-file-input" id="profile" name="profile"
                                       accept=".gif,.jpg,.jpeg,.png,.webp">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                            <small class="text-muted">Allowed formats: GIF, JPG, JPEG, PNG, WEBP</small>
                            <div class="invalid-feedback" id="fileError"></div>
                        </div>
                        <div class="mt-2 col-md-12" id="ajaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="border-radius: 5px;" type="button" class="btn btn-success btn-sm" onclick="AddIndividualBannerFormFunction()">Add Banner</button>
                    <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script>

    function updateFileName(input) {

        if (input.files && input.files.length > 0) {
            const fileName = input.files[0].name;
            const label = input.nextElementSibling;
            label.textContent = fileName;
            label.classList.add('selected'); // Optional: add class for styling
        }
    }

    function LoadIndividualBannerModal(id) {

        $('#AddIndividualBannerModal form#AddIndividualBannerForm input#ProfileUID').val(id);
        $('#AddIndividualBannerModal').modal('show');
    }

    function AddIndividualBannerFormFunction() {

        setTimeout(function (){
            $("#AddIndividualBannerModal #ajaxResponse").html('');
        }, 2000);

        const Alignment = $("#AddIndividualBannerModal form#AddIndividualBannerForm select#alignment").val();
        const Color = $("#AddIndividualBannerModal form#AddIndividualBannerForm select#color").val();
        const Speciality = $("#AddIndividualBannerModal form#AddIndividualBannerForm select#speciality").val();

        if(Alignment == ''){
            $("#AddIndividualBannerModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Alignment Required!</div>');
            return false;
        }
        if(Color == ''){
            $("#AddIndividualBannerModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Color Required!</div>');
            return false;
        }
        if(Speciality == ''){
            $("#AddIndividualBannerModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Speciality Required!</div>');
            return false;
        }

        const fileInput = document.getElementById('profile');
        const file = fileInput.files[0];
        const allowedExtensions = ['gif', 'jpg', 'jpeg', 'png', 'webp'];

        if (file) {
            const fileExt = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExt)) {
                $("#AddIndividualBannerModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Invalid file type. Allowed formats: '+ allowedExtensions.join(', ') +' </div>');
                fileInput.classList.add('is-invalid');
                return false;
            }

            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                $("#AddIndividualBannerModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> File size exceeds 2MB limit</div>');
                fileInput.classList.add('is-invalid');
                return false;
            }

        } else {

            $("#AddIndividualBannerModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Plz Select a File!</div>');
            fileInput.classList.add('is-invalid');
            return false;
        }

        fileInput.classList.remove('is-invalid');
        var formdata = new window.FormData($("form#AddIndividualBannerForm")[0]);
        var response = AjaxUploadResponse("builder/submit_individual_banners", formdata);

        if (response.status === 'success') {
            $("#ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 500);
        } else {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>
<script src="<?=$template?>assets/js/examples/form-validation.js"></script>