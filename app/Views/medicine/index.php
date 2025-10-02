<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<?php

$session = session();
$SessionFilters = $session->get('MedicineFilters');
$MedicineName = '';
if (isset($SessionFilters['MedicineName']) && $SessionFilters['MedicineName'] != '') {
    $MedicineName = $SessionFilters['MedicineName'];
}
?>
<style>
    .card .card-header {
        margin-bottom: 0;
        border-bottom: 1px solid #ebebeb;
    }

    .modal-header {
        margin-bottom: 0;
        border-bottom: 1px solid #ebebeb !important;
    }
</style>
<div class="card">
    <div class="card-header">
        <h4>Medicine
            <span style="float: right;">
                <button type="button" onclick="AddMedicine()"
                        class="btn btn-primary "
                        data-toggle="modal" data-target="#exampleModal">
              Add Medicine
            </button>
           </span></h4>
    </div>
    <div class="card-body">
        <div class="row d-none">
            <div class="col-md-12">
                <h5>Search Filters</h5>
                <hr>
                <form method="post" name="AllMedicineFilterForm" id="AllMedicineFilterForm"
                      onsubmit="SearchFilterFormSubmit('AllMedicineFilterForm');">
                    <div class="form-group">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label class="form-control-label no-padding-right">Medicine Name:</label>
                                <input type="text" id="MedicineName" name="MedicineName" placeholder="Medicine Name"
                                       class="form-control " value="<?= $MedicineName; ?>"
                                       data-validation-engine="validate[required]"
                                       data-errormessage="MAC Address is required"/>
                            </div>
                            <div class="form-group col-md-12" style="float: right">
                                 <span style="float: right;">
                                    <button class="btn btn-outline-primary" onclick="ClearAllFilter('MedicineFilters');"
                                            type="button">Clear</button>

                                <button class="btn btn-outline-success"
                                        onclick="SearchFilterFormSubmit('AllMedicineFilterForm');"
                                        type="button">Search!</button>
                                 </span>
                            </div>
                            <div class="mt-4" id="FilterResponse"></div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="mt-4" id="Response"></div>
        <table id="frutis" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th data-priority="1" width="5%">#</th>
                <th data-priority="2" width="15%">Formulation</th>
                <th data-priority="3">Name</th>
                <th data-priority="5" width="15%">Strength / Unit</th>
                <th data-priority="4" width="8%">Actions</th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<?php echo view('medicine/modal/add'); ?>
<?php echo view('medicine/modal/update'); ?>
<script>

    $(document).ready(function () {
        $('#frutis').DataTable({
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[50, 100, 500, 1000, -1], [50, 100, 500, 1000, 'All']],
            "pageLength": 50,
            "autoWidth": true,
            "ajax": {
                "url": "<?= $path ?>medicine/fetch_medicine",
                "type": "POST"
            }
        });
    });

    function AddMedicine() {

        const form = document.getElementById('AddMedicineForm');
        form.reset();
        form.classList.remove('was-validated');
        $('#ajaxResponse').html('');

        $('#AddMedicineModal').modal('show');
    }

    function UpdateMedicine(id) {

        const Items = AjaxResponse("medicine/get_medicine_record", "id=" + id);
        $('#UpdateMedicineModal form#UpdateMedicineForm input#UID').val(Items.record.UID);
        $('#UpdateMedicineModal form#UpdateMedicineForm input#MedicineTitle').val(Items.record.MedicineTitle);
        $('#UpdateMedicineModal form#UpdateMedicineForm input#DosageForm').val(Items.record.DosageForm);
        $('#UpdateMedicineModal form#UpdateMedicineForm input#Packing').val(Items.record.Packing);
        $('#UpdateMedicineModal').modal('show');
    }

    function DeleteMedicine(id) {
        if (confirm("Are you Sure U want to Delete this?")) {
           const response = AjaxResponse("medicine/delete", "id=" + id);
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
        var rslt = AjaxResponse('medicine/search_filter', data);
        if (rslt.status == 'success') {
            $("#AllMedicineFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }

    function ClearAllFilter(Session) {
        var rslt = AjaxResponse('home/clear_session', 'SessionName=' + Session);
        if (rslt.status == 'success') {
            $("#AllMedicineFilterForm form #FilterResponse").html(rslt.message);
            location.reload();
        }
    }
</script>

<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>
