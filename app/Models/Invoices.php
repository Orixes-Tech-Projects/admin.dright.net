<?php

namespace App\Models;

use CodeIgniter\Model;

class Invoices extends Model
{
    public
    function UpdateProductsProfileStatusToActive($InvoiceUID)
    {

        $Crud = new Crud();
        $InvoiceRecord = $Crud->SingleRecord('invoices', array('UID' => $InvoiceUID));
        if (isset($InvoiceRecord['UID']) && $InvoiceRecord['UID'] > 0) {
            if ($InvoiceRecord['ProductType'] == 'builder') {
                $Crud->UpdateeRecord('public."profiles"', ['Status' => 'active'], ['UID' => $InvoiceRecord['ProfileUID']]);
            } else {
                if ($InvoiceRecord['Product'] == 'hospitals') {
                    $Crud->UpdateRecord('extended_profiles', ['Status' => 'active'], ['UID' => $InvoiceRecord['ProfileUID']]);
                } else {
                    $Crud->UpdateRecord('pharmacy_profiles', ['Status' => 'active'], ['UID' => $InvoiceRecord['ProfileUID']]);
                }
            }
        }
    }

    public
    function AddProfileSubscriptionDetails($InvoiceDetailsArray = array())
    {

        $Crud = new Crud();
        $InvoiceNo = generate_invoice_number(
            $InvoiceDetailsArray['ProfileName'],
            $InvoiceDetailsArray['ProductType'],
            $InvoiceDetailsArray['Product']
        );

        $record = array();
        $record['InvoiceNo'] = $InvoiceNo;
        $record['ProductType'] = $InvoiceDetailsArray['ProductType'];
        $record['Product'] = $InvoiceDetailsArray['Product'];
        $record['ProfileUID'] = $InvoiceDetailsArray['ProfileUID'];
        $record['PackageUID'] = $InvoiceDetailsArray['PackageUID'];
        $record['OriginalPrice'] = $InvoiceDetailsArray['OriginalPrice'];
        $record['Discount'] = $InvoiceDetailsArray['Discount'];
        $record['Price'] = $InvoiceDetailsArray['Price'];
        $record['Status'] = 'un-paid';
        $RecordID = $Crud->AddRecord("invoices", $record);
        if (isset($RecordID) && $RecordID > 0) {

            if ($InvoiceDetailsArray['ProductType'] == 'extended') {
                $this->UpdateExtendedProfilesExpiryDate($InvoiceDetailsArray['ProfileUID'], $InvoiceDetailsArray['Product'], $RecordID);
            } else {
                $this->UpdateBuilderProfilesExpiryDate($InvoiceDetailsArray['ProfileUID'], $RecordID);
            }
        }

    }

    public function UpdateExtendedProfilesExpiryDate($profileUID, $product, $invoiceId)
    {
        $Crud = new Crud();
        $table = ($product == 'hospitals') ? 'extended_profiles' : 'pharmacy_profiles';

        $invoice = $Crud->SingleRecord("invoices", ['UID' => $invoiceId]);
        $package = $Crud->SingleRecord("items", ['UID' => $invoice['PackageUID']]);

        if ($package) {
            $startDate = date('Y-m-d');
            if ($package['Type'] == 'monthly') {
                $newExpiry = date('Y-m-d', strtotime("+{$package['NoOfMonths']} months", strtotime($startDate)));
            } else {
                $newExpiry = date('Y-m-d', strtotime("+1 year", strtotime($startDate)));
            }

            if ($table == 'extended_profiles') {
                $Crud->UpdateRecord($table, ['ExpireDate' => $newExpiry, 'Status' => 'block'], ['UID' => $profileUID]);
            } else {

                $LicenseCode = '';
                $PharmacyProfileData = $Crud->SingleRecord("pharmacy_profiles", ['UID' => $profileUID]);
                if (isset($PharmacyProfileData['MAC']) && $PharmacyProfileData['MAC'] != '') {
                    $MAC = $PharmacyProfileData['MAC'];
                    $license = array();
                    $license['RAND'] = rand(1000000000, 9999999999);
                    $license['ExpireDate'] = $newExpiry;
                    $license['CODE'] = md5($MAC);
                    $license['MAC'] = $MAC;
                    $LicenseCode = base64_encode(json_encode($license));
                }

                $Crud->UpdateRecord($table, ['ExpireDate' => $newExpiry, 'Status' => 'block', 'LicenseCode' => $LicenseCode], ['UID' => $profileUID]);
            }
        }
    }

