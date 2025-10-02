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
        <h4>Medicine Form
            <span style="float: right;">
                <button type="button" onclick="AddMedicineForms()"
                        class="btn btn-primary "
                        data-toggle="modal" data-target="#exampleModal">
              Add Medicine Forms
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
<?php echo view('medicine/modal/add_forms'); ?>
<?php echo view('medicine/modal/update_forms'); ?>
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
                "url": "<?= $path ?>medicine/fetch_medicine_forms",
                "type": "POST"
            }
        });
    });

    function AddMedicineForms() {

        const form = $('#AddFormsForm')[0];
        form.reset();
        $('#AddFormsForm').removeClass('was-validated');
        $('#ajaxResponse').html('');

        $('#AddMedicineFormsModal').modal('show');
    }

    function UpdateMedicineForms(id) {
        var Items = AjaxResponse("medicine/get_medicine_form_record", "id=" + id);

        $('#UpdateFormsMedicineModal form#UpdateFormsMedicineForm input#UID').val(Items.record.UID);
        $('#UpdateFormsMedicineModal form#UpdateFormsMedicineForm input#EngName').val(Items.record.EngName);
        $('#UpdateFormsMedicineModal form#UpdateFormsMedicineForm input#Name').val(Items.record.Name);

        $('#UpdateFormsMedicineModal').modal('show');
    }

    function DeleteMedicineForms(id) {
        if (confirm("Are you Sure U want to Delete this?")) {
            response = AjaxResponse("medicine/delete_form", "id=" + id);
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
