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

    public function systemusers()
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `system_users` where `Archive`=\'0\' Order By `SystemDate` DESC';
//        $Admin = $Crud->ExecuteSQL($SQL);
        return $SQL;
    }
    public
    function get_users_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->systemusers();
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
//        echo nl2br($SQL); exit;
        $records = $Crud->ExecuteSQL($SQL);
//        print_r($records);exit();

        return $records;
    }

    public
    function count_users_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->systemusers();
        $records = $Crud->ExecuteSQL($SQL);
//        print_r($records);exit();
        return count($records);
    }


}
