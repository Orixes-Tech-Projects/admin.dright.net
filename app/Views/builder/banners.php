<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('GeneralBannersFilters');
$Speciality = $Type = '';
if (isset($SessionFilters['Speciality']) && $SessionFilters['Speciality'] != '') {
    $Speciality = $SessionFilters['Speciality'];
}
if (isset($SessionFilters['Type']) && $SessionFilters['Type'] != '') {
    $Type = $SessionFilters['Type'];
}
?>
<div class="row mb-3">
    <div class="col-md-12">
        <div class="accordion accordion-primary custom-accordion">
            <div class="accordion-row <?= ((isset($SessionFilters) && $SessionFilters != '' && count($SessionFilters) > 0) ? 'open' : '') ?>">
                <a href="#" class="accordion-header">
                    <span>Search Filters <small>( Click to Search Record From Filters )</small></span>
                    <i class="accordion-status-icon close fa fa-chevron-up"></i>
                    <i class="accordion-status-icon open fa fa-chevron-down"></i>
                </a>
                <div class="accordion-body">
                    <form method="post" name="AllGeneralBannersFilterForm" id="AllGeneralBannersFilterForm"
                          onsubmit="SearchFilterFormSubmit('AllGeneralBannersFilterForm');">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-control-label no-padding-right">Speciality</label>
                                    <select class="form-control" id="SearchSpeciality" name="speciality">
                                        <option value="">Select Speciality</option>
                                        <?php foreach ($specialities as $record) { ?>
                                            <option value="<?= $record['UID'] ?>"><?= ucwords($record['Name']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <div class="row">
                                        <label>Type</label>
                                        <select class="form-control" id="type" name="type">
                                            <option value="">Select Type</option>
                                            <option <?= (($Type == 'custom-text') ? 'selected' : '') ?>
                                                    value="custom-text">Custom Text
                                            </option>
                                            <option <?= (($Type == 'image-only') ? 'selected' : '') ?>
                                                    value="image-only">Image Only
                                            </option>
                                            <option <?= (($Type == 'pre-designed') ? 'selected' : '') ?>
                                                    value="pre-designed">Pre Designed
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5" style="margin-top: 33px;">
                                    <button style="border-radius: 5px;" class="btn btn-outline-success btn-sm"
                                            onclick="SearchFilterFormSubmit('AllGeneralBannersFilterForm');"
                                            type="button">Search!
                                    </button>
                                    <button style="border-radius: 5px;" class="btn btn-outline-danger btn-sm"
                                            onclick="ClearAllFilter('GeneralBannersFilters');"
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
<div class="card">
    <div class="card-header">
        <h4>List Of All General Banners
            <button style="float: right; border-radius: 5px;" type="button" onclick="AddBanner()"
                    class="btn btn-primary btn-sm" data-toggle="modal"
                    data-target="#exampleModal">Add General Banners
            </button>
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="record" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1" width="5%">#</th>
                    <th data-priority="3">Type</th>
                    <th data-priority="4">Specialty</th>
                    <th data-priority="6">Alignment</th>
                    <th data-priority="2" width="12%">Image</th>
                    <th data-priority="5" width="10%">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php echo view('builder/modal/add_banner'); ?>
<script>
    $(document).ready(function () {
        $("select#SearchSpeciality").select2();

        const Speciality = "<?=$Speciality?>";
        if (Speciality != '') {
            $("select#SearchSpeciality").val(Speciality).trigger('change');
        }

        $('#record').DataTable({
            "scrollCollapse": true,
            "searching": false,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
            "pageLength": 25,
            "autoWidth": true,
            "ajax": {
                "url": "<?= $path ?>builder/get-banners",
                "type": "POST"
            }
        });
    });

</script>
<script>
    function AddBanner() {
        $('#AddBannerModal').modal('show');
    }

    function DeleteBanner(id) {
        if (confirm("Are you Sure You want to Delete this Permanently ?")) {
            var response = AjaxResponse("builder/delete-banner", "id=" + id);
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
        var rslt = AjaxResponse('builder/banners_search_filter', data);
        if (rslt.status == 'success') {
            $("#AllGeneralBannersFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }

    function ClearAllFilter(Session) {
        var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
        if (rslt.status == 'success') {
            $("#AllGeneralBannersFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }
</script>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>
