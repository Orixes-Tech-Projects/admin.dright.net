<?php

namespace App\Models;

use CodeIgniter\Model;

class BuilderModel extends Model
{

    var $data = array();

    public function __construct()
    {
//        $this->data = $this->DefaultVariable();
    }

    public function get_speciality_images_by_id($id)
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `speciality_metas` WHERE `SpecialityUID` = '" . $id . "' AND `Option` != 'heading' AND `Option` != 'short_message'";

        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }

    public function GetThemeSettingsDataByID($id)
    {
        $Crud = new Crud();
        $SQL = 'SELECT *
        FROM "public"."options"  
        where "public"."options"."ProfileUID" = \'' . $id . '\'';
        $Admin = $Crud->ExecutePgSQL($SQL);
        return $Admin;
    }

    public
    function OptionExtra($id, $options = array())
    {
        $db = \Config\Database::connect('website_db');
        $SQL = 'SELECT * FROM "public"."options" 
        where "public"."options"."ProfileUID" = \'' . $id . '\' ';
        if (count($options) > 0) {
            $SQL .= ' AND "Name" IN (\'' . implode("', '", $options) . '\') ';
        }
        $rslt = $db->query($SQL)->getResult('array');
        $records = array();
        foreach ($rslt as $row) {
            $records[$row['Name']] = $row['Description'];
        }
        return ((isset($records)) ? $records : '');
    }

    public function get_profile_options_data_by_id_option($id, $option)
    {
        $Crud = new Crud();
        $SQL = 'SELECT *
        FROM "public"."options"  
        where "public"."options"."ProfileUID" = \'' . $id . '\' And "public"."options"."Name" = \'' . $option . '\'; ';
        $Admin = $Crud->ExecutePgSQL($SQL);
        return $Admin;
    }

    public function get_website_profile_meta_data_by_id_option($id, $option)
    {
        $Crud = new Crud();
        $SQL = 'SELECT *
        FROM "public"."profile_metas"  
        where "public"."profile_metas"."ProfileUID" = \'' . $id . '\' And "public"."profile_metas"."Option" = \'' . $option . '\'; ';
        $Admin = $Crud->ExecutePgSQL($SQL);
        return $Admin;
    }

    public function specialities()
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `specialities` ORDER BY `specialities`.`Name` ASC";
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }

    public function extended_profiles()
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM  `extended_profiles` ORDER BY `extended_profiles`.`FullName` ASC";
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }

    public function get_all_sponsors()
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `sponsors` WHERE `Archive` = '0' ORDER BY `sponsors`.`OrderID` ASC";
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }

    public function get_speciality_meta_data_by_id_option($id, $option)
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `speciality_metas` WHERE `SpecialityUID` = $id AND `Option` = '$option'";
        $Admin = $Crud->ExecuteSQL($SQL);
