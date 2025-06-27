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
//        print_r($Admin);exit();
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
        $Crud = new Crud();
        $SQL = 'SELECT `general_banners`.*, `specialities`.`Name` AS Title FROM `general_banners` 
                LEFT JOIN `specialities` ON `general_banners`.`Speciality` = `specialities`.`UID`
                ORDER BY `general_banners`.`SystemDate` DESC';
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
    function get_doct_datatables($type, $keyword)
    {
        $Crud = new Crud();

        $SQL = $this->Allprofiless($type, $keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecutePgSQL($SQL);

        return $records;
    }

    public function Allprofiless($ID, $keyword)
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
                WHERE public."profiles"."Type" = \'' . $ID . '\' ';
        if (isset($SessionFilters['Name']) && $SessionFilters['Name'] != '') {
            $Name = $SessionFilters['Name'];
            $SQL .= ' AND  public."profiles"."Name"  ILIKE \'%' . $Name . '%\'';
        }
        if (isset($SessionFilters['City']) && $SessionFilters['City'] != '') {
            $City = $SessionFilters['City'];
            $SQL .= ' AND  public."profiles"."City"  =' . $City . ' ';
        }
        if ($keyword != '') {
            $SQL .= ' AND public."profiles"."Name"  ILIKE \'%' . $keyword . '%\'   ';
        }
        $SQL .= ' Order By public."profiles"."Name"  ASC';

        return $SQL;
    }

    public
    function count_doct_datatables($type, $keyword)
    {
        $Crud = new Crud();

        $SQL = $this->Allprofiless($type, $keyword);
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
}
