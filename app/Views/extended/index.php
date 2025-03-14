<br>
<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('ExtendedProfileFilters');
$URL='';
$FullName='';
$Status='';
if (isset($SessionFilters['URL']) && $SessionFilters['URL'] != '') {
    $URL = $SessionFilters['URL'];
}
if (isset($SessionFilters['FullName']) && $SessionFilters['FullName'] != '') {
    $FullName = $SessionFilters['FullName'];
}
if (isset($SessionFilters['Status']) && $SessionFilters['Status'] != '') {
    $Status = $SessionFilters['Status'];
}
?>
<div class="card">
    <div class="card-body">
        <h4>Profile
            <span style="float: right;">            <button type="button" onclick="AddProfile()"
                                                            class="btn btn-primary "
                                                            data-toggle="modal" data-target="#exampleModal">
              Add
            </button>
           </span>
        </h4>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h5>Search Filters</h5>
                <hr>
                <form method="post" name="AllExtendedFilterForm" id="AllExtendedFilterForm"
                      onsubmit="SearchFilterFormSubmit('AllExtendedFilterForm');">
                    <div class="form-group">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label no-padding-right">SubDomain Url:</label>
                                <input type="text" id="URL" name="URL" placeholder=" URL"
                                       class="form-control "  value="<?=$URL;?>" data-validation-engine="validate[required]"
                                      />
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-control-label no-padding-right">Full Name</label>
                                <input type="text" id="FullName" name="FullName" placeholder=" FullName"
                                       class="form-control "  value="<?=$FullName;?>" data-validation-engine="validate[required]"
                                      />
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-sm-4">Status<span class="text-danger">*</span></label>
                                    <div class="col-sm-12">
                                        <select id="Status" name="Status" class="form-control"
                                                data-validation-engine="validate[required]">
                                            <option value="">Please Select</option>

                                            <option value="active" <?= (isset($Status) && $Status == 'active') ? 'selected' : '' ?>
                                            >Active</option>
                                            <option value="block" <?= (isset($Status) && $Status == 'block') ? 'selected' : '' ?>
                                            >Block</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-12" style="float: right">
                                 <span style="float: right;">
                                    <button class="btn btn-outline-primary" onclick="ClearAllFilter('ExtendedProfileFilters');"
                                            type="button">Clear</button>

                                <button class="btn btn-outline-success"
                                        onclick="SearchFilterFormSubmit('AllExtendedFilterForm');"
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
        <table id="record" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Sr. No</th>
                <th>Name</th>
                <th>City</th>
                <th>Database Name</th>
<!--                <th>Last Patient Invoice</th>-->
<!--                <th>Last Pharmacy Invoice</th>-->
                <th>SubDomain Url</th>
                <th>Expire Date</th>
                <th>Status</th>
                <th>SMS Credits</th>

                <th >Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th>Sr. No</th>
                <th>Name</th>
                <th>City</th>
                <th>Database Name</th>
<!--                <th>Last Patient Invoice</th>-->
<!--                <th>Last Pharmacy Invoice</th>-->
                <th>SubDomain Url</th>
                <th>Expire Date</th>
                <th>Status</th>
                <th>SMS Credits</th>
                <th >Actions</th>
            </tr>
            <div class="mt-5" id="delResponse"></div>

            </tfoot>
        </table>
    </div>

<!--    --><?php //echo view('extended/modal/add'); ?>

    <script>
        $(document).ready(function () {
            $('#record').DataTable({
                "scrollY": "800px",
                "scrollCollapse": true,
                "searching": false,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "lengthMenu": [[100, 500, 1000, -1], [100, 500, 1000, 'All']],
                "pageLength": 100,
                "autoWidth": true,
                "ajax": {
                    "url": "<?= $path ?>extended/get-profile",
                    "type": "POST"
                }
            });
        });

    </script>
    <script>
        function AddProfile() {
            location.href = "<?=$path?>extended/add-profile";

        }
        function UpdateProfile(id) {
            location.href = "<?=$path?>extended/update-profile/" + id;

        }function ProfileDetail(id) {
            location.href = "<?=$path?>extended/extended_profile_detail/" + id;

        }
        function SearchFilterFormSubmit(parent) {

            var data = $("form#" + parent).serialize();
            var rslt = AjaxResponse('extended/search_filter', data);
            if (rslt.status == 'success') {
                $("#AllExtendedFilterForm form #FilterResponse").html(rslt.message);
                location.reload();
            }
        }

        function ClearAllFilter(Session) {
            var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
            if (rslt.status == 'success') {
                $("#AllExtendedFilterForm form #FilterResponse").html(rslt.message);
                location.reload();
            }
        }
        function DeleteProfile(id) {
            if (confirm("Are you Sure You want to Delete this Permanently ?")) {
                response = AjaxResponse("extended/delete_profile", "id=" + id);
                if (response.status == 'success') {
                    $("#Response").html('<div class="alert alert-success mb-4" style="margin: 10px;" role="alert"> <strong>Deleted Successfully!</strong>  </div>')
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    $("#delResponse").html('<div class="alert alert-danger mb-4" style="margin: 10px;" role="alert"> <strong>Error! Not Deleted</strong>  </div>')
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }

            }
        }
    </script>
    <script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>