//        print_r($SQL);exit();
        return $Admin;
    }

    public
    function get_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->general_banners();
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);

        return $records;
    }

    public function general_banners()
    {
        $session = session();
        $SessionFilters = $session->get('GeneralBannersFilters');

        $Crud = new Crud();
        $SQL = 'SELECT `general_banners`.*, `specialities`.`Name` AS Title FROM `general_banners` 
                LEFT JOIN `specialities` ON `general_banners`.`Speciality` = `specialities`.`UID` WHERE 1=1 ';
        if (isset($SessionFilters['Speciality']) && $SessionFilters['Speciality'] != '') {
            $Speciality = $SessionFilters['Speciality'];
            $SQL .= ' AND  `general_banners`.`Speciality` = ' . $Speciality . ' ';
        }
        if (isset($SessionFilters['Type']) && $SessionFilters['Type'] != '') {
            $Type = $SessionFilters['Type'];
            $SQL .= ' AND  `general_banners`.`Type` = \'' . $Type . '\' ';
        }
        $SQL .= ' ORDER BY `general_banners`.`SystemDate` DESC';

        return $SQL;
    }

    public
    function count_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->general_banners();
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_images_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->websites_images();
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);

        return $records;
    }

    public function websites_images()
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `websites_images` ORDER BY `websites_images`.`SystemDate` DESC";
        return $SQL;
    }

    public
    function count_image_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->websites_images();
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_doct_datatables($type, $keyword, $MiniHims = 0, $PromotionalWebsites = 0)
    {
        $Crud = new Crud();

        $SQL = $this->Allprofiless($type, $keyword, $MiniHims, $PromotionalWebsites);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecutePgSQL($SQL);

        return $records;
    }

    public function Allprofiless($ID = 'all', $keyword, $MiniHims = 0, $PromotionalWebsites = 0)
    {
        $Crud = new Crud();
        $session = session();
        if ($ID == 'doctors') {
            $SessionFilters = $session->get('DoctorFilters');
        } else {
            $SessionFilters = $session->get('HospitalFilters');
        }
        $SQL = 'SELECT "profiles"."UID", "profiles"."SystemDate", "profiles"."Type", "profiles"."Name", "profiles"."Email", "profiles"."Password",
                        "profiles"."City", "profiles"."ContactNo", "profiles"."SubDomain",  "profiles"."LastLoginDateTime",  "profiles"."LastVisitDateTime",
                "profiles"."Status", "profiles"."ExpireDate"
                FROM public."profiles" 
                WHERE "profiles"."SubDomain" != \'\' AND "MiniHims" = ' . $MiniHims . ' AND "IsPromotionalWebsite" = ' . $PromotionalWebsites . ' ';
        if ($ID != 'all') {
            $SQL .= ' AND "profiles"."Type" = \'' . $ID . '\' ';
        }
        if (isset($SessionFilters['Name']) && $SessionFilters['Name'] != '') {
            $Name = $SessionFilters['Name'];
            $SQL .= ' AND  public."profiles"."Name"  ILIKE \'%' . $Name . '%\'';
        }
        if (isset($SessionFilters['City']) && $SessionFilters['City'] != '') {
            $City = $SessionFilters['City'];
            $SQL .= ' AND  public."profiles"."City"  =' . $City . ' ';
        }
        if (isset($SessionFilters['Type']) && $SessionFilters['Type'] != '') {
            $Type = $SessionFilters['Type'];
            $SQL .= ' AND  "profiles"."Type" = \'' . $Type . '\' ';
        }
        if ($keyword != '') {
            $SQL .= ' AND public."profiles"."Name"  ILIKE \'%' . $keyword . '%\'   ';
        }
        $SQL .= ' Order By public."profiles"."Name"  ASC';

        return $SQL;
    }

    public
    function count_doct_datatables($type, $keyword, $MiniHims = 0, $PromotionalWebsites = 0)
    {
        $Crud = new Crud();

        $SQL = $this->Allprofiless($type, $keyword, $MiniHims, $PromotionalWebsites);
        $records = $Crud->ExecutePgSQL($SQL);
        return count($records);
    }

    public
    function get_specialities_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->specialitiess($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);

        return $records;
    }

    public function specialitiess($keyword)
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `specialities` WHERE `Archive` = '0' ";

        if ($keyword != '') {
            $SQL .= ' AND  `Name` LIKE \'%' . $keyword . '%\'   ';
//            $SQL .= ' AND  ( `Name` LIKE \'%' . $keyword . '%\'  OR `Tag` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY `Name` ASC';

//        print_r($SQL);exit();
        return $SQL;
    }

    public
    function count_specialities_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->specialitiess($keyword);
        $SQL = 'select count(*) as `UID` from ( ' . $SQL . ' ) as `MASTERTABLE`';
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin[0]['UID'];
    }

    public
    function get_sponser_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->sponser($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public function sponser($keyword)
    {
        $Crud = new Crud();

        $SQL = "SELECT * FROM `sponsors` WHERE `Archive` = '0' ";
        if ($keyword != '') {
            $SQL .= ' AND  `Name` LIKE \'%' . $keyword . '%\'   ';
        }
        $SQL .= ' ORDER BY `Name` ASC';
        return $SQL;
    }

    public
    function count_sponser_datatables($keyword)
    {
        $Crud = new Crud();
        $SQL = $this->sponser($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_sponsor_product_datatables($id, $keyword)
    {
        $Crud = new Crud();

        $SQL = $this->sponsor_product($id, $keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public function sponsor_product($id, $keyword)
    {
        $Crud = new Crud();

        $SQL = "SELECT * FROM `sponsors_products` WHERE `Archive` = '0' AND `SponsorID` = $id";
        if ($keyword != '') {
            $SQL .= ' AND  `Name` LIKE \'%' . $keyword . '%\'   ';
        }
        $SQL .= ' ORDER BY `Name` ASC';
        return $SQL;
    }

    public
    function count_sponsor_product_datatables($id, $keyword)
    {
        $Crud = new Crud();
        $SQL = $this->sponsor_product($id, $keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function GetProfilesScripts($ProfileUID)
    {
        $Crud = new Crud();
        $SQL = 'SELECT "third_party_scripts".* 
                FROM public."third_party_scripts" 
                Where "ProfileUID" = ' . $ProfileUID . ' ORDER BY "Title" ASC';
        $records = $Crud->ExecutePgSQL($SQL);
        return $records;
    }

    public function BuilderAllProfiles()
    {
        $Crud = new Crud();
        $SQL = 'SELECT "profiles"."UID", "profiles"."SystemDate", "profiles"."Type", "profiles"."Name", "profiles"."Email", 
                        "profiles"."Password", "profiles"."City", "profiles"."ContactNo", "profiles"."SubDomain",  
                        "profiles"."LastLoginDateTime",  "profiles"."LastVisitDateTime", "profiles"."Status", "profiles"."ExpireDate"
                FROM public."profiles" 
                WHERE "profiles"."SubDomain" != \'\' AND "MiniHims" = 0 AND "Status" = \'active\' ';
        $SQL .= ' Order By public."profiles"."Name"  ASC';
        $records = $Crud->ExecutePgSQL($SQL);

        return $records;
    }

    public function GetBuilderProfilesDashboardGridDetails($keyword = '', $limit = -1, $start = 0)
    {
        $Crud = new Crud();

        $session = session();
        $SessionFilters = $session->get('BuilderDashboardClientsFilters');

        $SQL = 'SELECT "profiles"."UID", "profiles"."SystemDate", "profiles"."Name", "profiles"."Type", "profiles"."SubDomain", 
               "profiles"."ExpireDate", "profiles"."MiniHims",
               CASE 
                   WHEN EXISTS (SELECT 1 FROM public."options" pm1 
                               WHERE pm1."ProfileUID" = "profiles"."UID" 
                               AND pm1."Name" = \'opd_invoicing\' 
                               AND pm1."Description" = \'1\')
                        OR 
                        EXISTS (SELECT 1 FROM public."options" pm2 
                               WHERE pm2."ProfileUID" = "profiles"."UID" 
                               AND pm2."Name" = \'prescription_module\' 
                               AND pm2."Description" = \'1\')
                   THEN \'VAS\' 
                   ELSE \'Standard\' 
               END AS "ClientLevel",
               (SELECT COUNT(*) FROM public."profile_invoice_subscription" 
                WHERE "ProfileUID" = "profiles"."UID") AS "SubscriptionInvoices",
               COALESCE((SELECT SUM(CAST("Price" AS INTEGER)) FROM public."profile_invoice_subscription" 
                WHERE "ProfileUID" = "profiles"."UID"), 0) AS "SubscriptionInvoicesAmount",
               (SELECT COUNT(*) FROM public."profile_prescription_subscription" 
                WHERE "ProfileUID" = "profiles"."UID") AS "SubscriptionPrescriptions",
               COALESCE((SELECT SUM(CAST("Price" AS INTEGER)) FROM public."profile_prescription_subscription" 
                WHERE "ProfileUID" = "profiles"."UID"), 0) AS "SubscriptionPrescriptionsAmount"
        FROM public."profiles"
        WHERE "profiles"."SubDomain" != \'\' 
            AND "profiles"."Status" = \'active\' 
            AND "profiles"."IsPromotionalWebsite" = 0 AND "profiles"."MiniHims" = 0 ';

        // Date filter
        if (isset($SessionFilters['StartDate']) && $SessionFilters['StartDate'] != ''
            && isset($SessionFilters['EndDate']) && $SessionFilters['EndDate'] != '') {
            $SQL .= ' AND "profiles"."SystemDate" BETWEEN \'' . date("Y-m-d", strtotime($SessionFilters['StartDate'])) . ' 00:00:00\' AND \'' . date("Y-m-d", strtotime($SessionFilters['EndDate'])) . ' 23:59:59\' ';
        }

        // Client Type filter
        if (isset($SessionFilters['ClientType']) && $SessionFilters['ClientType'] != '') {
            $SQL .= ' AND "Type" = \'' . $SessionFilters['ClientType'] . '\' ';
        }

        // Client Level filter
        if (isset($SessionFilters['ClientLevel']) && $SessionFilters['ClientLevel'] != '') {
            if ($SessionFilters['ClientLevel'] == 'VAS') {
                $SQL .= ' AND (EXISTS (SELECT 1 FROM public."options" pm1 
                                  WHERE pm1."ProfileUID" = "profiles"."UID" 
                                  AND pm1."Name" = \'opd_invoicing\' 
                                  AND pm1."Description" = \'1\')
                         OR 
                         EXISTS (SELECT 1 FROM public."options" pm2 
                                  WHERE pm2."ProfileUID" = "profiles"."UID" 
                                  AND pm2."Name" = \'prescription_module\' 
                                  AND pm2."Description" = \'1\')) ';
            } elseif ($SessionFilters['ClientLevel'] == 'Standard') {
                $SQL .= ' AND NOT (EXISTS (SELECT 1 FROM public."options" pm1 
                                      WHERE pm1."ProfileUID" = "profiles"."UID" 
                                      AND pm1."Name" = \'opd_invoicing\' 
                                      AND pm1."Description" = \'1\')
                             OR 
                             EXISTS (SELECT 1 FROM public."options" pm2 
                                      WHERE pm2."ProfileUID" = "profiles"."UID" 
                                      AND pm2."Name" = \'prescription_module\' 
                                      AND pm2."Description" = \'1\')) ';
            }
        }

        $SQL .= ' ORDER BY "profiles"."SystemDate" DESC';

        if ($limit != -1) {
            $SQL .= ' LIMIT ' . $limit . ' OFFSET ' . $start . ' ';
        }

        $records = $Crud->ExecutePgSQL($SQL);
        $invoiceData = $this->getInvoiceDataFromMySQL();

        return $this->mergeProfileWithInvoiceData($records, $invoiceData);
    }

    private function getInvoiceDataFromMySQL()
    {
        $Crud = new Crud();
        $SQL = 'SELECT ProfileUID, Product, 
                   COUNT(UID) as TotalInvoices,
                   SUM(Price) as TotalAmount,
                   SUM(ReceivedAmount) as ReceivedAmount
            FROM invoices WHERE ProductType = \'builder\'
            GROUP BY ProfileUID, Product ';

        return $Crud->ExecuteSQL($SQL);
    }

    private function mergeProfileWithInvoiceData($profiles, $invoiceData)
    {
        // Create a lookup array for invoice data
        $invoiceLookup = [];
        foreach ($invoiceData as $invoice) {
            $key = $invoice['ProfileUID'] . '|' . $invoice['Product'];
            $invoiceLookup[$key] = $invoice;
        }

        // Merge data
        foreach ($profiles as &$profile) {
            $key = $profile['UID'] . '|' . $profile['Type'];

            if (isset($invoiceLookup[$key])) {
                $profile['TotalInvoices'] = $invoiceLookup[$key]['TotalInvoices'];
                $profile['TotalAmount'] = $invoiceLookup[$key]['TotalAmount'];
                $profile['ReceivedAmount'] = $invoiceLookup[$key]['ReceivedAmount'];
            } else {
                $profile['TotalInvoices'] = 0;
                $profile['TotalAmount'] = 0;
                $profile['ReceivedAmount'] = 0;
            }
        }

        return $profiles;
    }

    public function GetDashboardStats()
    {
        $FinalArray = array();
        $Crud = new Crud();

        // Total Clients
        $SQL = 'SELECT COUNT("profiles"."UID") AS "TotalClients"             
        FROM public."profiles"             
        WHERE "profiles"."SubDomain" != \'\'             
        AND "profiles"."Status" = \'active\'             
        AND "profiles"."IsPromotionalWebsite" = 0             
        AND "profiles"."MiniHims" = 0 ';
        $records = $Crud->ExecutePgSQL($SQL);
        $FinalArray['TotalClients'] = $records[0]['TotalClients'];

        // MTD Revenue and Outstanding Value - Make sure to specify database
        $SQL_Stats = 'SELECT                     
        COALESCE(SUM(CASE WHEN MONTH(`SystemDate`) = MONTH(CURRENT_DATE)                       
        AND YEAR(`SystemDate`) = YEAR(CURRENT_DATE)                       
        THEN `Price` ELSE 0 END), 0) AS "MTDRevenue",                    
        COALESCE(SUM(`Price` - `ReceivedAmount`), 0) AS "OutstandingValue"                  
        FROM `invoices`                   
        WHERE (`Price` - `ReceivedAmount`) > 0                   
        OR (MONTH(`SystemDate`) = MONTH(CURRENT_DATE)                       
        AND YEAR(`SystemDate`) = YEAR(CURRENT_DATE))';
        $records_Stats = $Crud->ExecuteSQL($SQL_Stats);
        $FinalArray['MTDRevenue'] = $records_Stats[0]['MTDRevenue'];
        $FinalArray['OutstandingValue'] = $records_Stats[0]['OutstandingValue'];

        // Current Month Expiry - Count profiles expiring in current month
        $SQL_MonthExpiry = 'SELECT COUNT("profiles"."UID") AS "CurrentMonthExpiry"             
        FROM public."profiles"             
        WHERE "profiles"."SubDomain" != \'\'             
        AND "profiles"."Status" = \'active\'             
        AND "profiles"."IsPromotionalWebsite" = 0             
        AND "profiles"."MiniHims" = 0
        AND EXTRACT(MONTH FROM "profiles"."ExpireDate") = EXTRACT(MONTH FROM CURRENT_DATE)
        AND EXTRACT(YEAR FROM "profiles"."ExpireDate") = EXTRACT(YEAR FROM CURRENT_DATE)';
        $records_MonthExpiry = $Crud->ExecutePgSQL($SQL_MonthExpiry);
        $FinalArray['CurrentMonthExpiry'] = $records_MonthExpiry[0]['CurrentMonthExpiry'];

        // Quarter Expiry - Count profiles expiring in current quarter
        $SQL_QuarterExpiry = 'SELECT COUNT("profiles"."UID") AS "QuarterExpiry"             
        FROM public."profiles"             
        WHERE "profiles"."SubDomain" != \'\'             
        AND "profiles"."Status" = \'active\'             
        AND "profiles"."IsPromotionalWebsite" = 0             
        AND "profiles"."MiniHims" = 0
        AND EXTRACT(QUARTER FROM "profiles"."ExpireDate") = EXTRACT(QUARTER FROM CURRENT_DATE)
        AND EXTRACT(YEAR FROM "profiles"."ExpireDate") = EXTRACT(YEAR FROM CURRENT_DATE)';
        $records_QuarterExpiry = $Crud->ExecutePgSQL($SQL_QuarterExpiry);
        $FinalArray['QuarterExpiry'] = $records_QuarterExpiry[0]['QuarterExpiry'];

        // Current Month Inactive Domains - Count blocked profiles that expired in current month
        $SQL_MonthInactive = 'SELECT COUNT("profiles"."UID") AS "CurrentMonthInactive"             
        FROM public."profiles"             
        WHERE "profiles"."SubDomain" != \'\'             
        AND "profiles"."Status" = \'block\'             
        AND "profiles"."IsPromotionalWebsite" = 0             
        AND "profiles"."MiniHims" = 0
        AND EXTRACT(MONTH FROM "profiles"."ExpireDate") = EXTRACT(MONTH FROM CURRENT_DATE)
        AND EXTRACT(YEAR FROM "profiles"."ExpireDate") = EXTRACT(YEAR FROM CURRENT_DATE)';
        $records_MonthInactive = $Crud->ExecutePgSQL($SQL_MonthInactive);
        $FinalArray['CurrentMonthInactive'] = $records_MonthInactive[0]['CurrentMonthInactive'];

        // Quarter Inactive Domains - Count blocked profiles that expired in current quarter
        $SQL_QuarterInactive = 'SELECT COUNT("profiles"."UID") AS "QuarterInactive"             
        FROM public."profiles"             
        WHERE "profiles"."SubDomain" != \'\'             
        AND "profiles"."Status" = \'block\'             
        AND "profiles"."IsPromotionalWebsite" = 0             
        AND "profiles"."MiniHims" = 0
        AND EXTRACT(QUARTER FROM "profiles"."ExpireDate") = EXTRACT(QUARTER FROM CURRENT_DATE)
        AND EXTRACT(YEAR FROM "profiles"."ExpireDate") = EXTRACT(YEAR FROM CURRENT_DATE)';
        $records_QuarterInactive = $Crud->ExecutePgSQL($SQL_QuarterInactive);
        $FinalArray['QuarterInactive'] = $records_QuarterInactive[0]['QuarterInactive'];

        return $FinalArray;
    }

}
