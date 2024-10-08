<?php

namespace App\Models;

use CodeIgniter\Model;

class Main extends Model
{

    var $data = array();

    public function __construct()
    {
        $this->data = $this->DefaultVariable();
    }

    public function DefaultVariable()
    {
        helper('main');
//        $session = session();
        $data = $this->data;
        $data['path'] = PATH;
        $data['template'] = TEMPLATE;
        $page = getSegment(1);
        $data['segment_a'] = getSegment(1);
        $data['segment_b'] = getSegment(2);
        $data['segment_c'] = getSegment(3);

        return $data;
    }

    public
    function get_fruit_datatables($keyword = '')
    {
        $Crud = new Crud();
        $SQL = $this->Diet();
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
//        echo nl2br($SQL); exit;
        $records = $Crud->ExecuteSQL($SQL);
//        print_r($records);exit();

        return $records;
    }

    public
    function count_fruit_datatables($keyword = '')
    {
        $Crud = new Crud();
        $SQL = $this->Diet();
        $records = $Crud->ExecuteSQL($SQL);
//        print_r($records);exit();
        return count($records);
    }
}
