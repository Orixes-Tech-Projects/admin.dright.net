<?php

namespace App\Controllers;


use App\Models\BuilderModel;
use App\Models\Crud;
use App\Models\Main;
use App\Models\PharmacyModal;
use App\Models\SystemUser;

class Builder extends BaseController
{
    var $data = array();

    public function __construct()
    {

        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
//        $ipAddress = $this->request->getIPAddress();

    }

    public function index()
    {
        $data = $this->data;
        $data['page'] = getSegment(2);

        $BuilderModel = new \App\Models\BuilderModel();
        $PharmacyModal = new \App\Models\PharmacyModal();
        $data['Cities'] = $PharmacyModal->citites();
        $data['specialities'] = $BuilderModel->specialities();
        $data['Sponsors'] = $BuilderModel->get_all_sponsors();
        $data['extended_profiles'] = $BuilderModel->extended_profiles();
        $data['PAGE'] = array();

        echo view('header', $data);
        if ($data['page'] == 'add-doctor') {
            echo view('builder/main_form', $data);

        } elseif ($data['page'] == 'add-hospital') {

            echo view('builder/hospital_main_form', $data);

        } elseif ($data['page'] == 'specialities') {

            echo view('builder/specialities', $data);

        } elseif ($data['page'] == 'update-doctor') {
            $UID = getSegment(3);
            $data['UID'] = $UID;
            $Crud = new Crud();
            $PAGE = $Crud->SingleeRecord('public."profiles"', array("UID" => $UID));
            $data['PAGE'] = $PAGE;
            echo view('builder/main_form', $data);

        } elseif ($data['page'] == 'update-hospital') {
            $UID = getSegment(3);
            $data['UID'] = $UID;
            $Crud = new Crud();
            $PAGE = $Crud->SingleeRecord('public."profiles"', array("UID" => $UID));
            $data['PAGE'] = $PAGE;
            echo view('builder/hospital_main_form', $data);

        } elseif ($data['page'] == 'hospital') {
            echo view('builder/hospital', $data);

        } elseif ($data['page'] == 'images') {
            echo view('builder/images', $data);

        } elseif ($data['page'] == 'banners') {
            $BuilderModel = new \App\Models\BuilderModel();
            $data['specialities'] = $BuilderModel->specialities();
            echo view('builder/banners', $data);

        } else {
//            $data['doctors'] = $BuilderModel->Allprofiless('doctors');
//            print_r($data['doctors'] );exit();
            echo view('builder/index', $data);
        }
        echo view('footer', $data);
    }

