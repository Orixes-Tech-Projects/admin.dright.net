<?php

namespace App\Controllers;


use App\Models\BuilderModel;
use App\Models\Crud;
use App\Models\Invoices;
use App\Models\Main;
use App\Models\PharmacyModal;
use App\Models\ProfileDuplicate;
use App\Models\SystemUser;

class Builder extends BaseController
{
    var $data = array();

    public function __construct()
    {

        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
        helper('cpanel');
    }

    public
    function test()
    {

        $ProfileDuplicate = new ProfileDuplicate();
        $ProfileDuplicate->GetProfileDoctorsRecordAndInsert(164, 545);
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
        } elseif ($data['page'] == 'add-mini-hims') {
            echo view('builder/mini_hims_form', $data);
        } elseif ($data['page'] == 'add-promotional-websites') {
            echo view('builder/promotional_websites_form', $data);
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
        } elseif ($data['page'] == 'update-mini-hims') {
            $UID = getSegment(3);
            $data['UID'] = $UID;
            $Crud = new Crud();
            $PAGE = $Crud->SingleeRecord('public."profiles"', array("UID" => $UID));
            $data['PAGE'] = $PAGE;
            echo view('builder/mini_hims_form', $data);
        } elseif ($data['page'] == 'update-promotional-websites') {
            $UID = getSegment(3);
            $data['UID'] = $UID;
            $Crud = new Crud();
            $PAGE = $Crud->SingleeRecord('public."profiles"', array("UID" => $UID));
            $data['PAGE'] = $PAGE;
            echo view('builder/promotional_websites_form', $data);
        } elseif ($data['page'] == 'hospital') {
            echo view('builder/hospital', $data);

        } elseif ($data['page'] == 'images') {
            echo view('builder/images', $data);

        } elseif ($data['page'] == 'banners') {
            $BuilderModel = new \App\Models\BuilderModel();
            $data['specialities'] = $BuilderModel->specialities();
            echo view('builder/banners', $data);

        } elseif ($data['page'] == 'mini_hims') {
            echo view('builder/mini_hims', $data);

        } elseif ($data['page'] == 'promotional-websites') {
            echo view('builder/promotional_websites', $data);

        } else {
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
            $data[] = isset($record['Type']) ? '<b>' . htmlspecialchars(ucwords(str_replace('-', ' ', $record['Type']))) . '</b>' : '';
            $data[] = isset($record['Title']) ? htmlspecialchars($record['Title']) : '';
            $data[] = isset($record['Alignment']) ? htmlspecialchars(ucwords($record['Alignment'])) : '';
            $data[] = isset($record['Image'])
                ? '<img src="' . load_image('mysql|general_banners|' . $record['UID']) . '" style="display: block; padding: 2px; border: 1px solid #145388 !important; border-radius: 3px; width: 150px;">'
                : '';
            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a style="cursor: pointer;" class="dropdown-item" onclick="DeleteBanner(' . htmlspecialchars($record['UID']) . ')">Delete</a>
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
        $dataarr = array();
        $cnt = $_POST['start'];

        foreach ($Data as $record) {
            $Actions = [];
            if ($Users->checkAccessKey('builder_doctor_profiles_update'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="EditDoctors(' . htmlspecialchars($record['UID']) . ')">Update</a>';
            $Actions[] = '<a target="_blank" href="' . PATH . 'builder/third-party-script/' . $record['UID'] . '" style="cursor:pointer;" class="dropdown-item">Third Party Scripts</a>';

            if ($Users->checkAccessKey('builder_doctor_profiles_delete'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="DeleteDoctor(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            if ($Users->checkAccessKey('builder_doctor_profiles_add_theme'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="AddTheme(' . htmlspecialchars($record['UID']) . ')">Add Theme</a>';

            $cnt++;
            $SmsCredits = $BuilderModel->get_profile_options_data_by_id_option($record['UID'], 'sms_credits');
            $TeleMedicineCredits = array(); // $BuilderModel->get_profile_options_data_by_id_option($record['UID'], 'telemedicine_credits');
            $Sponsor = $BuilderModel->get_profile_options_data_by_id_option($record['UID'], 'sponsor');
            $Sponsor = (isset($Sponsor[0]['UID']) && $Sponsor[0]['Description'] != '') ? $Sponsor[0]['Description'] : 0;
            $CityName = '-';
            if (isset($record['City']) && $record['City'] > 0) {
                $city = $PharmacyModal->getcitybyid($record['City']);
                $CityName = ((isset($city[0]['FullName']) && $city[0]['FullName'] != '') ? $city[0]['FullName'] : '-');
            }

            $class = ($record['SubDomain'] == '') ? 'background-color: #FFD4DB;' : '';
            if ($record['LastVisitDateTime'] == date("Y-m-d")) {
                $class = 'background-color: #D7FFCD;';
            }

            // Check last visit date format
            $lastVisit = !empty($record['LastVisitDateTime']) ? date("d M, Y", strtotime($record['LastVisitDateTime'])) : "N/A";

            $data = [];
            $data[] = $cnt;
            $data[] = $record['Name'];
            $data[] = ((isset($record['ContactNo']) && $record['ContactNo'] != '') ? $record['ContactNo'] : '-');
            $data[] = !empty($record['SubDomain']) ? '<a style="color:crimson;" title="Click To View" href="https://' . $record['SubDomain'] . '" target="_blank">' . $record['SubDomain'] . '</a>' : '-';
            $data[] = $CityName;
            $data[] = '<badge class="badge badge-' . (($record['Status'] == 'active') ? 'success' : 'danger') . '">' . ucwords($record['Status']) . '</badge>';
            $data[] = ((isset($record['ExpireDate']) && $record['ExpireDate'] != '') ? '<b>' . date('d M, Y', strtotime($record['ExpireDate'])) . '</b>' : '<badge class="badge badge-danger">Expired</badge>');
            $data[] = $record['Email'];
            $data[] = $lastVisit;
            $data[] = '<td class="text-end">
                        <div class="dropdown">
                            <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
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
        $data = $this->data;

        $BuilderModel = new BuilderModel();
        $PharmacyModal = new PharmacyModal();
        $type = 'hospitals';
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $BuilderModel->get_doct_datatables($type, $keyword);
        $totalfilterrecords = $BuilderModel->count_doct_datatables($type, $keyword);
        $Users = new SystemUser();
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $Actions = [];
            $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="Updatehospital(' . htmlspecialchars($record['UID']) . ')">Update</a>';
            $Actions[] = '<a target="_blank" href="' . PATH . 'builder/third-party-script/' . $record['UID'] . '" style="cursor:pointer;" class="dropdown-item">Third Party Scripts</a>';
            if ($Users->checkAccessKey('builder_hospital_profiles_delete'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="DeleteHospital(' . htmlspecialchars($record['UID']) . ')">Delete</a>';

            if ($Users->checkAccessKey('builder_hospital_profiles_add_theme'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="AddTheme(' . htmlspecialchars($record['UID']) . ')">Add Theme</a>';
            if ($Users->checkAccessKey('builder_banners_add'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="LoadIndividualBannerModal(' . htmlspecialchars($record['UID']) . ')">Individualized Banner</a>';

            $cnt++;
            $SmsCredits = $BuilderModel->get_profile_options_data_by_id_option($record['UID'], 'sms_credits');
            $CityName = '-';
            if (isset($record['City']) && $record['City'] > 0) {
                $city = $PharmacyModal->getcitybyid($record['City']);
                $CityName = ((isset($city[0]['FullName']) && $city[0]['FullName'] != '') ? $city[0]['FullName'] : '-');
            }
            $lastVisit = !empty($record['LastVisitDateTime']) ? date("d M, Y", strtotime($record['LastVisitDateTime'])) : "N/A";

            $data = [];
            $data[] = $cnt;
            $data[] = $record['Name'];
            $data[] = !empty($record['SubDomain']) ? '<a title="Click to View" style="color:crimson" href="https://' . $record['SubDomain'] . '" target="_blank">' . $record['SubDomain'] . '</a>' : '';
            $data[] = $CityName;
            $data[] = '<badge class="badge badge-' . (($record['Status'] == 'active') ? 'success' : 'danger') . '">' . ucwords($record['Status']) . '</badge>';
            $data[] = ((isset($record['ExpireDate']) && $record['ExpireDate'] != '') ? '<b>' . date('d M, Y', strtotime($record['ExpireDate'])) . '</b>' : '<badge class="badge badge-danger">Expired</badge>');
            $data[] = $record['Email'];
            $data[] = $lastVisit;

            $smsCredits = isset($SmsCredits[0]['Description']) && $SmsCredits[0]['Description'] != ''
                ? '<strong>' . $SmsCredits[0]['Description'] . '</strong> SMS Credits<br>
                <button style="border-radius: 5px;" class="btn btn-gradient-warning btn-sm" onclick="AddSmsCredits(' . $record['UID'] . ', 250);"><strong>250</strong></button>
                <button style="border-radius: 5px;"  class="btn btn-gradient-warning btn-sm" onclick="AddSmsCredits(' . $record['UID'] . ', 500);"><strong>500</strong></button>'
                : '<button style="border-radius: 5px;" class="btn btn-gradient-warning btn-sm" onclick="AddSmsCredits(' . $record['UID'] . ', 100);"><strong>Free Credits</strong></button>';
            $data[] = $smsCredits;


            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
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

        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $Actions = [];
            if ($Users->checkAccessKey('builder_specialities_update'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="UpdateSpecialities(' . htmlspecialchars($record['UID']) . ')">Update</a>';
            if ($Users->checkAccessKey('builder_specialities_delete'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="Deletespecialities(' . htmlspecialchars($record['UID']) . ')">Delete</a>';
            if ($Users->checkAccessKey('builder_specialities_heading'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="AddHeadings(' . htmlspecialchars($record['UID']) . ')">Add heading</a>';
            if ($Users->checkAccessKey('builder_specialities_gallery'))
                $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="AddGallery(' . htmlspecialchars($record['UID']) . ')">Add Gallery</a>';

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

            $data = [];
            $data[] = $cnt;
            $data[] = '<img src="' . PATH . 'upload/specialities/' . $file . '" height="45">';
            $data[] = $record['Name'];
            $data[] = isset($TotalSpecialities) ? $TotalSpecialities : '0';

            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
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

        $msg = $_SESSION['FullName'] . ' Delete Doctor Through Admin Dright';
        $logesegment = 'Doctor';
        $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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

        $msg = $_SESSION['FullName'] . ' Delete Hospital Through Admin Dright';
        $logesegment = 'Hospital';
        $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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

        $msg = $_SESSION['FullName'] . ' Delete specialities Through Admin Dright';
        $logesegment = 'Specialities';
        $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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
        // echo'<pre>';print_r($_POST);exit;

        $Crud = new Crud();
        $Main = new Main();

        $Type = $this->request->getVar('type');
        $alignment = $this->request->getVar('alignment');
        $color = $this->request->getVar('color');
        $speciality = $this->request->getVar('speciality');

        if ($this->request->getFile('profile') && $this->request->getFile('profile')->isValid()) {

            $file = $this->request->getFile('profile');
            $fileName = $file->getName();
            $fileExt = strtolower($file->getClientExtension());
            $allowedExt = ['gif', 'jpg', 'jpeg', 'png', 'webp'];
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
            } else {
                $fileContent = '';
            }

        } else {
            $data = ['status' => 'error', 'msg' => 'No file selected or invalid extension.'];
            echo json_encode($data);
            exit;
        }

        $record['Type'] = ((isset($Type) && $Type != '') ? $Type : 'custom-text');
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

            $msg = $_SESSION['FullName'] . ' Add Telemedicine Credit Through Admin Dright';
            $logesegment = 'Telemedicine Credit';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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

        $msg = $_SESSION['FullName'] . ' Add SMS Credit Through Admin Dright';
        $logesegment = 'SMS Credit';
        $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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


        $msg = $_SESSION['FullName'] . ' Specialities Image Submit Through Admin Dright';
        $logesegment = 'Image Submit';
        $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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
        $file = $this->request->getFile('profile');
        $fileContents = '';
        if ($file->isValid() && !$file->hasMoved()) {
            $fileContents = file_get_contents($file->getTempName());
        }

        if ($id == 0) {

            $subdomain = $this->request->getVar('sub_domain');

            $EmailRecord = $Crud->SingleeRecord('public."profiles"', ["Email" => trim($email)]);
            if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                        (($EmailRecord['SubDomain'] ?? $EmailRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            $ContactNoRecord = $Crud->SingleeRecord('public."profiles"', ['ContactNo' => trim($ContactNo)]);
            if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                        (($ContactNoRecord['SubDomain'] ?? $ContactNoRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            if (trim($subdomain) != '') {

                $SubDomainRecord = $Crud->SingleeRecord('public."profiles"', ['SubDomain' => trim($subdomain)]);
                if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                            (($SubDomainRecord['SubDomain'] ?? $SubDomainRecord['Name']) ?? 'another user') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }
            }

            $record['Type'] = 'hospitals';
            $record['Name'] = $this->request->getVar('name');
            $record['Email'] = $this->request->getVar('email');
            $record['Password'] = $this->request->getVar('password');
            $record['City'] = $this->request->getVar('city');
            $record['ContactNo'] = $this->request->getVar('ContactNo');
            $record['SubDomain'] = $subdomain;
            if ($fileContents != '') {
                $record['Profile'] = base64_encode($fileContents);
            } else {
                $record['Profile'] = '';
            }
            $website_profile_id = $Crud->AddRecordPG("public.profiles", $record);
            if ($website_profile_id) {


                $themeOptions = array(
                    'banner_style' => 'version3',
                    'home_ceo_message' => 1,
                    'home_facilities' => 1,
                    'home_news' => 1,
                    'home_reviews' => 1,
                    'theme' => 'mist',
                    'theme_primary_color' => '#9d0101',
                    'theme_secondary_color' => '#ffffff',
                    'theme_header' => 'icon-header',
                    'theme_footer' => 'extended',
                    'theme_service' => 'version_1',
                    'theme_facilities' => 'simple-info-box',
                    'theme_facilities' => 'theme_specialities',
                );
                foreach ($themeOptions as $key => $value) {

                    if ($value != '') {
                        $themerecord['ProfileUID'] = $website_profile_id;
                        $themerecord['Name'] = $key;
                        $themerecord['Description'] = $value;
                        $Crud->AddRecordPG("public.options", $themerecord);
                    }
                }

                $theme = $this->request->getVar('theme');
                $PrescriptionSegment = $this->request->getVar('prescription_module');
                $PrescriptionPricingType = $this->request->getVar('prescription_pricing_type');
                $PerPrescriptionPrice = $this->request->getVar('prescription_price');

                $OPDInvoicing = $this->request->getVar('opd_invoicing');
                $OPDPricingType = $this->request->getVar('opd_pricing_type');
                $OPDInvoicePrice = $this->request->getVar('opd_invoice_price');

                $Options = array('theme_css' => 'dore.light.red.css', 'theme' => ((isset($theme) && $theme != '') ? $theme : ''),
                    'sms_credits' => 100, 'notify_sms' => 1, 'notify_email' => 1,
                    'prescription_module' => ((isset($PrescriptionSegment) && $PrescriptionSegment != '') ? $PrescriptionSegment : 0),
                    'prescription_pricing_type' => ((isset($PrescriptionPricingType) && $PrescriptionPricingType != '') ? $PrescriptionPricingType : 'with-subscription'),
                    'prescription_price' => ((isset($PerPrescriptionPrice) && $PerPrescriptionPrice != '') ? $PerPrescriptionPrice : 0),
                    'opd_invoicing' => ((isset($OPDInvoicing) && $OPDInvoicing != '') ? $OPDInvoicing : 0),
                    'opd_pricing_type' => ((isset($OPDPricingType) && $OPDPricingType != '') ? $OPDPricingType : 'with-subscription'),
                    'opd_invoice_price' => ((isset($OPDInvoicePrice) && $OPDInvoicePrice != '') ? $OPDInvoicePrice : 0));

                foreach ($Options as $key => $value) {
                    if ($value != '') {
                        $record_option['ProfileUID'] = $website_profile_id;
                        $record_option['Name'] = $key;
                        $record_option['Description'] = $value;
                        $Crud->AddRecordPG("public.options", $record_option);
                    }
                }

                $ExtendedArray = array('clinta_extended_profiles', 'short_description', 'healthcare_status', 'patient_portal');
                foreach ($ExtendedArray as $M) {
                    $record_meta['ProfileUID'] = $website_profile_id;
                    $record_meta['Option'] = $M;
                    $record_meta['Value'] = $this->request->getVar($M);
                    $Crud->AddRecordPG("public.profile_metas", $record_meta);
                }

                $PackageUID = $this->request->getVar('Package');
                if (isset($PackageUID) && $PackageUID > 0) {

                    $Invoices = new Invoices();
                    $OriginalPrice = $this->request->getVar('OriginalPrice');
                    $Discount = $this->request->getVar('Discount');
                    $Price = $this->request->getVar('Price');
                    $InvoiceDetailsArray = array(
                        'ProfileName' => $record['Name'],
                        'ProductType' => 'builder',
                        'Product' => 'hospitals',
                        'ProfileUID' => $website_profile_id,
                        'PackageUID' => $PackageUID,
                        'OriginalPrice' => $OriginalPrice,
                        'Discount' => $Discount,
                        'Price' => $Price
                    );
                    $Invoices->AddProfileSubscriptionDetails($InvoiceDetailsArray);
                }

                $msg = $_SESSION['FullName'] . ' Hospital Profile Submit Through Admin Dright';
                $logesegment = 'Hospitals';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

                $response = array();
                $response['status'] = "success";
                $response['id'] = $website_profile_id;
                $response['message'] = "Hospitals Profile Added Successfully.....!";
                $response['subdomain'] = $subdomain;
                echo json_encode($response);

            } else {

                $response = array();
                $response['status'] = "fail";
                $response['message'] = "Error in Adding Hospitals Profile...!";
                $response['subdomain'] = $subdomain;
                echo json_encode($response);
                return;
            }
        } else {

            $subdomain = $this->request->getVar('sub_domain');

            $EmailRecord = $Crud->SingleeRecord('public."profiles"', ["Email" => trim($email), 'UID !=' => $id]);
            if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                        (($EmailRecord['SubDomain'] ?? $EmailRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            $ContactNoRecord = $Crud->SingleeRecord('public."profiles"', ['ContactNo' => trim($ContactNo), 'UID !=' => $id]);
            if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                        (($ContactNoRecord['SubDomain'] ?? $ContactNoRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            if (trim($subdomain) != '') {

                $SubDomainRecord = $Crud->SingleeRecord('public."profiles"', ['SubDomain' => trim($subdomain), 'UID !=' => $id]);
                if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                            (($SubDomainRecord['SubDomain'] ?? $SubDomainRecord['Name']) ?? 'another user') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }
            }

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
            if ($website_profile_id) {
                $ExtendedArray = array('clinta_extended_profiles', 'short_description', 'healthcare_status', 'patient_portal');
                foreach ($ExtendedArray as $EA) {
                    $Crud->DeleteRecordPG('public."profile_metas"', array("ProfileUID" => $id, 'Option' => $EA));
                }
                foreach ($ExtendedArray as $M) {

                    $record_meta['ProfileUID'] = $id;
                    $record_meta['Option'] = $M;
                    $record_meta['Value'] = $this->request->getVar($M);
                    $Crud->AddRecordPG("public.profile_metas", $record_meta);
                }

                $theme = $this->request->getVar('theme');
                $PrescriptionSegment = $this->request->getVar('prescription_module');
                $PrescriptionPricingType = $this->request->getVar('prescription_pricing_type');
                $PerPrescriptionPrice = $this->request->getVar('prescription_price');

                $OPDInvoicing = $this->request->getVar('opd_invoicing');
                $OPDPricingType = $this->request->getVar('opd_pricing_type');
                $OPDInvoicePrice = $this->request->getVar('opd_invoice_price');

                $Options = array('theme_css' => 'dore.light.red.css', 'theme' => ((isset($theme) && $theme != '') ? $theme : ''),
                    'sms_credits' => 100, 'notify_sms' => 1, 'notify_email' => 1,
                    'prescription_module' => ((isset($PrescriptionSegment) && $PrescriptionSegment != '') ? $PrescriptionSegment : 0),
                    'prescription_pricing_type' => ((isset($PrescriptionPricingType) && $PrescriptionPricingType != '') ? $PrescriptionPricingType : 'with-subscription'),
                    'prescription_price' => ((isset($PerPrescriptionPrice) && $PerPrescriptionPrice != '') ? $PerPrescriptionPrice : 0),
                    'opd_invoicing' => ((isset($OPDInvoicing) && $OPDInvoicing != '') ? $OPDInvoicing : 0),
                    'opd_pricing_type' => ((isset($OPDPricingType) && $OPDPricingType != '') ? $OPDPricingType : 'with-subscription'),
                    'opd_invoice_price' => ((isset($OPDInvoicePrice) && $OPDInvoicePrice != '') ? $OPDInvoicePrice : 0));
                foreach ($Options as $key => $value) {
                    $Data = $Crud->SingleeRecord('public."options"', array("ProfileUID" => $id, 'Name' => $key));
                    if (isset($Data['UID'])) {
                        $record_option['Description'] = $value;
                        $Crud->UpdateeRecord("public.options", $record_option, array('UID' => $Data['UID']));
                    } else {
                        $record_option['Description'] = $value;
                        $record_option['Name'] = $key;
                        $record_option['ProfileUID'] = $id;
                        $Crud->AddRecordPG("public.options", $record_option);
                    }
                }
            }

            $msg = $_SESSION['FullName'] . ' Hospital Profile Update Through Admin Dright';
            $logesegment = 'Hospitals';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

            $response = array();
            $response['status'] = "success";
            $response['id'] = $id;
            $response['message'] = "Hospitals Profile Updated Successfully.....!";
            echo json_encode($response);
        }

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
            $fileContents = file_get_contents($file->getTempName());
        }
        if ($initatived_logo->isValid() && !$initatived_logo->hasMoved()) {
            $fileinitatived_logo = file_get_contents($initatived_logo->getTempName());

        }

        if ($id == 0) {

            $subdomain = $this->request->getVar('sub_domain');
            $AdminDomain = $this->request->getVar('AdminDomain');

            $EmailRecord = $Crud->SingleeRecord('public."profiles"', ["Email" => trim($email)]);
            if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                        (($EmailRecord['SubDomain'] ?? $EmailRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            $ContactNoRecord = $Crud->SingleeRecord('public."profiles"', ['ContactNo' => trim($ContactNo)]);
            if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                        (($ContactNoRecord['SubDomain'] ?? $ContactNoRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            if (trim($subdomain) != '') {

                $SubDomainRecord = $Crud->SingleeRecord('public."profiles"', ['SubDomain' => trim($subdomain)]);
                if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                            (($SubDomainRecord['SubDomain'] ?? $SubDomainRecord['Name']) ?? 'another user') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }
            }

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

            } else {
                $record['Profile'] = '';
            }
            $website_profile_id = $Crud->AddRecordPG("public.profiles", $record);
            if ($website_profile_id) {

                $themeOptions = array(
                    'banner_style' => 'version3',
                    'home_ceo_message' => 1,
                    'home_facilities' => 1,
                    'home_news' => 1,
                    'home_reviews' => 1,
                    'theme' => 'mist',
                    'theme_primary_color' => '#ff0000',
                    'theme_secondary_color' => '#400080',
                    'theme_header' => 'simple-header',
                    'theme_footer' => 'dark-style-1',
                    'theme_service' => 'version_1',
                    'theme_facilities' => 'simple-info-box',
                    'theme_specialities' => 'simple-info-box',
                );
                foreach ($themeOptions as $key => $value) {

                    if ($value != '') {
                        $themerecord['ProfileUID'] = $website_profile_id;
                        $themerecord['Name'] = $key;
                        $themerecord['Description'] = $value;
                        $Crud->AddRecordPG("public.options", $themerecord);
                    }
                }

                if ($fileinitatived_logo != '') {
                    $records['ProfileUID'] = $website_profile_id;
                    $records['Option'] = 'initatived_logo';
                    $records['Value'] = base64_encode($fileinitatived_logo);
                    $Crud->AddRecordPG("public.profile_metas", $records);
                }

                $Metas = array('speciality', 'qualification', 'pmdcno', 'department', 'short_description', 'telemedicine_id', 'initatived_text', 'healthcare_status', 'patient_portal');
                foreach ($Metas as $M) {

                    if ($M != '') {
                        $record_meta['ProfileUID'] = $website_profile_id;
                        $record_meta['Option'] = $M;
                        $record_meta['Value'] = trim($this->request->getVar($M));
                        $Crud->AddRecordPG("public.profile_metas", $record_meta);
                    }
                }

                $Sponsor = $this->request->getVar('sponsor');
                $theme = $this->request->getVar('theme');

                $PrescriptionSegment = $this->request->getVar('prescription_module');
                $PrescriptionPricingType = $this->request->getVar('prescription_pricing_type');
                $PerPrescriptionPrice = $this->request->getVar('prescription_price');

                $OPDInvoicing = $this->request->getVar('opd_invoicing');
                $OPDPricingType = $this->request->getVar('opd_pricing_type');
                $OPDInvoicePrice = $this->request->getVar('opd_invoice_price');
                $Options = array('award_nav' => 'show', 'patient_nav' => 'show', 'research_nav' => 'show', 'theme_css' => 'dore.light.red.css', 'custom_banners' => '5',
                    'theme' => ((isset($theme) && $theme != '') ? $theme : ''), 'sms_credits' => 100, 'notify_sms' => 1,
                    'notify_email' => 1, 'sponsor' => ((isset($Sponsor) && $Sponsor != '') ? $Sponsor : ''),
                    'prescription_module' => ((isset($PrescriptionSegment) && $PrescriptionSegment != '') ? $PrescriptionSegment : 0),
                    'prescription_pricing_type' => ((isset($PrescriptionPricingType) && $PrescriptionPricingType != '') ? $PrescriptionPricingType : 'with-subscription'),
                    'prescription_price' => ((isset($PerPrescriptionPrice) && $PerPrescriptionPrice != '') ? $PerPrescriptionPrice : 0),
                    'opd_invoicing' => ((isset($OPDInvoicing) && $OPDInvoicing != '') ? $OPDInvoicing : 0),
                    'opd_pricing_type' => ((isset($OPDPricingType) && $OPDPricingType != '') ? $OPDPricingType : 'with-subscription'),
                    'opd_invoice_price' => ((isset($OPDInvoicePrice) && $OPDInvoicePrice != '') ? $OPDInvoicePrice : 0));

                foreach ($Options as $key => $value) {

                    $record_option['ProfileUID'] = $website_profile_id;
                    $record_option['Name'] = $key;
                    $record_option['Description'] = $value;
                    $Crud->AddRecordPG("public.options", $record_option);
                }

                $PackageUID = $this->request->getVar('Package');
                if (isset($PackageUID) && $PackageUID > 0) {

                    $Invoices = new Invoices();
                    $OriginalPrice = $this->request->getVar('OriginalPrice');
                    $Discount = $this->request->getVar('Discount');
                    $Price = $this->request->getVar('Price');
                    $InvoiceDetailsArray = array(
                        'ProfileName' => $record['Name'],
                        'ProductType' => 'builder',
                        'Product' => 'doctors',
                        'ProfileUID' => $website_profile_id,
                        'PackageUID' => $PackageUID,
                        'OriginalPrice' => $OriginalPrice,
                        'Discount' => $Discount,
                        'Price' => $Price
                    );
                    $Invoices->AddProfileSubscriptionDetails($InvoiceDetailsArray);
                }

                $msg = $_SESSION['FullName'] . ' Doctor Profile Submit Through Admin DRight';
                $logesegment = 'Doctor';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

                $response = array();
                $response['status'] = "success";
                $response['id'] = $website_profile_id;
                $response['message'] = "Doctor Profile Added Successfully.....!";
                $response['subdomain'] = $subdomain;
                echo json_encode($response);

                return;

            } else {
                $response = array();
                $response['status'] = "fail";
                $response['message'] = "Error in Adding Doctors Profile...!";
                $response['subdomain'] = $subdomain;
                echo json_encode($response);
                return;
            }

        } else {

            $subdomain = $this->request->getVar('sub_domain');
            $AdminDomain = $this->request->getVar('AdminDomain');

            $EmailRecord = $Crud->SingleeRecord('public."profiles"', ["Email" => trim($email), 'UID !=' => $id]);
            if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                        (($EmailRecord['SubDomain'] ?? $EmailRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            $ContactNoRecord = $Crud->SingleeRecord('public."profiles"', ['ContactNo' => trim($ContactNo), 'UID !=' => $id]);
            if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                        (($ContactNoRecord['SubDomain'] ?? $ContactNoRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            if (trim($subdomain) != '') {

                $SubDomainRecord = $Crud->SingleeRecord('public."profiles"', ['SubDomain' => trim($subdomain), 'UID !=' => $id]);
                if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                            (($SubDomainRecord['SubDomain'] ?? $SubDomainRecord['Name']) ?? 'another user') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }
            }

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
            if ($updateid > 0) {

                $Sponsor = $this->request->getVar('sponsor');
                if (isset($Sponsor) && $Sponsor != '') {
                    $sql = 'SELECT * FROM public."options" WHERE "ProfileUID" = \'' . $id . '\' AND "Name" = \'sponsor\'';
                    $SponsorData = $Crud->ExecutePgSQL($sql);
                    if (count($SponsorData) > 0) {
                        $Crud->DeleteRecordPG("public.options", array('Name' => 'sponsor', 'ProfileUID' => $id));
                        $record_option['Description'] = $Sponsor;
                        $record_option['Name'] = 'sponsor';
                        $record_option['ProfileUID'] = $id;
                        $Crud->AddRecordPG("public.options", $record_option);
                        // $Crud->UpdateeRecord("public.options", array('Description' => $Sponsor), array('UID' => $SponsorData['UID']));
                    } else {
                        $record_option['Description'] = $Sponsor;
                        $record_option['Name'] = 'sponsor';
                        $record_option['ProfileUID'] = $id;
                        $Crud->AddRecordPG("public.options", $record_option);
                    }
                }
                $Metas = array('speciality', 'qualification', 'pmdcno', 'department', 'short_description', 'telemedicine_id', 'initatived_text', 'healthcare_status', 'patient_portal');
                foreach ($Metas as $M) {

                    if ($this->request->getVar($M) != '') {

                        $sql = 'SELECT "profile_metas".* FROM public."profile_metas" WHERE "ProfileUID" = \'' . $id . '\' AND  "Option" = \'' . $M . '\'';
                        $ProfileMetaData = $Crud->ExecutePgSQL($sql);
                        if (count($ProfileMetaData) > 0) {
                            $Crud->DeleteRecordPG("public.profile_metas", array('ProfileUID' => $id, 'Option' => $M));
                            $record_meta['Value'] = trim($this->request->getVar($M));
                            $record_meta['Option'] = $M;
                            $record_meta['ProfileUID'] = $id;
                            $Crud->AddRecordPG("public.profile_metas", $record_meta);
                        } else {
                            $record_meta['Value'] = trim($this->request->getVar($M));
                            $record_meta['Option'] = $M;
                            $record_meta['ProfileUID'] = $id;
                            $Crud->AddRecordPG("public.profile_metas", $record_meta);
                        }
                    }
                }

                $theme = $this->request->getVar('theme');
                $Options = array('theme' => ((isset($theme) && $theme != '') ? $theme : ''));

                $PrescriptionSegment = $this->request->getVar('prescription_module');
                $PrescriptionPricingType = $this->request->getVar('prescription_pricing_type');
                $PerPrescriptionPrice = $this->request->getVar('prescription_price');

                $OPDInvoicing = $this->request->getVar('opd_invoicing');
                $OPDPricingType = $this->request->getVar('opd_pricing_type');
                $OPDInvoicePrice = $this->request->getVar('opd_invoice_price');

                $Options['prescription_module'] = ((isset($PrescriptionSegment) && $PrescriptionSegment != '') ? $PrescriptionSegment : 0);
                $Options['prescription_pricing_type'] = ((isset($PrescriptionPricingType) && $PrescriptionPricingType != '') ? $PrescriptionPricingType : 'with-subscription');
                $Options['prescription_price'] = ((isset($PerPrescriptionPrice) && $PerPrescriptionPrice != '') ? $PerPrescriptionPrice : 0);

                $Options['opd_invoicing'] = ((isset($OPDInvoicing) && $OPDInvoicing != '') ? $OPDInvoicing : 0);
                $Options['opd_pricing_type'] = ((isset($OPDPricingType) && $OPDPricingType != '') ? $OPDPricingType : 'with-subscription');
                $Options['opd_invoice_price'] = ((isset($OPDInvoicePrice) && $OPDInvoicePrice != '') ? $OPDInvoicePrice : 0);

                $Options_record = array();
                foreach ($Options as $key => $value) {

                    $Data = $Crud->SingleeRecord('public."options"', array("ProfileUID" => $id, 'Name' => $key));
                    if (isset($Data['UID'])) {
                        $Crud->DeleteRecordPG("public.options", array('UID' => $Data['UID']));
                        $Options_record['Description'] = $value;
                        $Options_record['Name'] = $key;
                        $Options_record['ProfileUID'] = $id;
                        $Crud->AddRecordPG("public.options", $Options_record);

                    } else {
                        $Options_record['Description'] = $value;
                        $Options_record['Name'] = $key;
                        $Options_record['ProfileUID'] = $id;
                        $Crud->AddRecordPG("public.options", $Options_record);
                    }
                }

                if ($fileinitatived_logo != '') {
                    $Data = $Crud->SingleeRecord('public."profile_metas"', array("ProfileUID" => $id, 'Option' => 'initatived_logo'));
                    if (isset($Data['UID'])) {

                        $Crud->DeleteRecordPG("public.profile_metas", array('UID' => $Data['UID']));
                        $records['ProfileUID'] = $id;
                        $records['Option'] = 'initatived_logo';
                        $records['Value'] = base64_encode($fileinitatived_logo);
                        $Crud->AddRecordPG("public.profile_metas", $records);

                    } else {

                        $records['ProfileUID'] = $id;
                        $records['Option'] = 'initatived_logo';
                        $records['Value'] = base64_encode($fileinitatived_logo);
                        $Crud->AddRecordPG("public.profile_metas", $records);
                    }
                }

                $msg = $_SESSION['FullName'] . ' Doctor Profile Update Through Admin Dright';
                $logesegment = 'Doctor';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

                $response = array();
                $response['status'] = "success";
                $response['id'] = $id;
                $response['message'] = "Doctors Profile Updated Successfully.....!";
                echo json_encode($response);
                return;
            } else {

                $response = array();
                $response['status'] = "fail";
                $response['message'] = "Error in Updating Doctors Profile...!";
                echo json_encode($response);
                return;
            }

        }

    }

    public
    function load_speciality_metas_data_grid()
    {
        $html = '';
        $BuilderModel = new BuilderModel();

        $id = $this->request->getVar('id');
        $option = $this->request->getVar('option');
        $Data = $BuilderModel->get_speciality_meta_data_by_id_option($id, $option);
        if (count($Data) > 0) {

            $html .= '<div class="col-md-12">
							<div style="margin-bottom: 0rem !important;" class="table table-responsive">
								<table class="table table-bordered table-striped">
									<thead style="background: currentColor;">
										<tr>
											<th width="30">#</th>
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
    }

    public
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
        $fileImage = '';
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
            if ($fileImage != '') {
                $record['Image'] = base64_encode($fileImage);

            }
            $RecordId = $Crud->AddRecord("sponsors", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $msg = $_SESSION['FullName'] . ' Submit Sponser Through Admin Dright';
                $logesegment = 'Sponsors';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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
            if ($fileImage != '') {
                $record['Image'] = base64_encode($fileImage);

            }
            $Crud->UpdateRecord("sponsors", $record, array("UID" => $id));
            $msg = $_SESSION['FullName'] . ' Update Sponsor Through Admin Dright';
            $logesegment = 'Sponsors';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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
        $fileImage = '';

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
            if ($fileImage != '') {
                $record['Image'] = base64_encode($fileImage);

            }
            $RecordId = $Crud->AddRecord("sponsors_products", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $msg = $_SESSION['FullName'] . ' Submit Sponsor Product Through Admin Dright';
                $logesegment = 'Sponsors Product';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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
            if ($fileImage != '') {
                $record['Image'] = base64_encode($fileImage);

            }

            $Crud->UpdateRecord("sponsors_products", $record, array("UID" => $id));
            $msg = $_SESSION['FullName'] . ' Submit Sponsor Product Through Admin Dright';
            $logesegment = 'Sponsors Product';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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

        $msg = $_SESSION['FullName'] . ' Delete Sponsor Through Admin Dright';
        $logesegment = 'Sponsors';
        $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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

        $msg = $_SESSION['FullName'] . ' Delete Sponsor Product Through Admin Dright';
        $logesegment = 'Sponsors Product';
        $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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

        // echo'<pre>';print_r($option);exit();
        if ($id == 0) {
            foreach ($option as $key => $value) {
                $Crud->DeleteRecordPG("public.options", array('Name' => $key, 'ProfileUID' => $ProfileUID));

                $record['Name'] = $key;
                $record['Description'] = ((isset($value)) ? $value : '');
                $record['ProfileUID'] = $ProfileUID;
                $RecordId = $Crud->AddRecordPG('public."options"', $record);
            }
            $msg = $_SESSION['FullName'] . ' Update Theme Setting Through Admin Dright';
            $logesegment = 'Hospital';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
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

    public
    function submit_individual_banners()
    {
        $Crud = new Crud();
        $Main = new Main();

        $ProfileUID = $this->request->getVar('ProfileUID');
        $alignment = $this->request->getVar('alignment');
        $color = $this->request->getVar('color');
        $speciality = $this->request->getVar('speciality');
        if ($this->request->getFile('profile') && $this->request->getFile('profile')->isValid()) {

            $file = $this->request->getFile('profile');
            $fileName = $file->getName();
            $fileExt = strtolower($file->getClientExtension());
            $allowedExt = ['gif', 'jpg', 'jpeg', 'png', 'webp'];
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
            } else {
                $fileContent = '';
            }

        } else {
            $data = ['status' => 'error', 'msg' => 'No file selected or invalid extension.'];
            echo json_encode($data);
            exit;
        }

        $record['ProfileUID'] = $ProfileUID;
        $record['Alignment'] = $alignment;
        $record['Color'] = $color;
        $record['Speciality'] = $speciality;
        $record['Image'] = $fileContent;
        $RecordId = $Crud->AddRecord('individual_banners', $record);
        if (isset($RecordId) && $RecordId > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Individual Banners Added Successfully...!';
        } else {
            $response['status'] = 'fail';
            $response['message'] = 'Data Didnt Submitted Successfully...!';
        }
        echo json_encode($response);

    }

    public function CreateSubdomainsWorker()
    {
        header('Content-Type: application/json');

        $subdomain = $this->request->getVar('subdomain');

        if (!empty($subdomain)) {
            $this->CreateSubDomains($subdomain);
            $this->CreateAdminSubDomains($subdomain);
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
        create_subdomain_cpanel(trim($cpanel_domain), 'clinta.biz', 'reactjs.webbuilder');
    }

    private function CreateAdminSubDomains($subdomain = '')
    {
        /** Auto Creating Domain Code */
        $parts = explode('.', $subdomain);
        $admin_cpanel_domain = 'admin.' . $parts[0];
        create_subdomain_cpanel(trim($admin_cpanel_domain), 'clinta.biz', 'admin.webbuilder');
    }

    public function CreateMiniHimsDomainsWorkers()
    {
        header('Content-Type: application/json');

        $subdomain = $this->request->getVar('subdomain');

        if (!empty($subdomain)) {
            $this->CreateMiniHimsSubDomains($subdomain);
        }

        echo json_encode([
            'status' => 'success',
            'message' => 'Sub Domains Created Successfully.'
        ]);
        return;
    }

    private function CreateMiniHimsSubDomains($subdomain = '')
    {
        /** Auto Creating Domain Code */
        $parts = explode('.', $subdomain);
        $cpanel_domain = $parts[0];
        create_subdomain_cpanel(trim($cpanel_domain), 'clinta.biz', 'admin.webbuilder');
    }

    public
    function fetch_mini_hims()
    {
        $BuilderModel = new BuilderModel();
        $PharmacyModal = new PharmacyModal();
        $type = 'hospitals';
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $BuilderModel->get_doct_datatables($type, $keyword, 1, 0);
        $totalfilterrecords = $BuilderModel->count_doct_datatables($type, $keyword, 1, 0);

        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {

            $Actions = [];
            $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="UpdateMiniHims(' . htmlspecialchars($record['UID']) . ')">Update</a>';
            $cnt++;
            $CityName = '-';
            if (isset($record['City']) && $record['City'] > 0) {
                $city = $PharmacyModal->getcitybyid($record['City']);
                $CityName = ((isset($city[0]['FullName']) && $city[0]['FullName'] != '') ? $city[0]['FullName'] : '-');
            }
            $lastVisit = !empty($record['LastVisitDateTime']) ? date("d M, Y", strtotime($record['LastVisitDateTime'])) : "N/A";

            $data = [];
            $data[] = $cnt;
            $data[] = $record['Name'];
            $data[] = !empty($record['SubDomain']) ? '<a title="Click to View" style="color:crimson" href="https://' . $record['SubDomain'] . '" target="_blank">' . $record['SubDomain'] . '</a>' : '';
            $data[] = $CityName;
            $data[] = '<badge class="badge badge-' . (($record['Status'] == 'active') ? 'success' : 'danger') . '">' . ucwords($record['Status']) . '</badge>';
            $data[] = ((isset($record['ExpireDate']) && $record['ExpireDate'] != '') ? '<b>' . date('d M, Y', strtotime($record['ExpireDate'])) . '</b>' : '<badge class="badge badge-danger">Expired</badge>');
            $data[] = $record['Email'];
            $data[] = $lastVisit;
            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
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
    function mini_hims_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();
        $records = array();
        $id = $this->request->getVar('UID');
        $email = $this->request->getVar('email');
        $ContactNo = $this->request->getVar('ContactNo');
        $file = $this->request->getFile('profile');
        $fileContents = '';
        if ($file->isValid() && !$file->hasMoved()) {
            $fileContents = file_get_contents($file->getTempName());
        }

        if ($id == 0) {

            $subdomain = $this->request->getVar('sub_domain');

            $EmailRecord = $Crud->SingleeRecord('public."profiles"', ["Email" => trim($email)]);
            if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                        (($EmailRecord['SubDomain'] ?? $EmailRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            $ContactNoRecord = $Crud->SingleeRecord('public."profiles"', ['ContactNo' => trim($ContactNo)]);
            if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                        (($ContactNoRecord['SubDomain'] ?? $ContactNoRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            if (trim($subdomain) != '') {

                $SubDomainRecord = $Crud->SingleeRecord('public."profiles"', ['SubDomain' => trim($subdomain)]);
                if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                            (($SubDomainRecord['SubDomain'] ?? $SubDomainRecord['Name']) ?? 'another user') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }
            }

            $record['Type'] = 'hospitals';
            $record['Name'] = $this->request->getVar('name');
            $record['Email'] = $this->request->getVar('email');
            $record['Password'] = $this->request->getVar('password');
            $record['City'] = $this->request->getVar('city');
            $record['ContactNo'] = $this->request->getVar('ContactNo');
            $record['SubDomain'] = $subdomain;
            $record['MiniHims'] = 1;
            if ($fileContents != '') {
                $record['Profile'] = base64_encode($fileContents);
            } else {
                $record['Profile'] = '';
            }
            $website_profile_id = $Crud->AddRecordPG("public.profiles", $record);
            if ($website_profile_id) {

                $PrescriptionSegment = $this->request->getVar('prescription_module');
                $PrescriptionPricingType = $this->request->getVar('prescription_pricing_type');
                $PerPrescriptionPrice = $this->request->getVar('prescription_price');

                $OPDInvoicing = $this->request->getVar('opd_invoicing');
                $OPDPricingType = $this->request->getVar('opd_pricing_type');
                $OPDInvoicePrice = $this->request->getVar('opd_invoice_price');

                $Options = array(
                    'prescription_module' => ((isset($PrescriptionSegment) && $PrescriptionSegment != '') ? $PrescriptionSegment : 1),
                    'prescription_pricing_type' => ((isset($PrescriptionPricingType) && $PrescriptionPricingType != '') ? $PrescriptionPricingType : 'with-subscription'),
                    'prescription_price' => ((isset($PerPrescriptionPrice) && $PerPrescriptionPrice != '') ? $PerPrescriptionPrice : 0),
                    'opd_invoicing' => ((isset($OPDInvoicing) && $OPDInvoicing != '') ? $OPDInvoicing : 1),
                    'opd_pricing_type' => ((isset($OPDPricingType) && $OPDPricingType != '') ? $OPDPricingType : 'with-subscription'),
                    'opd_invoice_price' => ((isset($OPDInvoicePrice) && $OPDInvoicePrice != '') ? $OPDInvoicePrice : 0));

                foreach ($Options as $key => $value) {
                    if ($value != '') {
                        $record_option['ProfileUID'] = $website_profile_id;
                        $record_option['Name'] = $key;
                        $record_option['Description'] = $value;
                        $Crud->AddRecordPG("public.options", $record_option);
                    }
                }

                $PackageUID = $this->request->getVar('Package');
                if (isset($PackageUID) && $PackageUID > 0) {

                    $Invoices = new Invoices();
                    $OriginalPrice = $this->request->getVar('OriginalPrice');
                    $Discount = $this->request->getVar('Discount');
                    $Price = $this->request->getVar('Price');
                    $InvoiceDetailsArray = array(
                        'ProfileName' => $record['Name'],
                        'ProductType' => 'builder',
                        'Product' => 'hospitals',
                        'ProfileUID' => $website_profile_id,
                        'PackageUID' => $PackageUID,
                        'OriginalPrice' => $OriginalPrice,
                        'Discount' => $Discount,
                        'Price' => $Price
                    );
                    $Invoices->AddProfileSubscriptionDetails($InvoiceDetailsArray);
                }

                $msg = $_SESSION['FullName'] . ' Mini Hims Profile Submit Through Admin Dright';
                $logesegment = 'Mini Hims';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

                $response = array();
                $response['status'] = "success";
                $response['id'] = $website_profile_id;
                $response['message'] = "Mini Hims Profile Added Successfully.....!";
                $response['subdomain'] = $subdomain;
                echo json_encode($response);

            } else {

                $response = array();
                $response['status'] = "fail";
                $response['message'] = "Error in Adding Mini Hims Profile...!";
                $response['subdomain'] = $subdomain;
                echo json_encode($response);
                return;
            }

        } else {

            $subdomain = $this->request->getVar('sub_domain');

            $EmailRecord = $Crud->SingleeRecord('public."profiles"', ["Email" => trim($email), 'UID !=' => $id]);
            if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                        (($EmailRecord['SubDomain'] ?? $EmailRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            $ContactNoRecord = $Crud->SingleeRecord('public."profiles"', ['ContactNo' => trim($ContactNo), 'UID !=' => $id]);
            if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                        (($ContactNoRecord['SubDomain'] ?? $ContactNoRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            if (trim($subdomain) != '') {

                $SubDomainRecord = $Crud->SingleeRecord('public."profiles"', ['SubDomain' => trim($subdomain), 'UID !=' => $id]);
                if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                            (($SubDomainRecord['SubDomain'] ?? $SubDomainRecord['Name']) ?? 'another user') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }
            }

            $record['Type'] = 'hospitals';
            $record['Name'] = $this->request->getVar('name');
            $record['Email'] = $this->request->getVar('email');
            $record['Password'] = $this->request->getVar('password');
            $record['City'] = $this->request->getVar('city');
            $record['ContactNo'] = $this->request->getVar('ContactNo');
            $record['SubDomain'] = $subdomain;
            $record['MiniHims'] = 1;
            if ($fileContents != '') {
                $record['Profile'] = base64_encode($fileContents);
            }
            $website_profile_id = $Crud->UpdateeRecord("public.profiles", $record, array('UID' => $id));
            if ($website_profile_id) {
                $PrescriptionSegment = $this->request->getVar('prescription_module');
                $PrescriptionPricingType = $this->request->getVar('prescription_pricing_type');
                $PerPrescriptionPrice = $this->request->getVar('prescription_price');

                $OPDInvoicing = $this->request->getVar('opd_invoicing');
                $OPDPricingType = $this->request->getVar('opd_pricing_type');
                $OPDInvoicePrice = $this->request->getVar('opd_invoice_price');

                $Options = array('prescription_module' => ((isset($PrescriptionSegment) && $PrescriptionSegment != '') ? $PrescriptionSegment : 1),
                    'prescription_pricing_type' => ((isset($PrescriptionPricingType) && $PrescriptionPricingType != '') ? $PrescriptionPricingType : 'with-subscription'),
                    'prescription_price' => ((isset($PerPrescriptionPrice) && $PerPrescriptionPrice != '') ? $PerPrescriptionPrice : 0),
                    'opd_invoicing' => ((isset($OPDInvoicing) && $OPDInvoicing != '') ? $OPDInvoicing : 1),
                    'opd_pricing_type' => ((isset($OPDPricingType) && $OPDPricingType != '') ? $OPDPricingType : 'with-subscription'),
                    'opd_invoice_price' => ((isset($OPDInvoicePrice) && $OPDInvoicePrice != '') ? $OPDInvoicePrice : 0));
                foreach ($Options as $key => $value) {
                    $Data = $Crud->SingleeRecord('public."options"', array("ProfileUID" => $id, 'Name' => $key));
                    if (isset($Data['UID'])) {
                        $record_option['Description'] = $value;
                        $Crud->UpdateeRecord("public.options", $record_option, array('UID' => $Data['UID']));
                    } else {
                        $record_option['Description'] = $value;
                        $record_option['Name'] = $key;
                        $record_option['ProfileUID'] = $id;
                        $Crud->AddRecordPG("public.options", $record_option);
                    }
                }
            }
            $msg = $_SESSION['FullName'] . ' Mini Hims Profile Update Through Admin Dright';
            $logesegment = 'Mini Hims';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

            $response = array();
            $response['status'] = "success";
            $response['id'] = $id;
            $response['message'] = "Mini Hims Profile Updated Successfully.....!";
            echo json_encode($response);
        }

    }

    public
    function mini_hims_search_filter()
    {
        $session = session();
        $city = $this->request->getVar('City');
        $Name = $this->request->getVar('Name');
        $Type = $this->request->getVar('Type');
        $AllFilter = array(
            'City' => ((isset($city) && $city != '') ? $city : ''),
            'Name' => (($Name != '') ? trim($Name) : ''),
            'Type' => ((isset($Type) && $Type != '') ? $Type : '')
        );

        $session->set('HospitalFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }

    public
    function banners_search_filter()
    {
        $session = session();
        $Speciality = $this->request->getVar('speciality');
        $Type = $this->request->getVar('type');
        $AllFilter = array(
            'Speciality' => ((isset($Speciality) && $Speciality != '') ? $Speciality : ''),
            'Type' => ((isset($Type) && $Type != '') ? $Type : '')
        );

        $session->set('GeneralBannersFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }

    public
    function third_party_scripts()
    {

        $data = $this->data;
        $Crud = new Crud();
        $data['ProfileUID'] = $ProfileUID = getSegment(3);
        if (isset($ProfileUID) && $ProfileUID != '' && $ProfileUID > 0) {

            $data['ProfileData'] = $Crud->SingleeRecord('public."profiles"', array("UID" => $ProfileUID));

            echo view('header', $data);
            echo view('builder/third-party-script', $data);
            echo view('footer', $data);

        } else {
            return redirect()->route('builder');
        }
    }

    public
    function profiles_script_form_submit()
    {
        $Crud = new Crud();
        $ProfileUID = $this->request->getVar('ProfileUID');
        $UID = $this->request->getVar('UID');
        $Title = $this->request->getVar('title');
        $Scripts = $this->request->getVar('script');

        $SubmittedBy = $_SESSION['UID'];

        if ($UID == 0) {

            $record = array();
            $record['SystemDate'] = date("Y-m-d H:i:s");
            $record['ProfileUID'] = $ProfileUID;
            $record['Title'] = trim($Title);
            $record['Script'] = trim($Scripts);
            $record['SubmittedBy'] = $SubmittedBy;
            $ScriptInsertID = $Crud->AddRecordPG("public.third_party_scripts", $record);
            if ($ScriptInsertID > 0) {
                $response = array();
                $response['status'] = "success";
                $response['message'] = "Script Added Successfully...!";
                echo json_encode($response);
            } else {
                $response = array();
                $response['status'] = "fail";
                $response['message'] = "Failed to Add Script!";
                echo json_encode($response);
            }

        } else {

            $record = array();
            $record['ProfileUID'] = $ProfileUID;
            $record['Title'] = trim($Title);
            $record['Script'] = trim($Scripts);
            $record['SubmittedBy'] = $SubmittedBy;
            $Crud->UpdateeRecord("public.third_party_scripts", $record, array('UID' => $UID));

            $response = array();
            $response['status'] = "success";
            $response['message'] = "Script Updated Successfully.....!";
            echo json_encode($response);
        }
    }

    public
    function get_all_profiles_scripts()
    {

        $data = $this->data;
        $BuilderModel = new BuilderModel();
        $ProfileUID = $this->request->getVar('ProfileUID');
        $data['ScriptRecords'] = $ScriptRecords = $BuilderModel->GetProfilesScripts($ProfileUID);
        if (count($ScriptRecords) > 0) {
            $page = view('builder/_ProfilesScriptGrid', $data);
            echo json_encode(array('status' => 'success', 'page' => $page));
        } else {
            echo json_encode(array('status' => 'fail', 'page' => ''));
        }
    }

    public
    function get_profile_script_record_by_id()
    {

        $Crud = new Crud();
        $ScriptUID = $this->request->getVar('ScriptUID');
        $ScriptRecord = $Crud->SingleeRecord('public."third_party_scripts"', array("UID" => $ScriptUID));
        echo json_encode($ScriptRecord);
    }

    public
    function remove_profile_script()
    {

        $Crud = new Crud();
        $ScriptUID = $this->request->getVar('ScriptUID');
        $Crud->DeleteRecordPG('public."third_party_scripts"', array("UID" => $ScriptUID));

        $response = array();
        $response['status'] = 'success';
        $response['message'] = 'Script Deleted Successfully';
        echo json_encode($response);
    }


    public
    function fetch_promotional_websites()
    {
        $BuilderModel = new BuilderModel();
        $type = 'all';
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $BuilderModel->get_doct_datatables($type, $keyword, 0, 1);
        $totalfilterrecords = $BuilderModel->count_doct_datatables($type, $keyword, 0, 1);

        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {

            $Actions = [];
            $Actions[] = '<a style="cursor:pointer;" class="dropdown-item" onclick="UpdatePromotionalWebsites(' . htmlspecialchars($record['UID']) . ')">Update</a>';
            $cnt++;
            $lastVisit = !empty($record['LastVisitDateTime']) ? date("d M, Y", strtotime($record['LastVisitDateTime'])) : "N/A";

            $data = [];
            $data[] = $cnt;
            $data[] = $record['Name'];
            $data[] = !empty($record['SubDomain']) ? '<a title="Click to View" style="color:crimson" href="https://' . $record['SubDomain'] . '" target="_blank">' . $record['SubDomain'] . '</a>' : '';
            $data[] = ((isset($record['Type']) && $record['Type'] != '') ? '<badge style="color:white;" class="badge badge-' . (($record['Type'] == 'doctors') ? 'success' : 'warning') . '">' . ucwords($record['Type']) . '</badge>' : '');;
            $data[] = '<badge class="badge badge-' . (($record['Status'] == 'active') ? 'success' : 'danger') . '">' . ucwords($record['Status']) . '</badge>';
            $data[] = ((isset($record['ExpireDate']) && $record['ExpireDate'] != '') ? '<b>' . date('d M, Y', strtotime($record['ExpireDate'])) . '</b>' : '<badge class="badge badge-danger">Expired</badge>');
            $data[] = $record['Email'];
            $data[] = $lastVisit;
            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
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
    function promotional_websites_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $ProfileDuplicate = new ProfileDuplicate();
        $response = array();
        $record = array();
        $records = array();
        $id = $this->request->getVar('UID');
        $email = $this->request->getVar('email');
        $ContactNo = $this->request->getVar('ContactNo');
        $file = $this->request->getFile('profile');
        $fileContents = '';
        if ($file->isValid() && !$file->hasMoved()) {
            $fileContents = file_get_contents($file->getTempName());
        }

        $CopyProfileUID = $this->request->getVar('copy_profile_id');
        $ProfileType = $this->request->getVar('profile_type');

        if ($id == 0) {

            $subdomain = $this->request->getVar('sub_domain');

            $EmailRecord = $Crud->SingleeRecord('public."profiles"', ["Email" => trim($email)]);
            if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                        (($EmailRecord['SubDomain'] ?? $EmailRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            $ContactNoRecord = $Crud->SingleeRecord('public."profiles"', ['ContactNo' => trim($ContactNo)]);
            if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                        (($ContactNoRecord['SubDomain'] ?? $ContactNoRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            if (trim($subdomain) != '') {

                $SubDomainRecord = $Crud->SingleeRecord('public."profiles"', ['SubDomain' => trim($subdomain)]);
                if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                            (($SubDomainRecord['SubDomain'] ?? $SubDomainRecord['Name']) ?? 'another user') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }
            }

            $record['Type'] = $ProfileType;
            $record['Name'] = $this->request->getVar('name');
            $record['Email'] = $this->request->getVar('email');
            $record['Password'] = $this->request->getVar('password');
            $record['City'] = $this->request->getVar('city');
            $record['ContactNo'] = $this->request->getVar('ContactNo');
            $record['SubDomain'] = $subdomain;
            $record['IsPromotionalWebsite'] = 1;
            $record['Status'] = 'active';
            $record['ExpireDate'] = date("Y-12-31", strtotime("+1 Year"));
            if ($fileContents != '') {
                $record['Profile'] = base64_encode($fileContents);
            } else {
                $record['Profile'] = '';
            }
            $website_profile_id = $Crud->AddRecordPG("public.profiles", $record);
            if ($website_profile_id) {

                $record_meta = array();
                $PatientPortal = $this->request->getVar('patient_portal');
                if (isset($PatientPortal) && $PatientPortal != '') {
                    $record_meta['ProfileUID'] = $website_profile_id;
                    $record_meta['Option'] = 'patient_portal';
                    $record_meta['Value'] = $PatientPortal;
                    $Crud->AddRecordPG("public.profile_metas", $record_meta);
                }

                $msg = $_SESSION['FullName'] . ' Promotional Website Profile Submit Through Admin Dright';
                $logesegment = 'Promotional Website';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

                $response = array();
                $response['status'] = "success";
                $response['id'] = $website_profile_id;
                $response['message'] = "Promotional Website Profile Added Successfully.....!";
                $response['subdomain'] = $subdomain;
                echo json_encode($response);

                if (isset($CopyProfileUID) && $CopyProfileUID > 0) {
                    $ProfileDuplicate->GetProfileMetaRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileOptionsRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileBannersRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileSpecialtiesRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileFacilitiesRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileAuthorsRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileBlogsRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileNewsRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileReviewsRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileGalleryRecordAndInsert($CopyProfileUID, $website_profile_id);
                    $ProfileDuplicate->GetProfileDoctorsRecordAndInsert($CopyProfileUID, $website_profile_id);


                    if ($ProfileType == 'doctors') {
                        $ProfileDuplicate->GetDoctorProfileHospitalClinicsRecordAndInsert($CopyProfileUID, $website_profile_id);
                        $ProfileDuplicate->GetProfileAwardsAndMemberShipRecordAndInsert($CopyProfileUID, $website_profile_id);
                        $ProfileDuplicate->GetProfileGraduationRecordAndInsert($CopyProfileUID, $website_profile_id);
                        $ProfileDuplicate->GetProfilePostGraduationRecordAndInsert($CopyProfileUID, $website_profile_id);
                        $ProfileDuplicate->GetProfileExperienceRecordAndInsert($CopyProfileUID, $website_profile_id);
                    }
                }

            } else {

                $response = array();
                $response['status'] = "fail";
                $response['message'] = "Error in Adding Mini Hims Profile...!";
                $response['subdomain'] = $subdomain;
                echo json_encode($response);
                return;
            }

        } else {

            $subdomain = $this->request->getVar('sub_domain');

            $EmailRecord = $Crud->SingleeRecord('public."profiles"', ["Email" => trim($email), 'UID !=' => $id]);
            if (!empty($EmailRecord['UID']) && $EmailRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Email</strong> Already Assigned to <strong>' .
                        (($EmailRecord['SubDomain'] ?? $EmailRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            $ContactNoRecord = $Crud->SingleeRecord('public."profiles"', ['ContactNo' => trim($ContactNo), 'UID !=' => $id]);
            if (!empty($ContactNoRecord['UID']) && $ContactNoRecord['UID'] > 0) {
                $response = [
                    'status' => 'fail',
                    'message' => '<strong>Contact No</strong> Already Assigned to <strong>' .
                        (($ContactNoRecord['SubDomain'] ?? $ContactNoRecord['Name']) ?? 'another user') . '</strong>!'
                ];
                echo json_encode($response);
                return;
            }

            if (trim($subdomain) != '') {

                $SubDomainRecord = $Crud->SingleeRecord('public."profiles"', ['SubDomain' => trim($subdomain), 'UID !=' => $id]);
                if (!empty($SubDomainRecord['UID']) && $SubDomainRecord['UID'] > 0) {
                    $response = [
                        'status' => 'fail',
                        'message' => '<strong>Sub Domain</strong> Already Assigned to <strong>' .
                            (($SubDomainRecord['SubDomain'] ?? $SubDomainRecord['Name']) ?? 'another user') . '</strong>!'
                    ];
                    echo json_encode($response);
                    return;
                }
            }

            $record['Name'] = $this->request->getVar('name');
            $record['Email'] = $this->request->getVar('email');
            $record['Password'] = $this->request->getVar('password');
            $record['City'] = $this->request->getVar('city');
            $record['ContactNo'] = $this->request->getVar('ContactNo');
            $record['SubDomain'] = $subdomain;
            $record['IsPromotionalWebsite'] = 1;
            if ($fileContents != '') {
                $record['Profile'] = base64_encode($fileContents);
            }
            $website_profile_id = $Crud->UpdateeRecord("public.profiles", $record, array('UID' => $id));
            if($website_profile_id){

                $record_meta = array();
                $PatientPortal = $this->request->getVar('patient_portal');
                if (isset($PatientPortal) && $PatientPortal != '') {
                    $Crud->DeleteRecordPG('public."profile_metas"', array("ProfileUID" => $id, 'Option' => 'patient_portal'));

                    $record_meta['ProfileUID'] = $id;
                    $record_meta['Option'] = 'patient_portal';
                    $record_meta['Value'] = $PatientPortal;
                    $Crud->AddRecordPG("public.profile_metas", $record_meta);
                }
            }

            $msg = $_SESSION['FullName'] . ' Promotional Websites Profile Update Through Admin Dright';
            $logesegment = 'Promotional Websites';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

            $response = array();
            $response['status'] = "success";
            $response['id'] = $id;
            $response['message'] = "Promotional Websites Profile Updated Successfully.....!";
            echo json_encode($response);
        }

    }

}