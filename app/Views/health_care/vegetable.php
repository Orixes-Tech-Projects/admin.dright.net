<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">

<div class="card">
    <div class="card-body">
        <h6 class="card-title mb-0">Vegetable</h6>
    </div>
    <div class="table-responsive">
        <table id="vegetable" class="table table-striped table-bordered">
            <thead>            <tr>
                <th>Sr No</th>
                <th>Image</th>
                <th>Name</th>
                <th>Urdu Name</th>
                <th>Nutritional Items</th>
                <!--                <th>Actions</th>-->
            </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
            <tr>
                <th>Sr No</th>
                <th>Image</th>
                <th>Name</th>
                <th>Urdu Name</th>
                 <th>Nutritional Items</th>
                <!--                <th>Actions</th>-->
            </tr>
            </tfoot>
        </table>
    </div>

    <script>
        $(document).ready(function (){
            $('#vegetable').DataTable({
                "scrollY": "800px",
                "scrollCollapse": true,
                "searching": false,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "lengthMenu": [[100, 500, 1000, -1], [100, 500, 1000, 'All']],
                "pageLength": 100,
                "autoWidth": true,
                "ajax": {
                    "url": "<?= $path ?>vegetable-data",
                    "type": "POST"
                }
            });});

    </script>

    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>