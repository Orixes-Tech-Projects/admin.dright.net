<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('PharmacyFilters');
$MACAddress='';
$FullName='';
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
<div class="card">
    <div class="card-body">
        <h3>Pharmacy
            <span style="float: right;">
                <button type="button" onclick="AddPharmacy()"
                        class="btn btn-primary "
                        data-toggle="modal" data-target="#exampleModal">
              Add
            </button>
           </span></h3>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h5>Search Filters</h5>
                <hr>
                <form method="post" name="AllFilterForm" id="AllFilterForm"
                      onsubmit="SearchFilterFormSubmit('AllFilterForm');">
                    <div class="form-group">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label no-padding-right">FullName:</label>
                                <input type="text" id="FullName"   value="<?=$FullName;?>" name="FullName" placeholder="FullName"
                                       class="form-control " data-validation-engine="validate[required]"
                                      />
                            </div>
                            <div class="form-group col-md-3">

                            <label class="col-sm-12">City:</label>
                                    <div class="col-sm-12">
                                        <select id="City" name="City" class="form-control" data-validation-engine="validate[required]">
                                            <option value="">Please Select</option>
                                            <?php  foreach ($cities as $record) { ?>
                                                <option value="<?= $record['UID'] ?>"
                                                ><?= ucwords($record['FullName']); ?></option>
                                            <?php } ?>                                </select>
                                    </div>
                                </div>

                            <div class="form-group col-md-3">
                                <label class="form-control-label no-padding-right">MAC Address:</label>
                                <input type="text" id="MACAddress"   value="<?=$MACAddress;?>" name="MACAddress" placeholder="MAC Address"
                                       class="form-control " data-validation-engine="validate[required]"
                                       data-errormessage="MAC Address is required"/>
                            </div>
                            <div class="form-group col-md-3">

                            <label class="col-sm-12">Deployment Date *</label>
                                    <div class="col-sm-12">
                                        <input type="date"  id="DeploymentDate" name="DeploymentDate" placeholder="Deployment Date" data-validation-engine="validate[required]" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12" style="float: right">
                                 <span style="float: right;">
                                    <button class="btn btn-outline-primary" onclick="ClearAllFilter('PharmacyFilters');"
                                            type="button">Clear</button>

                                <button class="btn btn-outline-success"
                                        onclick="SearchFilterFormSubmit('AllFilterForm');"
                                        type="button">Search!</button>
                                 </span>
                            </div>
                            <div class="mt-4" id="FilterResponse"></div>

                        </div>
                    </div>

                </form>
            </div>
        </div>

    </div>


    <div class="table-responsive">
        <table id="frutis" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Sr No</th>
                <th>Name</th>
                <th>City</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Sale Agent</th>
                <th>Mac Address</th>
                <th>Expire Date</th>
                <th>Deployment Date</th>

                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th>Sr No</th>
                <th>Name</th>
                <th>City</th>
                <th>Contact Number</th>
                <th>Address</th>
                <th>Sale Agent</th>
                <th>Mac Address</th>
                <th>Expire Date</th>
                <th>Deployment Date</th>

                <th>Actions</th>
            </tr>
            <div class="mt-4" id="Response"></div>

            </tfoot>
        </table>
    </div>
    <?php echo view('pharmacy/modal/add'); ?>
    <?php echo view('pharmacy/modal/update'); ?>
    <?php echo view('pharmacy/modal/pharmacy_license'); ?>
    <script>
        $(document).ready(function () {
            $('#frutis').DataTable({
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

        function LoadLicense(id) {
            var Items = AjaxResponse("pharmacy/get-record", "id=" + id);
            $('#LicenseFormModal form#LicenseForm input#MAC').val(Items.record.MAC);
            $('#LicenseFormModal form#LicenseForm input#ExpireDate').val(Items.record.ExpireDate);
            $('#LicenseFormModal form#LicenseForm textarea#LicenseCode').val(Items.record.LicenseCode);
            $('#LicenseFormModal').modal('show');

        }

        function UpdatePharmacy(id) {
            var Items = AjaxResponse("pharmacy/get-record", "id=" + id);

            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#UID').val(Items.record.UID);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#FullName').val(Items.record.FullName);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#Email').val(Items.record.Email);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#ContactNo').val(Items.record.ContactNo);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#Address').val(Items.record.Address);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#SaleAgent').val(Items.record.SaleAgent);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#DeploymentDate').val(Items.record.DeploymentDate);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#MAC').val(Items.record.MAC);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm input#ExpireDate').val(Items.record.ExpireDate);
            $('#UpdatePharmacyModal form#UpdatePharmacyForm select#City').val(Items.record.City);
            $('#UpdatePharmacyModal').modal('show');
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