    public function UpdateBuilderProfilesExpiryDate($profileUID, $invoiceId)
    {
        $Crud = new Crud();
        $table = 'public."profiles"';

        $invoice = $Crud->SingleRecord("invoices", ['UID' => $invoiceId]);
        $package = $Crud->SingleRecord("items", ['UID' => $invoice['PackageUID']]);

        if ($package) {
            $startDate = date('Y-m-d');
            if ($package['Type'] == 'monthly') {
                $newExpiry = date('Y-m-d', strtotime("+{$package['NoOfMonths']} months", strtotime($startDate)));
            } else {
                $newExpiry = date('Y-m-d', strtotime("+1 year", strtotime($startDate)));
            }

            $Crud->UpdateeRecord($table, ['ExpireDate' => $newExpiry, 'Status' => 'block'], ['UID' => $profileUID]);
        }
    }

    public function GetAllInvoicesRecord($keyword, $length = -1, $start = 0)
    {
        $Crud = new Crud();
        $SQL = 'SELECT `invoices`.*, `items`.`Name` AS "PackageName", `items`.`Price` AS "PackagePrice"
                FROM `invoices`
                JOIN `items` ON (`items`.`UID` = `invoices`.`PackageUID`)
                WHERE `Archive` = \'0\'';
        if ($keyword != '') {
            $SQL .= ' AND (`Name` LIKE \'%' . $keyword . '%\' OR `Email` LIKE \'%' . $keyword . '%\' OR `PhoneNumber` LIKE \'%' . $keyword . '%\')';
        }
        $SQL .= ' ORDER BY `SystemDate` DESC';
        if ($length != -1) {
            $SQL .= ' LIMIT ' . $length . ' OFFSET  ' . $start . '';
        }
        $invoices = $Crud->ExecuteSQL($SQL);


        if ($length != -1) {
            $builderProfiles = [];
            $mysqlHospitalProfiles = [];
            $mysqlPharmacyProfiles = [];

            foreach ($invoices as $invoice) {
                if ($invoice['ProductType'] == 'builder') {
                    $builderProfiles[] = $invoice['ProfileUID'];
                } else {
                    if ($invoice['Product'] == 'hospitals') {
                        $mysqlHospitalProfiles[] = $invoice['ProfileUID'];
                    } else {
                        $mysqlPharmacyProfiles[] = $invoice['ProfileUID'];
                    }
                }
            }

            if (!empty($builderProfiles)) {
                $pgProfiles = $this->fetchPostgresProfiles($builderProfiles);
            }

            if (!empty($mysqlHospitalProfiles)) {
                $mysqlHospitalProfiles = $this->fetchMysqlHospitalProfiles($mysqlHospitalProfiles);
            }

            if (!empty($mysqlPharmacyProfiles)) {
                $mysqlProfiles = $this->fetchMysqlPharmacyProfiles($mysqlPharmacyProfiles);
            }

            foreach ($invoices as &$invoice) {
                if ($invoice['ProductType'] == 'builder') {
                    $invoice['ProfileName'] = $pgProfiles[$invoice['ProfileUID']] ?? 'Unknown';
                } else {

                    if($invoice['Product'] == 'hospitals'){
                        $invoice['ProfileName'] = $mysqlHospitalProfiles[$invoice['ProfileUID']] ?? 'Unknown';
                    }else{
                        $invoice['ProfileName'] = $mysqlProfiles[$invoice['ProfileUID']] ?? 'Unknown';
                    }
                }
            }
        }

        return $invoices;
    }

    private function fetchPostgresProfiles(array $profileIds)
    {
        $Crud = new Crud();
        $profiles = [];
        if (empty($profileIds)) {
            return [];
        }

        $sanitizedIds = array_map(function ($id) {
            return pg_escape_string($id);
        }, $profileIds);

        $idList = "'" . implode("','", $sanitizedIds) . "'";
        $query = 'SELECT "UID", "Name" FROM public."profiles" WHERE "UID" IN (' . $idList . ') ';
        $result = $Crud->ExecutePgSQL($query);

        if (count($result) > 0) {
            foreach ($result as $r) {
                $profiles[$r['UID']] = $r['Name'];
            }
        }

        return $profiles;
    }

    private function fetchMysqlHospitalProfiles(array $profileIds)
    {
        $Crud = new Crud();
        $profiles = [];
        if (empty($profileIds)) {
            return [];
        }

        $idList = "'" . implode("','", $profileIds) . "'";
        $query = "SELECT `UID`, `FullName` FROM `extended_profiles` WHERE `UID` IN ($idList)";
        $result = $Crud->ExecuteSQL($query);
        foreach ($result as $r) {
            $profiles[$r['UID']] = $r['FullName'];
        }

        return $profiles;
    }

    private function fetchMysqlPharmacyProfiles(array $profileIds)
    {
        $Crud = new Crud();
        $profiles = [];
        if (empty($profileIds)) {
            return [];
        }

        $idList = "'" . implode("','", $profileIds) . "'";
        $query = "SELECT `UID`, `FullName` FROM `pharmacy_profiles` WHERE `UID` IN ($idList)";
        $result = $Crud->ExecuteSQL($query);
        foreach ($result as $r) {
            $profiles[$r['UID']] = $r['FullName'];
        }

        return $profiles;
    }

}


