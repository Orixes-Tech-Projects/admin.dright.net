<?php

namespace App\Controllers;

use App\Models\Crud;
use App\Models\ExtendedModel;
use App\Models\Invoices;
use App\Models\Main;
use App\Models\PharmacyModal;
use App\Models\SystemUser;
use CodeIgniter\Database\Config;


class Extended extends BaseController
{
    var $data = array();

    public function __construct()
    {

        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
        helper('cpanel');
    }

    public function index()
    {
        $data = $this->data;
        $data['PAGE'] = array();

        $data['page'] = getSegment(2);
        $PharmacyModal = new \App\Models\PharmacyModal();
        $ExtendedModel = new \App\Models\ExtendedModel();
        $data['Cities'] = $PharmacyModal->citites();
        echo view('header', $data);
        if ($data['page'] == 'add-profile') {
            echo view('extended/main_form', $data);

        } elseif ($data['page'] == 'update-profile') {
            $UID = getSegment(3);
            $data['UID'] = $UID;
            $Crud = new Crud();
            $PAGE = $Crud->SingleRecord('extended_profiles', array("UID" => $UID));
            $data['PAGE'] = $PAGE;
            echo view('extended/main_form', $data);

        } elseif ($data['page'] == 'extended_default_lookup') {
            echo view('extended/extended_default_lookup', $data);

        } elseif ($data['page'] == 'extended_profile_detail') {
            $UID = getSegment(3);
            $data['UID'] = $UID;
            $Crud = new Crud();
            $data['HospitalData'] = $ExtendedModel->GetExtendedProfielDataByID($UID);
//            print_r(  $data['HospitalData'] );exit;
            $data['HospitalAdminUsers'] = $ExtendedModel->GetAdminUsersByHospitalDB($data['HospitalData'][0]['DatabaseName']);


            $data['HospitalAdminSettings'] = $ExtendedModel->GetAdminSettingsByHospitalDB($data['HospitalData'][0]['DatabaseName']);
//            echo '<pre>'; print_r($data['HospitalAdminSettings']);exit();

            echo view('extended/extended_profile_detail', $data);

        } elseif ($data['page'] == 'extended_default_config') {
            echo view('extended/extended_default_config', $data);

        } else {
            echo view('extended/index', $data);

        }
        echo view('footer', $data);
    }

