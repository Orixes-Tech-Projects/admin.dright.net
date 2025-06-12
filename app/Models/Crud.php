<?php

namespace App\Models;

use CodeIgniter\Model;

class Crud extends Model
{

    var $data = array();



    public function __construct()
    {
        $this->data['path'] = PATH;
        $this->data['template'] = TEMPLATE;
    }

    public function UploadFile($inputName, $DBRef = '', $single = true)
    {
        //		echo "<pre>";print_r($inputName);
        //		echo "<pre>";print_r($_FILES);exit;
        if ($single) {
            $recordids = '';
            if (isset($_FILES[$inputName]['tmp_name']) && $_FILES[$inputName]['tmp_name'] != "") {

                $file = [
                    'type' => $_FILES[$inputName]['type'],
                    'tmp_name' => $_FILES[$inputName]['tmp_name'],
                    // 'error' => $_FILES[$inputName]['error'],
                    'size' => $_FILES[$inputName]['size']
                ];
                $recordid = $this->uploadAsFile($file, $DBRef);
                $recordids = $recordid;
            }
        } else {
            $recordids = array();

            if (isset($_FILES[$inputName]['tmp_name'][0]) && $_FILES[$inputName]['tmp_name'][0] != "") {
                for ($a = 0; $a < count($_FILES[$inputName]['name']); $a++) {
                    $file = [
                        'type' => $_FILES[$inputName]['type'][$a],
                        'tmp_name' => $_FILES[$inputName]['tmp_name'][$a],
                        // 'error' => $_FILES[$inputName]['error'][$a],
                        // 'size' => $_FILES[$inputName]['size'][$a]
                    ];
                    $recordid = $this->uploadAsFile($file, $DBRef);
                    $recordids[] = $recordid;
                }
            }
        }

        return $recordids;
    }

