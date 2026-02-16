<?php

namespace App\Controllers;


use App\Models\Crud;
use App\Models\Main;
use App\Models\PharmacyModal;
use App\Models\Invoices;

class Pharmacy extends BaseController
{
    var $data = array();

    public function __construct()
    {

        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
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

    public function pharmacy_profile_search_filter()
    {
        $session = session();
        $Categories = $this->request->getVar('Categories');


        $AllFilter = array(
            'Categories' => $Categories,

        );


//        print_r($AllCVFilter);exit;
        $session->set('PharmacyProfileFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }

    public function fetch_pharmacy()
    {
        $Crud = new Crud();
        $Main = new Main();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $PharmacyModal = new PharmacyModal();
        $Data = $PharmacyModal->get_datatables($keyword);
        $totalfilterrecords = $PharmacyModal->count_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {

            $cnt++;
            $cities = $Crud->SingleRecord("cities", array("UID" => $record['City']));

            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['FullName']) ? '<b>' . htmlspecialchars($record['FullName']) . '</b>' : '';
            $data[] = isset($cities['FullName']) ? htmlspecialchars($cities['FullName']) : '';
            $data[] = isset($record['ContactNo']) ? htmlspecialchars($record['ContactNo']) : '';
            $data[] = isset($record['MAC']) ? htmlspecialchars($record['MAC']) : '';
            $data[] = isset($record['DeploymentDate']) ? '<b>' . date("d M, Y", strtotime($record['DeploymentDate'])) . '</b>' : '';
            $data[] = ((isset($record['ExpireDate']) && $record['ExpireDate'] != '0000-00-00' && $record['ExpireDate'] != '') ? '<b>' . date("d M, Y", strtotime($record['ExpireDate'])) . '</b>' : '<badge class="badge badge-danger">EXPIRED</badge>');
            $data[] = isset($record['Status']) ? '<badge class="badge badge-' . (($record['Status'] == 'active') ? 'success' : 'danger') . '">' . ucwords(htmlspecialchars($record['Status'])) . '</badge>' : '';
            $Actions = '<td class="text-end">
                        <div class="dropdown">
                            <button style="border-radius: 5px;" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" title="Update Profile" href="javascript:void(0);" onclick="UpdatePharmacy(' . htmlspecialchars($record['UID']) . ')">
                                    <i class="fa fa-pencil"></i> Update Profile
                                </a>
                                <a class="dropdown-item" title="Delete" href="javascript:void(0);" onclick="DeletePharmacy(' . htmlspecialchars($record['UID']) . ')">
                                    <i class="fa fa-trash"></i> Delete
                                </a>';
            if ($record['Status'] != 'block' && $record['ExpireDate'] != '' && $record['ExpireDate'] != '0000-00-00' && date("Y-m-d", strtotime($record['ExpireDate'])) > date("Y-m-d")) {
                $Actions .= '<a class="dropdown-item" title="About License" href="javascript:void(0);" onclick="LoadLicense(' . htmlspecialchars($record['UID']) . ')">
                                    <i class="fa fa-key"></i> About License
                                </a>';
            }
            $Actions .= '</div>
                        </div>
                    </td>';

            $data[] = $Actions;
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
            // Generate LicenseCode when ExpireDate and MAC are provided
            if (!empty($record['ExpireDate']) && $record['ExpireDate'] != '0000-00-00' && !empty($record['MAC'])) {
                $license = array(
                    'RAND' => rand(1000000000, 9999999999),
                    'ExpireDate' => $record['ExpireDate'],
                    'CODE' => md5($record['MAC']),
                    'MAC' => $record['MAC']
                );
                $record['LicenseCode'] = base64_encode(json_encode($license));
            }

            $RecordId = $Crud->AddRecord("pharmacy_profiles", $record);
            if (isset($RecordId) && $RecordId > 0) {

                $PackageUID = $this->request->getVar('Package');
                if (isset($PackageUID) && $PackageUID > 0) {

                    $Invoices = new Invoices();
                    $OriginalPrice = $this->request->getVar('OriginalPrice');
                    $Discount = $this->request->getVar('Discount');
                    $Price = $this->request->getVar('Price');
                    $InvoiceDetailsArray = array(
                        'ProfileName' => $Pharmacy['FullName'],
                        'ProductType' => 'extended',
                        'Product' => 'pharmacy',
                        'ProfileUID' => $RecordId,
                        'PackageUID' => $PackageUID,
                        'OriginalPrice' => $OriginalPrice,
                        'Discount' => $Discount,
                        'Price' => $Price
                    );
                    $Invoices->AddProfileSubscriptionDetails($InvoiceDetailsArray);
                }

                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            // Get old profile record before updating
            $oldProfile = $Crud->SingleRecord("pharmacy_profiles", array("UID" => $id));
            $oldExpireDate = isset($oldProfile['ExpireDate']) ? $oldProfile['ExpireDate'] : '';

            foreach ($Pharmacy as $key => $value) {
                $record[$key] = $value;
            }

            // Check if ExpireDate is being updated
            $newExpireDate = isset($record['ExpireDate']) ? $record['ExpireDate'] : '';
            $expireDateChanged = false;

            // Normalize dates for comparison (handle '0000-00-00' and empty values)
            $oldExpireDateNormalized = ($oldExpireDate == '' || $oldExpireDate == '0000-00-00') ? '' : $oldExpireDate;
            $newExpireDateNormalized = ($newExpireDate == '' || $newExpireDate == '0000-00-00') ? '' : $newExpireDate;

            if ($oldExpireDateNormalized != $newExpireDateNormalized && !empty($newExpireDateNormalized)) {
                $expireDateChanged = true;
            }

            // Regenerate LicenseCode when ExpireDate or MAC is updated (license contains both)
            if (!empty($record['ExpireDate']) && $record['ExpireDate'] != '0000-00-00') {
                $profile = $oldProfile; // Use already fetched profile
                $MAC = $record['MAC'] ?? ($profile['MAC'] ?? '');
                if (!empty($MAC)) {
                    $license = array(
                        'RAND' => rand(1000000000, 9999999999),
                        'ExpireDate' => $record['ExpireDate'],
                        'CODE' => md5($MAC),
                        'MAC' => $MAC
                    );
                    $record['LicenseCode'] = base64_encode(json_encode($license));
                }
            }

            $Crud->UpdateRecord("pharmacy_profiles", $record, array("UID" => $id));

            // Send WhatsApp notification if ExpireDate was updated
            if ($expireDateChanged) {
                helper('whatsapp');
                $pharmacyName = isset($record['FullName']) ? trim($record['FullName']) : (isset($oldProfile['FullName']) ? $oldProfile['FullName'] : 'Unknown Pharmacy');
                $newExpireFormatted = date("d M, Y", strtotime($newExpireDateNormalized));
                $oldExpireFormatted = !empty($oldExpireDateNormalized) ? date("d M, Y", strtotime($oldExpireDateNormalized)) : 'N/A';
                $cityRecord = $Crud->SingleRecord("cities", array("UID" => (isset($record['City']) ? $record['City'] : 0)));
                $cityName = isset($cityRecord['FullName']) ? $cityRecord['FullName'] : 'N/A';
                $contactNo = isset($record['ContactNo']) ? $record['ContactNo'] : (isset($oldProfile['ContactNo']) ? $oldProfile['ContactNo'] : 'N/A');
                $saleAgent = isset($record['SaleAgent']) ? $record['SaleAgent'] : (isset($oldProfile['SaleAgent']) ? $oldProfile['SaleAgent'] : 'N/A');
                $message = "🔄 *Pharmacy License Updated*\n\n"
                    . "*Pharmacy:* {$pharmacyName}\n"
                    . "*Profile ID:* {$id}\n\n"
                    . "*License expiry*\n"
                    . "Previous: {$oldExpireFormatted}\n"
                    . "New: {$newExpireFormatted}\n\n"
                    . "*Contact:* {$contactNo}\n"
                    . "*City:* {$cityName}\n"
                    . "*Sale Agent:* {$saleAgent}";
                SendWhatsAPPWithBCC('923455913609', $message);
            }

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
        $response['code_detail'] = base64_decode($record['LicenseCode']);
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
        $session = session();;
        $MACAddress = $this->request->getVar('MACAddress');
        $FullName = $this->request->getVar('FullName');
        $City = $this->request->getVar('City');
        $DeploymentDate = $this->request->getVar('DeploymentDate');
        $AllFilter = array(
            'MACAddress' => $MACAddress,
            'FullName' => $FullName,
            'City' => $City,
            'DeploymentDate' => $DeploymentDate,

        );


        $session->set('PharmacyFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }
}
