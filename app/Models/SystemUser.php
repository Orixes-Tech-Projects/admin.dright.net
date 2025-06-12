<?php

namespace App\Models;

use CodeIgniter\Model;

class SystemUser extends Model
{

    var $data = array();

    public function __construct()
    {
//        $this->data = $this->DefaultVariable();
    }


    public function systemusers($keyword)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `system_users` where `Archive`=\'0\' AND `Email`!=\'info@orixestech.com\' ';
        if ($keyword != '') {
            $SQL .= ' AND  ( `FullName` LIKE \'%' . $keyword . '%\'  OR `Email` LIKE \'%' . $keyword . '%\' OR `AccessLevel` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY `SystemDate` DESC';
        return $SQL;
    }

    public function invoice($keyword)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `invoices` where `Archive`=\'0\'  ';
        if ($keyword != '') {
            $SQL .= ' AND  ( `Name` LIKE \'%' . $keyword . '%\'  OR `Email` LIKE \'%' . $keyword . '%\' OR `PhoneNumber` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY `SystemDate` DESC';
        return $SQL;
    }
    public function customer($keyword)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `invoice_customers` where `Archive`=\'0\'  ';
        if ($keyword != '') {
            $SQL .= ' AND  ( `Name` LIKE \'%' . $keyword . '%\'  OR `Email` LIKE \'%' . $keyword . '%\' OR `PhoneNumber` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY `SystemDate` DESC';
        return $SQL;
    }

    public function items()
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `items` where 1=1  ';

        $SQL .= ' ORDER BY `Name` DESC';
        $records = $Crud->ExecuteSQL($SQL);

        return $records;
    }      public function allcustomer()
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `invoice_customers`where `Archive`=\'0\'  ';

        $SQL .= ' ORDER BY `Name` ASC';
        $records = $Crud->ExecuteSQL($SQL);

        return $records;
    }

    public function system_user_roll($id)
    {
        $Crud = new Crud();
        $SQL = 'SELECT `AccessID` FROM `system_users_access` where `UserID`=\'' . $id . '\'  ';
        $records = $Crud->ExecuteSQL($SQL);

        return $records;
    }


    public function DietAdminCategoryList($keyword)
    {

        $Crud = new Crud();
//        $SQL = 'SELECT * FROM `admin_log` where `Archive`=\'0\' Order By `DiseaseName` ASC';

        $SQL = 'SELECT admin_updates.*, system_users.FullName as fullname  FROM admin_updates 
    LEFT JOIN system_users ON admin_updates.EditBy = system_users.UID
        WHERE 1=1

';
        if ($keyword != '') {
            $SQL .= ' AND  admin_updates.`Description` LIKE \'%' . $keyword . '%\'  ';
//            $SQL .= ' AND  ( `Name` LIKE \'%' . $keyword . '%\'  OR `Tag` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY admin_updates.`ApprovedBy` DESC';
        return $SQL;
    }

    public function AdminActivityList($keyword)
    {

        $Crud = new Crud();
//        $SQL = 'SELECT * FROM `admin_log` where `Archive`=\'0\' Order By `DiseaseName` ASC';

        $SQL = 'SELECT admin_log.*, system_users.*  FROM admin_log 
    LEFT JOIN system_users ON admin_log.LoggedUserID = system_users.UID
        WHERE 1=1

';
        if ($keyword != '') {
            $SQL .= ' AND  admin_log.`Description` LIKE \'%' . $keyword . '%\'  ';
//            $SQL .= ' AND  ( `Name` LIKE \'%' . $keyword . '%\'  OR `Tag` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY admin_log.`SystemDate` DESC';
        return $SQL;
    }

    public
    function get_users_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->systemusers($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_users_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->systemusers($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_invoice_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->invoice($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_invoice_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->invoice($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_invoice_customer_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->customer($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_invoice_customer_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->customer($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public function get_admin_activity_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->AdminActivityList($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public function count_admin_activity_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->AdminActivityList($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public function get_diet_admin_category_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->DietAdminCategoryList($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public function count_diet_admin_category_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->DietAdminCategoryList($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public function checkAccessKey($key)
    {
        if ($_SESSION['Email'] == 'info@orixestech.com' || $_SESSION['Email'] == 'jawad@holisticsolutions.com.pk') {
            return true;
        } else {
            $Crud = new Crud();
            $SQL = 'SELECT * FROM system_users_access 
                    WHERE AccessID IN ( SELECT UID FROM admin_access WHERE `AccessKey` = \'' . $key . '\' )
                    And UserID = \'' . $_SESSION['UID'] . '\' ';
            $Admin = $Crud->ExecuteSQL($SQL);
            if (count($Admin) > 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function checkAccessKeyforsession($key)
    {


        // Check if the session has already stored all valid access keys
        if (!isset($_SESSION['access_keys'])) {
            // Fetch all access keys for the current user and store them in the session
            $Crud = new Crud();

            // SQL query to get the UID from the lookups table for the current user
            $SQL = 'SELECT a.AccessKey FROM admin_access a
                INNER JOIN system_users_access s ON a.UID = s.AccessID
                WHERE s.UserID = \'' . $_SESSION['UID'] . '\'';

            // Execute the query and get the result
            $sqlResult = $Crud->ExecuteSQL($SQL);

            // Check if we got results and store the access keys in the session
            if (!empty($sqlResult)) {
                $_SESSION['access_keys'] = array_column($sqlResult, 'AccessKey');
            } else {
                $_SESSION['access_keys'] = []; // No keys found, set to empty array
            }
        }

        // Check if the provided key exists in the session access keys
        if (in_array($key, $_SESSION['access_keys'])) {
            return 1;  // Key is found, access is granted
        } else {
            return 0;  // Key is not found, no access
        }
    }


}
