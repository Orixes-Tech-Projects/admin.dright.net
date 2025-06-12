<?php

namespace App\Controllers;

use App\Models\BuilderModel;
use App\Models\Crud;
use App\Models\HealthcareModel;
use App\Models\Main;
use App\Models\SystemUser;

class Home extends BaseController
{
    var $data = array();
    var $MainModel;

    public function __construct()
    {
        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
    }
    public function invoice()
    {
        $data = $this->data;
        $UID = getSegment(3);
        $data['UID'] = $UID;

        echo view('header', $data);
        echo view('invoice', $data);
        echo view('footer', $data);
    }
    public function testing()
    {
        $data = $this->data;
        echo '<pre>';
        $Crud = new Crud();
         $Query = 'SELECT "UID" FROM clinta."AdminLog"';
         $records = $Crud->ExecutePgSQLClinta($Query);
         print_r($records);


//        $Main = new Main();
//        echo $Main->CRYPT("Shaheryar", "hide");
//        echo $Main->CRYPT("U2hhaGVyeWFy", "show");
    }

    public function index()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('home', $data);
        echo view('footer', $data);
    }
    public function ipaddress()
    {
        $ipAddress = $this->request->getIPAddress();
        return $ipAddress;

    }

    public function fruit_search_filter()
    {
        $session = session();
        $Categories = $this->request->getVar('Categories');
        $AllFilter = array(
            'Categories' => $Categories,

        );


        //        print_r($AllCVFilter);exit;
        $session->set('FruitFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }

    public function fetch_frenchises()
    {
        $Healthcare = new HealthcareModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Healthcare->get_frenchises_datatables($keyword);
        $totalfilterrecords = $Healthcare->count_frenchises_datatables($keyword);
//        print_r($Data);
//        exit();
        $system = new SystemUser();

        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $Actions = [];

            if( $system->checkAccessKey('healthcare_branches_update') )
                $Actions[] = '<a class="dropdown-item" onclick="UpdateBranches(' . htmlspecialchars($record['UID']) . ')">Update</a>';
            if( $system->checkAccessKey('healthcare_branches_delete') )
                $Actions[] = '<a class="dropdown-item" onclick="DeleteBranches(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            $data[] = $cnt;
            $data[] = (isset($record['ProfileImage']) && $record['ProfileImage'] != '')
                ? '<img src="' . PATH . 'upload/franchise/' . $record['ProfileImage'] . '" class="img-thumbnail" style="height:80px;">'
                : '<img class="img-thumbnail" style="height:40px;" src="' . PATH . 'upload/franchise/no-images.png">';
            $data[] = isset($record['FullName']) ? htmlspecialchars($record['FullName']) : '';
            $data[] = isset($record['Email']) ? htmlspecialchars($record['Email']) : '';
            $data[] = isset($record['ShortProfile']) ? htmlspecialchars($record['ShortProfile']) : '';
            $data[] = isset($record['ShortBusinessDesc']) ? htmlspecialchars($record['ShortBusinessDesc']) : '';
            $data[] = isset($record['Status'])
                ? ($record['Status'] == 2
                    ? 'requested'
                    : ($record['Status'] == 1
                        ? 'active'
                        : 'block'))
                : '';
//            echo 'fff00';exit();

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

    /**
     * Clears the given session variable.
     *
     * @return JSON
     */
    public
    function clear_session()
    {
        $session = session();
        $SessionName = $this->request->getVar('SessionName');

        $session->set($SessionName, array());

        $response = array();
        $response['status'] = 'success';
        $response['message'] = "Filters Updated Successfully";
        echo json_encode($response);
    }

    /**
     * Displays the login view.
     *
     * Loads the login view and passes the necessary data to it for rendering.
     */
    public function login()
    {
        $data = $this->data;
        echo view('login1', $data);
    }

    public function load_image()
    {
        $Code = getSegment(2);
        $Code = base64_decode($Code);
        list($driver, $table, $uid) = explode('|', $Code);
        $Crud = new Crud();
        header('Content-Type: image');


        if ($driver == 'mysql') {
            $record = $Crud->SingleRecord($table, array('UID' => $uid));

            if ($table == 'general_banners') {
                $column = 'Image';
            }
            if ($table == 'sponsors') {
                $column = 'Image';
            }
            if ($table == 'discount_center') {
                $column = 'Image';
            }
            if ($table == 'sponsors_products') {
                $column = 'Image';
            }

            $image = base64_decode($record[$column]);
            echo $image;
        }
        if ($driver == 'pgsql') {
            $record = $Crud->SingleeRecord($table, array('UID' => $uid));

            if ($table == '') {
                $column = 'Image';
            }
            if ($table == 'profiles') {
                $column = 'Profile';
            }


            $image = base64_decode($record[$column]);
            echo $image;
        }
        exit;
    }
    public function promotion_material_file_download()
    {
        $code = getSegment(2);
        $code = explode("_", base64_decode($code));
        $table = $code[0];
        $id = $code[1];
        $extension = $code[2];

        // Set the correct Content-Type header based on the file extension
        $contentType = 'application/octet-stream'; // Default MIME type
        switch (strtolower($extension)) {
            case 'pdf':
                $contentType = 'application/pdf';
                break;
            case 'jpeg':
            case 'jpg':
                $contentType = 'image/jpeg';
                break;
            case 'png':
                $contentType = 'image/png';
                break;
            case 'doc':
                $contentType = 'application/msword';
                break;
            case 'docx':
                $contentType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                break;
            case 'xls':
                $contentType = 'application/vnd.ms-excel';
                break;
            case 'xlsx':
                $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
        }

        // Set headers
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="file.' . $extension . '"');

        // Fetch file content from the database
        switch ($table) {
            case 'product-material':
                $dbtable = 'sponsors_products_promotional_material';
                $column = 'File';
                $defaultimg = 'no-sponsors.jpg';
                $Crud = new Crud();
                $data = $Crud->SingleRecord($dbtable, array("UID" => $id));
                if (empty($data[$column])) {
                    $fileURL = ROOT . "/upload/discount/" . $defaultimg;
                    if (file_exists($fileURL)) {
                        echo file_get_contents($fileURL);
                    } else {
                        http_response_code(404);
                        echo "Default file not found.";
                    }
                } else {
                    // Decode the base64-encoded data
                    $decodedContent = base64_decode($data[$column]);
                    if ($decodedContent === false) {
                        http_response_code(500);
                        echo "Failed to decode file content.";
                    } else {
                        echo $decodedContent;
                    }
                }
                break;
            case 'task-attachments':
                $dbtable = 'builder_task_attachments';
                $column = 'File';
                $defaultimg = 'no-image.png';
                $Crud = new Crud();
                $data = $Crud->SingleeRecord($dbtable, array("UID" => $id));
                if (empty($data[$column])) {
                    $fileURL = ROOT . "/upload/" . $defaultimg;
                    if (file_exists($fileURL)) {
                        echo file_get_contents($fileURL);
                    } else {
                        http_response_code(404);
                        echo "Default file not found.";
                    }
                } else {
                    // Decode the base64-encoded data
                    $decodedContent = base64_decode($data[$column]);
                    if ($decodedContent === false) {
                        http_response_code(500);
                        echo "Failed to decode file content.";
                    } else {
                        echo $decodedContent;
                    }
                }
                break;
            default:
                // Handle unknown table
                http_response_code(404);
                echo "File not found.";
                break;
        }
    }
    public function load_image_meta()
    {
        $Code = getSegment(2);
        $Code = base64_decode($Code);
        list($driver, $table, $uid) = explode('|', $Code);
        $Crud = new Crud();
        $BuilderModel = new BuilderModel();
        header('Content-Type: image');


        $record= $BuilderModel->get_website_profile_meta_data_by_id_option($uid,'initatived_logo');
        //print_r($record);exit;
        if ($driver == 'pgsql') {
            if ($table == '') {
                $column = '';
            }
            if ($table == 'profile_metas') {
                $column = 'Value';

            }
            $image = base64_decode($record[0][$column]);
            echo $image;
        }


        exit;

    }

    public function table()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('table', $data);
        echo view('footer', $data);
    }

    /**
     * Handles the login process for the system users
     *
     * Retrieves the input email and password from the request object, encrypts the password, and then
     * checks for a matching record in the system_users table. If a match is found, sets the session
     * variables and returns a success response, otherwise returns a fail response.
     *
     * @return mixed
     */
    public function system_user_login_submit()
    {
        $Email = $this->request->getVar('inputEmail');
        $password = $this->request->getVar('Password');

        $Crud = new Crud();
        $Main = new Main();

        $response = array();
        $table = 'system_users';
        $password = $Main->CRYPT($password, 'hide');
        $where = array("Email" => $Email, "Password" => $password);

        $Record = $Crud->SingleRecord($table, $where);

        if (!empty($Record['UID'])) {
            $SessionArray = [
                'UID' => $Record['UID'],
                'Email' => $Record['Email'],
                'FullName' => $Record['FullName'],
                'AccessLevel' => $Record['AccessLevel'],
                'UserType' => '',
                'logged_in' => TRUE
            ];
            //  print_r($SessionArray);exit();
            $session = session();

            $session->set("Profile", $SessionArray);
            $_SESSION['Profile'] = $SessionArray;
            // $session->set("Profile", $SessionArray);

            $response['status'] = "success";
            $response['message'] = "You are successfully logged";
            $response['session'] = $session->get();
        } else {
            $response['status'] = "fail";
            $response['message'] = "Invalid Login Credentials, Please Try again...";
        }
        // $response['status'] = "fail";
        // $response['message'] = "Invalid Login Credentials, Please Try again...";
        echo json_encode($response);
    }

    /**
     * Destroys the session and redirects to the login page
     *
     * @return void
     */
    public function logout()
    {
        $data = $this->data;
        // $session = session();
        // $session->destroy();
        header("Location: " . $data['path'] . "login");
        exit;
    }

    public function use_login_submit()
    {
        //{           echo 'ffff' ;exit();
        $inputEmail = $this->request->getVar('UserName');
        $password = $this->request->getVar('Password');

        $Crud = new Crud();
        $Main = new Main();
        $ipAddress = $this->request->getIPAddress();

        $password = $Main->CRYPT($password, 'hide');

        $Crud = new Crud();
        $session = session();
        $response = array();
        $table = 'system_users';
        $where = array("Email" => $inputEmail, "Password" => $password);
        $Record = $Crud->SingleRecord($table, $where);
        //    print_r($Record);exit();

        if (isset($Record['UID'])) {
            $SessionArray = [
                'UID' => $Record['UID'],
                'Email' => $Record['Email'],
                'FullName' => $Record['FullName'],
                'AccessLevel' => $Record['AccessLevel'],
                //                'status' => $Record['Status'],
                'logged_in' => TRUE
            ];


            $session->set($SessionArray);

            $msg=$_SESSION['FullName'].' Logged In Admin Dright';
            $logesegment='Log IN';
            $Main->adminlog($logesegment, $msg,$ipAddress);

            $response['status'] = "success";
            $response['message'] = "You are successfully logged";
        } else {
            $response['status'] = "fail";
            $response['message'] = "Invalid Login Credentials, Please Try again...";
        }

        echo json_encode($response);
    }
    public function load_file()
    {
        $data = $this->data;
        ini_set( 'memory_limit', '512M' );
        ob_start();
        $Code = (getSegment( 2 ) == '') ? 0 : getSegment( 2 );
        $Code = explode( "_", base64_decode( $Code ) );
        $UID = end( $Code );
        $Crud = new Crud();
//        print_r($UID);exit;
        $File = $Crud->SingleeRecord( 'public."Files"', array ( "UID" => $UID ) );
//        echo "Load File Details: " . $File['UID']; echo $File['Ext']; exit;
        if ( !isset( $File[ 'UID' ] ) ) {
            $File = $Crud->SingleeRecord( 'public."Files"', array ( "UID" => 0 ) );
        }

        // send headers then display image
        header( 'Content-Type: ' . $File[ 'Ext' ] );
//        header("Last-Modified: " . gmdate('D, d M Y H:i:s', $File['SystemDate']) . " GMT");
        //header("Cache-Control: max-age=9999");
//        header("Expires: " . gmdate("D, d M Y H:i:s", time() + 99999) . "GMT");
        echo base64_decode( $File[ 'Content' ] );
        exit;
    }
}
