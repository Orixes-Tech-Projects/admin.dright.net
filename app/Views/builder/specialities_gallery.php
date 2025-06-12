<?php

use App\Models\Crud;

$Crud = new Crud();
$specialities = $Crud->SingleRecord('specialities', array("UID" => $UID));
?>
<style>
    .image-thumbnail-wrapper {
        transition: all 0.3s ease;
    }

    .image-thumbnail-wrapper:hover {
        transform: scale(1.03);
    }

    .delete-btn {
        top: 8px;
        right: 8px;
        width: 24px;
        height: 24px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        text-decoration: none;
        font-size: 12px;
        opacity: 0.8;
        transition: all 0.2s ease;
    }

    .delete-btn:hover {
        opacity: 1;
        transform: scale(1.1);
        background: #c82333;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .img-thumbnail {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
</style>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container">
        <div class="accordion accordion-primary custom-accordion">
            <div class="accordion-row">
                <a href="#" class="accordion-header">
                    <strong>Add Gallery Images <small>( Click to Add New Gallery Images )</small></strong>
                    <i class="accordion-status-icon close fa fa-chevron-up"></i>
                    <i class="accordion-status-icon open fa fa-chevron-down"></i>
                </a>
                <div class="accordion-body">
                    <form method="post" action="" name="EventGalleryAddForm" id="EventGalleryAddForm"
                          enctype="multipart/form-data">
                        <input type="hidden" id="UID" name="UID" value="0">
                        <input type="hidden" id="SpecialityID" name="SpecialityID" value="<?= $UID; ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="validationCustom02">Size</label>
                                <select class="form-control" id="size" name="size[]">
                                    <option value="">Please Select</option>
                                    <option value="small">Small</option>
                                    <option value="medium">Medium</option>
                                    <option value="large">Large</option>
                                </select>
                                <div class="invalid-feedback">Please select a size</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <div class="custom-file">
                                    <input onchange="updateFileName(this)" type="file" class="custom-file-input"
                                           id="Image" name="Image[]">
                                    <label class="custom-file-label" for="customFile">Choose file</label>
                                    <div class="invalid-feedback">Please select an image</div>
                                </div>
                            </div>
                        </div>
                        <div id="image-fields"></div>
                        <div class="row">
                            <div class="col-12 mb-4" id="ajaxResponse"></div>
                            <div class="col-md-12" style="text-align: right;">
                                <button style="border-radius: 5px;" type="button" id="add-more"
                                        class="btn btn-primary btn-sm">Add More Rows
                                </button>
                                <button style="border-radius: 5px;" type="button" onclick="AddGalleryForm();"
                                        class="btn btn-success btn-sm">Submit Record
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-3 shadow-sm">
    <div class="card-header bg-white border-bottom-1">
        <h4 class="mb-0"><span class="text-danger">»</span> <strong style="color: crimson;"><?= htmlspecialchars($specialities['Name']); ?></strong> Gallery</h4>
    </div>
    <div class="card-body p-4">
        <div class="row">
            <div class="col-md-12" id="DeleteResponse"></div>
        </div>
        <div class="row g-3">
            <?php foreach ($Images as $IM): ?>
                <div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-2">
                    <div class="image-thumbnail-wrapper position-relative">
                        <a href="javascript:void(0);"
                           onclick="DeleteSpecialityImage(<?= $IM['UID'] ?>);"
                           class="delete-btn d-flex align-items-center justify-content-center position-absolute"
                           title="Delete Image">
                            <i class="fa fa-times"></i>
                        </a>
                        <img src="<?= htmlspecialchars($path . 'upload/specialities/' . $IM['Value']) ?>"
                             class="img-fluid rounded border"
                             style="height: 120px; width: 100%; object-fit: cover;">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<script>
    function DeleteSpecialityImage(id) {
        if (confirm("Are you Sure You want to Delete this Permanently ?")) {
            var response = AjaxResponse("builder/delete_specialities_image", "id=" + id);
            if (response.status == 'success') {
                $("#DeleteResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Image Deleted Successfully!</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1500);
            } else {
                $("#DeleteResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error! Not Deleted</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1500);
            }

        }
    }

    function updateFileName(input) {

        if (input.files && input.files.length > 0) {
            const fileName = input.files[0].name;
            const label = input.nextElementSibling;
            label.textContent = fileName;
            label.classList.add('selected'); // Optional: add class for styling
        }
    }

    $(document).on('change', '.custom-file-input', function () {
        updateFileName(this);
    });
</script>
<script>
    $(document).ready(function () {
        $('#add-more').click(function () {

            const newFields = `<div class="row align-items-end mb-3 dynamic-fields">
                                <div class="col-md-6">
                                    <label for="validationCustom02">Size</label>
                                    <select class="form-control" name="size[]">
                                        <option value="">Please Select</option>
                                        <option value="small">Small</option>
                                        <option value="medium">Medium</option>
                                        <option value="large">Large</option>
                                    </select>
                                    <div class="invalid-feedback">Please select a size</div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="custom-file flex-grow-1">
                                        <input type="file" class="custom-file-input" id="Image" name="Image[]">
                                        <label class="custom-file-label" for="customFile">Choose file</label>
                                        <div class="invalid-feedback">Please select an image</div>
                                    </div>
                                    <button style="border-radius: 5px !important; margin-bottom: 5px;" type="button" class="btn btn-danger btn-sm remove-btn ml-1">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>`;
            $('#image-fields').append(newFields);

            $(document).find('.custom-file-input').off('change').on('change', function () {
                const fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass('selected').html(fileName);
            });
        });
        $(document).on('click', '.remove-btn', function () {
            $(this).closest('.dynamic-fields').remove();
        });
    });

    function validateForm() {

        let isValid = true;
        const errorMessages = [];

        $('select[name="size[]"]').each(function (index) {
            if (!$(this).val()) {
                isValid = false;
                errorMessages.push(`&raquo; <b>Row ${index + 1}:</b> Please select a size`);
            }
        });

        $('input[name="Image[]"]').each(function (index) {
            if (!$(this)[0].files[0]) {
                isValid = false;
                errorMessages.push(`&raquo;  <b>Row ${index + 1}:</b> Please select an image`);
            } else {
                const file = $(this)[0].files[0];
                const validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                const extension = file.name.split('.').pop().toLowerCase();

                if (!validExtensions.includes(extension)) {
                    isValid = false;
                    errorMessages.push(`&raquo;  <b>Row ${index + 1}:</b> Invalid file type (${extension})`);
                }

                const maxSize = 2 * 1024 * 1024; // 2MB
                if (file.size > maxSize) {
                    isValid = false;
                    errorMessages.push(`&raquo;  <b>Row ${index + 1}:</b> File too large (max 2MB)`);
                }

                $(this).removeClass('is-invalid');
                $(this).next('.custom-file-label').removeClass('text-danger');
            }
        });

        if (!isValid) {
            const errorHtml = errorMessages.map(msg => `<div class="text-danger">${msg}</div>`).join('');
            $("#ajaxResponse").html(`<div class="alert alert-danger mb-2">${errorHtml}</div>`);
            return false;
        }

        return true;
    }

    function AddGalleryForm() {

        setTimeout(function (){
            $("#ajaxResponse").html('');
        }, 2500);

        if (!validateForm()) {
            return false;
        }

        var formdata = new window.FormData($("form#EventGalleryAddForm")[0]);
        var response = AjaxUploadResponse("builder/gallery_form_submit", formdata);

        if (response.status == 'success') {
            $("#ajaxResponse").html('<div class="alert alert-success mb-4" role="alert"><strong>Success!</strong> ' + response.message + '</div>');
            setTimeout(function () {
                location.reload();
            }, 1500);
        } else {
            $("#ajaxResponse").html('<div class="alert alert-danger mb-4" role="alert"><strong>Error!</strong> ' + response.message + '</div>');
        }
    }
</script>