    public function dashboard()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('extended/dashboard', $data);
        echo view('footer', $data);
    }

    public function fetch_profiles()
    {
        $Users = new ExtendedModel();
        $PharmacyModal = new PharmacyModal();
        $Data = $Users->get_datatables();
        $totalfilterrecords = $Users->count_datatables();

        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $city = $PharmacyModal->getcitybyid($record['City']);
            $Users = new SystemUser();
            $Actions = array();
            if ($Users->checkAccessKey('extended_profiles_update'))
                $Actions[] = '<a class="dropdown-item" onclick="UpdateProfile(' . $record['UID'] . ');">Update</a>';
            $Actions[] = '   <a class="dropdown-item" onclick="ProfileDetail(' . $record['UID'] . ');">Detail</a>';

            if ($Users->checkAccessKey('extended_profiles_delete'))
                $Actions[] = '<a class="dropdown-item" onclick="DeleteProfile(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            $cnt++;
            $data = array();
            $data[] = $cnt;

            $data[] = isset($record['FullName']) ? '<b>' . htmlspecialchars($record['FullName']) . '</b>' : '';
            $data[] = isset($city[0]['FullName']) ? htmlspecialchars($city[0]['FullName']) : '';
            $data[] = ((isset($record['DatabaseName']) && $record['DatabaseName'] != '' )? htmlspecialchars($record['DatabaseName']) : '-' );
            $data[] = ((isset($record['SubDomainUrl']) && $record['SubDomainUrl'] != '')? '<b>' . htmlspecialchars($record['SubDomainUrl']) . '</b>' : '-');
            $data[] = ((isset($record['ExpireDate']) && $record['ExpireDate'] != '' && $record['ExpireDate'] > date("Y-m-d"))? '<b>' . date("d M, Y", strtotime(htmlspecialchars($record['ExpireDate']))) . '</b>' : '<badge class="badge badge-danger">Expired</badge>');
            $data[] = isset($record['Status']) ? '<badge class="badge badge-' . (($record['Status'] == 'active') ? 'success' : 'danger') . '">' . ucwords(htmlspecialchars($record['Status'])) . '</badge>' : '';

            $smsCredits = isset($record['SMSCredits']) && $record['SMSCredits'] != ''
                ? '<strong>' . $record['SMSCredits'] . '</strong> SMS<br>
                <button style="border-radius: 5px;" class="btn btn-sm btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 250);"><strong>250</strong></button>
                <button style="border-radius: 5px;" class="btn btn-sm btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 500);"><strong>500</strong></button>'
                : '<button style="border-radius: 5px;" class="btn btn-sm btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 100);"><strong>Free Credits</strong></button>';
            $data[] = $smsCredits;

            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button style="border-radius: 5px;" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
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

    public function fetch_default_lookup()
    {
        $Users = new ExtendedModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Users->get_default_extended_lookup_datatables($keyword);
        $totalfilterrecords = $Users->count_default_extended_lookup_datatables($keyword);

        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $Users = new SystemUser();
            $Actions = [];
            if ($Users->checkAccessKey('extended_default_lookup_update'))
                $Actions[] = '<a class="dropdown-item" onclick="UpdateDefaultLookup(' . $record['UID'] . ');">Edit</a>
';


            if ($Users->checkAccessKey('extended_default_lookup_delete'))
                $Actions[] = '<a class="dropdown-item" onclick="DeleteDefaultLookup(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            $cnt++;
            $data = array();
            $data[] = $cnt;

            $data[] = isset($record['Name']) ? htmlspecialchars($record['Name']) : '';
            $data[] = isset($record['Key']) ? htmlspecialchars($record['Key']) : '';


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

    public function fetch_default_config()
    {
        $Users = new ExtendedModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Users->get_default_extended_config_datatables($keyword);
        $totalfilterrecords = $Users->count_default_extended_config_datatables($keyword);

        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $Users = new SystemUser();
            $Actions = [];
            if ($Users->checkAccessKey('extended_default_configration_update'))
                $Actions[] = '<a class="dropdown-item" onclick="UpdateDefaultConfig(' . $record['UID'] . ');">Edit</a>
';


            if ($Users->checkAccessKey('extended_default_configration_delete'))
                $Actions[] = '<a class="dropdown-item" onclick="DeleteDefaultconfig(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            $cnt++;
            $data = array();
            $data[] = $cnt;

            $data[] = isset($record['Name']) ? htmlspecialchars($record['Name']) : '';
            $data[] = isset($record['Key']) ? htmlspecialchars($record['Key']) : '';


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

    public function submit_default_lookup()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Item = $this->request->getVar('DefaultLookup');


        if ($id == 0) {
            foreach ($Item as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }

            $RecordId = $Crud->AddRecord("extended_lookups", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Item Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Item as $key => $value) {
                $record[$key] = $value;
            }


            $Crud->UpdateRecord("extended_lookups", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function submit_default_config()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Item = $this->request->getVar('DefaultConfig');


        if ($id == 0) {
            foreach ($Item as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }

            $RecordId = $Crud->AddRecord("extended_admin_setings", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Item Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Item as $key => $value) {
                $record[$key] = $value;
            }


            $Crud->UpdateRecord("extended_admin_setings", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function submit_profile()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Profile = $this->request->getVar('Profile');

        if (!empty($Profile['FullName']) && !empty($Profile['Email']) && !empty($Profile['ContactNo']) && !empty($Profile['City'])) {

            if ($id == 0) {

                $EmailRecord = $Crud->SingleRecord('extended_profiles', ["Email" => trim($Profile['Email'])]);
                if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                            (($EmailRecord['FullName'] ?? $EmailRecord['SubDomainUrl']) ?? 'Another Profile') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }

                $ContactNoRecord = $Crud->SingleRecord('extended_profiles', ['ContactNo' => trim($Profile['ContactNo'])]);
                if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                            (($ContactNoRecord['FullName'] ?? $ContactNoRecord['SubDomainUrl']) ?? 'Another Profile') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }

                if (trim($Profile['SubDomainUrl']) != '') {

                    $SubDomainRecord = $Crud->SingleRecord('extended_profiles', ['SubDomainUrl' => trim($Profile['SubDomainUrl'])]);
                    if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                        $response = [
                            'status' => 'fail',
                            'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                                (($SubDomainRecord['FullName'] ?? $SubDomainRecord['SubDomainUrl']) ?? 'Another Profile') . '</strong>!'
                        ];
                        echo json_encode($response);
                        return;
                    }
                }

                if (trim($Profile['DatabaseName']) != '') {

                    $SubDomainRecord = $Crud->SingleRecord('extended_profiles', ['DatabaseName' => trim($Profile['DatabaseName'])]);
                    if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                        $response = [
                            'status' => 'fail',
                            'message' => '<strong>DataBase Name</strong> Already Assigned to <strong>' .
                                (($SubDomainRecord['FullName'] ?? $SubDomainRecord['SubDomainUrl']) ?? 'Another Profile') . '</strong>!'
                        ];
                        echo json_encode($response);
                        return;
                    }
                }

                foreach ($Profile as $key => $value) {
                    $record[$key] = ((isset($value)) ? $value : '');
                }
                $RecordId = $Crud->AddRecord("extended_profiles", $record);
                if (isset($RecordId) && $RecordId > 0) {

                    $PackageUID = $this->request->getVar('Package');
                    if (isset($PackageUID) && $PackageUID > 0) {

                        $Invoices = new Invoices();
                        $OriginalPrice = $this->request->getVar('OriginalPrice');
                        $Discount = $this->request->getVar('Discount');
                        $Price = $this->request->getVar('Price');
                        $InvoiceDetailsArray = array(
                            'ProfileName' => $Profile['FullName'],
                            'ProductType' => 'extended',
                            'Product' => 'hospitals',
                            'ProfileUID' => $RecordId,
                            'PackageUID' => $PackageUID,
                            'OriginalPrice' => $OriginalPrice,
                            'Discount' => $Discount,
                            'Price' => $Price
                        );
                        $Invoices->AddProfileSubscriptionDetails($InvoiceDetailsArray);
                    }

                    $response['status'] = 'success';
                    $response['message'] = 'Profile Added Successfully...!';
                    $response['subdomain'] = trim($Profile['SubDomainUrl']);
                    $response['database'] = trim($Profile['DatabaseName']);
                } else {
                    $response['status'] = 'fail';
                    $response['message'] = 'Data Didnt Submitted Successfully...!';
                    $response['subdomain'] = trim($Profile['SubDomainUrl']);
                    $response['database'] = trim($Profile['DatabaseName']);
                }
            } else {

                $EmailRecord = $Crud->SingleRecord('extended_profiles', ["Email" => trim($Profile['Email']), 'UID !=' => $id]);
                if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                            (($EmailRecord['FullName'] ?? $EmailRecord['SubDomainUrl']) ?? 'Another Profile') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }

                $ContactNoRecord = $Crud->SingleRecord('extended_profiles', ['ContactNo' => trim($Profile['ContactNo']), 'UID !=' => $id]);
                if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                            (($ContactNoRecord['FullName'] ?? $ContactNoRecord['SubDomainUrl']) ?? 'Another Profile') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }

                if (trim($Profile['SubDomainUrl']) != '') {

                    $SubDomainRecord = $Crud->SingleRecord('extended_profiles', ['SubDomainUrl' => trim($Profile['SubDomainUrl']), 'UID !=' => $id]);
                    if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                        $response = [
                            'status' => 'fail',
                            'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                                (($SubDomainRecord['FullName'] ?? $SubDomainRecord['SubDomainUrl']) ?? 'Another Profile') . '</strong>!'
                        ];
                        echo json_encode($response);
                        return;
                    }
                }

                if (trim($Profile['DatabaseName']) != '') {

                    $SubDomainRecord = $Crud->SingleRecord('extended_profiles', ['DatabaseName' => trim($Profile['DatabaseName']), 'UID !=' => $id]);
                    if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                        $response = [
                            'status' => 'fail',
                            'message' => '<strong>DataBase Name</strong> Already Assigned to <strong>' .
                                (($SubDomainRecord['FullName'] ?? $SubDomainRecord['SubDomainUrl']) ?? 'Another Profile') . '</strong>!'
                        ];
                        echo json_encode($response);
                        return;
                    }
                }

                foreach ($Profile as $key => $value) {
                    $record[$key] = $value;
                }
                $Crud->UpdateRecord("extended_profiles", $record, array("UID" => $id));
                $response['status'] = 'success';
                $response['message'] = 'Updated Successfully...!';
            }
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Fields Cant Be Empty...!';
        }
        echo json_encode($response);
    }

    public function delete_default_lookup()
    {
        $Crud = new Crud();
        $id = $this->request->getVar('id');
        $Crud->DeleteRecord('extended_lookups', array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public function get_default_lookup_record()
    {
        $Crud = new Crud();
        $id = $this->request->getVar('id');

        $record = $Crud->SingleRecord("extended_lookups", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function delete_default_config()
    {
        $Crud = new Crud();
        $id = $this->request->getVar('id');
        $Crud->DeleteRecord('extended_admin_setings', array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public function delete_profile()
    {
        $Crud = new Crud();
        $id = $this->request->getVar('id');
        $Crud->DeleteRecord('extended_profiles', array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public function get_default_config_record()
    {
        $Crud = new Crud();
        $id = $this->request->getVar('id');

        $record = $Crud->SingleRecord("extended_admin_setings", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    function extended_admin_user_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();

        $DBName = $this->request->getVar('DBName');
//        $AccessLevels = $Main->GetCEConfigItem('AccessLevel');
        $AccessLevels = array();
        $result = [];

        foreach ($AccessLevels as $key => $value) {
            foreach ($value as $accesslevel => $description) {
                $result[] = $accesslevel;
            }
        }

        $id = $this->request->getVar('id');
        $name = $this->request->getVar('name');
        $username = $this->request->getVar('user_name');
        $email = $this->request->getVar('email');
        $contactno = $this->request->getVar('contactno');
        $password = $this->request->getVar('password');
        $usertype = $this->request->getVar('usertype');
        $branch = $this->request->getVar('branch') ?: 0;

        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $DBName = 'clinta_extended';
        }

        $custom = [
            'DSN' => '',
            'hostname' => PGDB_HOST,
            'username' => PGDB_USER,
            'password' => 'PostgreSql147',
            'database' => $DBName,
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
        $builder->where('Username', $username);
        $query = $builder->get();
        $records = $query->getResultArray();

        if ($id == 0) {
            if (!empty($records)) {
                $data = [
                    'status' => "fail",
                    'form_type' => "add",
                    'message' => "User Name Already Exist...!"
                ];
            } else {
                // Start transaction
                $ExtendedDb->transStart();

                $data = [
                    'Username' => $username,
                    'Password' => $password,
                    'FullName' => $name,
                    'MobileNo' => $contactno ?: '',
                    'AccessLevel' => $usertype,
                    'Email' => $email ?: '',
                    'BranchID' => $branch,
                    'Archive' => 0,
                ];

                if ($ExtendedDb->table('clinta.AdminUsers')->insert($data)) {
                    $insert_id = $ExtendedDb->insertID();
                    foreach ($result as $r) {
                        $ExtendedDb->table('clinta.AccessLevel')->insert([
                            'UserID' => $insert_id,
                            'AccessKey' => $r,
                            'Access' => 1
                        ]);
                    }
                    $ExtendedDb->transComplete();
                    $data = [
                        'status' => "success",
                        'form_type' => "add",
                        'message' => "User Account Successfully Added...!"
                    ];
                } else {
                    $ExtendedDb->transRollback();
                    $data = [
                        'status' => "fail",
                        'form_type' => "add",
                        'message' => "Error...!"
                    ];
                }
            }
        } else {
            $ExtendedDb->transStart();
            $builder->select('*');
            $builder->where('Username', $username);
            $builder->where('UID !=', $id);
            $query = $builder->get();
            $row = $query->getRowArray();

            if ($row) {
                $data = [
                    'status' => "fail",
                    'form_type' => "edit",
                    'message' => "User Name Already Exist...!"
                ];
            } else {
                $data = [
                    'Username' => $username,
                    'FullName' => $name,
                    'MobileNo' => $contactno ?: '',
                    'AccessLevel' => $usertype,
                    'Email' => $email ?: '',
                    'BranchID' => $branch,
                    'Archive' => 0,
                ];

                if ($password != '') {
                    $data['Password'] = $password;
                }

                $builder->where('UID', $id);
                if ($builder->update($data)) {
                    $ExtendedDb->transComplete();
                    $data = [
                        'status' => "success",
                        'form_type' => "edit",
                        'message' => "User Account Successfully updated...!"
                    ];
                } else {
                    $ExtendedDb->transRollback();
                    $data = [
                        'status' => "fail",
                        'form_type' => "edit",
                        'message' => "Error...!"
                    ];
                }
            }
        }

        echo json_encode($data);
    }

    public function get_record()
    {
        $Crud = new Crud();
        $ExtendedModel = new ExtendedModel();

        $dbname = $_POST['dbname'];
        $id = $_POST['uid'];
        $record = $ExtendedModel->GetExtendedUserDataByDBOrID($dbname, $id);
//        $record = $Crud->SingleRecordExtended('clinta."AdminUsers"', array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record[0];
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public
    function update_extended_admin_settings()
    {

        //echo'<pre>';print_r( $_REQUEST );exit;

        $DBName = $this->request->getVar('DBName');
        $data = array();
        $SKey = array();
        $Keys = '';

        foreach ($_POST as $K => $V) {
            if ($K == 'specialized_mode') {
                foreach ($V as $arr) {
                    $SKey[] = $arr;
                }
                $Keys = implode(",", $SKey);
                $data [$K] = $Keys;

            } else {
                $data [$K] = $V;
            }
        }

        if ($_FILES['profile_logo']) {
            $data ['profile_logo'] = $_FILES['profile_logo'];
        }

        $data['DBName'] = $DBName;

        $updateResponse = $this->UpdateAdminSettings($data);

        $result = array();
        if ($updateResponse == true) {
            $result['status'] = 'success';
            $result['msg'] = 'Successfully updated Admin Settings';
        } else {
            $result['status'] = 'fail';
            $result['msg'] = 'Fail to update system settings';

        }
        echo json_encode($result);
    }

    public function UpdateAdminSettings($result = [])
    {
        // Set database name based on environment
        $DBName = ($_SERVER['HTTP_HOST'] == 'localhost') ? 'clinta_extended' : $result['DBName'];

        // Define the custom database configuration
        $custom = [
            'DSN' => '',
            'hostname' => PGDB_HOST,
            'username' => PGDB_USER,
            'password' => PGDB_PASS,
            'database' => $DBName,
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

        // Establish database connection
        $ExtendedDb = \Config\Database::connect($custom);

        // Clear descriptions except for `profile_logo`
        $ExtendedDb->transStart();
        $ExtendedDb->table('clinta.AdminSettings')
            ->set('Description', '')
            ->where('Key !=', 'profile_logo')
            ->update();
        $ExtendedDb->transComplete();

        // Process each setting in the input array
        $ExtendedDb->transStart();
        $cnt = 0;
        foreach ($result as $key => $value) {
            if ($key === 'DBName') {
                continue; // Skip DBName
            }

            if ($key === 'profile_logo') {
                // Handle profile_logo specifically
                if (is_object($value) && $value->isValid() && !$value->hasMoved()) {
                    $fileContents = file_get_contents($value->getTempName());

                    if (!empty($fileContents)) {
                        $ExtendedDb->table('clinta.AdminSettings')
                            ->set('Description', base64_encode($fileContents))
                            ->set('OrderNo', $cnt)
                            ->where('Key', $key)
                            ->update();
                    }
                } else {
                    $ExtendedDb->table('clinta.AdminSettings')
                        ->set('OrderNo', $cnt)
                        ->where('Key', $key)
                        ->update();
                }
            } else {
                // Handle other settings
                $ExtendedDb->table('clinta.AdminSettings')
                    ->set('Description', $value)
                    ->set('OrderNo', $cnt)
                    ->where('Key', $key)
                    ->update();
            }

            $cnt++;
        }
        $ExtendedDb->transComplete();

        return true;
    }

    public function search_filter()
    {
        $session = session();
        $URL = $this->request->getVar('URL');
        $FullName = $this->request->getVar('FullName');
        $Status = $this->request->getVar('Status');
        $AllFilter = array(
            'URL' => $URL,
            'FullName' => $FullName,
            'Status' => $Status,

        );
        $session->set('ExtendedProfileFilters', $AllFilter);
        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }

    public function CreateSubdomainsWorker()
    {
        header('Content-Type: application/json');
        $subdomain = $this->request->getVar('subdomain');
        if (!empty($subdomain)) {
            $this->CreateSubDomains($subdomain);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Sub Domains Created Successfully.'
        ]);
        return;
    }

    private function CreateSubDomains($subdomain = '')
    {
        /** Auto Creating Domain Code */
        $parts = explode('.', $subdomain);
        $cpanel_domain = $parts[0];
        create_subdomain_cpanel(trim($cpanel_domain), 'clinta.biz', 'extended.clinta.biz');
    }

    public function CreateDataBaseWorker()
    {
        header('Content-Type: application/json');
        $database = $this->request->getVar('database');
        if (!empty($database)) {
            $this->CreatePostgresSQLDataBase($database);
        }

        echo json_encode(array(
            'status' => 'success',
            'message' => 'DataBase Created Successfully.'
        ));
        return;
    }

    private function CreatePostgresSQLDataBase($database = '')
    {
        /** Auto Creating DataBase Code */
        create_postgres_db_with_existing_user(trim($database), 'dright_maindb');
    }
}
