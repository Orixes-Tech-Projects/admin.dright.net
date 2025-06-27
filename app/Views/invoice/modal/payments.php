<style>
    .payments-record thead, th {
        color: black !important;
    }
</style>
<div class="modal" id="InvoicePaymentsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="min-width: 60%;">
        <div class="modal-content">
            <form method="post" action="" name="InvoicePaymentsForm" id="InvoicePaymentsForm" class="needs-validation"
                  novalidate enctype="multipart/form-data">
                <input type="hidden" name="InvoiceID" id="InvoiceID" value="0">
                <input type="hidden" name="InvoiceAmount" id="InvoiceAmount" value="0">
                <input type="hidden" name="ReceivedAmount" id="ReceivedAmount" value="0">
                <input type="hidden" name="PendingAmount" id="PendingAmount" value="0">
                <div style="border-bottom: 1px solid #dee2e6;" class="modal-header">
                    <h5 class="modal-title">Payments Of Invoice# <span style="font-weight: bold; color: crimson"
                                                                       id="InvoiceNoSpan"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Payment Form Section (shown when not paid) -->
                    <div id="paymentFormSection">
                        <div class="row">
                            <div class="col-md-4">
                                <label>Payment Mode <span style="color: crimson">*</span></label>
                                <select name="PaymentMode" id="PaymentMode" class="form-control" required>
                                    <option value="">Select Mode</option>
                                    <option value="bank-transfer">Bank Transfer</option>
                                    <option value="cash">Cash</option>
                                </select>
                                <div class="invalid-feedback">Please select payment mode</div>
                            </div>
                            <div class="col-md-4">
                                <label>Date <span style="color: crimson">*</span></label>
                                <input type="date" name="Date" id="Date" class="form-control" required>
                                <div class="invalid-feedback">Please select date</div>
                            </div>
                            <div class="col-md-4">
                                <label>Amount <span style="color: crimson">*</span></label>
                                <input type="number" name="Amount" id="Amount" class="form-control" required min="0">
                                <div class="invalid-feedback" id="amountError">Please enter valid amount</div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details Section (shown always) -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div class="alert alert-info">
                                <strong>Invoice Amount:</strong> <span id="displayInvoiceAmount">0</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-success">
                                <strong>Received Amount:</strong> <span id="displayReceivedAmount">0</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning">
                                <strong>Pending Amount:</strong> <span id="displayPendingAmount">0</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment History Section -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h5 class="section-title">Payment History</h5>
                            <div id="paymentHistoryContainer" class="table-responsive">
                                <!-- Payment history will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12" id="PaymentsAjaxResponse"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="addPaymentBtn" onclick="validateAndSubmit()">Add
                        Payment
                    </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function LoadInvoicePaymentModal(InvoiceID, InvoiceNo, InvoiceAmount, ReceivedAmount, Status) {
        const PendingAmount = parseInt(InvoiceAmount) - parseInt(ReceivedAmount);

        $("#InvoiceNoSpan").text(InvoiceNo);
        $("#InvoiceID").val(InvoiceID);
        $("#InvoiceAmount").val(InvoiceAmount);
        $("#ReceivedAmount").val(ReceivedAmount);
        $("#PendingAmount").val(PendingAmount);

        $("#displayInvoiceAmount").text(InvoiceAmount);
        $("#displayReceivedAmount").text(ReceivedAmount);
        $("#displayPendingAmount").text(PendingAmount);

        $("#InvoicePaymentsForm")[0].reset();
        $("#InvoicePaymentsForm").removeClass('was-validated');

        $("#Amount").attr('max', PendingAmount);

        if (Status === 'paid') {
            $("#paymentFormSection").hide();
            $("#addPaymentBtn").hide();
        } else {
            $("#paymentFormSection").show();
            $("#addPaymentBtn").show();
        }

        loadPaymentHistory(InvoiceID);

        $("#InvoicePaymentsModal").modal('show');
    }

    function loadPaymentHistory(InvoiceID) {
        const PaymentDetails = AjaxResponse('invoice/get_invoice_payments_record', 'InvoiceID=' + InvoiceID);
        $("#paymentHistoryContainer").closest('.row').hide();
        if (PaymentDetails != null && PaymentDetails.length > 0) {
            $("#paymentHistoryContainer").closest('.row').show();
            renderPaymentHistory(PaymentDetails);
        }
    }

    function renderPaymentHistory(payments) {
        if (payments.length === 0) {
            // Hide the section when no payments
            $("#paymentHistoryContainer").closest('.row').hide();
            return;
        }

        let html = `
            <table style="margin-bottom: 0rem !important;" class="table table-striped table-hover payments-record">
                <thead style="background: rgba(51, 181, 229, 0.2) !important;">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Payment Mode</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>`;

        payments.forEach((payment, index) => {
            html += `
                <tr>
                    <td><b>${index + 1}</b></td>
                    <td>${payment.Date}</td>
                    <td><b>${payment.PaymentMode}</b></td>
                    <td class="text-right"><b>${payment.Amount}</b></td>
                </tr>`;
        });

        html += `
                </tbody>
            </table>`;

        $("#paymentHistoryContainer").html(html);
    }

    function validateAndSubmit() {
        const form = $("#InvoicePaymentsForm")[0];
        const amountInput = $("#Amount");
        const pendingAmount = parseFloat($("#PendingAmount").val());
        const enteredAmount = parseFloat(amountInput.val()) || 0;

        if (pendingAmount > 0 && enteredAmount <= 0) {
            amountInput[0].setCustomValidity("Amount must be greater than 0");
            $("#amountError").text("Amount must be greater than 0");
            amountInput.addClass('is-invalid');
            form.classList.add('was-validated');
            return;
        }

        if (enteredAmount > pendingAmount) {
            amountInput[0].setCustomValidity("Amount cannot exceed pending amount");
            $("#amountError").text("Amount cannot exceed pending amount");
            amountInput.addClass('is-invalid');
            form.classList.add('was-validated');
            return;
        }

        amountInput[0].setCustomValidity("");
        amountInput.removeClass('is-invalid');

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        AddInvoicePaymentsFormSubmit();
    }

    function AddInvoicePaymentsFormSubmit() {
        const data = $("form#InvoicePaymentsForm").serialize();
        var rslt = AjaxResponse('invoice/invoice_payments_form_submit', data);
        if (rslt.status == 'success') {
            $("#PaymentsAjaxResponse").html('<div class="alert alert-success text-center font-weight-bold">' + rslt.message + '</div>');
            loadPaymentHistory($("#InvoiceID").val());
            setTimeout(function () {
                location.reload();
            }, 2500);
        } else {
            $("#PaymentsAjaxResponse").html('<div class="alert alert-danger text-center font-weight-bold">' + rslt.message + '</div>');
        }
    }

    $("#Amount").on('input', function () {
        const pendingAmount = parseFloat($("#PendingAmount").val());
        const enteredAmount = parseFloat($(this).val()) || 0;

        if (pendingAmount > 0 && enteredAmount <= 0) {
            $(this).addClass('is-invalid');
            $("#amountError").text("Amount must be greater than 0");
        } else if (enteredAmount > pendingAmount) {
            $(this).addClass('is-invalid');
            $("#amountError").text("Amount cannot exceed pending amount");
        } else {
            $(this).removeClass('is-invalid');
        }
    });
</script>