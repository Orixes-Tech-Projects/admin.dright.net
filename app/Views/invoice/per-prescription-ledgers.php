<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<style>
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        background-color: #ededed;
        height: calc(1.5em + .75rem + 3px) !important;
        border-radius: 0.8rem !important;
        padding: 5px 10px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 4px !important;
    }


    .select2-container--default .select2-selection--single {
        border: none !important;
    }

</style>
<div class="card">
    <div class="card-header">
        <h4>List Of Customers Per Prescription Ledgers</h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="record" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1" width="5%">#</th>
                    <th data-priority="2">Profile</th>
                    <th data-priority="8">Type</th>
                    <th data-priority="4">Total Prescription</th>
                    <th data-priority="5">Total Amount</th>
                    <th data-priority="6">Received</th>
                    <th data-priority="7">Status</th>
                    <th data-priority="3">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <?php echo view('invoice/modal/per-prescription-payments'); ?>
    <script>
        $(document).ready(function () {
            $('#record').DataTable({
                "searching": false,
                "processing": true,
                "serverSide": true,
                "responsive": true,
                "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
                "pageLength": 25,
                "autoWidth": true,
                "ajax": {
                    "url": "<?= $path ?>invoice/fetch_per_prescription_ledgers",
                    "type": "POST"
                }
            });
        });
    </script>
    <script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>