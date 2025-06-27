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
        <h4>List Of All Invoices
            <span style="float: right;">
                <button type="button" onclick="LoadAddInvoiceModal()"
                        class="btn btn-primary">
              Create Invoice
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
                    <th data-priority="2">Invoice#</th>
                    <th data-priority="3">Date</th>
                    <th data-priority="4">Type</th>
                    <th data-priority="5">Profile</th>
                    <th data-priority="8">Package</th>
                    <th data-priority="7">Status</th>
                    <th data-priority="6">Actions</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


    <?php echo view('invoice/modal/add_invoice'); ?>
    <?php echo view('invoice/modal/update_invoice'); ?>
    <?php echo view('invoice/modal/payments'); ?>
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
                    "url": "<?= $path ?>invoice/fetch_invoice",
                    "type": "POST"
                }
            });
        });
    </script>
    <script>
        function Invoice(id) {
            location.href = "<?=$path?>invoice/invoice_detail/" + id;
        }

        function DeleteInvoice(id) {
            if (confirm("Are you Sure U want to Delete this?")) {
                response = AjaxResponse("invoice/delete_invoice", "id=" + id);
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

        function handlePackageChange(ModalID, FormID) {

            const $packageSelect = $(`#${ModalID} #Package`);
            const $originalPrice = $(`#${ModalID} #OriginalPrice`);
            const $discount = $(`#${ModalID} #Discount`);
            const $price = $(`#${ModalID} #Price`);

            $(`#${ModalID} #OrgPriceDiv, #${ModalID} #DiscountDiv, #${ModalID} #PriceDiv`).hide();

            $packageSelect.change(function () {
                if ($(this).val() !== "") {

                    $(`#${ModalID} #OrgPriceDiv, #${ModalID} #DiscountDiv, #${ModalID} #PriceDiv`).show();

                    const PackageID = $(this).val();
                    const PackageRecord = AjaxResponse("support-ticket/get-record-items", "id=" + PackageID);
                    if (PackageRecord.record != '') {
                        $(`#${ModalID} #${FormID} input#OriginalPrice`).val(PackageRecord.record.OriginalPrice);
                        $(`#${ModalID} #${FormID} input#Discount`).val(PackageRecord.record.Discount);
                        $(`#${ModalID} #${FormID} input#Price`).val(PackageRecord.record.Price);
                    }

                } else {
                    // Hide and reset fields
                    $(`#${ModalID} #OrgPriceDiv, #${ModalID} #DiscountDiv, #${ModalID} #PriceDiv`).hide();
                    $originalPrice.val("");
                    $discount.val("0");
                    $price.val("");
                }
            });

            $originalPrice.add($discount).on('input', function () {
                const originalPrice = parseFloat($originalPrice.val()) || 0;
                const discount = parseFloat($discount.val()) || 0;
                const discountedPrice = originalPrice - (originalPrice * (discount / 100));
                $price.val(Math.round(discountedPrice));
            });
        }
    </script>
    <script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
    <script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?= $template ?>assets/js/examples/datatable.js"></script>
    <script src="<?= $template ?>vendors/prism/prism.js"></script>