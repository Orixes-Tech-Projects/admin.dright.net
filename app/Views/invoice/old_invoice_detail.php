<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
<style>

    body{margin-top:20px;
        background-color:#eee;
    }

    .card {
        box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
    }
    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 0 solid rgba(0,0,0,.125);
        border-radius: 1rem;
    }



</style>

            <div class="card">
                <div class="card-body">
                    <div class="invoice-title">
                        <h4 class="float-end font-size-15">Invoice #<?php echo isset($InvoiceDetail['UID'])?$InvoiceDetail['UID']:'00';?> <span style="float: right;">
                <button type="button" onclick="AddItemforInvoice('<?php echo $InvoiceDetail['UID']?>')"
                        class="btn btn-primary "
                        data-toggle="modal" data-target="#exampleModal">
              Add Item
            </button>
           </span></h4>
                        <div class="mb-4">
                            <h2 class="mb-1 text-muted">Bootdey.com</h2>
                        </div>
                        <div class="text-muted">
                            <p class="mb-1">3184 Spruce Drive Pittsburgh, PA 15201</p>
                            <p class="mb-1"><i class="uil uil-envelope-alt me-1"></i> xyz@987.com</p>
                            <p><i class="uil uil-phone me-1"></i> 012-345-6789</p>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="text-muted">
                                <h5 class="font-size-16 mb-3">Billed To:</h5>
                                <h5 class="font-size-15 mb-2"><?php echo isset($InvoiceDetail['Name'])?$InvoiceDetail['Name']:'';?></h5>
                                <p class="mb-1"><?php echo isset($InvoiceDetail['Address'])?$InvoiceDetail['Address']:'';?></p>
                                <p class="mb-1"><?php echo isset($InvoiceDetail['Email'])?$InvoiceDetail['Email']:'';?></p>
                                <p><?php echo isset($InvoiceDetail['PhoneNumber'])?$InvoiceDetail['PhoneNumber']:'';?></p>
                            </div>
                        </div>
                        <!-- end col -->
                        <div class="col-sm-6">
                            <div class="text-muted text-sm-end">
                                <div>
                                    <h5 class="font-size-15 mb-1">Invoice No:</h5>
                                    <p><?php echo isset($InvoiceDetail['InvoiceID'])?$InvoiceDetail['InvoiceID']:'00';?></p>
                                </div>
                                <div class="mt-4">
                                    <h5 class="font-size-15 mb-1">Invoice Date:</h5>
                                    <p>12 Oct, 2020</p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="py-2">
                        <h5 class="font-size-15">Summary</h5>

                        <div class="table-responsive">
                            <table class="table align-middle table-nowrap table-centered mb-0">
                                <thead>
                                <tr>
                                    <th style="width: 70px;">No.</th>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th class="text-end" style="width: 120px;">Total</th>
                                </tr>
                                </thead><!-- end thead -->
                                <tbody>
                                <tr>
                                    <th scope="row">01</th>
                                    <td>
                                        <div>
                                            <h5 class="text-truncate font-size-14 mb-1">Black Strap A012</h5>
                                            <p class="text-muted mb-0">Watch, Black</p>
                                        </div>
                                    </td>
                                    <td>$ 245.50</td>
                                    <td>1</td>
                                    <td class="text-end">$ 245.50</td>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <th scope="row">02</th>
                                    <td>
                                        <div>
                                            <h5 class="text-truncate font-size-14 mb-1">Stainless Steel S010</h5>
                                            <p class="text-muted mb-0">Watch, Gold</p>
                                        </div>
                                    </td>
                                    <td>$ 245.50</td>
                                    <td>2</td>
                                    <td class="text-end">$491.00</td>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <th scope="row" colspan="4" class="text-end">Sub Total</th>
                                    <td class="text-end">$732.50</td>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <th scope="row" colspan="4" class="border-0 text-end">
                                        Discount :</th>
                                    <td class="border-0 text-end">- $25.50</td>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <th scope="row" colspan="4" class="border-0 text-end">
                                        Shipping Charge :</th>
                                    <td class="border-0 text-end">$20.00</td>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <th scope="row" colspan="4" class="border-0 text-end">
                                        Tax</th>
                                    <td class="border-0 text-end">$12.00</td>
                                </tr>
                                <!-- end tr -->
                                <tr>
                                    <th scope="row" colspan="4" class="border-0 text-end">Total</th>
                                    <td class="border-0 text-end"><h4 class="m-0 fw-semibold">$739.00</h4></td>
                                </tr>
                                <!-- end tr -->
                                </tbody><!-- end tbody -->
                            </table><!-- end table -->
                        </div><!-- end table responsive -->
                        <div class="d-print-none mt-4">
                            <div class="float-end">
                                <a href="javascript:window.print()" class="btn btn-success me-1"><i class="fa fa-print"></i></a>
                                <a href="#" class="btn btn-primary w-md">Send</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
<script src="<?= $template ?>vendors/select2/js/select2.min.js"></script>
<script src="<?= $template ?>vendors/dataTable/datatables.min.js"></script>
<script src="<?= $template ?>assets/js/examples/datatable.js"></script>
<script src="<?= $template ?>vendors/prism/prism.js"></script>
<?php echo view('invoice/modal/invoice_detail_items'); ?>

<script>
    function AddItemforInvoice(id) {
        // Set the value of the input field with id InvoiceID
        $('#ItemInvoiceDetailModal form#AddItemInvoiceDetail input#InvoiceID').val(id);

        // Show the modal
        $('#ItemInvoiceDetailModal').modal('show');
    }

</script>