<?php

namespace App\Models;

use CodeIgniter\Model;

class MedicineModel extends Model
{

    var $data = array();

    public function __construct()
    {
//        $this->data = $this->DefaultVariable();
    }

//    public function DefaultVariable()
//    {
//        helper('main');
//        $session = session();
//        $data = $this->data;
//        $data['path'] = PATH;
//        $data['template'] = TEMPLATE;
//        $data['site_title'] = SITETITLE;
//        $page = getSegment(1);
//        $data['segment_a'] = getSegment(1);
//        $data['segment_b'] = getSegment(2);
//        $data['segment_c'] = getSegment(3);
//        $data['session'] = $session->get();
//        $data['page'] = ($page == '') ? 'home' : $page;
//
//        return $data;
//    }

    public
    function ListAllMedicines($keyword)
    {
        $Crud = new Crud();
        $session = session();
        $SessionFilters = $session->get('MedicineFilters');
        $SQL = 'SELECT medicines.*, pharma_company.CompanyName AS PharmaTitle FROM medicines
                        LEFT JOIN pharma_company ON medicines.PharmaCompanyUID = pharma_company.UID
                WHERE medicines.`Archive` = 0 ';
        if (isset($SessionFilters['MedicineName']) && $SessionFilters['MedicineName'] != '') {
            $MedicineName = $SessionFilters['MedicineName'];
            $SQL .= ' AND  `MedicineTitle` LIKE \'%' . trim($MedicineName) . '%\'';
        }
        if ($keyword != '') {
            $SQL .= ' AND  ( `MedicineTitle` LIKE \'%' . trim($keyword) . '%\'  OR `Packing` LIKE \'%' . trim($keyword) . '%\') ';
        }
        $SQL .= ' ORDER BY medicines.`MedicineTitle` ASC';

        return $SQL;
    }

    public function ListAllMedicinesbyID($keyword, $id)
    {

        $Crud = new Crud();

        $SQL = 'SELECT medicines.*, pharma_company.CompanyName AS PharmaTitle FROM medicines
    LEFT JOIN pharma_company ON medicines.PharmaCompanyUID = pharma_company.UID
        WHERE medicines.`Archive`=\'0\' AND medicines.`PharmaCompanyUID`= ' . $id . '

';

        if ($keyword != '') {
            $SQL .= ' AND  ( `MedicineTitle` LIKE \'%' . $keyword . '%\'  OR `Ingredients` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY medicines.`MedicineTitle` ASC';
        return $SQL;
    }

    public function medicines_take_types($keyword)
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `medicines_take_types` WHERE 1=1";
        if ($keyword != '') {
            $SQL .= " AND (`TakeType` LIKE '%" . $keyword . "%' OR `TakeTypeEng` LIKE '%" . $keyword . "%')";
        }
        $SQL .= " ORDER BY `TakeType` ASC";

        return $SQL;
    }

    public
    function medicines_forms($keyword)
    {
        $Crud = new Crud();

        $SQL = "SELECT * FROM `medicines_forms` WHERE 1=1";

        if ($keyword != '') {
            $SQL .= " AND (`Name` LIKE '%" . $keyword . "%' OR `EngName` LIKE '%" . $keyword . "%')";
        }

        $SQL .= " ORDER BY `SortOrder` ASC";

        return $SQL;
    }

    public function pharma_company($keyword)
    {
        $Crud = new Crud();

        $SQL = "SELECT * FROM `pharma_company` Where 1=1 ";
        if ($keyword != '') {
            $SQL .= ' AND  `CompanyName` LIKE \'%' . $keyword . '%\'   ';
        }
        $SQL .= ' ORDER BY `CompanyName` ASC';
        return $SQL;
    }

    public
    function medicines_timing($keyword)
    {
        $Crud = new Crud();

        $SQL = "SELECT * FROM `medicines_timings` Where 1=1 ";
        if ($keyword != '') {
            $SQL .= " AND (`Name` LIKE '%" . trim($keyword) . "%' OR `EngName` LIKE '%" . trim($keyword) . "%')";
        }
        $SQL .= ' ORDER BY `Name` ASC';
        return $SQL;
    }

    public function ListAllCompanies()
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `pharma_company` where `Archive`=\'0\' Order By `CompanyName` ASC';
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }

    public function GetMedicinesByPharmaID($id)
    {
        $Crud = new Crud();
        $id = intval($id); // Ensure `$id` is an integer to prevent SQL injection
        $SQL = "SELECT * FROM `medicines` WHERE `PharmaCompanyUID` = $id";
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }

    public
    function get_medicine_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->ListAllMedicines($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_medicine_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->ListAllMedicines($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_medicine_take_type_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->medicines_take_types($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_medicine_take_type_datatables($keyword)
    {
        $Crud = new Crud();
        $SQL = $this->medicines_take_types($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_medicine_forms_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->medicines_forms($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_medicine_forms_datatables($keyword)
    {
        $Crud = new Crud();
        $SQL = $this->medicines_forms($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_medicine_timing_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->medicines_timing($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_medicine_timing_datatables($keyword)
    {
        $Crud = new Crud();
        $SQL = $this->medicines_timing($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_pharma_company_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->pharma_company($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_pharma_company_datatables($keyword)
    {
        $Crud = new Crud();
        $SQL = $this->pharma_company($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function get_med_by_id_datatables($keyword, $id)
    {
        $Crud = new Crud();

        $SQL = $this->ListAllMedicinesbyID($keyword, $id);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_med_by_id_datatables($keyword, $id)
    {
        $Crud = new Crud();
        $SQL = $this->ListAllMedicinesbyID($keyword, $id);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }


    public
    function get_medicine_routes_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->medicines_routes($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_medicine_routes_datatables($keyword)
    {
        $Crud = new Crud();
        $SQL = $this->medicines_routes($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public
    function medicines_routes($keyword)
    {
        $Crud = new Crud();

        $SQL = "SELECT * FROM `medicine_routes` Where 1=1 ";
        if ($keyword != '') {
            $SQL .= " AND (`Name` LIKE '%" . trim($keyword) . "%' OR `EngName` LIKE '%" . trim($keyword) . "%')";
        }
        $SQL .= ' ORDER BY `Name` ASC';
        return $SQL;
    }

}
