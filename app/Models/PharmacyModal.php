<?php

namespace App\Models;

use CodeIgniter\Model;

class PharmacyModal extends Model
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

    public function pharmacy_profiles($keyword)
    {
        $Crud = new Crud();
        $session = session();
        $SessionFilters = $session->get('PharmacyFilters');
        $SQL = "SELECT * FROM `pharmacy_profiles` Where 1=1 ";
//        $Admin = $Crud->ExecuteSQL($SQL);
        if (isset($SessionFilters['MACAddress']) && $SessionFilters['MACAddress'] != '') {
            $Categories = $SessionFilters['MACAddress'];
            $SQL .= ' AND  `MAC` LIKE \'%' . $Categories . '%\'';
        }
        if (isset($SessionFilters['FullName']) && $SessionFilters['FullName'] != '') {
            $FullName = $SessionFilters['FullName'];
            $SQL .= ' AND  `FullName` ILIKE \'%' . $FullName . '%\'';
        }
        if (isset($SessionFilters['City']) && $SessionFilters['City'] != '') {
            $City = $SessionFilters['City'];
            $SQL .= ' AND  `City` =' . $City . ' ';
        }
        if (isset($SessionFilters['DeploymentDate']) && $SessionFilters['DeploymentDate'] != '') {
            $DeploymentDate = $SessionFilters['DeploymentDate'];
            $SQL .= ' AND  `DeploymentDate` <=' . $DeploymentDate . ' ';
        }
        if($keyword!=''){
//            $SQL .= ' AND  `Name` LIKE \'%' . $keyword . '%\'   ';
            $SQL .= ' AND  ( `FullName` LIKE \'%' . $keyword . '%\'  OR `ContactNo` LIKE \'%' . $keyword . '%\'  OR `Address` LIKE \'%' . $keyword . '%\') ';
        }
        $SQL .= ' ORDER BY `FullName` ASC';
//        print_r($SQL);exit();
        return $SQL;
    }
    public function citites()
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `cities` ORDER BY `cities`.`FullName` ASC";
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }
    public function getcitybyid($id)
    {
        $Crud = new Crud();
        $SQL = "SELECT * FROM `cities` WHERE `UID` = '" . $id . "' ORDER BY `FullName` ASC";
        $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }

    public
    function get_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->pharmacy_profiles($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
//        echo nl2br($SQL); exit;
        $records = $Crud->ExecuteSQL($SQL);
//        print_r($records);exit();

        return $records;
    }

    public
    function count_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->pharmacy_profiles($keyword);
        $records = $Crud->ExecuteSQL($SQL);
//        print_r($records);exit();
        return count($records);
    }


}
