<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('ExtendedProfileFilters');
$URL = $FullName = $Status = '';
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
                    <form method="post" name="AllExtendedFilterForm" id="AllExtendedFilterForm"
                          onsubmit="SearchFilterFormSubmit('AllExtendedFilterForm');">
                        <div class="row mt-2">
                            <div class="col-md-3 ">
                                <label class="form-control-label no-padding-right">SubDomain Url:</label>
                                <input type="text" id="URL" name="URL" placeholder=" URL"
                                       class="form-control " value="<?= $URL; ?>"
                                       data-validation-engine="validate[required]"
                                />
                            </div>
                            <div class="col-md-3">
                                <label class="form-control-label no-padding-right">Full Name</label>
                                <input type="text" id="FullName" name="FullName" placeholder=" FullName"
                                       class="form-control " value="<?= $FullName; ?>"
                                       data-validation-engine="validate[required]"
                                />
                            </div>
                            <div class="col-md-3">
                                <div class="form-group row">
                                    <label class="col-sm-4">Status</label>
                                    <div class="col-sm-12">
                                        <select id="Status" name="Status" class="form-control"
                                                data-validation-engine="validate[required]">
                                            <option value="">Please Select</option>

                                            <option value="active" <?= (isset($Status) && $Status == 'active') ? 'selected' : '' ?>
                                            >Active
                                            </option>
                                            <option value="block" <?= (isset($Status) && $Status == 'block') ? 'selected' : '' ?>
                                            >Block
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" style="margin-top: 33px;">
                                <button style="border-radius: 0.2rem !important;" class="btn btn-outline-success btn-sm"
                                        onclick="SearchFilterFormSubmit('AllExtendedFilterForm');"
                                        type="button">Search!
                                </button>
                                <button style="border-radius: 0.2rem !important;" class="btn btn-outline-danger btn-sm"
                                        onclick="ClearAllFilter('ExtendedProfileFilters');"
                                        type="button">Clear
                                </button>
                            </div>
                            <div class="col-md-12 mt-3" id="FilterResponse"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-3">
    <div class="card-header">
        <h4>List Of All Extended / Online Pharmacy Profiles
            <button style="float: right; border-radius: 5px;" type="button" onclick="AddProfile()"
                    class="btn btn-primary btn-sm">
                Add Profile
            </button>
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="record" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1">#</th>
                    <th data-priority="2">Name</th>
                    <th data-priority="9">City</th>
                    <th data-priority="3">Database Name</th>
                    <th data-priority="4">SubDomain Url</th>
                    <th data-priority="5">Expire Date</th>
                    <th data-priority="6">Status</th>
                    <th data-priority="8">SMS</th>
                    <th data-priority="7">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#record').DataTable({
                "searching": false,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
                "pageLength": 25,
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

        }

        function ProfileDetail(id) {
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
