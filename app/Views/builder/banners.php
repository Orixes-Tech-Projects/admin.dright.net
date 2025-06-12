<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">

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
                    <th data-priority="5">Alignment</th>
                    <th data-priority="6">Color</th>
                    <th data-priority="4">Specialty</th>
                    <th data-priority="2" width="12%">Image</th>
                    <th data-priority="3" width="10%">Actions</th>
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
</script>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>
