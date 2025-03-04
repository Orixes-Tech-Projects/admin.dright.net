<br>
<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<div class="card">
    <div class="card-body">
        <h4>Users
            <span style="float: right;">
                <button type="button" onclick="AddUser()"
                                                            class="btn btn-primary "
                                                            data-toggle="modal" data-target="#exampleModal">
              Add User
            </button>
           </span>
        </h4>
    </div>
    <div class="table-responsive">
        <table id="record" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Sr No</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th>Sr No</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Type</th>
                <th>Actions</th>
            </tr>
            <div class="mt-5" id="Response"></div>

            </tfoot>
        </table>
    </div>
    <?php echo view('users/modal/add'); ?>
    <?php echo view('users/modal/update'); ?>

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
                    "url": "<?= $path ?>users/users-data",
                    "type": "POST"
                }
            });
        });

    </script>
    <script>
        function AddUser() {
            $('#AddUserModal').modal('show');

        }
        function AddAccessLevel(id) {
            location.href = "<?=$path?>users/access_level/" + id;
        }
        function UpdateUser(id) {
            var Items = AjaxResponse("users/get-record", "id=" + id);

            $('#UpdateUserModal form#UpdateUserForm input#UID').val(Items.record.UID);
            $('#UpdateUserModal form#UpdateUserForm input#FullName').val(Items.record.FullName);
            $('#UpdateUserModal form#UpdateUserForm input#Email').val(Items.record.Email);
            $('#UpdateUserModal form#UpdateUserForm input#Password').val(Items.record.Password);
            $('#UpdateUserModal form#UpdateUserForm select#AccessLevel').val(Items.record.AccessLevel);
            $('#UpdateUserModal').modal('show');
        }

        function DeleteUser(id) {
            if (confirm("Are you Sure U want to Delete this?")) {
                response = AjaxResponse("users/delete", "id=" + id);
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