    public function UploadFileFromURL($fileUrl, $DBRef = '')
    {
        $fileContents = file_get_contents($fileUrl);
        $tempFilePath = sys_get_temp_dir() . '/' . basename($fileUrl);
        file_put_contents($tempFilePath, $fileContents);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $tempFilePath);
        finfo_close($finfo);
        $fileSize = filesize($tempFilePath);
        $file = [
            'type' => $mimeType,
            'tmp_name' => $tempFilePath,
            'size' => $fileSize
        ];
        $recordid = $this->uploadAsFile($file, $DBRef);
        unlink($tempFilePath);
        return $recordid;
    }

    public
    function ExecuteSQL($Query, $view = false)
    {
        $db = \Config\Database::connect();
        $records = $db->query($Query)->getResult('array');
        if ($view)
            echo $db->getLastQuery() . "<hr>";
        // $db->close();
        return $records;
    }

    public
    function ExecutePgSQL($Query, $view = false)
    {
        $pgsql = \Config\Database::connect('website_db');
        $records = $pgsql->query($Query)->getResult('array');
        if ($view)
            echo $pgsql->getLastQuery() . "<hr>";
        $pgsql->close();
        return $records;
    }

    public
    function ExecutePgSQLClinta($Query, $view = false)
    {
        $pgsql = \Config\Database::connect('clinta_extended');
        $records = $pgsql->query($Query)->getResult('array');
        if ($view)
            echo $pgsql->getLastQuery() . "<hr>";
        $pgsql->close();
        return $records;
    }
    public
    function ExecutePgSQLExtended($Query, $view = false)
    {
        $pgsql = \Config\Database::connect('clinta_extended');
        $records = $pgsql->query($Query)->getResult('array');
        if ($view)
            echo $pgsql->getLastQuery() . "<hr>";
        $pgsql->close();
        return $records;
    }

    public
    function AddRecord($table, $records, $view = false)
    {
        $db = \Config\Database::connect();
        $db->db_debug = false;
        $builder = $db->table($table);
        $builder->insert($records);
        if ($view) {
            $QUERY = $db->getLastQuery() . ";<br>";
            // $Main = new Main();
            //  $Main->SendEmail('info@orixestech.com', 'Umrah Furas :: Insert Query Error', $QUERY);
            echo $QUERY;
        }
        $insertID = $db->insertID();
        // $db->close();
        return $insertID;
    }
    public
    function AddRecordClinta($table, $records, $view = false)
    {
        $db = \Config\Database::connect('clinta_extended');
        $db->db_debug = false;
        $builder = $db->table($table);
        $builder->insert($records);
        if ($view) {
            $QUERY = $db->getLastQuery() . ";<br>";
            // $Main = new Main();
            //  $Main->SendEmail('info@orixestech.com', 'Umrah Furas :: Insert Query Error', $QUERY);
            echo $QUERY;
        }
        $insertID = $db->insertID();
        // $db->close();
        return $insertID;
    }
    public
    function AddRecordPG($table, $records, $view = false)
    {
        $db = \Config\Database::connect('website_db');
        $db->db_debug = false;
        $builder = $db->table($table);
        $builder->insert($records);
        if ($view) {
            $QUERY = $db->getLastQuery() . ";<br>";
            // $Main = new Main();
            //  $Main->SendEmail('info@orixestech.com', 'Umrah Furas :: Insert Query Error', $QUERY);
            echo $QUERY;
        }
        $insertID = $db->insertID();
        // $db->close();
        return $insertID;
    }  public
    function AddRecordExtended($table, $records, $view = false)
    {
        $db = \Config\Database::connect('clinta_extended');
        $db->db_debug = false;
        $builder = $db->table($table);
        $builder->insert($records);
        if ($view) {
            $QUERY = $db->getLastQuery() . ";<br>";
            // $Main = new Main();
            //  $Main->SendEmail('info@orixestech.com', 'Umrah Furas :: Insert Query Error', $QUERY);
            echo $QUERY;
        }
        $insertID = $db->insertID();
        // $db->close();
        return $insertID;
    }
    public
    function DeleteRecordPG($table, $where)
    {
        $db = \Config\Database::connect('website_db');
        $builder = $db->table($table);
        if (count($where) > 0) {
            $builder->where($where);
        }
        $builder->delete();
        $db->close();

        return true;
    }   public
    function DeleteRecordExtended($table, $where)
    {
        $db = \Config\Database::connect('clinta_extended');
        $builder = $db->table($table);
        if (count($where) > 0) {
            $builder->where($where);
        }
        $builder->delete();
        $db->close();

        return true;
    }
    public
    function DeleteRecord($table, $where)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table);
        if (count($where) > 0) {
            $builder->where($where);
        }
        $builder->delete();
        // $db->close();

        return true;
    }

    public
    function SingleRecord($table, $wheres = array(), $view = false)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table);

        $builder->select('*');
        if (count($wheres) > 0) {
            $builder->where($wheres);
        }
        $query = $builder->get();
        $record = (array)$query->getRowArray();
        if (!is_array($record)) {
            $record = array();
        }
        //print_r($record);
        //$record = $query->getRowArray();
        if ($view) echo $db->getLastQuery() . "<hr>";

        // $db->close();
        return $record;
    }
    public
    function SingleRecordExtended($table, $wheres = array(), $view = false)
    {
        $db = \Config\Database::connect('clinta_extended');
        $builder = $db->table($table);

        $builder->select('*');
        if (count($wheres) > 0) {
            $builder->where($wheres);
        }
        $query = $builder->get();
        $record = (array)$query->getRowArray();
        if (!is_array($record)) {
            $record = array();
        }
        //print_r($record);
        //$record = $query->getRowArray();
        if ($view) echo $db->getLastQuery() . "<hr>";

        // $db->close();
        return $record;
    }
    public
    function SingleeRecord($table, $wheres = array(), $view = false)
    {
        $db = \Config\Database::connect('website_db');
        $builder = $db->table($table);

        $builder->select('*');
        if (count($wheres) > 0) {
            $builder->where($wheres);
        }
        $query = $builder->get();
        $record = (array)$query->getRowArray();
        if (!is_array($record)) {
            $record = array();
        }
        //print_r($record);
        //$record = $query->getRowArray();
        if ($view) echo $db->getLastQuery() . "<hr>";

        // $db->close();
        return $record;
    }
    public function MultipleRecords($table, $conditions = [])
    {
        $db = \Config\Database::connect();  // Connect to the database
        $builder = $db->table($table);      // Define the table

        // Apply conditions
        if (!empty($conditions)) {
            $builder->where($conditions);
        }

        // Fetch the records
        $query = $builder->get();

        return $query->getResultArray();  // Return results as an array
    }

    public
    function UpdateRecord($table, $records, $where)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table);
        if (count($where) > 0) {
            $builder->where($where);
        }
        $builder->update($records);
        // echo $db->getLastQuery() . "<hr>";

        // $db->close();
        return true;
    }
    public
    function UpdateeRecord($table, $records, $where)
    {
        $db = \Config\Database::connect('website_db');
        $builder = $db->table($table);
        if (count($where) > 0) {
            $builder->where($where);
        }
        $builder->update($records);
        // echo $db->getLastQuery() . "<hr>";

        // $db->close();
        return true;
    }  public
    function UpdateRecordExtended($table, $records, $where)
    {
        $db = \Config\Database::connect('clinta_extended');
        $builder = $db->table($table);
        if (count($where) > 0) {
            $builder->where($where);
        }
        $builder->update($records);
        // echo $db->getLastQuery() . "<hr>";

        // $db->close();
        return true;
    }

    public
    function ListRecords($table, $wheres = array(), $order = array(), $limit = 0)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($table);

        $builder->select('*');
        if (count($wheres) > 0) {
            $builder->where($wheres);
        }
        if (count($order) > 0) {
            foreach ($order as $ordK => $ordV) {
                $builder->orderBy($ordK, $ordV);
            }
        }
        if ($limit > 0) {
            $builder->limit($limit);
        }

        $query = $builder->get();
        $records = $query->getResultArray();
        if (!is_array($records)) {
            $records = array();
        }

        //echo $db->getLastQuery() . "<hr>";
        // $db->close();
        return $records;
    }

    public function UploadAsFile($file, $dbRef = '')
    {
        $recordid = '';
        $records = array();
        $records['SystemDate'] = 'now()';
        $records['Ext'] = $file['type'];
        $records['Size'] = $file['size'];
        $records['DBRef'] = $dbRef;
        if (isset($file['tmp_name'])) {
            $file_content = file_get_contents($file["tmp_name"]);
            $file_content = base64_encode($file_content);
            $records['Content'] = $file_content;
            $recordid = $this->AddRecordPG('public."Files"', $records);
        }
        return $recordid;
    }
}
