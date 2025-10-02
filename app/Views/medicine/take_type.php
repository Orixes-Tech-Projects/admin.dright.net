<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<style>
    .modal-header {
        margin-bottom: 0;
        border-bottom: 1px solid #ebebeb !important;
    }
</style>
<div class="card">
    <div class="card-header">
        <h4>Medicine Take Type
            <span style="float: right;">
                <button type="button" onclick="AddMedicineTakeType()"
                        class="btn btn-primary "
                        data-toggle="modal" data-target="#exampleModal">
              Add Take Type
            </button>
           </span></h4>
    </div>
    <div class="card-body">
        <div class="mt-2" id="Response"></div>
        <table id="frutis" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th width="5%">#</th>
                <th>English Name</th>
                <th>Urdu Name</th>
                <th width="10%">Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<?php echo view('medicine/modal/add_take_type'); ?>
<?php echo view('medicine/modal/update_take_type'); ?>

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
                "url": "<?= $path ?>medicine/fetch_medicine_take_type",
                "type": "POST"
            }
        });
    });

    function AddMedicineTakeType() {

        $('#AddTakeTypeForm')[0].reset();
        $('#AddTakeTypeForm').removeClass('was-validated');
        $('#ajaxResponse').html('');

        $('#AddMedicineTakeTypeModal').modal('show');
    }

    function UpdateMedicineTakeType(id) {

        var Items = AjaxResponse("medicine/get_medicine_take_type_record", "id=" + id);

        $('#UpdateTakeTypeMedicineModal form#UpdateTakeTypeMedicineForm input#UID').val(Items.record.UID);
        $('#UpdateTakeTypeMedicineModal form#UpdateTakeTypeMedicineForm input#TakeTypeEng').val(Items.record.TakeTypeEng);
        $('#UpdateTakeTypeMedicineModal form#UpdateTakeTypeMedicineForm input#TakeType').val(Items.record.TakeType);
        $('#UpdateTakeTypeMedicineModal').modal('show');
    }

    function DeleteMedicineTakeType(id) {

        if (confirm("Are you Sure U want to Delete this?")) {
            const response = AjaxResponse("medicine/delete_take_type", "id=" + id);
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
