<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportTicketModel extends Model
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

    public function GetAllClinTaExtendedSupportTicketsData()
    {
        $Crud = new Crud();
        $key='ClinTa_Extended';
        $session = session();
        $SessionFilters = $session->get('ExtendedFilters');
        $SQL = 'SELECT * FROM `tasks` where `Product` = \'' . $key . '\'';
//        $Admin = $Crud->ExecuteSQL($SQL);
        if (isset($SessionFilters['Profile']) && $SessionFilters['Profile'] != '') {
            $ProductProfielID = $SessionFilters['Profile'];
            $SQL .= ' AND  `ProductProfielID` LIKE \'%' . $ProductProfielID . '%\'';
        }if (isset($SessionFilters['Status']) && $SessionFilters['Status'] != '') {
        $Status = $SessionFilters['Status'];
        $SQL .= ' AND  `Status` LIKE \'%' . $Status . '%\'';
    }

        $SQL .=' Order By `SystemDate` DESC';
        return $SQL;
    }

    public
    function get_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->GetAllClinTaExtendedSupportTicketsData();
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
//        echo nl2br($SQL); exit;
        $records = $Crud->ExecuteSQL($SQL);
        return $records;
    }

    public
    function count_datatables()
    {
        $Crud = new Crud();

        $SQL = $this->GetAllClinTaExtendedSupportTicketsData();
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }

    public function GetExtendedUserDataByDBOrID($DBName, $uid)
    {
        // Set database name for localhost
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $DBName = 'clinta_extended';
        }

        // Custom database configuration
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

        try {
            // Connect to the custom database
            $ExtendedDb = \Config\Database::connect($custom);

            // Query builder for the table
            $builder = $ExtendedDb->table('clinta.AdminUsers');

            // Fetch records with specified conditions
            $builder->select('*');
            $builder->where([
                'UID' => $uid,
                'Archive' => 0
            ]);

            $query = $builder->get();
            $records = $query->getResultArray();

            if (!is_array($records)) {
                $records = [];
            }

            return $records;
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            log_message('error', 'Database connection or query failed: ' . $e->getMessage());
            return [];
        }
    }

    public function GetLatestCommentDataByTicketID($key)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `taskcomments` where `TaskID` = \'' . $key . '\'';

        $SQL .= ' ORDER BY `SystemDate` DESC';
        //        print_r($SQL);exit();
                $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }
    public function GetExtendedProfielDataByID($key)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `extended_profiles` where `UID` = \'' . $key . '\'';

        //        print_r($SQL);exit();
                $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }
    public function GetTicketDataByID($key)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `tasks` where `UID` = \'' . $key . '\'';
         $Admin = $Crud->ExecuteSQL($SQL);
        return $Admin;
    }
    public function GetTicketAllCommentsData($key)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `taskcomments` where `TaskID` = \'' . $key . '\' Order by `SystemDate` DESC';
        $Admin = $Crud->ExecuteSQL($SQL);
//        print_r($SQL);exit();

        return $Admin;
    }
    public function GetAllAttachmentsByCommentID($key)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `taskattachments` where `CommentID` = \'' . $key . '\' Order by `SystemDate` DESC';
         $Admin = $Crud->ExecuteSQL($SQL);
//         print_r($SQL);exit();
        return $Admin;
    }
    public function Items($keyword)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM `items` where 1=1';
        if($keyword!=''){
            $SQL .= ' AND `Name`  LIKE \'%' . $keyword . '%\'   ';
        }
        $SQL .=' Order by `Name` Asc';
//         $Admin = $Crud->ExecuteSQL($SQL);
//         print_r($SQL);exit();
        return $SQL;
    }
    public
    function get_item_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->Items($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
//        echo nl2br($SQL); exit;
        $records = $Crud->ExecuteSQL($SQL);
//        print_r($records);exit();

        return $records;
    }

    public
    function count_item_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->Items($keyword);
        $records = $Crud->ExecuteSQL($SQL);
        return count($records);
    }
    public function AllQueriesFromBuilder($keyword)
    {
        $Crud = new Crud();
        $session = session();
        $SessionFilters = $session->get('SupportTicketFilters');
        $SQL = 'SELECT "public"."builder_support_ticket".*
        FROM "public"."builder_support_ticket"  
        WHERE 1=1
      ';
        if($keyword!=''){
            $SQL .= ' AND "public"."builder_support_ticket"."Title"  LIKE \'%' . $keyword . '%\'   ';
        }
        if (isset($SessionFilters['Status']) && $SessionFilters['Status'] != '') {
            $Status = $SessionFilters['Status'];
            $SQL .= ' AND  "public"."builder_support_ticket"."Status" ILIKE \'%' . $Status . '%\'';
        }
        if (isset($SessionFilters['CreatedDate']) && $SessionFilters['CreatedDate'] != '') {
            $CreatedDate = $SessionFilters['CreatedDate'];

            // Convert 'm d y' format to 'Y-m-d' (compatible with the timestamp field)
            $ConvertedDate = date('Y-m-d', strtotime($CreatedDate));

            // Use a proper date format in SQL and enclose it in single quotes
            $SQL .= ' AND "public"."builder_support_ticket"."SystemDate"::DATE <= \'' . $ConvertedDate . '\'';
        }
        $SQL .=' Order By "public"."builder_support_ticket"."SystemDate"  Desc';
        return $SQL;
    }
    public function GetBuilderTicketDataByID($key)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM "public"."builder_support_ticket" where "public"."builder_support_ticket"."UID" = \'' . $key . '\'';
        $Admin = $Crud->ExecutePgSQL($SQL);
        return $Admin;
    }
    public function GetTicketAllCommentsDataBuilder($key)
    {
        $Crud = new Crud();
        $SQL = 'SELECT * FROM "public"."builder_task_attachments" 
            WHERE "builder_task_attachments"."TaskID" = \'' . $key . '\' 
            ORDER BY "builder_task_attachments"."SystemDate" DESC';

        $Admin = $Crud->ExecutePgSQL($SQL);
        return $Admin;
    }
    public function GetTicketAllCommentsLastReply($key)
    {
        $Crud = new Crud();
        $SQL = 'SELECT "public"."builder_task_attachments"."SystemDate" FROM "public"."builder_task_attachments" 
            WHERE "builder_task_attachments"."TaskID" = \'' . $key . '\' 
            ORDER BY "builder_task_attachments"."SystemDate" DESC Limit 1';

        $Admin = $Crud->ExecutePgSQL($SQL);
        $Admin=$Admin[0]['SystemDate'];
        return isset($Admin)?$Admin:'';
    }

    public
    function get_builder_task_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->AllQueriesFromBuilder($keyword);
        if ($_POST['length'] != -1)
            $SQL .= ' limit ' . $_POST['length'] . ' offset  ' . $_POST['start'] . '';
//        echo nl2br($SQL); exit;
        $records = $Crud->ExecutePgSQL($SQL);
//        print_r($records);exit();

        return $records;
    }

    public
    function count_builder_task_datatables($keyword)
    {
        $Crud = new Crud();

        $SQL = $this->AllQueriesFromBuilder($keyword);
        $records = $Crud->ExecutePgSQL($SQL);
        return count($records);
    }

}
