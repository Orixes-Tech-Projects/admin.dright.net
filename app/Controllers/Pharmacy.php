<?php

namespace App\Controllers;


use App\Models\Crud;
use App\Models\PharmacyModal;
use App\Models\Main;

class Pharmacy extends BaseController
{
    var $data = array();

    public function __construct()
    {
        helper('main');
//        $session = session();
//        $session = $session->get();
//
        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
        $this->data['template'] = TEMPLATE;
        $this->data['path'] = PATH;
//        $this->data[ 'session' ] = $session;
//        CheckLogin( $this->data );
    }

    public function index()
    {
        $PharmacyModal = new PharmacyModal();

        $data = $this->data;
        $data['page'] = getSegment(2);
        $data['cities'] = $PharmacyModal->citites();

        echo view('header', $data);

            echo view('pharmacy/index', $data);


        echo view('footer', $data);
    }

    public function dashboard()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('pharmacy/dashboard', $data);
        echo view('footer', $data);
    }
    public function fetch_pharmacy()
    {
        $Crud = new Crud();
        $Main = new Main();

        $PharmacyModal = new PharmacyModal();
        $Data = $PharmacyModal->get_datatables();
        $totalfilterrecords = $PharmacyModal->count_datatables();
//        print_r($Data);
//        exit();
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $cities = $Crud->SingleRecord("cities", array("UID" => $record['City']));
            $StatusUrl = $Main->SeoUrl('module/pharmacy_profiles/status/' . $record['UID']);
            $CODE = base64_decode($record['LicenseCode']);
            $CODE = json_decode($CODE, true);

            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['FullName']) ? htmlspecialchars($record['FullName']) : '';
            $data[] = isset($cities['FullName']) ? htmlspecialchars($cities['FullName']) : '';
            $data[] = isset($record['ContactNo']) ? htmlspecialchars($record['ContactNo']) : '';
            $data[] = isset($record['Address']) ? htmlspecialchars($record['Address']) : '';
            $data[] = isset($record['SaleAgent']) ? htmlspecialchars($record['SaleAgent']) : '';
            $data[] = isset($CODE['MAC']) ? htmlspecialchars($CODE['MAC']) : '';
            $data[] = isset($CODE['ExpireDate']) ? date("d M, Y", strtotime($CODE['ExpireDate'])) : '';
            $data[] = isset($record['DeploymentDate']) ? date("d M, Y", strtotime($record['DeploymentDate'])) : '';
            $data[] = '
    <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                Actions
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" title="Edit Profile" href="javascript:void(0);" onclick="UpdatePharmacy(' . htmlspecialchars($record['UID']) . ')">
                    <i class="fa fa-pencil"></i> Edit Profile
                </a>
                <a class="dropdown-item" title="Delete" href="javascript:void(0);" onclick="DeletePharmacy(' . htmlspecialchars($record['UID']) . ')">
                    <i class="fa fa-trash"></i> Delete
                </a>
                <a class="dropdown-item" title="About License" href="javascript:void(0);" onclick="LoadLicense(' . htmlspecialchars($record['UID']) . ')">
                    <i class="fa fa-key"></i> About License
                </a>
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
    public function form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Pharmacy = $this->request->getVar('Pharmacy');

        if ($id == 0) {
            foreach ($Pharmacy as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }

            $RecordId = $Crud->AddRecord("pharmacy_profiles", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Pharmacy as $key => $value) {
                $record[$key] = $value;
            }
            $Crud->UpdateRecord("pharmacy_profiles", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }
    public function get_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("pharmacy_profiles", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }
    public function delete()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
        $Crud->DeleteRecord("pharmacy_profiles", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';
        echo json_encode($response);
    }

    public function search_filter()
    {
        $session = session();
        $Key = $this->request->getVar( 'Key' );
        $city = $this->request->getVar( 'city' );
        $MACAddress = $this->request->getVar( 'MACAddress' );


        $AllFilter = array (
            'Key' => $Key,
            'city' => $city,
            'MACAddress' => $MACAddress,

        );


//        print_r($AllCVFilter);exit;
        $session->set( 'Filters', $AllFilter );

        $response[ 'status' ] = "success";
        $response[ 'message' ] = "Filters Updated Successfully";

        echo json_encode( $response );
    }
}