    public function dashboard()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('builder/dashboard', $data);
        echo view('footer', $data);
    }

    public function gallery()
    {
        $BuilderModel = new \App\Models\BuilderModel();

        $data = $this->data;
        $UID = getSegment(3);
        $data['Images'] = $BuilderModel->get_speciality_images_by_id($UID);
        $data['UID'] = $UID;
        echo view('header', $data);
        echo view('builder/specialities_gallery', $data);
        echo view('footer', $data);
    }
    public function add_theme()
    {
        $BuilderModel = new \App\Models\BuilderModel();

        $data = $this->data;
        $UID = getSegment(3);
//        $data['Metas'] = $BuilderModel->GetThemeSettingsDataByID($UID);
        $data['UID'] = $UID;
        echo view('header', $data);
        echo view('builder/hospital_meta', $data);
        echo view('footer', $data);
    }

    public function gallery_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();

        $SpecialityID = $this->request->getVar('SpecialityID');
        $size = $this->request->getVar('size');
        if (isset($_FILES['Image'])) {
            $data = array();

            $files = $_FILES;
            $count = count($_FILES['Image']['name']);
            $IMAGEERROR = array();
            for ($i = 0; $i < $count; $i++) {

                $icon = '';
                if ($_FILES['Image']['tmp_name'] != '') {
                    $ext = @end(@explode(".", basename($files['Image']['name'][$i])));
                    $uploaddir = ROOT . "/upload/specialities/";
                    $uploadfile = strtolower($Main->RandFileName() . "." . $ext);


                    //if ( $this->upload->do_upload( 'image' ) ) {
                    if (move_uploaded_file($files['Image']['tmp_name'][$i], $uploaddir . $uploadfile)) {

                        $record['SpecialityUID'] = $SpecialityID;
                        $record['Option'] = $size[$i];
                        $record['Value'] = $uploadfile;
                        $RecordId = $Crud->AddRecord('speciality_metas', $record);
                        if (isset($RecordId) && $RecordId > 0) {
                            $response['status'] = 'success';
                            $response['message'] = 'Speciality Images Added Successfully...!';
                        } else {
                            $response['status'] = 'fail';
                            $response['message'] = 'Data Didnt Submitted Successfully...!';
                        }
                    }


                }
            }
        }
        echo json_encode($response);
    }


    public function fetch_banners()
    {
        $BuilderModel = new BuilderModel();
        $Data = $BuilderModel->get_datatables();
        $totalfilterrecords = $BuilderModel->count_datatables();
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {

            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['Alignment']) ? htmlspecialchars($record['Alignment']) : '';
            $data[] = isset($record['Color']) ? htmlspecialchars($record['Color']) : '';
            $data[] = isset($record['Title']) ? htmlspecialchars($record['Title']) : '';
            $data[] = isset($record['Image'])
                ? '<img src="' . load_image('mysql|general_banners|' . $record['UID']) . '" style="display: block; padding: 2px; border: 1px solid #145388 !important; border-radius: 3px; width: 150px;">'
                : '';
            $data[] = '
    <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                Actions
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" onclick="DeleteBanner(' . htmlspecialchars($record['UID']) . ')">Delete</a>

            </div>
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

    public function delete_banner()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
        $Crud->DeleteRecord("general_banners", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public function fetch_images()
    {
        $BuilderModel = new BuilderModel();
        $Data = $BuilderModel->get_images_datatables();
        $totalfilterrecords = $BuilderModel->count_image_datatables();
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {

            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['Filename']) ? '<img src="' . PATH . 'upload/specialities/' . $record['Filename'] . '" class="img-thumbnail" style="height:80px;">' : '';
            $data[] = '
    <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                Actions
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" onclick="DeleteImage(' . htmlspecialchars($record['UID']) . ')">Delete</a>

            </div>
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

    public function fetch_doctors()
    {
        $BuilderModel = new BuilderModel();
        $PharmacyModal = new PharmacyModal();
        $Users = new SystemUser();

        $type = 'doctors';
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $BuilderModel->get_doct_datatables($type, $keyword);
        $totalfilterrecords = $BuilderModel->count_doct_datatables($type, $keyword);
//        $SmsCredits = $BuilderModel->get_profile_options_data_by_id_option(315, 'sms_credits');

//        print_r($Data);exit();
        $dataarr = array();
        $cnt = $_POST['start'];
//            echo 'ddddd00';exit();
        foreach ($Data as $record) {
            $Actions = [];
            if( $Users->checkAccessKey('builder_doctor_profiles_update') )
                $Actions[] = '<a class="dropdown-item" onclick="EditDoctors(' . htmlspecialchars($record['UID']) . ')">Update</a>';

            if( $Users->checkAccessKey('builder_doctor_profiles_delete') )
                $Actions[] = '<a class="dropdown-item" onclick="DeleteDoctor(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            if( $Users->checkAccessKey('builder_doctor_profiles_add_theme') )
                $Actions[] = '<a class="dropdown-item" onclick="AddTheme(' . htmlspecialchars($record['UID']) . ')">Add Theme</a>';

            $cnt++;
            $SmsCredits = $BuilderModel->get_profile_options_data_by_id_option($record['UID'], 'sms_credits');
            $TeleMedicineCredits = $BuilderModel->get_profile_options_data_by_id_option($record['UID'], 'telemedicine_credits');
            $Sponsor = $BuilderModel->get_profile_options_data_by_id_option($record['UID'], 'sponsor');
            $Sponsor = (isset($Sponsor[0]['UID']) && $Sponsor[0]['Description'] != '') ? $Sponsor[0]['Description'] : 0;
            $city = $PharmacyModal->getcitybyid($record['City']);

            // Determine row color based on specific conditions
            $class = ($record['SubDomain'] == '') ? 'background-color: #FFD4DB;' : '';
            if ($record['LastVisitDateTime'] == date("Y-m-d")) {
                $class = 'background-color: #D7FFCD;';
            }

            // Check last visit date format
            $lastVisit = !empty($record['LastVisitDateTime']) ? date("d M, Y", strtotime($record['LastVisitDateTime'])) : "N/A";

//            $ping = ping($record['SubDomain']);
            $pingicon = isset($record['SubDomain']) ? '<span class="fa fa-check ks-icon btn-success"></span>' : '<span class="fa fa-ban ks-icon btn-danger"></span>';

            // Add all necessary table columns to data array
            $data = [];
            $data[] = $cnt;
            $data[] = $record['Name'];
//            $data[] = '<img src="' . load_image('sponsors_' . $Sponsor) . '" height="45">';
            $data[] = $record['Email'];
            $data[] = isset($city[0]['FullName'])?$city[0]['FullName']:'';

            // TeleMedicine Credits Column
            $telemedicineCredits = isset($TeleMedicineCredits[0]['Description']) && $TeleMedicineCredits[0]['Description'] != ''
                ? '<strong>' . $TeleMedicineCredits[0]['Description'] . '</strong> TeleMedicine Credits<br>
                <button class="btn btn-outline-success" onclick="AddTeleMedicineCredits(' . $record['UID'] . ', 50);"><strong>50</strong></button>
                <button  class="btn btn-outline-success" onclick="AddTeleMedicineCredits(' . $record['UID'] . ', 100);"><strong>100</strong></button>'
                : '<button  class="btn btn-outline-success" onclick="AddTeleMedicineCredits(' . $record['UID'] . ', 100);"><strong>Free Credits</strong></button>';
            $data[] = $telemedicineCredits;

            // SMS Credits Column
            $smsCredits = isset($SmsCredits[0]['Description']) && $SmsCredits[0]['Description'] != ''
                ? '<strong>' . $SmsCredits[0]['Description'] . '</strong> SMS Credits<br>
                <button  class="btn btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 250);"><strong>250</strong></button>
                <button  class="btn btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 500);"><strong>500</strong></button>'
                : '<button  class="btn btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 100);"><strong>Free Credits</strong></button>';
            $data[] = $smsCredits;


            $data[] = $lastVisit;
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

    public
    function fetch_hospitals()
    {
        $BuilderModel = new BuilderModel();
        $PharmacyModal = new PharmacyModal();
        $type = 'hospitals';
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $BuilderModel->get_doct_datatables($type, $keyword);
        $totalfilterrecords = $BuilderModel->count_doct_datatables($type, $keyword);
        $Users = new SystemUser();
        print_r($Data);exit;
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $Actions = [];
                $Actions[] = '<a class="dropdown-item" onclick="Updatehospital(' . htmlspecialchars($record['UID']) . ')">Update</a>';

            if( $Users->checkAccessKey('builder_hospital_profiles_delete') )
                $Actions[] = '<a class="dropdown-item" onclick="DeleteHospital(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            if( $Users->checkAccessKey('builder_hospital_profiles_add_theme') )
                $Actions[] = '<a class="dropdown-item" onclick="AddTheme(' . htmlspecialchars($record['UID']) . ')">Add Theme</a>';
  if( $Users->checkAccessKey('builder_banners_add') )
                $Actions[] = '<a class="dropdown-item" onclick="AddBanner(' . htmlspecialchars($record['UID']) . ')">Add Individualized Banner</a>';

            $cnt++;
            $SmsCredits = $BuilderModel->get_profile_options_data_by_id_option($record['UID'], 'sms_credits');
            $city = $PharmacyModal->getcitybyid($record['City']);

            $data = [];
            $data[] = $cnt;
            $data[] = $record['Name'];
//            $data[] = '<img src="' . load_image('sponsors_' . $Sponsor) . '" height="45">';
            $data[] = $record['Email'];
            $data[] = $city[0]['FullName'];
            $data[] = $record['SubDomain'];


            // SMS Credits Column
            $smsCredits = isset($SmsCredits[0]['Description']) && $SmsCredits[0]['Description'] != ''
                ? '<strong>' . $SmsCredits[0]['Description'] . '</strong> SMS Credits<br>
                <button  class="btn btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 250);"><strong>250</strong></button>
                <button  class="btn btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 500);"><strong>500</strong></button>'
                : '<button  class="btn btn-gradient-warning" onclick="AddSmsCredits(' . $record['UID'] . ', 100);"><strong>Free Credits</strong></button>';
            $data[] = $smsCredits;


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

    public
    function fetch_specialities()
    {
        $BuilderModel = new BuilderModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');
        $Users = new SystemUser();

        $Data = $BuilderModel->get_specialities_datatables($keyword);
        $totalfilterrecords = $BuilderModel->count_specialities_datatables($keyword);

//        print_r($totalfilterrecords);exit();
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $Actions = [];
            if( $Users->checkAccessKey('builder_specialities_update') )

                $Actions[] = '<a class="dropdown-item" onclick="Editspecialities(' . htmlspecialchars($record['UID']) . ')">Update</a>';

            if( $Users->checkAccessKey('builder_specialities_delete') )
                $Actions[] = '<a class="dropdown-item" onclick="Deletespecialities(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            if( $Users->checkAccessKey('builder_specialities_heading') )
                $Actions[] = '<a class="dropdown-item" onclick="Addheading(' . htmlspecialchars($record['UID']) . ')">Add heading</a>';
            if( $Users->checkAccessKey('builder_specialities_gallery') )
                $Actions[] = '<a class="dropdown-item" onclick="AddGallery(' . htmlspecialchars($record['UID']) . ')">Add Gallery</a>';

            $cnt++;
            if ($record['Icon'] != '') {
                if (file_exists(ROOT . "/upload/specialities/" . $record['Icon'])) {
                    $file = $record['Icon'];
                } else {
                    $file = 'no-image.png';
                }
            } else {
                $file = 'no-image.png';

            }

            $TotalSpecialities = count($BuilderModel->get_speciality_images_by_id($record['UID']));
//            print_r($TotalSpecialities);exit();
            $data = [];
            $data[] = $cnt;
            $data[] = $record['Name'];
            $data[] = '<img src="' . PATH . 'upload/specialities/' . $file . '" height="45">';
            $data[] = isset($TotalSpecialities) ? $TotalSpecialities : '0';


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

    public
    function delete_images()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
//        print_r($id);exit();
        $Crud->DeleteRecord("websites_images", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public
    function delete_specialities_image()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
//        print_r($id);exit();
        $Crud->DeleteRecord("speciality_metas", array("UID" => $id));

        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public
    function delete_doctor()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
//        print_r($id);exit();
        $Crud->DeleteRecordPG('public."profiles"', array("UID" => $id));
        $Crud->DeleteRecordPG('public."profile_metas"', array("ProfileUID" => $id));
        $Main = new Main();

        $msg=$_SESSION['FullName'].' Delete Doctor Through Admin Dright';
        $logesegment='Doctor';
        $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public
    function delete_hospital()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
//        print_r($id);exit();
        $Crud->DeleteRecordPG('public."profiles"', array("UID" => $id));
        $Crud->DeleteRecordPG('public."profile_metas"', array("ProfileUID" => $id));
        $Main = new Main();

        $msg=$_SESSION['FullName'].' Delete Hospital Through Admin Dright';
        $logesegment='Hospital';
        $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public
    function delete_specialities()
    {
        $BuilderModel = new BuilderModel();
        $Crud = new Crud();
        $response = array();

        $id = $this->request->getVar('id');
        $record = $BuilderModel->get_speciality_images_by_id($id);
        if (count($record) === 0) {

            $response['message'] = 'No Images Found';

        } else {
            foreach ($record as $r) {
                @unlink("upload/specialities/" . $r['Value']);
            }
            $Crud->DeleteRecord('public."speciality_metas"', array("SpecialityUID" => $id));

        }
        $Crud->DeleteRecord('specialities', array("UID" => $id));
        $Main = new Main();

        $msg=$_SESSION['FullName'].' Delete specialities Through Admin Dright';
        $logesegment='Specialities';
        $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
        $response['status'] = 'success';
        $response['message'] .= ' And Specialities Deleted Successfully...!';
        echo json_encode($response);
    }

    public
    function delete_specialities_meta()
    {
        $BuilderModel = new BuilderModel();
        $Crud = new Crud();
        $response = array();

        $id = $this->request->getVar('id');

        $Crud->DeleteRecord('speciality_metas', array("UID" => $id));

        $response['status'] = 'success';
        $response['message'] = '  Specialities Meta Deleted Successfully...!';
        echo json_encode($response);
    }

    public
    function submit_general_image()
    {
        $Crud = new Crud();
        $Main = new Main();
        $alignment = $this->request->getVar('alignment');
        $color = $this->request->getVar('color');
        $speciality = $this->request->getVar('speciality');
//        print_r($alignment);exit();

        if ($this->request->getFile('profile') && $this->request->getFile('profile')->isValid()) {

            $file = $this->request->getFile('profile');
            $fileName = $file->getName();
            $fileExt = strtolower($file->getClientExtension());

            // Define allowed extensions
            $allowedExt = ['gif', 'jpg', 'jpeg', 'png', 'webp'];

            // Check if the file extension is allowed
            if (!in_array($fileExt, $allowedExt)) {
                $data = ['status' => 'error', 'msg' => 'Invalid file type. Only GIF, JPG, JPEG, WEBP, and PNG files are allowed.'];
                echo json_encode($data);
                exit;
            }

            $newWidth = 1200;
            if ($file->isValid() && !$file->hasMoved()) {
                list($width, $height) = getimagesize($file->getTempName());
                $width = $width ?: 1200;
                $height = $height ?: 800;
                $newWidth = ($width > $newWidth) ? $width : $newWidth;

                $fileContent = $Main->image_uploader($file, $newWidth, $height);
//                echo json_encode(['status' => 'success', 'data' => $fileContent]);
//                exit;
            } else {
                $fileContent = '';
            }
        } else {
            $data = ['status' => 'error', 'msg' => 'No file selected or invalid extension.'];
            echo json_encode($data);
            exit;
        }
        $record['Alignment'] = $alignment;
        $record['Color'] = $color;
        $record['Speciality'] = $speciality;
        $record['Image'] = $fileContent;
        $RecordId = $Crud->AddRecord('general_banners', $record);
        if (isset($RecordId) && $RecordId > 0) {
            $response['status'] = 'success';
            $response['message'] = 'General Banners Added Successfully...!';
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Data Didnt Submitted Successfully...!';
        }
        echo json_encode($response);

    }

    public
    function get_specialities_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("specialities", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public
    function add_telemedicine_credits()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
        $record = array();

        $newcredits = $_POST['newcredits'];
//        print_r($id);exit();
        $option = $Crud->SingleeRecord('public."options"', array("ProfileUID" => $id, 'Name' => 'telemedicine_credits'));
        $oldcredits = 0;
        if (isset($option['Description'])) {
            $oldcredits = $option['Description'];
        }


        $Crud->DeleteRecordPG('public."options"', array("ProfileUID" => $id, 'Name' => 'telemedicine_credits'));
        $record['ProfileUID'] = $id;
        $record['Name'] = 'telemedicine_credits';
        $record['Description'] = $oldcredits + $newcredits;
        $RecordId = $Crud->AddRecordPG('public."options"', $record);
        if (isset($RecordId) && $RecordId > 0) {
            $Main = new Main();

            $msg=$_SESSION['FullName'].' Add Telemedicine Credit Through Admin Dright';
            $logesegment='Telemedicine Credit';
            $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
            $response['status'] = 'success';
            $response['message'] = 'Telemedicine Credits Added Successfully...!';
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Data Didnt Submitted Successfully...!';
        }
        echo json_encode($response);
    }

    public
    function submit_specialities()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $tag = $this->request->getVar('tag');
        $name = $this->request->getVar('name');
        $id = $this->request->getVar('UID');
        $icon = '';
        if ($_FILES['icon']['tmp_name'] != '') {
            $ext = @end(@explode(".", basename($_FILES['icon']['name'])));
            $uploaddir = ROOT . "/upload/specialities/";
            $uploadfile = strtolower($Main->RandFileName() . "." . $ext);

            if (move_uploaded_file($_FILES['icon']['tmp_name'], $uploaddir . $uploadfile)) {
                $icon = $uploadfile;
            }
        }

        if ($id == 0) {
            $record['Tag'] = $tag;
            $record['Name'] = $name;

            if ($icon != "") {
                $record['Icon'] = $icon;
            }
//            print_r($record);exit();

            $RecordId = $Crud->AddRecord("specialities", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = ' Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            $record['Tag'] = $tag;
            $record['Name'] = $name;

            if ($icon != "") {
                $record['Icon'] = $icon;


            }
            $Crud->UpdateRecord("specialities", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = ' Updated Successfully...!';
        }
        echo json_encode($response);

    }

    public
    function submit_specialities_meta()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $id = $this->request->getVar('UID');
        $SpecialityID = $this->request->getVar('SpecialityID');
        $meta = $this->request->getVar('meta');
        $name = $this->request->getVar('name');

        if ($id == 0) {
            $record['SpecialityUID'] = $SpecialityID;
            $record['Option'] = $meta;
            $record['Value'] = $name;


            $RecordId = $Crud->AddRecord("speciality_metas", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = ' Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            $record['SpecialityUID'] = $SpecialityID;
            $record['Option'] = $meta;
            $record['Value'] = $name;
            $Crud->UpdateRecord("speciality_metas", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = ' Updated Successfully...!';
        }
        echo json_encode($response);

    }

    public
    function add_sms_credits()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
        $record = array();

        $newcredits = $_POST['newcredits'];
        $option = $Crud->SingleeRecord('public."options"', array("ProfileUID" => $id, 'Name' => 'sms_credits'));
        $oldcredits = 0;
        if (isset($option['Description'])) {
            $oldcredits = $option['Description'];
        }
        $Crud->DeleteRecordPG('public."options"', array("ProfileUID" => $id, 'Name' => 'sms_credits'));
        $record['ProfileUID'] = $id;
        $record['Name'] = 'sms_credits';
        $record['Description'] = $oldcredits + $newcredits;
        $RecordId = $Crud->AddRecordPG('public."options"', $record);
        $Main = new Main();

        $msg=$_SESSION['FullName'].' Add SMS Credit Through Admin Dright';
        $logesegment='SMS Credit';
        $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
        if (isset($RecordId) && $RecordId > 0) {
            $response['status'] = 'success';
            $response['message'] = 'SMS Credits Added Successfully...!';
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Data Didnt Submitted Successfully...!';
        }
        echo json_encode($response);
    }

    public
    function image_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();


        $msg=$_SESSION['FullName'].' Specialities Image Submit Through Admin Dright';
        $logesegment='Image Submit';
        $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
        $filename = "";

        if ($_FILES['Image']['tmp_name']) {
            $ext = @end(@explode(".", basename($_FILES['Image']['name'])));
            $uploaddir = ROOT . "/upload/specialities/";
            $uploadfile = strtolower($Main->RandFileName() . "." . $ext);

            if (move_uploaded_file($_FILES['Image']['tmp_name'], $uploaddir . $uploadfile)) {
                $filename = $uploadfile;
            }
        }

        if ($filename != "") {
            $record['Filename'] = $filename;
        }
//            print_r($record);exit();
        $RecordId = $Crud->AddRecord("websites_images", $record);
        if (isset($RecordId) && $RecordId > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Added Successfully...!';
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Data Didnt Submitted Successfully...!';
        }


        echo json_encode($response);
    }

    public
    function hospitals_profile_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();
        $records = array();
        $id = $this->request->getVar('UID');
        $email = $this->request->getVar('email');
        $ContactNo = $this->request->getVar('ContactNo');
//        $file = $this->request->getFile('profile');
        // Load the uploaded file using CodeIgniter's services
        $file = $this->request->getFile('profile');
        $fileContents = '';
        if ($file->isValid() && !$file->hasMoved()) {
            $fileContents = file_get_contents($file->getTempName());

        }

//        print_r($fileContents);exit();
//        $file = file_get_contents($_FILES['profile']['tmp_name']);
//        echo 'dddddd';exit();
        if ($id == 0) {
            $Data = $Crud->SingleeRecord('public."profiles"', array("Email" => $email, 'ContactNo' => $ContactNo));

            if (!empty($Data) && $Data['UID'] > 0) {
                if ($Data['ContactNo'] == $ContactNo) {

                    $responce = array();
                    $responce['status'] = 'fail';
                    $responce['message'] = '<strong>Contact No</strong> Already Assign to <strong>' . (($Data['SubDomain'] != '') ? $Data['SubDomain'] : $Data['Name']) . '</strong> ...!';
                    echo json_encode($responce);

                } else if ($Data['Email'] == $email) {

                    $responce = array();
                    $responce['status'] = 'fail';
                    $responce['message'] = '<strong>Email</strong> Already Assign to <strong>' . (($Data['SubDomain'] != '') ? $Data['SubDomain'] : $Data['Name']) . '</strong> ...!';
                    echo json_encode($responce);
                }

            } else {
                $subdomain = $this->request->getVar('sub_domain');
                $record['Type'] = 'hospitals';
                $record['Name'] = $this->request->getVar('name');
                $record['Email'] = $this->request->getVar('email');
                $record['Password'] = $this->request->getVar('password');
                $record['City'] = $this->request->getVar('city');
                $record['ContactNo'] = $this->request->getVar('ContactNo');
                $record['SubDomain'] = $subdomain;
//                $fileContents = '';
                if ($fileContents != '') {
                    $record['Profile'] = base64_encode($fileContents);

                } else {
                    $record['Profile'] = '';

                }
                $website_profile_id = $Crud->AddRecordPG("public.profiles", $record);

                if ($website_profile_id) {
                    $theme = $this->request->getVar('theme');
                    $Options = array('theme_css' => 'dore.light.red.css', 'theme' => ((isset($theme) && $theme != '') ? $theme : ''), 'sms_credits' => 100, 'notify_sms' => 1, 'notify_email' => 1);
                    foreach ($Options as $key => $value) {

                        if ($value != '') {
                            $record_option['ProfileUID'] = $website_profile_id;
                            $record_option['Name'] = $key;
                            $record_option['Description'] = $value;

                            $id = $Crud->AddRecordPG("public.options", $record_option);
                        }
                    }
                    $ExtendedArray = array('clinta_extended_profiles', 'short_description');
                    foreach ($ExtendedArray as $M) {


                        $record_meta['ProfileUID'] = $website_profile_id;
                        $record_meta['Option'] = $M;
                        $record_meta['Value'] = $this->request->getVar($M);

                        $id = $Crud->AddRecordPG("public.profile_metas", $record_meta);

                    }

//
//                    $message = 'Dear Clinta Support,
//"' . $this->request->getVar('name') . '" New Hospital Added Successfully in Clinta Apanel,
//Please Assign SubDomain.';
//                    $Main->send('03155913609', $message);


//                    if (isset($subdomain) && $subdomain != '') {
//                        $mobile = $this->request->getVar('contact_no');
//                        $message = 'Dear ' . $this->request->getVar('name') . ',
//Congratulations, your own website has been created.
//URL: http://' . $subdomain . '
//Email: ' . $this->request->getVar('email') . '
//Password: ' . $this->request->getVar('password');
//                        $Main->send($mobile, $message);
//                    }
//                    echo 'sdvsdv';exit();
                    $msg=$_SESSION['FullName'].' Hospital Profile Submit Through Admin Dright';
                    $logesegment='Hospitals';
                    $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
                    $responce = array();
                    $responce['status'] = "success";
                    $responce['id'] = $website_profile_id;
                    $responce['message'] = "Hospitals Profile Added Successfully.....!";


                } else {

                    $responce = array();
                    $responce['status'] = "fail";
                    $responce['message'] = "Error in Adding Hospitals Profile...!";
                }
            }

        }
        else {
//            $Data = $Crud->SingleeRecord('public."profiles"', array("Email" => $email, 'ContactNo' => $ContactNo));
//
//            if (!empty($Data) && $Data['UID'] > 0) {
//                if ($Data['ContactNo'] == $ContactNo) {
//
//                    $responce = array();
//                    $responce['status'] = 'fail';
//                    $responce['message'] = '<strong>Contact No</strong> Already Assign to <strong>' . (($Data['SubDomain'] != '') ? $Data['SubDomain'] : $Data['Name']) . '</strong> ...!';
//                    echo json_encode($responce);
//
//                } else if ($Data['Email'] == $email) {
//
//                    $responce = array();
//                    $responce['status'] = 'fail';
//                    $responce['message'] = '<strong>Email</strong> Already Assign to <strong>' . (($Data['SubDomain'] != '') ? $Data['SubDomain'] : $Data['Name']) . '</strong> ...!';
//                    echo json_encode($responce);
//                }
//
//            } else {

            $subdomain = $this->request->getVar('sub_domain');
            $record['Type'] = 'hospitals';
            $record['Name'] = $this->request->getVar('name');
            $record['Email'] = $this->request->getVar('email');
            $record['Password'] = $this->request->getVar('password');
            $record['City'] = $this->request->getVar('city');
            $record['ContactNo'] = $this->request->getVar('ContactNo');
            $record['SubDomain'] = $subdomain;
            if ($fileContents != '') {
                $record['Profile'] = base64_encode($fileContents);

            }
            $website_profile_id = $Crud->UpdateeRecord("public.profiles", $record, array('UID' => $id));
//                print_r($website_profile_id);exit();
            if ($website_profile_id) {
                $ExtendedArray = array('clinta_extended_profiles', 'short_description', 'healthcare_status', 'patient_portal');
                foreach ($ExtendedArray as $EA) {
                    $Crud->DeleteRecordPG('public."profile_metas"', array("ProfileUID" => $id, 'Option' => $EA));
                }
                foreach ($ExtendedArray as $M) {


                    $record_meta['ProfileUID'] = $id;
                    $record_meta['Option'] = $M;
                    $record_meta['Value'] = $this->request->getVar($M);

                    $idd = $Crud->AddRecordPG("public.profile_metas", $record_meta);


                }

                $theme = $this->request->getVar('theme');
                $Options = array('theme_css' => 'dore.light.red.css', 'theme' => ((isset($theme) && $theme != '') ? $theme : ''), 'sms_credits' => 100, 'notify_sms' => 1, 'notify_email' => 1);

                foreach ($Options as $key => $value) {

                    $Data = $Crud->SingleeRecord('public."options"', array("ProfileUID" => $id, 'Name' => $key));
//                                print_r($Data);

                    if (isset($Data['UID'])) {
                        $record_option['Description'] = $value;
                        $website_profile_id = $Crud->UpdateeRecord("public.options", $record_option, array('UID' => $Data['UID']));

                    } else {
                        $record_option['Description'] = $value;
                        $record_option['Name'] = $key;
                        $record_option['ProfileUID'] = $id;

                        $id = $Crud->AddRecordPG("public.options", $record_option);

                    }
//                            echo 'cccc';exit();


                }
            }
            $msg=$_SESSION['FullName'].' Hospital Profile Update Through Admin Dright';
            $logesegment='Hospitals';
            $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
//            }
            $responce = array();
            $responce['status'] = "success";
            $responce['id'] = $id;
            $responce['message'] = "Hospitals Profile Updated Successfully.....!";
        }

        echo json_encode($responce);

    }


    public
    function doctors_profile_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();
        $records = array();
        $record_meta = array();
        $logo_record = array();
        $record_option = array();
        $id = $this->request->getVar('UID');
        $email = $this->request->getVar('email');
        $ContactNo = $this->request->getVar('ContactNo');

        $file = $this->request->getFile('profile');
        $initatived_logo = $this->request->getFile('initatived_logo');
        $fileContents = '';
        $fileinitatived_logo = '';
        if ($file->isValid() && !$file->hasMoved()) {
            // Read the file contents
            $fileContents = file_get_contents($file->getTempName());

            // Now you can process $fileContents as needed
        }//        echo 'dddd';
//        exit();
        if ($initatived_logo->isValid() && !$initatived_logo->hasMoved()) {
            // Read the file contents
            $fileinitatived_logo = file_get_contents($initatived_logo->getTempName());
//            print_r($initatived_logo);exit();

        }


        if ($id == 0) {

            $Data = $Crud->SingleeRecord('public."profiles"', array("Email" => $email, 'ContactNo' => $ContactNo));

            if (!empty($Data) && $Data['UID'] > 0) {
                if ($Data['ContactNo'] == $ContactNo) {

                    $responce = array();
                    $responce['status'] = 'fail';
                    $responce['message'] = '<strong>Contact No</strong> Already Assign to <strong>' . (($Data['SubDomain'] != '') ? $Data['SubDomain'] : $Data['Name']) . '</strong> ...!';
                    echo json_encode($responce);

                } else if ($Data['Email'] == $email) {

                    $responce = array();
                    $responce['status'] = 'fail';
                    $responce['message'] = '<strong>Email</strong> Already Assign to <strong>' . (($Data['SubDomain'] != '') ? $Data['SubDomain'] : $Data['Name']) . '</strong> ...!';
                    echo json_encode($responce);
                }

            } else {


                $subdomain = $this->request->getVar('sub_domain');
                $AdminDomain = $this->request->getVar('AdminDomain');

                $record['Type'] = 'doctors';
                $record['Name'] = $this->request->getVar('name');
                $record['Email'] = $this->request->getVar('email');
                $record['Password'] = $this->request->getVar('password');
                $record['City'] = $this->request->getVar('city');
                $record['ContactNo'] = $this->request->getVar('ContactNo');
                $record['SubDomain'] = $subdomain;
                $record['AdminDomain'] = $AdminDomain;

                if ($fileContents != '') {
                    $record['Profile'] = base64_encode($fileContents);

//                    $pgsql->set('Profile', base64_encode($file));

                } else {
                    $record['Profile'] = '';

//                    $pgsql->set('Profile', '');
                }
                $website_profile_id = $Crud->AddRecordPG("public.profiles", $record);

                if ($website_profile_id) {


                    $logos = array();


                    $records['ProfileUID'] = $website_profile_id;
                    $records['Option'] = 'initatived_logo';
                    $records['Value'] = base64_encode($fileinitatived_logo);

                    $id = $Crud->AddRecordPG("public.profile_metas", $records);
                    $Metas = array('speciality', 'qualification', 'pmdcno', 'department', 'short_description', 'telemedicine_id', 'initatived_text', 'healthcare_status', 'patient_portal');

                    foreach ($Metas as $M) {



                            $record_meta['ProfileUID'] = $website_profile_id;
                            $record_meta['Option'] = $M;
                            $record_meta['Value'] = $this->request->getVar($M);

                            $id = $Crud->AddRecordPG("public.profile_metas", $record_meta);


                    }

                    $Sponsor = $this->request->getVar('sponsor');
                    $theme = $this->request->getVar('theme');
                    $Options = array('award_nav' => 'show', 'patient_nav' => 'show', 'research_nav' => 'show', 'theme_css' => 'dore.light.red.css', 'custom_banners' => '5', 'theme' => ((isset($theme) && $theme != '') ? $theme : ''), 'sms_credits' => 100, 'notify_sms' => 1, 'notify_email' => 1, 'sponsor' => ((isset($Sponsor) && $Sponsor != '') ? $Sponsor : ''));
                    foreach ($Options as $key => $value) {


                            $record_option['ProfileUID'] = $website_profile_id;
                            $record_option['Name'] = $key;
                            $record_option['Description'] = $value;

                            $id = $Crud->AddRecordPG("public.options", $record_option);


                    }

                    $response = array();
                    $msg=$_SESSION['FullName'].' Doctor Profile Submit Through Admin Dright';
                    $logesegment='Doctor';
                    $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
                    $response['status'] = "success";
                    $response['id'] = $website_profile_id;
                    $response['message'] = "Doctor Profile Added Suuccessfully.....!";
                } else {
                    $response = array();
                    $response['status'] = "fail";
                    $response['message'] = "Error in Adding Doctors Profile...!";
                }
            }

        } else {

            $subdomain = $this->request->getVar('sub_domain');
            $AdminDomain = $this->request->getVar('AdminDomain');
            $record['Type'] = 'doctors';
            $record['Name'] = $this->request->getVar('name');
            $record['Email'] = $this->request->getVar('email');
            $record['Password'] = $this->request->getVar('password');
            $record['City'] = $this->request->getVar('city');
            $record['ContactNo'] = $this->request->getVar('ContactNo');
            if (isset($AdminDomain) && $AdminDomain != '') {

                $record['AdminDomain'] = $AdminDomain;

            }
            if (isset($subdomain) && $subdomain != '') {
                $record['SubDomain'] = $subdomain;

                $mobile = $this->request->getVar('ContactNo');
            }
            if ($fileContents != '') {
                $record['Profile'] = base64_encode($fileContents);
            }
            $updateid = $Crud->UpdateeRecord("public.profiles", $record, array('UID' => $id));
//                $pgsql->where('UID', $id);
            if ($updateid > 0) {

                $Sponsor = $this->request->getVar('sponsor');
                if (isset($Sponsor) && $Sponsor != '') {
                    $sql = 'SELECT * FROM public."options" WHERE "ProfileUID" = \'' . $id . '\' AND "Name" = \'sponsor\'';
                    $SponsorData = $Crud->ExecutePgSQL($sql);

                    if (isset($SponsorData['UID'])) {
                        $updateid = $Crud->UpdateeRecord("public.options", array('Description' => $Sponsor), array('UID' => $SponsorData['UID']));

                    } else {
                        $record_option['Description'] = $Sponsor;
                        $record_option['Name'] = 'sponsor';
                        $record_option['ProfileUID'] = $id;
                        $id = $Crud->AddRecordPG("public.options", $record_option);

                    }
                }
                $Metas = array('speciality', 'qualification', 'pmdcno', 'department', 'short_description', 'telemedicine_id', 'initatived_text', 'healthcare_status');
                foreach ($Metas as $M) {

                        $sql = 'SELECT * FROM public."profile_metas" WHERE "ProfileUID" = \'' . $id . '\' AND  "Option"= \'' . $M . '\'';
                        $ProfileMetaData = $Crud->ExecutePgSQL($sql);
                        if (isset($ProfileMetaData['UID'])) {
                            $Crud->DeleteRecordPG("public.profile_metas", array('UID' => $ProfileMetaData['UID']));
                            $record_meta['Value'] = $this->request->getVar($M);
                            $record_meta['Option'] = $M;
                            $record_meta['ProfileUID'] = $id;
                            $id = $Crud->AddRecordPG("public.profile_metas", $record_meta);
                        } else {
                            $record_meta['Value'] = $this->request->getVar($M);
                            $record_meta['Option'] = $M;
                            $record_meta['ProfileUID'] = $id;
                            $id = $Crud->AddRecordPG("public.profile_metas", $record_meta);
                        }

                }


                $theme = $this->request->getVar('theme');
                $Options = array('theme' => ((isset($theme) && $theme != '') ? $theme : ''));
                $Options_record = array();
                foreach ($Options as $key => $value) {


                        $Data = $Crud->SingleeRecord('public."options"', array("ProfileUID" => $id, 'Name' => $key));

                        if (isset($Data['UID'])) {
                            $Crud->DeleteRecordPG("public.options", array('UID' => $Data['UID']));
                            $Options_record['Description'] = $value;
                            $Options_record['Name'] = $key;
                            $Options_record['ProfileUID'] = $id;
                            $id = $Crud->AddRecordPG("public.options", $Options_record);

                        } else {
                            $Options_record['Description'] = $value;
                            $Options_record['Name'] = $key;
                            $Options_record['ProfileUID'] = $id;
                            $id = $Crud->AddRecordPG("public.options", $Options_record);
                        }


                }


                $Data = $Crud->SingleeRecord('public."profile_metas"', array("ProfileUID" => $id, 'Option' => 'initatived_logo'));

                if (isset($Data['UID'])) {

                    $Crud->DeleteRecordPG("public.profile_metas", array('UID' => $Data['UID']));
                    $records['ProfileUID'] = $id;
                    $records['Option'] = 'initatived_logo';
                    $records['Value'] = base64_encode($fileinitatived_logo);

                    $id = $Crud->AddRecordPG("public.profile_metas", $records);

                } else {
                    $records['ProfileUID'] = $id;
                    $records['Option'] = 'initatived_logo';
                    $records['Value'] = base64_encode($fileinitatived_logo);

                    $id = $Crud->AddRecordPG("public.profile_metas", $records);
                }

                $msg=$_SESSION['FullName'].' Doctor Profile Update Through Admin Dright';
                $logesegment='Doctor';
                $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
                $response = array();
                $response['status'] = "success";
                $response['id'] = $id;
                $response['message'] = "Doctors Profile Updated Suuccessfully.....!";

            } else {

                $response = array();
                $response['status'] = "fail";
                $response['message'] = "Error in Updating Doctors Profile...!";
            }

        }


        echo json_encode($response);
    }

    public
    function load_speciality_metas_data_grid()
    {
        $BuilderModel = new BuilderModel();

        $id = $this->request->getVar('id');
        $option = $this->request->getVar('option');
//            print_r($option);exit();
        $Data = $BuilderModel->get_speciality_meta_data_by_id_option($id, $option); //print_r($id); exit;
//            print_r($option);exit();
        if (count($Data) > 0) {

            $html = '';

            $html .= '<div class="col-md-12">
							<div class="table table-responcive">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th width="30">Sr.No</th>
											<th>Name</th>
											<th width="40">Action</th>
										</tr>
									</thead>
									<tbody>';
            $cnt = 0;
            foreach ($Data as $D) {
                $cnt++;
                $html .= '<tr>
															<td>' . $cnt . '</td>
															<td>' . $D['Value'] . '</td>
															<td><a href="javascript:void(0);" onclick="DeleteSpecialityMetas( ' . $D['UID'] . ' );"><i style="color:red;" class="fa fa-trash"></i></a></td>
														</tr>';
            }

            $html .= '</tbody>
								</table>
							</div>
						</div>';
        } else {
            $html = ' No records found';
        }

        echo $html;
    }

    public
    function hospital_search_filter()
    {
        $session = session();
//        $Key = $this->request->getVar( 'Key' );
        $city = $this->request->getVar('City');
        $Name = $this->request->getVar('Name');


        $AllFilter = array(
//            'Key' => $Key,
            'City' => $city,
            'Name' => $Name,

        );


//        print_r($AllFilter);exit();
        $session->set('HospitalFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }  public
    function doctor_search_filter()
    {
        $session = session();
//        $Key = $this->request->getVar( 'Key' );
        $city = $this->request->getVar('City');
        $Name = $this->request->getVar('Name');
        $AllFilter = array(
//            'Key' => $Key,
            'City' => $city,
            'Name' => $Name,

        );

//        print_r($AllFilter);exit();
        $session->set('DoctorFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }

    public
    function sponser()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('builder/sponser', $data);
        echo view('footer', $data);
    }

    public
    function sponsor_product()
    {
        $data = $this->data;
        $data['UID'] = getSegment(3);
        echo view('header', $data);
        echo view('builder/sponsor_product', $data);
        echo view('footer', $data);
    }

    public
    function fetch_sponser()
    {
        $BuilderModel = new BuilderModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $BuilderModel->get_sponser_datatables($keyword);
//        print_r($Data);exit();
        $totalfilterrecords = $BuilderModel->count_sponser_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['Name']) ? htmlspecialchars($record['Name']) : '';
            $data[] = isset($record['OrderID']) ? htmlspecialchars($record['OrderID']) : '';
//            $data[] = isset($record['Image']) ? "<img src='" . load_image('sponsors_' . $record['UID']) . "' style='width: 100px;'>" : '';
            $data[] = isset($record['Image'])
                ? "<img src='" . load_image('mysql|sponsors|' . $record['UID']) . "' style='display: block; padding: 2px; border: 1px solid #145388 !important; border-radius: 3px; width: 150px;' />"
                : '';
            $imageurl = load_image('mysql|sponsors|' . $record['UID']);
            $data[] = '
    <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                Actions
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" onclick="UpdateSponser(\'' . htmlspecialchars($record['UID']) . '\', \'' . htmlspecialchars($imageurl) . '\')">Update</a>
                <a class="dropdown-item" onclick="DeleteSponser(\'' . htmlspecialchars($record['UID']) . '\')">Delete</a>
                <a class="dropdown-item" onclick="SponserProduct(\'' . htmlspecialchars($record['UID']) . '\')">Sponser Product</a>
            </div>
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

    public
    function submit_sponser()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();
        $fileImage='';
        $id = $this->request->getVar('UID');
        $Sponsor = $this->request->getVar('Sponsor');

        $Image = $this->request->getFile('Image');
//print_r($Image);exit();
        if ($Image->isValid() && !$Image->hasMoved()) {
            $fileImage = file_get_contents($Image->getTempName());

        }
        if ($id == 0) {
            foreach ($Sponsor as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }
            if ($fileImage !=''){
                $record['Image'] = base64_encode($fileImage);

            }            $RecordId = $Crud->AddRecord("sponsors", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $msg=$_SESSION['FullName'].' Submit Sponser Through Admin Dright';
                $logesegment='Sponsors';
                $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Sponsor as $key => $value) {
                $record[$key] = $value;
            }
            if ($fileImage !=''){
                $record['Image'] = base64_encode($fileImage);

            }
            $Crud->UpdateRecord("sponsors", $record, array("UID" => $id));
            $msg=$_SESSION['FullName'].' Update Sponsor Through Admin Dright';
            $logesegment='Sponsors';
            $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public
    function submit_sponser_product()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();
        $fileImage='';

        $id = $this->request->getVar('UID');
        $Sponsor = $this->request->getVar('SponsorProduct');
        $Image = $this->request->getFile('Image');
//print_r($Image);exit();
        if ($Image->isValid() && !$Image->hasMoved()) {
            $fileImage = file_get_contents($Image->getTempName());

        }
//        if ($this->request->getFile('Image')->isValid()) {
//            $file = $Main->upload_image('Image', 1024);
//        } else {
//            $file = '';
//        }
//        print_r($this->request->getFile('Image'));
//        exit();

        if ($id == 0) {
            foreach ($Sponsor as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }
            if ($fileImage !=''){
                $record['Image'] = base64_encode($fileImage);

            }            $RecordId = $Crud->AddRecord("sponsors_products", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $msg=$_SESSION['FullName'].' Submit Sponsor Product Through Admin Dright';
                $logesegment='Sponsors Product';
                $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Sponsor as $key => $value) {
                $record[$key] = $value;
            }
            if ($fileImage !=''){
                $record['Image'] = base64_encode($fileImage);

            }

            $Crud->UpdateRecord("sponsors_products", $record, array("UID" => $id));
            $msg=$_SESSION['FullName'].' Submit Sponsor Product Through Admin Dright';
            $logesegment='Sponsors Product';
            $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public
    function delete_sponser()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();

        $table = "sponsors";
        $record['Archive'] = 1;
        $where = array('UID' => $UID);
        $Crud->UpdateRecord($table, $record, $where);
        $Main = new Main();

        $msg=$_SESSION['FullName'].' Delete Sponsor Through Admin Dright';
        $logesegment='Sponsors';
        $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';

        echo json_encode($response);
    }

    public
    function delete_sponser_product()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();
        $table = "sponsors_products";
        $record['Archive'] = 1;
        $where = array('UID' => $UID);
        $Crud->UpdateRecord($table, $record, $where);
        $Main = new Main();

        $msg=$_SESSION['FullName'].' Delete Sponsor Product Through Admin Dright';
        $logesegment='Sponsors Product';
        $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';

        echo json_encode($response);
    }

    public
    function get_sponser_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("sponsors", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public
    function get_sponser_product_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("sponsors_products", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public
    function fetch_sponsor_product()
    {
        $BuilderModel = new BuilderModel();
        $ID = $this->request->getVar('UID');
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $BuilderModel->get_sponsor_product_datatables($ID, $keyword);
        $totalfilterrecords = $BuilderModel->count_sponsor_product_datatables($ID, $keyword);

        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['Name']) ? htmlspecialchars($record['Name']) : '';
            $data[] = isset($record['PackSize']) ? htmlspecialchars($record['PackSize']) : '';
            $data[] = isset($record['Image']) ? "<img src='" . load_image('mysql|sponsors_products|' . $record['UID']) . "' style='width: 100px;'>" : '';

            $data[] = '
    <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                Actions
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" onclick="UpdateSponsorProduct(\'' . htmlspecialchars($record['UID']) . '\', \'' . htmlspecialchars($ID) . '\')">Update</a>
                <a class="dropdown-item" onclick="DeleteSponserProduct(\'' . htmlspecialchars($record['UID']) . '\')">Delete</a>
            </div>
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
    public function theme_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $ProfileUID = $this->request->getVar('ProfileUID');
        $id = $this->request->getVar('id');
        $option = $this->request->getVar('option');

//print_r($Lookup);exit();
        if ($id == 0) {
            foreach ($option as $key => $value) {
                $Crud->DeleteRecordPG("public.options", array('Name' => $key));

                $record['Name'] = $key;
                $record['Description'] = ((isset($value)) ? $value : '');
                $record['ProfileUID']=$ProfileUID;
                $RecordId = $Crud->AddRecordPG('public."options"', $record);
            }
            $msg=$_SESSION['FullName'].' Update Theme Setting Through Admin Dright';
            $logesegment='Hospital';
            $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }

        }

        echo json_encode($response);
    }


}