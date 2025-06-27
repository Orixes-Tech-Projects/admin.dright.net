<link rel="stylesheet" href="<?= $template ?>vendors/dataTable/datatables.min.css" type="text/css">
<link rel="stylesheet" href="<?= $template ?>vendors/select2/css/select2.min.css" type="text/css">
<div class="card">
    <div class="card-header">
        <h4>All Packages
            <span style="float: right;">
                <button type="button" onclick="LoadPackageAddModal()"
                        class="btn btn-primary "
                        data-toggle="modal" data-target="#exampleModal">
              Add Package
            </button>
           </span>
        </h4>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="record" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th data-priority="1" width="5%">#</th>
                    <th data-priority="2">Name</th>
                    <th data-priority="8">Code</th>
                    <th data-priority="3" width="10%">Org.Price</th>
                    <th data-priority="4" width="10%">Discount</th>
                    <th data-priority="5" width="10%">Price</th>
                    <th data-priority="7" width="15%">Type</th>
                    <th data-priority="6" width="10%">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php echo view('support_ticket/modal/add'); ?>
<?php echo view('support_ticket/modal/update'); ?>
<script>
    $(document).ready(function () {
        $('#record').DataTable({
            "searching": true,
            "processing": true,
            "serverSide": true,
            "responsive": true,
            "lengthMenu": [[25, 50, 100, 500, 1000, -1], [25, 50, 100, 500, 1000, 'All']],
            "pageLength": 25,
            "autoWidth": true,
            "ajax": {
                "url": "<?= $path ?>support-ticket/fetch-items",
                "type": "POST"
            }
        });
    });

    function LoadNoOfMonthsDiv(Value, FormType) {

        if (FormType == 'add') {

            $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").addClass('d-none');
            $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").removeClass('col-md-6');
            $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").addClass('col-md-12');

            $("#AddPackageModal form#AddPackageForm div#PackageTypeDiv").removeClass('col-md-6');
            $("#AddPackageModal form#AddPackageForm div#PackageTypeDiv").addClass('col-md-12');

            if (Value == 'monthly') {

                $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").removeClass('d-none');
                $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").addClass('col-md-6');
                $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").removeClass('col-md-12');

                $("#AddPackageModal form#AddPackageForm div#PackageTypeDiv").addClass('col-md-6');
                $("#AddPackageModal form#AddPackageForm div#PackageTypeDiv").removeClass('col-md-12');

            } else {

                $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").addClass('d-none');
                $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").removeClass('col-md-6');
                $("#AddPackageModal form#AddPackageForm div#NoOfMonthsDiv").addClass('col-md-12');

                $("#AddPackageModal form#AddPackageForm div#PackageTypeDiv").removeClass('col-md-6');
                $("#AddPackageModal form#AddPackageForm div#PackageTypeDiv").addClass('col-md-12');
            }
        } else {

            $("#UpdatePackageModal form#UpdatePackageForm div#NoOfMonthsDiv").addClass('d-none');
            $("#UpdatePackageModal form#UpdatePackageForm div#NoOfMonthsDiv").removeClass('col-md-6');
            $("#UpdatePackageModal form#UpdatePackageForm div#NoOfMonthsDiv").addClass('col-md-12');

            $("#UpdatePackageModal form#UpdatePackageForm div#UpdatePackageTypeDiv").removeClass('col-md-6');
            $("#UpdatePackageModal form#UpdatePackageForm div#UpdatePackageTypeDiv").addClass('col-md-12');

            if (Value == 'monthly') {

                $("#UpdatePackageModal form#UpdatePackageForm div#UpdateNoOfMonthsDiv").removeClass('d-none');
                $("#UpdatePackageModal form#UpdatePackageForm div#UpdateNoOfMonthsDiv").addClass('col-md-6');
                $("#UpdatePackageModal form#UpdatePackageForm div#UpdateNoOfMonthsDiv").removeClass('col-md-12');

                $("#UpdatePackageModal form#UpdatePackageForm div#UpdatePackageTypeDiv").addClass('col-md-6');
                $("#UpdatePackageModal form#UpdatePackageForm div#UpdatePackageTypeDiv").removeClass('col-md-12');

            } else {

                $("#UpdatePackageModal form#UpdatePackageForm div#UpdateNoOfMonthsDiv").addClass('d-none');
                $("#UpdatePackageModal form#UpdatePackageForm div#UpdateNoOfMonthsDiv").removeClass('col-md-6');
                $("#UpdatePackageModal form#UpdatePackageForm div#UpdateNoOfMonthsDiv").addClass('col-md-12');

                $("#UpdatePackageModal form#UpdatePackageForm div#UpdatePackageTypeDiv").removeClass('col-md-6');
                $("#UpdatePackageModal form#UpdatePackageForm div#UpdatePackageTypeDiv").addClass('col-md-12');
            }
        }
    }

    function DeleteItem(id) {
        if (confirm("Are you Sure U want to Delete this?")) {
            response = AjaxResponse("support-ticket/delete-item", "id=" + id);
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
<script>
    document.addEventListener('DOMContentLoaded', function () {

        function calculatePriceAfterDiscount(priceInput, discountInput, priceAfterDiscountInput) {
            const price = parseFloat(priceInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            const discountedPrice = price - (price * (discount / 100));
            priceAfterDiscountInput.value = Math.round(discountedPrice);
        }

        const addPriceInput = document.querySelector('#AddPackageForm input[id="OriginalPrice"]');
        const addDiscountInput = document.querySelector('#AddPackageForm input[id="Discount"]');
        const addPriceAfterDiscountInput = document.querySelector('#AddPackageForm input[id="Price"]');
        if (addPriceInput && addDiscountInput && addPriceAfterDiscountInput) {
            addPriceInput.addEventListener('input', function () {
                calculatePriceAfterDiscount(addPriceInput, addDiscountInput, addPriceAfterDiscountInput);
            });
            addDiscountInput.addEventListener('input', function () {
                calculatePriceAfterDiscount(addPriceInput, addDiscountInput, addPriceAfterDiscountInput);
            });
        }

        const updatePriceInput = document.querySelector('#UpdatePackageForm input[id="OriginalPrice"]');
        const updateDiscountInput = document.querySelector('#UpdatePackageForm input[id="Discount"]');
        const updatePriceAfterDiscountInput = document.querySelector('#UpdatePackageForm input[id="Price"]');
        if (updatePriceInput && updateDiscountInput && updatePriceAfterDiscountInput) {
            updatePriceInput.addEventListener('input', function () {
                calculatePriceAfterDiscount(updatePriceInput, updateDiscountInput, updatePriceAfterDiscountInput);
            });
            updateDiscountInput.addEventListener('input', function () {
                calculatePriceAfterDiscount(updatePriceInput, updateDiscountInput, updatePriceAfterDiscountInput);
            });
        }

        $('#AddPackageModal').on('shown.bs.modal', function () {
            if (addPriceInput && addDiscountInput && addPriceAfterDiscountInput) {
                calculatePriceAfterDiscount(addPriceInput, addDiscountInput, addPriceAfterDiscountInput);
            }
        });

        $('#UpdatePackageModal').on('shown.bs.modal', function () {
            if (updatePriceInput && updateDiscountInput && updatePriceAfterDiscountInput) {
                calculatePriceAfterDiscount(updatePriceInput, updateDiscountInput, updatePriceAfterDiscountInput);
            }
        });
    });
</script>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>

