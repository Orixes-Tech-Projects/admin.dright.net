<?php

namespace App\Models;

use CodeIgniter\Model;

class ExtendedModel extends Model
{

    var $data = array();

    public function __construct()
    {
        //        $this->data = $this->DefaultVariable();
    }

    public function extended_profiles()
    {
        $session = session();
        $SessionFilters = $session->get('ExtendedProfileFilters');
        $Crud = new Crud();
        $SQL = "SELECT * FROM  `extended_profiles` where 1=1";

        if (isset($SessionFilters['URL']) && $SessionFilters['URL'] != '') {
            $URL = $SessionFilters['URL'];
            $SQL .= ' AND  `SubDomainUrl` LIKE \'%' . $URL . '%\'';
        }
        if (isset($SessionFilters['FullName']) && $SessionFilters['FullName'] != '') {
            $FullName = $SessionFilters['FullName'];
            $SQL .= ' AND  `FullName` LIKE \'%' . $FullName . '%\'';
        }
        if (isset($SessionFilters['Status']) && $SessionFilters['Status'] != '') {
            $Status = $SessionFilters['Status'];
            $SQL .= ' AND  `Status` LIKE \'%' . $Status . '%\'';
        }
        $SQL .= '  ORDER BY `extended_profiles`.`FullName` ASC';
//        print_r($SQL);exit;
        return $SQL;
    }
    public function GetExtendedProfielDataByID($ID)
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `extended_profiles` WHERE `UID` = '" . $ID . "'";
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }
    public
    function GetAdminUsersByHospitalDB($DBName)
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost')
            $DBName = 'clinta_extended';

        $custom = [
            'DSN'          => '',
            'hostname'     => PGDB_HOST,
            'username'     => PGDB_USER,
            'password'     => PGDB_PASS,
            'database'     => $DBName,
            'DBDriver' => 'Postgre',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug' => true,
            'charset' => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre' => '',
            'encrypt' => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port' => 5432,
            'numberNative' => false,
        ];
        $ExtendedDb = \Config\Database::connect($custom);
        $builder = $ExtendedDb->table('clinta.AdminUsers');
        $builder->select('*');
        $builder->where([
            'Archive' => 0
        ]);
        $query = $builder->get();
        $records = $query->getResultArray();
        if (!is_array($records)) {
            $records = array();
        }
        //echo $db->getLastQuery() . "<hr>";
        //$db->close();
        return $records;
    }
    public
    function GetExtendedProductCategoriesByDBName($DBName)
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost')
            $DBName = 'clinta_extended';

        $custom = [
            'DSN'          => '',
            'hostname'     => PGDB_HOST,
            'username'     => PGDB_USER,
            'password'     => PGDB_PASS,
            'database'     => $DBName,
            'DBDriver' => 'Postgre',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug' => true,
            'charset' => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre' => '',
            'encrypt' => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port' => 5432,
            'numberNative' => false,
        ];
        $ExtendedDb = \Config\Database::connect($custom);
        $builder = $ExtendedDb->table('inventory.product_categories');
        $builder->select('*');
        $builder->where([
            'Archive' => 0
        ]);
        $query = $builder->get();
        $records = $query->getResultArray();
        if (!is_array($records)) {
            $records = array();
        }
        //echo $db->getLastQuery() . "<hr>";
        //$db->close();
        return $records;
    }
    public
    function GetAdminSettingsByHospitalDB($DBName)
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost')
            $DBName = 'clinta_extended';

        $custom = [
            'DSN'          => '',
            'hostname'     => PGDB_HOST,
            'username'     => PGDB_USER,
            'password'     => PGDB_PASS,
            'database'     => $DBName,
            'DBDriver' => 'Postgre',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug' => true,
            'charset' => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre' => '',
            'encrypt' => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port' => 5432,
            'numberNative' => false,
        ];
        $ExtendedDb = \Config\Database::connect($custom);
        $builder = $ExtendedDb->table('clinta.AdminSettings');
        $builder->select('*');
        $builder->orderBy('Key', 'ASC');
        $query = $builder->get();
        $records = $query->getResultArray();
        if (!is_array($records)) {
            $records = array();
        }
        //echo $db->getLastQuery() . "<hr>";
        //$db->close();
        return $records;
    }
    public
    function GetExtendedUserDataByDBOrID($DBName, $uid)
    {
        if ($_SERVER['HTTP_HOST'] == 'localhost')
            $DBName = 'clinta_extended';

        $custom = [
            'DSN'          => '',
            'hostname'     => PGDB_HOST,
            'username'     => PGDB_USER,
            'password'     => PGDB_PASS,
            'database'     => $DBName,
            'DBDriver' => 'Postgre',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug' => true,
            'charset' => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre' => '',
            'encrypt' => false,
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port' => 5432,
            'numberNative' => false,
        ];
        $ExtendedDb = \Config\Database::connect($custom);
        $builder = $ExtendedDb->table('clinta.AdminUsers');
        $builder->select('*');
        $builder->where([
            'UID' => $uid,
            'Archive' => 0,
        ]);
        $query = $builder->get();
        $records = $query->getResultArray();
        if (!is_array($records)) {
            $records = array();
        }
        //echo $db->getLastQuery() . "<hr>";
        //$db->close();
        return $records;
    }
    public function get_all_extended_default_lookups($keyword)
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM  `extended_lookups` WHERE `Archive` = '0' ";
        if ($keyword != '') {
            //            $SQL .= ' AND  `Name` LIKE \'%' . $keyword . '%\'   ';
            $SQL .= ' AND  ( `Name` LIKE \'%' . $keyword . '%\'  OR `Key` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY `Name` ASC';
        //        print_r($SQL);exit();
        //        $Admin = $Crud->ExecuteSQL($SQL);
        return $SQL;
    }
    function AssignAccessLevelToUserByID($dbname, $id)
    {

        $error = true;
        //$ExtendedDb = $this->load->database( $dbname , TRUE);
        $ExtendedDb = $this->Postgre->LoadPGDB($dbname);

        $AccessLevels = $this->GetCEConfigItem('AccessLevel');
        //echo "Final Access Level>>>"; print_r($AccessLevels); exit;
        $result = array();
        foreach ($AccessLevels as $key => $value) {
            foreach ($value as $accesslevel => $descriptio) {
                $result[] = $accesslevel;
            }
        }


        if ($error == 'false') {
            $data = array();
            $data['status'] = "success";
            $data['msg'] = "User Access Levels Successfully updated...!";
        } else {
            $data = array();
            $data['status'] = "fail";
            $data['msg'] = "Error...!";
        }

        echo json_encode($data);
    }
    public function get_all_extended_default_config($keyword)
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM  `extended_admin_setings` ";
        if ($keyword != '') {
            //            $SQL .= ' AND  `Name` LIKE \'%' . $keyword . '%\'   ';
            $SQL .= ' Where ( `Name` LIKE \'%' . $keyword . '%\'  OR `Key` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY `Name` ASC';
        //        print_r($SQL);exit();
        //        $Admin = $Crud->ExecuteSQL($SQL);
        return $SQL;
    }

    public function Allprofiless($ID)
    {
        $Crud = new Crud();
        $SQL = 'SELECT "public"."profiles".*
        FROM "public"."profiles"  
        LEFT JOIN "public"."profile_metas" 
        ON "public"."profiles"."UID" = "public"."profile_metas"."ProfileUID"  
            LEFT JOIN "public"."visitors" 
        ON "public"."profiles"."UID" = "public"."visitors"."ProfileUID"

        WHERE "public"."profiles"."Type" =\'' . $ID . '\' 
        ORDER BY "public"."profiles"."Name" ASC ';
        //        $Admin = $Crud->ExecutePgSQL($SQL);
        //        print_r($SQL);exit();
        return $SQL;
    }
    public function GetExtendedLookupsDataByDBOrID($DBName, $key)
    {
        $Crud = new Crud();
        //        $id=0;
        // Define the database name based on the environment
        $DBName = ($_SERVER['HTTP_HOST'] == 'localhost') ? 'clinta_extended' : '';

        // Custom database connection configuration
        $custom = [
            'DSN'          => '',
            'hostname'     => PGDB_HOST,
            'username'     => PGDB_USER,
            'password'     => PGDB_PASS,
            'database'     => $DBName,
            'DBDriver'     => 'Postgre',
            'DBPrefix'     => '',
            'pConnect'     => false,
            'DBDebug'      => true,
            'charset'      => 'utf8',
            'DBCollat'     => 'utf8_general_ci',
            'swapPre'      => '',
            'encrypt'      => false,
            'compress'     => false,
            'strictOn'     => false,
            'failover'     => [],
            'port'         => 5432,
            'numberNative' => false,
        ];

        // Connect to the extended database
        $ExtendedDb = \Config\Database::connect($custom);

        // Retrieve the UID from the Lookups table based on the provided key
        $builder = $ExtendedDb->table('clinta."Lookups"');
        $builder->select('UID');
        $builder->where('Key', $key);
        $query = $builder->get();

        $lookupRecord = $query->getRowArray();
        if (empty($lookupRecord)) {
            return []; // Return an empty array if no UID found
        }

        $lookupId = $lookupRecord['UID'];

        // Retrieve the LookupsOptions based on the lookup UID
        $builder = $ExtendedDb->table('clinta."LookupsOptions"');
        $builder->where('LookupID', $lookupId);
        $builder->orderBy('Name', 'ASC');
        $query = $builder->get();

        $options = $query->getResultArray();

        return $options;
    }


    public
    function get_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->extended_profiles();
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        //        echo nl2br($SQL); exit;
        $records = $Crud->ExecuteSQL($SQL);
        //        print_r($records);exit();

        return $records;
    }

    public
    function count_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->extended_profiles();
        $records = $Crud->ExecuteSQL($SQL);
        //        print_r($records);exit();
        return count($records);
    }
    public
    function get_default_extended_lookup_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->get_all_extended_default_lookups($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        //        echo nl2br($SQL); exit;
        $records = $Crud->ExecuteSQL($SQL);
        //        print_r($records);exit();

        return $records;
    }

    public
    function count_default_extended_lookup_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->get_all_extended_default_lookups($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        //        print_r($records);exit();
        return count($records);
    }
    public
    function get_default_extended_config_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->get_all_extended_default_config($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        //        echo nl2br($SQL); exit;
        $records = $Crud->ExecuteSQL($SQL);
        //        print_r($records);exit();

        return $records;
    }

    public
    function count_default_extended_config_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->get_all_extended_default_config($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        //        print_r($records);exit();
        return count($records);
    }
}
