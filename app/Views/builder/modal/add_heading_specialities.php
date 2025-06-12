<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<div class="modal" id="AddHeadingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 50%;">
        <div class="modal-content">
            <form method="post" action="" name="AddHeadingForm" id="AddHeadingForm" class="needs-validation"
                  novalidate=""
                  enctype="multipart/form-data">
                <input type="hidden" name="UID" id="UID" value="0">
                <input type="hidden" name="SpecialityID" id="SpecialityID" value="">
                <input type="hidden" name="meta" id="meta" value="heading"/>
                <div style="border-bottom: 1px solid #dee2e6;" class="modal-header">
                    <h5 class="modal-title">Add Headings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Heading :</label>
                            <input type="text" id="name" name="name" placeholder="Name" class="form-control"
                                   data-validation-engine="validate[required]"/>
                            <button style="border-radius: 5px;float: right;" type="button"
                                    class="btn btn-success btn-sm mt-2"
                                    onclick="FormHeadingSubmit()">Add Heading
                            </button>
                        </div>
                        <div class="mt-2 col-md-12" id="ajaxResponse"></div>
                    </div>
                    <div class="row mt-2" id="SpecialityHeadingDiv"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-radius: 5px;" class="btn btn-primary btn-sm"
                            data-dismiss="modal">Close
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>

<script>

    function AddHeadings(id) {

        LoadSpecialityMetaDiv(id, 'heading', 'SpecialityHeadingDiv');

        $('#AddHeadingModal form#AddHeadingForm input#SpecialityID').val(id);
        $('#AddHeadingModal').modal('show');
    }

    function LoadSpecialityMetaDiv(id, option, div) {
        AjaxRequest("builder/load_speciality_metas_data_grid", "id=" + id + "&option=" + option, div);
    }

    function DeleteSpecialityMetas(id) {

        setTimeout(function () {
            $("#AddHeadingModal #ajaxResponse").html('');
        }, 2500);

        var SpecialityID = $('#AddHeadingModal form#AddHeadingForm input#SpecialityID').val();

        if (confirm("Are you Sure You want to Delete this Permanently ?")) {
            var response = AjaxResponse("builder/delete_specialities_meta", "id=" + id);
            if (response.status == 'success') {
                $("#AddHeadingModal #ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Deleted Successfully!</strong>  </div>')
                setTimeout(function () {
                    LoadSpecialityMetaDiv(SpecialityID, 'heading', 'SpecialityHeadingDiv');
                }, 1000);
            } else {
                $("#AddHeadingModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error! Not Deleted</strong>  </div>')
            }

        }
    }

    function FormHeadingSubmit() {

        setTimeout(function () {
            $("#AddHeadingModal #ajaxResponse").html('');
        }, 2500);

        var Heading = $("#AddHeadingModal form#AddHeadingForm input#name").val();
        var SpecialityID = $('#AddHeadingModal form#AddHeadingForm input#SpecialityID').val();

        if (Heading == '') {
            $("#AddHeadingModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> Heading Required! </div>');
            return false;
        }

        var FormData = $("#AddHeadingModal form#AddHeadingForm").serialize();
        var response = AjaxResponse("builder/submit_specialities_meta", FormData);
        if (response.status === 'success') {
            $("#AddHeadingModal #ajaxResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Success!</strong> ' + response.message + ' </div>');
            setTimeout(function () {
                $("#AddHeadingModal form#AddHeadingForm input#name").val('');
                LoadSpecialityMetaDiv(SpecialityID, 'heading', 'SpecialityHeadingDiv');
            }, 1000);
        } else {
            $("#AddHeadingModal #ajaxResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error!</strong> ' + response.message + ' </div>');
        }
    }
</script>

<script src="<?=$template?>assets/js/examples/form-validation.js"></script>