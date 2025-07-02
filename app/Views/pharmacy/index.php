<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('PharmacyFilters');
$MACAddress = '';
$FullName = '';
if (isset($SessionFilters['MACAddress']) && $SessionFilters['MACAddress'] != '') {
    $MACAddress = $SessionFilters['MACAddress'];
}
if (isset($SessionFilters['FullName']) && $SessionFilters['FullName'] != '') {
    $FullName = $SessionFilters['FullName'];
}
if (isset($SessionFilters['City']) && $SessionFilters['City'] != '') {
    $City = $SessionFilters['City'];
}
if (isset($SessionFilters['DeploymentDate']) && $SessionFilters['DeploymentDate'] != '') {
    $DeploymentDate = $SessionFilters['DeploymentDate'];
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="accordion accordion-primary custom-accordion">
            <div class="accordion-row <?= ((isset($SessionFilters) && $SessionFilters != '' && count($SessionFilters) > 0) ? 'open' : '') ?>">
                <a href="#" class="accordion-header">
                    <span>Search Filters <small>( Click to Search Records From Filter )</small></span>
                    <i class="accordion-status-icon close fa fa-chevron-up"></i>
                    <i class="accordion-status-icon open fa fa-chevron-down"></i>
                </a>
                <div class="accordion-body">
                    <form method="post" name="AllFilterForm" id="AllFilterForm"
                          onsubmit="SearchFilterFormSubmit('AllFilterForm');">
                        <div class="row mt-2">
                            <div class="form-group col-md-3">
                                <label class="form-control-label no-padding-right">FullName:</label>
                                <input type="text" id="FullName" value="<?= $FullName; ?>" name="FullName"
                                       placeholder="FullName"
                                       class="form-control " data-validation-engine="validate[required]"
                                />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-sm-12">City:</label>
                                <div class="col-sm-12">
                                    <select id="City" name="City" class="form-control"
                                            data-validation-engine="validate[required]">
                                        <option value="">Please Select</option>
                                        <?php foreach ($cities as $record) { ?>
                                            <option value="<?= $record['UID'] ?>"
                                            ><?= ucwords($record['FullName']); ?></option>
                                        <?php } ?>                                </select>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="col-sm-12">Deployment Date *</label>
                                <div class="col-sm-12">
                                    <input type="date" id="DeploymentDate" name="DeploymentDate"
                                           placeholder="Deployment Date" data-validation-engine="validate[required]"
                                           class="form-control"/>
                                </div>
                            </div>
                            <div class="form-group col-md-3" style="text-align: right; margin-top: 28px;">
                                <button class="btn btn-outline-success"
                                        onclick="SearchFilterFormSubmit('AllFilterForm');"
                                        type="button">Search!
                                </button>
                                <button class="btn btn-outline-primary" onclick="ClearAllFilter('PharmacyFilters');"
                                        type="button">Clear
                                </button>
                            </div>
                            <div class="mt-4" id="FilterResponse"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header">
        <h3>List Of All Offline Pharmacy Profiles
            <button style="float: right;" type="button" onclick="AddPharmacy()"
                    class="btn btn-primary "
                    data-toggle="modal" data-target="#exampleModal">
                Add Profile
            </button>
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="frutis" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1">#</th>
                    <th data-priority="2">Name</th>
                    <th data-priority="8">City</th>
                    <th data-priority="3">Contact</th>
                    <th>Mac Address</th>
                    <th data-priority="7">Deployment Date</th>
                    <th data-priority="4">Expire Date</th>
                    <th data-priority="5">Status</th>
                    <th data-priority="6">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo view('pharmacy/modal/add'); ?>
    <?php echo view('pharmacy/modal/update'); ?>
    <?php echo view('pharmacy/modal/pharmacy_license'); ?>
    <script>
        $(document).ready(function () {
            $('#frutis').DataTable({
                "searching": true,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
                "pageLength": 25,
                "autoWidth": true,
                "ajax": {
                    "url": "<?= $path ?>pharmacy/pharmacy-data",
                    "type": "POST"
                }
            });
        });

    </script>
    <script>
        function AddPharmacy() {
            $('#AddPharmacyModal').modal('show');

        }

        function DeletePharmacy(id) {
            if (confirm("Are you Sure U want to Delete this?")) {
                response = AjaxResponse("pharmacy/delete", "id=" + id);
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
            var rslt = AjaxResponse('pharmacy_profile_search_filter', data);
            if (rslt.status == 'success') {
                $("#AllFilterForm form #FilterResponse").html(rslt.message);
                location.reload();
            }
        }

        function ClearAllFilter(Session) {
            var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
            if (rslt.status == 'success') {
                $("#AllFilterForm form #FilterResponse").html(rslt.message);
                location.reload();
            }
        }
    </script>

    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>
