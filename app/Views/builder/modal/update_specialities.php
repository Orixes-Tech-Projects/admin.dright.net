<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<div class="modal" id="UpdateSpecialitiesModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <form method="post" action="" name="UpdateSpecialitiesForm" id="UpdateSpecialitiesForm"
                  class="needs-validation" novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <div style="border-bottom: 1px solid #dee2e6;" class="modal-header">
                    <h5 class="modal-title">Update Speciality</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom01">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="Enter name"
                                   required="">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom02">Tag</label>
                            <select class="form-control" id="tag" name="tag">
                                <option value="">Please Select</option>
                                <option value="Short List">Short List</option>
                                <option value="Long List">Long List</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="validationCustom05">Icon</label>
                            <div class="custom-file">
                                <input onchange="updateIconTags(this)" type="file" class="custom-file-input" id="icon-image" name="icon">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div id="ImageHTML"></div>
                        </div>
                        <div class="mt-2 col-md-12" id="UpdateAjaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button style="border-radius: 5px;" type="button" class="btn btn-success btn-sm" onclick="UpdateSpecialitiesFormFunction()">Update Record
                    </button>
                    <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script>

    function updateIconTags(input) {

        if (input.files && input.files.length > 0) {
            const fileName = input.files[0].name;
            const label = input.nextElementSibling;
            label.textContent = fileName;
            label.classList.add('selected'); // Optional: add class for styling
        }
    }

    function UpdateSpecialities(id) {

        var Items = AjaxResponse("builder/get_specialities_record", "id=" + id);
        $('#UpdateSpecialitiesModal form#UpdateSpecialitiesForm input#UID').val(Items.record.UID);
        $('#UpdateSpecialitiesModal form#UpdateSpecialitiesForm input#name').val(Items.record.Name);
        $('#UpdateSpecialitiesModal form#UpdateSpecialitiesForm select#tag').val(Items.record.Tag);

        var path = '<?=$path?>';
        var imageHTML;
        if (Items.record.Icon) {
            imageHTML = '<img src="' + path + 'upload/specialities/' + Items.record.Icon + '" style="height:80px;">';
        }

        // Set the image HTML in the modal
        $('#UpdateSpecialitiesModal form#UpdateSpecialitiesForm #ImageHTML').html(imageHTML);
        $('#UpdateSpecialitiesModal').modal('show');
    }

    function UpdateSpecialitiesFormFunction() {

        setTimeout(function (){
            $("#UpdateSpecialitiesModal #UpdateAjaxResponse").html('');
        }, 2000);

        const Name = $("#UpdateSpecialitiesModal form#UpdateSpecialitiesForm input#name").val();
        const Tag = $("#UpdateSpecialitiesModal form#UpdateSpecialitiesForm select#tag").val();

        if(Name == ''){
            $("#UpdateSpecialitiesModal #UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Name Required!</div>');
            return false;
        }
        if(Tag == ''){
            $("#UpdateSpecialitiesModal #UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Tag Required!</div>');
            return false;
        }

        const fileInput = document.getElementById('icon-image');
        const file = fileInput.files[0];
        const allowedExtensions = ['gif', 'jpg', 'jpeg', 'png', 'webp'];

        if (file) {
            const fileExt = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExt)) {
                $("#UpdateSpecialitiesModal #UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Invalid file type. Allowed formats: '+ allowedExtensions.join(', ') +' </div>');
                return false;
            }

            const maxSize = 1 * 1024 * 1024; // 1MB
            if (file.size > maxSize) {
                $("#UpdateSpecialitiesModal #UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> File size exceeds 1MB limit</div>');
                return false;
            }
        }

        var formdata = new window.FormData($("form#UpdateSpecialitiesForm")[0]);
        var response = AjaxUploadResponse("builder/submit_specialities", formdata);
        if (response.status === 'success') {
            $("#UpdateAjaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                location.reload();
            }, 1000);
        } else {
            $("#UpdateAjaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>

<script src="<?=$template?>assets/js/examples/form-validation.js"></script>