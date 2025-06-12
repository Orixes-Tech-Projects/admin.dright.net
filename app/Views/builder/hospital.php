<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('HospitalFilters');
$Name = '';
$City = '';
if (isset($SessionFilters['Name']) && $SessionFilters['Name'] != '') {
    $Name = $SessionFilters['Name'];
}
if (isset($SessionFilters['City']) && $SessionFilters['City'] != '') {
    $City = $SessionFilters['City'];
}
?>
<style>
    .form-control {
        border-radius: 0.2rem !important;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="accordion accordion-primary custom-accordion">
            <div class="accordion-row <?= ((isset($SessionFilters) && $SessionFilters != '' && count($SessionFilters) > 0) ? 'open' : '') ?>">
                <a href="#" class="accordion-header">
                    <span>Search Filters <small>( Click to Search Record From Filters )</small></span>
                    <i class="accordion-status-icon close fa fa-chevron-up"></i>
                    <i class="accordion-status-icon open fa fa-chevron-down"></i>
                </a>
                <div class="accordion-body">
                    <form method="post" name="AllHospitalFilterForm" id="AllHospitalFilterForm"
                          onsubmit="SearchFilterFormSubmit('AllHospitalFilterForm');">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-control-label no-padding-right">Name:</label>
                                    <input type="text" id="Name" name="Name" placeholder="Name"
                                           class="form-control " value="<?= $Name; ?>"
                                           data-validation-engine="validate[required]"
                                           data-errormessage="MAC Address is required"/>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <label>City:</label>
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
                                <div class="col-md-6" style="margin-top: 33px;">
                                    <button style="border-radius: 5px;" class="btn btn-outline-success btn-sm"
                                            onclick="SearchFilterFormSubmit('AllHospitalFilterForm');"
                                            type="button">Search!
                                    </button>
                                    <button style="border-radius: 5px;" class="btn btn-outline-danger btn-sm" onclick="ClearAllFilter('HospitalFilters');"
                                            type="button">Clear
                                    </button>
                                </div>
                                <div class="mt-4 col-md-12" id="FilterResponse"></div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-2">
    <div class="card-header">
        <h5>List Of All Hospitals
            <button style="float: right; border-radius:5px;" type="button" onclick="AddHospital()"
                    class="btn btn-primary btn-sm">
                Add Hospital
            </button>
        </h5>
    </div>
    <div style="padding: 0.8rem !important;" class="card-body">
        <div class="table-responsive">
            <table id="hospital" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1">#</th>
                    <th data-priority="2">Name</th>
                    <th data-priority="5">Email</th>
                    <th data-priority="6">City</th>
                    <th data-priority="3">SubDomain</th>
                    <th data-priority="7">Last Visit</th>
                    <th data-priority="8">SMS Credits</th>
                    <th data-priority="4">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo view('builder/modal/add_individual_banner'); ?>
</div>
<script>
    $(document).ready(function () {
        $('#hospital').DataTable({
            "scrollCollapse": true,
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
            "pageLength": 25,
            "autoWidth": true,
            "ajax": {
                "url": "<?= $path ?>builder/fetch_hospitals",
                "type": "POST"
            }
        });
    });

</script>
<script>
    function AddHospital() {
        location.href = "<?=$path?>builder/add-hospital";
    }

    function Updatehospital(id) {
        location.href = "<?=$path?>builder/update-hospital/" + id;
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

        function AddTheme(id) {
            location.href = "<?=$path?>builder/add_theme/" + id;

        }

        function DeleteHospital(id) {
            if (confirm("Are you Sure U want to Delete this?")) {
                response = AjaxResponse("builder/delete-hospital", "id=" + id);
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
            var rslt = AjaxResponse('builder/hospital_search_filter', data);
            if (rslt.status == 'success') {
                $("#AllHospitalFilterForm form #FilterResponse").html(rslt.message);
                location.reload();
            }
        }

        function ClearAllFilter(Session) {
            var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
            if (rslt.status == 'success') {
                $("#AllHospitalFilterForm form #FilterResponse").html(rslt.message);
                location.reload();
            }
        }
    </script>

    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>
