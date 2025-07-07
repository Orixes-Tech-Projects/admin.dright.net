<?php

namespace App\Controllers;

use App\Models\Crud;
use App\Models\Invoices;

class Cron extends BaseController
{
    var $data = array();

    public function check_builder_profiles_subscription_billing()
    {
        helper('invoice');

        $Crud = new Crud();
        $Invoices = new Invoices();
        $SQL = 'SELECT "profiles"."UID", "profiles"."Name", "profiles"."Type"
            FROM public."profiles" 
            WHERE "ExpireDate" IS NOT NULL
            AND (
                "ExpireDate" <= CURRENT_DATE + INTERVAL \'10 days\'
                OR
                "ExpireDate" < CURRENT_DATE
            )';
        $ProfileRecords = $Crud->ExecutePgSQL($SQL);
        if (count($ProfileRecords) > 0) {
            foreach ($ProfileRecords as $PR) {

                $ClientLastInvoice = $Crud->SingleRecord('invoices', array('ProductType' => 'builder', 'Product' => $PR['Type'], 'ProfileUID' => $PR['UID']));
                if (isset($ClientLastInvoice['UID']) && $ClientLastInvoice['Status'] == 'paid') {

                    $InvoiceNo = generate_invoice_number($PR['Name'], 'builder', $PR['Type']);
                    $NewInvoiceArray = array(
                        'SystemDate' => date("Y-m-d H:i:s"),
                        'InvoiceNo' => $InvoiceNo,
                        'ProductType' => 'builder',
                        'Product' => $PR['Type'],
                        'ProfileUID' => $PR['UID'],
                        'PackageUID' => $ClientLastInvoice['PackageUID'],
                        'OriginalPrice' => $ClientLastInvoice['OriginalPrice'],
                        'Discount' => $ClientLastInvoice['Discount'],
                        'Price' => $ClientLastInvoice['Price'],
                        'Status' => 'un-paid'
                    );
                    $RecordID = $Crud->AddRecord("invoices", $NewInvoiceArray);
                    if (isset($RecordID) && $RecordID > 0) {
                        $Invoices->UpdateBuilderProfilesExpiryDate($PR['UID'], $RecordID);
                    }
                }
            }
        }
    }
}
