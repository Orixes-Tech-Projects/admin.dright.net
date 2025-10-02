<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
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
        <h4>Medicine Timing
            <span style="float: right;">
                <button type="button" onclick="AddMedicineTiming()"
                        class="btn btn-primary "
                        data-toggle="modal" data-target="#exampleModal">
              Add Medicine Timings
            </button>
           </span>
        </h4>
    </div>
    <div class="card-body">
        <div class="mt-2" id="Response"></div>
        <table id="frutis" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th width="5%">#</th>
                <th>Eng Name</th>
                <th>Urdu Name</th>
                <th width="10%">Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<?php echo view('medicine/modal/add_timing'); ?>
<?php echo view('medicine/modal/update_timing'); ?>
<script>

    $(document).ready(function () {
        $('#frutis').DataTable({
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[100, 500, 1000, -1], [100, 500, 1000, 'All']],
            "pageLength": 100,
            "autoWidth": true,
            "ajax": {
                "url": "<?= $path ?>medicine/fetch_medicine_timing",
                "type": "POST"
            }
        });
    });

    function AddMedicineTiming() {

        const form = $('#AddTimingMedicineForm')[0];
        form.reset();
        $('#AddTimingMedicineForm').removeClass('was-validated');
        $('#ajaxResponse').html('');

        $('#AddMedicineTimingModal').modal('show');
    }

    function UpdateMedicineTiming(id) {
        var Items = AjaxResponse("medicine/get_medicine_timing_record", "id=" + id);

        $('#UpdateTimingMedicineModal form#UpdateTimingMedicineForm input#UID').val(Items.record.UID);
        $('#UpdateTimingMedicineModal form#UpdateTimingMedicineForm input#EngName').val(Items.record.EngName);
        $('#UpdateTimingMedicineModal form#UpdateTimingMedicineForm input#Name').val(Items.record.Name);
        $('#UpdateTimingMedicineModal form#UpdateTimingMedicineForm input#SortOrder').val(Items.record.SortOrder);
        $('#UpdateTimingMedicineModal').modal('show');
    }

    function DeleteMedicineTiming(id) {
        if (confirm("Are you Sure U want to Delete this?")) {
            response = AjaxResponse("medicine/delete_timing", "id=" + id);
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

</script>

<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>
