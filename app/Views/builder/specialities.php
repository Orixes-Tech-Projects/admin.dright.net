<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">

<div class="card">
    <div class="card-header">
        <h4>List Of All Specialities
            <button style="float: right; border-radius: 5px;" type="button" onclick="LoadAddSpecialitiesModal()"
                    class="btn btn-primary btn-sm"
                    data-toggle="modal" data-target="#exampleModal">
                Add Speciality
            </button>
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="record" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1" width="5%">#</th>
                    <th data-priority="2" width="10%">Icon</th>
                    <th data-priority="3">Name</th>
                    <th data-priority="5" width="12%">Total Images</th>
                    <th data-priority="4" width="10%">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo view('builder/modal/add_specialities'); ?>
<?php echo view('builder/modal/update_specialities'); ?>
<?php echo view('builder/modal/add_heading_specialities'); ?>


<script>
    $(document).ready(function () {
        $('#record').DataTable({
            "scrollCollapse": true,
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
            "pageLength": 25,
            "autoWidth": true,
            "ajax": {
                "url": "<?= $path ?>builder/fetch_specialities",
                "type": "POST"
            }
        });
    });

</script>
<script>

    function AddGallery(id) {
        location.href = "<?=$path?>builder/specialities-gallery/" + id;
    }

    function Deletespecialities(id) {
        if (confirm("Are you Sure You want to Delete this Permanently ?")) {
            response = AjaxResponse("builder/delete_specialities", "id=" + id);
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
