<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $InvoiceDetail['InvoiceNo'] ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        /* Print-specific styles */
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            flex: 1;
            display: flex;
            flex-direction: column;
            width: 100%;
            height: 100%;
            box-shadow: none;
            border-radius: 0;
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px;
            background: linear-gradient(50deg, #667eeab5 0%, #764ba2 100%);
            color: white;
        }

        .logo {
            max-width: 180px;
            filter: brightness(100%) invert(0);
        }

        .invoice-info {
            text-align: right;
        }

        /* Main Content */
        .invoice-content {
            flex: 1;
            padding: 0 30px;
        }

        /* Footer Section */
        .footer {
            padding: 10px 0px;
            background: #f8f9fa;
            text-align: center;
            border-top: 1px solid #e9ecef;
            font-size: 11px;
            color: #495057;
            margin-top: auto;
        }

        .invoice-number {
            font-size: 16px;
            opacity: 0.9;
            font-weight: bold;
        }

        .date {
            font-size: 14px;
            opacity: 0.8;
        }

        .customer-details {
            padding: 30px 0px;
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
        }

        .section-title {
            font-size: 18px;
            color: #4a5568;
            margin-bottom: 15px;
            font-weight: 600;
            position: relative;
            padding-bottom: 8px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 3px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .detail-item {
            margin-bottom: 5px;
        }

        .detail-label {
            font-weight: 500;
            color: #718096;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th {
            background: #6877e1;
            color: white !important;
            padding: 10px 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .text-right {
            text-align: right;
        }

        .total-row {
            font-weight: 600;
            background-color: #f8fafcc2 !important;
        }

        .total-row td {
            border-top: 2px solid #e2e8f0;
            border-bottom: none;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .grand-total {
            background-color: #f1f5f9 !important;
            font-size: 15px;
        }

        .grand-total td {
            border-top: 2px solid #cbd5e0;
        }

        .payment-details {
            padding: 20px 30px;
            background: #fff;
            border-top: 1px solid #f1f5f9;
        }

        .payment-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .company-address {
            line-height: 1.6;
            margin-bottom: 0px;
        }

        @media print {
            body {
                height: auto;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .invoice-container {
                box-shadow: none;
                border-radius: 0;
                page-break-after: avoid;
                page-break-inside: avoid;
            }

            .footer {
                position: fixed;
                bottom: 40px;
                width: 100%;
            }
            .footer-contact {
                position: fixed;
                bottom: 0;
                width: 100%;
            }

            /* Ensure tables don't break across pages */
            table {
                page-break-inside: avoid;
            }

            /* Remove URL for printed documents */
            a[href]:after {
                content: none !important;
            }
        }

        .invoice-status {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .invoice-status:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0.1;
        }

        .status-paid {
            color: #166534;
            background-color: #dcfce7;
        }

        .status-paid:after {
            background: linear-gradient(45deg, #22c55e, #86efac);
        }

        .status-partiallypaid {
            color: #854d0e;
            background-color: #fef9c3;
        }

        .status-partiallypaid:after {
            background: linear-gradient(45deg, #f59e0b, #fcd34d);
        }

        .status-unpaid {
            color: #991b1b;
            background-color: #fee2e2;
        }

        .status-unpaid:after {
            background: linear-gradient(45deg, #ef4444, #fca5a5);
        }

        .footer-contact {
            background: linear-gradient(50deg, #667eeab5 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .contact-item i {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <!-- Header Section -->
    <div class="header">
        <div class="logo-container">
            <img src="<?= $template ?>assets/logo.png" alt="Company Logo" class="logo">
        </div>
        <div class="invoice-info">
            <div class="invoice-number"><?= $InvoiceDetail['InvoiceNo'] ?></div>
            <div class="date">Issued: <?= date("d M, Y", strtotime($InvoiceDetail['SystemDate'])) ?></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="invoice-content">
        <!-- Customer Details -->
        <div class="customer-details">
            <h3 class="section-title">Bill To</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Customer Name</div>
                    <div><?= (($InvoiceDetail['ProductType'] == 'builder') ? $ClientDetails['Name'] : $ClientDetails['FullName']) ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div><?= ((isset($ClientDetails['Email']) && $ClientDetails['Email'] != '') ? $ClientDetails['Email'] : '-') ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Phone</div>
                    <div><?= ((isset($ClientDetails['ContactNo']) && $ClientDetails['ContactNo'] != '') ? $ClientDetails['ContactNo'] : '-') ?></div>
                </div>
            </div>
        </div>

        <!-- Package Details Table -->
        <table>
            <thead>
            <tr>
                <th>Package</th>
                <th>Org.Price</th>
                <th>Discount</th>
                <th class="text-right">Amount</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?= $PackageDetail['Name'] ?></td>
                <td><?= money($InvoiceDetail['OriginalPrice'], false) ?></td>
                <td><?= $InvoiceDetail['Discount'] ?>%</td>
                <td class="text-right"><?= money($InvoiceDetail['Price'], false) ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">Subtotal:</td>
                <td class="text-right"><?= money($InvoiceDetail['Price'], false) ?></td>
            </tr>
            <tr class="total-row">
                <td colspan="3" class="text-right">Tax:</td>
                <td class="text-right">0</td>
            </tr>
            <tr class="total-row grand-total">
                <td colspan="3" class="text-right">Total Amount:</td>
                <td class="text-right">
                    <?= money($InvoiceDetail['Price']) ?>
                </td>
            </tr>
            <tr class="total-row grand-total">
                <td colspan="3" class="text-right">Payment Status:</td>
                <td class="text-right">
                    <span class="invoice-status <?= 'status-' . str_replace('-', '', strtolower($InvoiceDetail['Status'])) ?>">
                        <?= ucwords(str_replace('-', ' ', $InvoiceDetail['Status'])) ?>
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <?php
    if (count($PaymentDetails) > 0) {
        ?>
        <div class="payment-details">
            <h3 class="section-title">Payment Information</h3>
            <?php
            foreach ($PaymentDetails as $PD) {
                ?>
                <div class="payment-grid">
                    <div class="detail-item">
                        <div class="detail-label">Date</div>
                        <div><?= date("d M, Y", strtotime($PD['Date'])) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Payment Mode</div>
                        <div><?= ucwords(str_replace('-', ' ', $PD['PaymentMode'])) ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Amount</div>
                        <div><?= money($PD['Amount'], false) ?></div>
                        <!--                        <div><span class="status status-paid">Paid</span></div>-->
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <!-- Footer -->
    <div class="footer">
        <div class="company-address">
            <i class="fas fa-map-marker-alt fa-lg" style="font-size: 1rem; margin-right: 5px;" title="Address"></i>
            Basement 132, Block C, Civic Center Phase 4 Bahria Town, Islamabad
        </div>
    </div>
    <div class="footer-contact" style="background: linear-gradient(50deg, #667eeab5 0%, #764ba2 100%);color: white;border-radius:0px !important;padding: 10px 0px;text-align: center;">
        <div style="display: flex;justify-content: center;gap: 25px;flex-wrap: wrap;">
            <a href="tel:+0518484883" target="_blank" style="color: white; text-decoration: none;">
                <i class="fas fa-phone fa-lg" style="font-size: 1rem;" title="Call"> 051-8484883</i>
            </a>
            <a href="tel:+923033330023" style="color: white; text-decoration: none;">
                <i class="fas fa-mobile fa-lg" style="font-size: 1rem;" title="Call Us"> 0303-3330023</i>
            </a>
            <a href="mailto:info@dright.net" style="color: white; text-decoration: none;">
                <i class="fas fa-envelope fa-lg" style="font-size: 1rem;" title="Email Us"> info@dright.net</i>
            </a>
            <a href="https://www.dright.net" target="_blank" style="color: white; text-decoration: none;">
                <i class="fas fa-globe fa-lg" style="font-size: 1rem;" title="Visit Website"> www.dright.net</i>
            </a>
        </div>
    </div>
</div>

<script>
    // Automatically trigger print when page loads
    window.onload = function () {
        setTimeout(function () {
            window.print();
        }, 500);

        // For better UX - close the window after printing (optional)
        window.onafterprint = function () {
            window.close();
        };
    };
</script>
</body>
</html>