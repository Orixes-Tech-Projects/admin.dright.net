<br>
<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">

<div class="card">
    <div class="card-body">
        <h4>Extended Default Configration
            <span style="float: right;">            <button type="button" onclick="AddDefaultConfig()"
                                                            class="btn btn-primary "
                                                            data-toggle="modal" data-target="#exampleModal">
              Add
            </button>
           </span>
        </h4>
    </div>
    <div class="table-responsive">
        <table id="record" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Sr. No</th>
                <th>Name</th>
                <th>Key</th>

                <th >Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th>Sr. No</th>
                <th>Name</th>
                <th>Key</th>
                <th >Actions</th>
            </tr>
            <div class="mt-5" id="Response"></div>

            </tfoot>
        </table>
    </div>

    <?php echo view('extended/modal/add_default_config'); ?>
    <?php echo view('extended/modal/update_default_config'); ?>

    <script>
        $(document).ready(function () {
            $('#record').DataTable({
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
                    "url": "<?= $path ?>extended/fetch_default_config",
                    "type": "POST"
                }
            });
        });

    </script>
    <script>
        function AddDefaultConfig() {
            $('#AddDefaultConfigModal').modal('show');

        }
        function UpdateDefaultConfig(id) {
            var Items = AjaxResponse("extended/get_default_config_record", "id=" + id);

            // Set form fields
            $('#UpdateDefaultConfigModal form#UpdateDefaultConfigForm input#UID').val(Items.record.UID);
            $('#UpdateDefaultConfigModal form#UpdateDefaultConfigForm input#Name').val(Items.record.Name);
            $('#UpdateDefaultConfigModal form#UpdateDefaultConfigForm input#Key').val(Items.record.Key);
            $('#UpdateDefaultConfigModal form#UpdateDefaultConfigForm textarea#Description').val(Items.record.Description);
            $('#UpdateDefaultConfigModal').modal('show');

        }
        function DeleteDefaultconfig(id) {
            if (confirm("Are you Sure You want to Delete this Permanently ?")) {
                response = AjaxResponse("extended/delete_default_config", "id=" + id);
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
    <script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>
