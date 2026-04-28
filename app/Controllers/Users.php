<?php

namespace App\Controllers;


use App\Models\Crud;
use App\Models\Main;
use App\Models\SystemUser;
use Config\Database;

class Users extends BaseController
{
    var $data = array();
    var $MainModel;
    var $table = 'system_users';

    public function __construct()
    {

        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
    }

    public function index()
    {
        $data = $this->data;
        $data['page'] = getSegment(2);
        $Users = new SystemUser();

        echo view('header', $data);
        if ($data['page'] == 'access_level') {
            echo view('users/access_level', $data);
        } elseif ($data['page'] == 'add') {
            echo view('users/main_form', $data);
        } elseif ($data['page'] == 'admin-activites') {
            echo view('users/admin_activites', $data);
        } elseif ($data['page'] == 'admin-approval') {
            echo view('users/admin_approval', $data);
        } elseif ($data['page'] == 'database-backup') {
            echo view('users/database_backup', $data);
        } else {
            echo view('users/index', $data);
        }
        echo view('footer', $data);
    }

    public function dashboard()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('support_ticket/dashboard', $data);
        echo view('footer', $data);
    }

    public function access_level()
    {
        $data = $this->data;
        $Users = new SystemUser();

        $data['UserID'] = getSegment(3);
        $data['UserRoll'] = $Users->system_user_roll($data['UserID']);

//        print_r($data['UserRoll']);exit();
//        $date['user'] = 1;
        echo view('header', $data);
        echo view('users/access_level', $data);
        echo view('footer', $data);
    }

    public function fetch_users()
    {
        $Users = new SystemUser();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Users->get_users_datatables($keyword);
        $totalfilterrecords = $Users->count_users_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['FullName']) ? htmlspecialchars($record['FullName']) : '';
            $data[] = isset($record['Email']) ? htmlspecialchars($record['Email']) : '';
            $data[] = isset($record['AccessLevel']) ? htmlspecialchars($record['AccessLevel']) : '';

            $Actions = [];
            if( $Users->checkAccessKey('dashboards') )
                $Actions[] = '<a class="dropdown-item" onclick="AddAccessLevel(' . htmlspecialchars($record['UID']) . ')">Access Level</a>';

            if( $Users->checkAccessKey('dashboards') )
                $Actions[] = '<a class="dropdown-item" onclick="UpdateUser(' . htmlspecialchars($record['UID']) . ')">Update</a>';

            if( $Users->checkAccessKey('dashboards') )
                $Actions[] = '<a class="dropdown-item" onclick="DeleteUser(' . htmlspecialchars($record['UID']) . ')">Delete</a>';


            $data[] = '
    <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                Actions
            </button>
            <div class="dropdown-menu">' . implode(" ", $Actions) . '</div>
        </div>
    </td>';
            $dataarr[] = $data;
        }

        $response = array(
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => count($Data),
            "recordsFiltered" => $totalfilterrecords,
            "data" => $dataarr
        );
        echo json_encode($response);
    }

    public function fetch_admin_activites()
    {
        $Users = new SystemUser();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Users->get_admin_activity_datatables($keyword);
        $totalfilterrecords = $Users->count_admin_activity_datatables($keyword);
//        echo '<pre>';
//        print_r($Data);exit();
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['FullName']) ? htmlspecialchars($record['FullName']) : '';
            $data[] = isset($record['Segment']) ? htmlspecialchars($record['Segment']) : '';
            $data[] = isset($record['Description']) ?
                htmlspecialchars(str_replace(['<strong>', '</strong>'], ' ', $record['Description'])) : '';

            $dataarr[] = $data;
        }

        $response = array(
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => count($Data),
            "recordsFiltered" => $totalfilterrecords,
            "data" => $dataarr
        );
        echo json_encode($response);
    }

    public function fetch_admin_approval()
    {
        $Users = new SystemUser();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Users->get_diet_admin_category_datatables($keyword);
        $totalfilterrecords = $Users->count_diet_admin_category_datatables($keyword);
//        echo '<pre>';
//        print_r($Data);exit();
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['EditBy']) ? htmlspecialchars($record['EditBy']) : '';
            $data[] = isset($record['ModuleRef']) ? htmlspecialchars($record['ModuleRef']) : '';
            $data[] = isset($record['Description']) ? substr(strip_tags($record['Description']), 0, 50) . ' ... <a href="javascript:void(0)" style="color: red;" onclick="LoadDescriptionModel(' . $record['UID'] . ')">read more</a>' : '';
            $data[] = ($record['ApprovedBy'] > 0) ? '<span class="btn btn-info rounded btn-sm">Approved</span>' : '<a href="javascript:void(0)" onClick="ApproveQuery(' . $record['UID'] . ')" class="btn btn-danger-outline ks-no-text"><span class="fa fa-check ks-icon"></span></a>';


            $dataarr[] = $data;
        }

        $response = array(
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => count($Data),
            "recordsFiltered" => $totalfilterrecords,
            "data" => $dataarr
        );
        echo json_encode($response);
    }

    public function user_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $User = $this->request->getVar('User');


        if ($id == 0) {
            foreach ($User as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }
            $record['Password'] = $Main->CRYPT($record['Password'], 'hide');
            $RecordId = $Crud->AddRecord("system_users", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $Main = new Main();

                $msg = $_SESSION['FullName'] . ' Add User Through Admin Dright';
                $logesegment = 'Users';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
                $response['status'] = 'success';
                $response['message'] = 'User Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($User as $key => $value) {
                $record[$key] = $value;
            }
            $record['Password'] = $Main->CRYPT($record['Password'], 'hide');

            $msg = $_SESSION['FullName'] . ' Update User Through Admin Dright';
            $logesegment = 'Users';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
            $Crud->UpdateRecord("system_users", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'User Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function user_roll_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $UserID = $this->request->getVar('UserID');
        $access = $this->request->getVar('access');
        $Crud->DeleteRecord("system_users_access", array("UserID" => $UserID));
//            print_r($access);exit();
        foreach ($access as $key => $value) {
            $record['AccessID'] = $key;
            $record['UserID'] = $UserID;
            $RecordId = $Crud->AddRecord("system_users_access", $record);

        }
        if (isset($RecordId) && $RecordId > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Access Level Added Successfully...!';
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Data Didnt Submitted Successfully...!';
        }

        echo json_encode($response);
    }

    public function delete_user()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
        $Crud->DeleteRecord("system_users", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['message'] = 'User Deleted Successfully...!';
        echo json_encode($response);
    }

    public function get_item_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
        $Main = new Main();

        $record = $Crud->SingleRecord("system_users", array("UID" => $id));
        $record['Password'] = $Main->CRYPT($record['Password'], 'show');

        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function get_admin_updates_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("admin_updates", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function download_database_backup()
    {
        $backupDir = WRITEPATH . 'uploads/database-backups/';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0775, true);
        }

        if (!class_exists('ZipArchive')) {
            return redirect()->to(PATH . 'users/database-backup')->with('error', 'ZIP extension is not enabled on server.');
        }

        $timestamp = date('Ymd_His');
        $fileName = 'database_backup_' . $timestamp . '.zip';
        $filePath = $backupDir . $fileName;

        $zip = new \ZipArchive();
        if ($zip->open($filePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->to(PATH . 'users/database-backup')->with('error', 'Unable to create backup zip file.');
        }

        $manifest = array(
            'generated_at' => date('c'),
            'generated_by' => (isset($_SESSION['FullName']) ? $_SESSION['FullName'] : 'system'),
            'format_version' => '1.0',
            'description' => 'Portable DB backup for import on another system.'
        );

        try {
            $mysqlExport = $this->export_mysql_tables();
            $pgsqlExport = $this->export_pgsql_tables();

            $zip->addFromString('manifest.json', json_encode($manifest, JSON_PRETTY_PRINT));
            $zip->addFromString('README.txt', $this->backup_readme_text());
            $zip->addFromString('mysql/tables.json', json_encode($mysqlExport, JSON_PRETTY_PRINT));
            $zip->addFromString('pgsql/tables.json', json_encode($pgsqlExport, JSON_PRETTY_PRINT));
            $zip->addFromString('mysql/backup.sql', $this->generate_mysql_import_sql($mysqlExport));
            $zip->addFromString('pgsql/backup.sql', $this->generate_pgsql_import_sql($pgsqlExport));

            $zip->close();
        } catch (\Throwable $e) {
            $zip->close();
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            return redirect()->to(PATH . 'users/database-backup')->with('error', 'Backup failed: ' . $e->getMessage());
        }

        register_shutdown_function(static function () use ($filePath) {
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
        });

        return $this->response->download($filePath, null);
    }

    private function export_mysql_tables()
    {
        $db = Database::connect('default');
        $tables = $db->listTables();
        $export = array();

        foreach ($tables as $table) {
            $rows = $db->table($table)->get()->getResultArray();
            $export[$table] = $rows;
        }

        return $export;
    }

    private function export_pgsql_tables()
    {
        $db = Database::connect('website_db');
        $tables = $db->query("SELECT tablename FROM pg_tables WHERE schemaname='public' ORDER BY tablename ASC")->getResultArray();
        $export = array();

        foreach ($tables as $tableRow) {
            $table = $tableRow['tablename'];
            $rows = $db->query('SELECT * FROM public."' . $table . '"')->getResultArray();
            $export[$table] = $rows;
        }

        return $export;
    }

    private function generate_mysql_import_sql($export)
    {
        $sql = "-- MySQL import file generated at " . date('Y-m-d H:i:s') . "\n\n";
        foreach ($export as $table => $rows) {
            $sql .= "TRUNCATE TABLE `" . $table . "`;\n";
            foreach ($rows as $row) {
                $columns = array_map(static function ($col) {
                    return '`' . str_replace('`', '``', $col) . '`';
                }, array_keys($row));
                $values = array_map(array($this, 'sql_value_mysql'), array_values($row));
                $sql .= "INSERT INTO `" . $table . "` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
            }
            $sql .= "\n";
        }
        return $sql;
    }

    private function generate_pgsql_import_sql($export)
    {
        $sql = "-- PostgreSQL import file generated at " . date('Y-m-d H:i:s') . "\n\n";
        foreach ($export as $table => $rows) {
            $sql .= "TRUNCATE TABLE public.\"" . $table . "\" RESTART IDENTITY CASCADE;\n";
            foreach ($rows as $row) {
                $columns = array_map(static function ($col) {
                    return '"' . str_replace('"', '""', $col) . '"';
                }, array_keys($row));
                $values = array_map(array($this, 'sql_value_pgsql'), array_values($row));
                $sql .= "INSERT INTO public.\"" . $table . "\" (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
            }
            $sql .= "\n";
        }
        return $sql;
    }

    private function sql_value_mysql($value)
    {
        if ($value === null) {
            return 'NULL';
        }
        return "'" . addslashes((string)$value) . "'";
    }

    private function sql_value_pgsql($value)
    {
        if ($value === null) {
            return 'NULL';
        }
        return "'" . str_replace("'", "''", (string)$value) . "'";
    }

    private function backup_readme_text()
    {
        return "Database Backup Package\n"
            . "=======================\n\n"
            . "This ZIP contains portable exports for both databases used by the system.\n\n"
            . "Included:\n"
            . "- manifest.json\n"
            . "- mysql/tables.json\n"
            . "- mysql/backup.sql\n"
            . "- pgsql/tables.json\n"
            . "- pgsql/backup.sql\n\n"
            . "Import note:\n"
            . "Use SQL files for direct DB restore, or JSON files for custom import tooling.\n";
    }
}
