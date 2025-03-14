<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('DoctorFilters');
$Name = '';
$City = '';
if (isset($SessionFilters['Name']) && $SessionFilters['Name'] != '') {
    $Name = $SessionFilters['Name'];
}
if (isset($SessionFilters['City']) && $SessionFilters['City'] != '') {
    $City = $SessionFilters['City'];
}
?>

<div class="card">
    <div class="card-header mt-3">
        <h3>Doctors
            <span style="float: right;">
                <button type="button" onclick="AddDoctor()"
                        class="btn btn-primary "
                        data-toggle="modal" data-target="#exampleModal">
              Add Doctor
            </button>
           </span>
        </h3>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h5>Search Filters</h5>
                <hr>
                <form method="post" name="AllDoctorFilterForm" id="AllDoctorFilterForm"
                      onsubmit="SearchFilterFormSubmit('AllDoctorFilterForm');">
                    <div class="form-group">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label no-padding-right">Name:</label>
                                <input type="text" id="Name" name="Name" placeholder="Name"
                                       class="form-control " value="<?= $Name; ?>"
                                       data-validation-engine="validate[required]"
                                       data-errormessage="MAC Address is required"/>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-sm-4">City:</label>
                                    <div class="col-sm-12">
                                        <select id="City" name="City" class="form-control"
                                                data-validation-engine="validate[required]">
                                            <option value="">Please Select</option>
                                            <?php foreach ($Cities as $record) { ?>
                                                <option value="<?= $record['UID'] ?>" <?= (isset($City) && $City == $record['UID']) ? 'selected' : '' ?>
                                                ><?= ucwords($record['FullName']); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12" style="float: right">
                                 <span style="float: right;">
                                    <button class="btn btn-outline-primary" onclick="ClearAllFilter('DoctorFilters');"
                                            type="button">Clear</button>

                                <button class="btn btn-outline-success"
                                        onclick="SearchFilterFormSubmit('AllDoctorFilterForm');"
                                        type="button">Search!</button>
                                 </span>
                                <div class="mt-5" id="FilterResponse"></div>

                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="doctor" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <div class="mt-5" id="Telemedicine"></div>
                    <div class="mt-5" id="AddSmsCreditsResponse"></div>

                    <th>Sr No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Telemedicine Credits</th>
                    <th>SMS Credits</th>
                    <th>Last Visit Date</th>

                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                <tr>
                    <th>Sr No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>City</th>
                    <th>Telemedicine Credits</th>
                    <th>SMS Credits</th>
                    <th>Last Visit Date</th>

                    <th>Actions</th>
                </tr>
                <div class="mt-5" id="Response"></div>

                </tfoot>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#doctor').DataTable({
            "scrollY": "800px",
            "scrollCollapse": true,
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[100, 500, 1000, -1], [100, 500, 1000, 'All']],
            "pageLength": 100,
            "autoWidth": true,
            "ajax": {
                "url": "<?= $path ?>builder/get-doctor",
                "type": "POST"
            }
        });
    });

</script>
<script>
    function AddDoctor() {
        location.href = "<?=$path?>builder/add-doctor";


    }

    function AddTheme(id) {
        location.href = "<?=$path?>builder/add_theme/" + id;

    }

    function EditDoctors(id) {
        location.href = "<?=$path?>builder/update-doctor/" + id;

    }

    function AddTeleMedicineCredits(id, newcredits) {

        if (confirm("Are You Want To Add " + newcredits + " Telemedicine Credits")) {

            response = AjaxResponse('builder/add_telemedicine_credits', "id=" + id + "&newcredits=" + newcredits);

            if (response.status == 'success') {
                $("#TelemedicineResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Add Successfully!</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                $("#TelemedicineResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error! Not Added</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    }

    function AddSmsCredits(id, newcredits) {

        if (confirm("Are You Want To Add " + newcredits + " SMS Credits")) {

            response = AjaxResponse('builder/add_sms_credits', "id=" + id + "&newcredits=" + newcredits);

            if (response.status == 'success') {
                $("#AddSmsCreditsResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Added Successfully!</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                $("#AddSmsCreditsResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error! Not Added</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        }
    }


    function DeleteDoctor(id) {
        if (confirm("Are you Sure U want to Delete this?")) {
            response = AjaxResponse("builder/delete-doctor", "id=" + id);
            if (response.status == 'success') {
                $("#Response").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Deleted Successfully!</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            } else {
                $("#Response").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error! Not Deleted</strong>  </div>')
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }

        }
    }

    function SearchFilterFormSubmit(parent) {

        var data = $("form#" + parent).serialize();
        var rslt = AjaxResponse('builder/doctor_search_filter', data);
        if (rslt.status == 'success') {
            $("#AllDoctorFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }

    function ClearAllFilter(Session) {
        var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
        if (rslt.status == 'success') {
            $("#FilterResponse").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Clear Successfully!</strong>  </div>')
            location.reload();
        }
    }
</script>

<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>
