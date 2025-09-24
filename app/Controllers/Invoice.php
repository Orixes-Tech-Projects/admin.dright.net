<?php

namespace App\Controllers;

use App\Models\BuilderModel;
use App\Models\Crud;
use App\Models\Invoices;
use App\Models\Main;
use App\Models\SystemUser;
use DateTime;

class Invoice extends BaseController
{
    var $data = array();
    var $MainModel;

    public function __construct()
    {

        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
    }

    public function index()
    {
        $data = $this->data;
        $data['page'] = getSegment(2);

        echo view('header', $data);

        echo view('invoice/index', $data);

        echo view('footer', $data);
    }

    public function customer()
    {
        $data = $this->data;
        $data['page'] = getSegment(2);
        $Invoice = new SystemUser();

        $data['AllCustomers'] = $Invoice->allcustomer();

        echo view('header', $data);

        echo view('invoice/customer', $data);

        echo view('footer', $data);
    }

    public function invoice_detail()
    {
        $data = $this->data;
        $Crud = new Crud();
        $data['UID'] = $UID = getSegment(3);

        if (empty($UID) || !is_numeric($UID) || $UID <= 0) {
            return redirect()->to($data['path'] . 'invoice');
        }

        $data['InvoiceDetail'] = $InvoiceDetail = $Crud->SingleRecord("invoices", ["UID" => $UID]);
        if (empty($InvoiceDetail['UID'])) {
            return redirect()->to($data['path'] . 'invoice');
        }
        $data['PackageDetail'] = $Crud->SingleRecord("items", ["UID" => $InvoiceDetail['PackageUID']]);
        $data['PaymentDetails'] = $Crud->ListRecords("invoices_payments", ["InvoiceUID" => $UID], ['Date' => 'DESC']);
        $data['ClientDetails'] = $this->GetClientDetails($Crud, $InvoiceDetail);

        return view('invoice/invoice_detail', $data);
    }

    protected function GetClientDetails($Crud, $invoiceDetail)
    {
        if (empty($invoiceDetail['ProfileUID'])) {
            return [];
        }

        switch (true) {
            case ($invoiceDetail['ProductType'] == 'builder'):
                return $Crud->SingleeRecord('public."profiles"', ['UID' => $invoiceDetail['ProfileUID']]);

            case ($invoiceDetail['Product'] == 'hospitals'):
                return $Crud->SingleRecord('extended_profiles', ['UID' => $invoiceDetail['ProfileUID']]);

            default:
                return $Crud->SingleRecord('pharmacy_profiles', ['UID' => $invoiceDetail['ProfileUID']]);
        }
    }


    public function fetch_invoice()
    {
        $MainData = $this->data;
        $DataArray = array();
        $Invoices = new Invoices();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');
        $length = $_POST['length'];
        $start = $_POST['start'];

        $Data = $Invoices->GetAllInvoicesRecord($keyword, $length, $start);
        $totalfilterrecords = $Invoices->GetAllInvoicesRecord($keyword);
        $cnt = $_POST['start'];
        foreach ($Data as $record) {

            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = ((isset($record['InvoiceNo']) && $record['InvoiceNo'] != '') ? '<b>' . $record['InvoiceNo'] . '</b>' : '-');
            $data[] = date('d M, Y', strtotime($record['SystemDate']));
            $data[] = isset($record['ProductType']) ? '<b>ClinTa ' . ucwords($record['ProductType']) . ' / ' . ucwords($record['Product']) . '</b>' : '';
            $data[] = isset($record['ProfileName']) ? $record['ProfileName'] : '';
            $data[] = isset($record['PackageName']) ? $record['PackageName'] : '';
            $data[] = isset($record['Status']) ? '<badge style="color:white !important;" class="badge badge-' . (($record['Status'] == 'un-paid') ? 'danger' : (($record['Status'] == 'paid') ? 'success' : 'warning')) . '">' . ucwords(str_replace('-', ' ', $record['Status'])) . '</badge>' : '';
            $action = '<td class="text-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">';
            if ($record['Status'] == 'un-paid') {
                $action .= ' <a style="cursor: pointer;" class="dropdown-item" onclick="LoadUpdateInvoiceModal(' . htmlspecialchars($record['UID']) . ')">Update</a>';
            }
            $action .= ' <a style="cursor: pointer;" class="dropdown-item" onclick="LoadInvoicePaymentModal(' . htmlspecialchars($record['UID']) . ', \'' . htmlspecialchars($record['InvoiceNo']) . '\', \'' . htmlspecialchars($record['Price']) . '\', \'' . htmlspecialchars($record['ReceivedAmount']) . '\', \'' . htmlspecialchars($record['Status']) . '\')">Payments</a>';
            $action .= '<a target="_blank" style="cursor: pointer;" href="' . $MainData['path'] . 'invoice/invoice_detail/' . $record['UID'] . '" class="dropdown-item">View Invoice</a>
                                    <a class="dropdown-item d-none" onclick="DeleteInvoice(' . htmlspecialchars($record['UID']) . ')">Delete</a>
                                </div>
                            </div>
                        </td>';
            $data[] = $action;

            $DataArray[] = $data;
        }

        $response = array(
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => count($Data),
            "recordsFiltered" => count($totalfilterrecords),
            "data" => $DataArray
        );
        echo json_encode($response);
    }

    public function fetch_invoice_customer()
    {
        $Users = new SystemUser();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Users->get_invoice_customer_datatables($keyword);
        $totalfilterrecords = $Users->count_invoice_customer_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['Name']) ? htmlspecialchars($record['Name']) : '';
            $data[] = isset($record['Email']) ? htmlspecialchars($record['Email']) : '';
            $data[] = isset($record['PhoneNumber']) ? htmlspecialchars($record['PhoneNumber']) : '';
            $data[] = '
                    <td class="text-end">
                        <div class="dropdown">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" onclick="UpdateCustomer(' . htmlspecialchars($record['UID']) . ')">Update</a>
                                <a class="dropdown-item" onclick="DeleteCustomer(' . htmlspecialchars($record['UID']) . ')">Delete</a>
                
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

    protected function HasProfilesUnPaidInvoices($profileUID, $productType, $product)
    {
        $Crud = new Crud();
        $TotalRecords = $Crud->ListRecords("invoices", [
            'ProfileUID' => $profileUID,
            'ProductType' => $productType,
            'Product' => $product,
            'Status' => 'un-paid'
        ]);

        return count($TotalRecords) > 0;
    }

    protected function CheckExtendedProfilesEligibility($profileUID, $product)
    {
        $Crud = new Crud();
        $table = ($product == 'hospitals') ? 'extended_profiles' : 'pharmacy_profiles';
        $profile = $Crud->SingleRecord($table, ['UID' => $profileUID]);

        if (empty($profile) || is_null($profile['ExpireDate'])) {
            return true;
        }

        try {

            $expiryDate = new DateTime($profile['ExpireDate']);
            $today = new DateTime();

            if ($today > $expiryDate) {
                return true;
            }

            $interval = $today->diff($expiryDate);
            $daysRemaining = $interval->days;

            return $daysRemaining <= 15;

        } catch (Exception $e) {
            return false;
        }
    }

    protected function CheckBuilderProfilesEligibility($profileUID)
    {
        $Crud = new Crud();
        $table = 'public."profiles"';
        $profile = $Crud->SingleeRecord($table, ['UID' => $profileUID]);
        if (empty($profile) || is_null($profile['ExpireDate'])) {
            return true;
        }

        try {

            $expiryDate = new DateTime($profile['ExpireDate']);
            $today = new DateTime();

            if ($today > $expiryDate) {
                return true;
            }

            $interval = $today->diff($expiryDate);
            $daysRemaining = $interval->days;

            return $daysRemaining <= 15;

        } catch (Exception $e) {
            return false;
        }
    }

    public function invoice_detail_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $Invoices = new Invoices();
        $response = array();
        $record = array();

        $ID = $this->request->getVar('UID');
        $ProductType = $this->request->getVar('ProductType');
        $BuilderProduct = $this->request->getVar('BuilderProducts');
        $ExtendedProduct = $this->request->getVar('ExtendedProducts');
        $Profile = $this->request->getVar('Profile');
        $Package = $this->request->getVar('Package');
        $OriginalPrice = $this->request->getVar('OriginalPrice');
        $Discount = $this->request->getVar('Discount');
        $Price = $this->request->getVar('Price');

        $ProfileName = $this->request->getVar('ProfileName');
        $ProfileName = ((isset($ProfileName) && $ProfileName != '') ? $ProfileName : 'Demo');

        $Product = (($ProductType == 'builder') ? $BuilderProduct : $ExtendedProduct);

        if ($ID == 0) {

            // Check for unpaid invoices first
            if ($this->HasProfilesUnPaidInvoices($Profile, $ProductType, $Product)) {
                $response['status'] = 'fail';
                $response['message'] = 'Cannot create invoice - client has unpaid invoices';
                echo json_encode($response);
                return;
            }

            // For extended products, check expiry conditions
            if ($ProductType == 'extended' && !$this->CheckExtendedProfilesEligibility($Profile, $Product)) {
                $response['status'] = 'fail';
                $response['message'] = 'You can only create new invoices when your current subscription is within 15 days of expiry.';
                echo json_encode($response);
                return;
            }

            // For builder products, check expiry conditions
            if ($ProductType == 'builder' && !$this->CheckBuilderProfilesEligibility($Profile)) {
                $response['status'] = 'fail';
                $response['message'] = 'You can only create new invoices when your current subscription is within 15 days of expiry.';
                echo json_encode($response);
                return;
            }


            $InvoiceNo = generate_invoice_number(
                $ProfileName,
                $ProductType,
                $Product
            );

            $record['InvoiceNo'] = $InvoiceNo;
            $record['ProductType'] = $ProductType;
            $record['Product'] = $Product;
            $record['ProfileUID'] = $Profile;
            $record['PackageUID'] = $Package;
            $record['OriginalPrice'] = $OriginalPrice;
            $record['Discount'] = $Discount;
            $record['Price'] = $Price;
            $record['Status'] = 'un-paid';
            $RecordId = $Crud->AddRecord("invoices", $record);
            if (isset($RecordId) && $RecordId > 0) {

                if ($ProductType == 'extended') {
                    $Invoices->UpdateExtendedProfilesExpiryDate($Profile, $Product, $RecordId);
                } else {
                    $Invoices->UpdateBuilderProfilesExpiryDate($Profile, $RecordId);
                }

                $Main = new Main();
                $msg = $_SESSION['FullName'] . ' Add Invoice Details Through Admin DRight';
                $logesegment = 'Users';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());

                $response['status'] = 'success';
                $response['message'] = 'Invoice Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }

        } else {

            $record['ProductType'] = $ProductType;
            $record['Product'] = $Product;
            $record['ProfileUID'] = $Profile;
            $record['PackageUID'] = $Package;
            $record['OriginalPrice'] = $OriginalPrice;
            $record['Discount'] = $Discount;
            $record['Price'] = $Price;

            $UpdateRecord = $Crud->UpdateRecord("invoices", $record, array("UID" => $ID));
            if (isset($UpdateRecord) && $UpdateRecord > 0) {

                if ($ProductType == 'extended') {
                    $Invoices->UpdateExtendedProfilesExpiryDate($Profile, $Product, $ID);
                } else {
                    $Invoices->UpdateBuilderProfilesExpiryDate($Profile, $ID);
                }
            }

            $msg = $_SESSION['FullName'] . ' Update Invoice Detail Through Admin DRight';
            $logesegment = 'Users';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
            $response['status'] = 'success';
            $response['message'] = 'User Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function customer_detail_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $User = $this->request->getVar('Invoice');


        if ($id == 0) {
            foreach ($User as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }

            $RecordId = $Crud->AddRecord("invoice_customers", $record);
            if (isset($RecordId) && $RecordId > 0) {

                $Main = new Main();

                $msg = $_SESSION['FullName'] . ' Add Customer Detail Through Admin Dright';
                $logesegment = 'Users';
                $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
                $response['status'] = 'success';
                $response['message'] = 'Customer Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($User as $key => $value) {
                $record[$key] = $value;
            }

            $msg = $_SESSION['FullName'] . ' Update  Detail Through Admin Dright';
            $logesegment = 'Customer';
            $Main->adminlog($logesegment, $msg, $this->request->getIPAddress());
            $Crud->UpdateRecord("invoice_customers", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'User Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function delete_invoice()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();
        $table = "invoices";
        $record['Archive'] = 1;
        $where = array('UID' => $UID);
        $Crud->UpdateRecord($table, $record, $where);
        $response['status'] = 'success';
        $response['delete_invoice'] = 'Deleted Successfully...!';
        echo json_encode($response);

    }

    public function delete_invoice_customers()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();
        $table = "invoice_customers";
        $record['Archive'] = 1;
        $where = array('UID' => $UID);
        $Crud->UpdateRecord($table, $record, $where);
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';
        echo json_encode($response);

    }

    public function get_record_invoice()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("invoices", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function get_record_invoice_customers()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("invoice_customers", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function get_item_price()
    {
        $response = ['success' => false, 'price' => '', 'message' => ''];
        $Crud = new Crud();

        $UID = $this->request->getVar('UID');
//        print_r($UID);
//        exit();
        if ($UID) {
            // Fetch the item details based on UID
            $item = $Crud->SingleRecord('items', ['UID' => $UID]);
            if ($item) {
                $response['success'] = true;
                $response['price'] = $item['Price']; // Assuming 'Price' is the column name
            } else {
                $response['message'] = 'Item not found.';
            }
        } else {
            $response['message'] = 'Invalid item ID.';
        }


        echo json_encode($response);
    }


    public
    function load_invoice_product_customer_view()
    {
        $Crud = new Crud();
        $BuilderModel = new BuilderModel();
        $data = $this->data;
        $ProductType = $this->request->getVar('ProductType');
        $Product = $this->request->getVar('Product');

        $data['ProductType'] = $ProductType;
        $data['Product'] = $Product;

        if ($ProductType == 'builder') {
            $SQL = $BuilderModel->Allprofiless($Product, '');
            $data['Records'] = $Crud->ExecutePgSQL($SQL);
        } else {
            if ($Product == 'hospitals') {
                $data['Records'] = $Crud->ListRecords('extended_profiles', array(), array('FullName' => 'ASC'));
            } else {
                $data['Records'] = $Crud->ListRecords('pharmacy_profiles', array(), array('FullName' => 'ASC'));
            }
        }

        $page = view('invoice/modal/_ProductCustomersDropDown', $data);
        echo json_encode(array('status' => 'success', 'page' => $page));
    }

    public
    function invoice_payments_form_submit()
    {

        $Crud = new Crud();
        $Invoices = new Invoices();
        $InvoiceUID = $this->request->getVar('InvoiceID');
        $InvoiceAmount = $this->request->getVar('InvoiceAmount');
        $ReceivedAmount = $this->request->getVar('ReceivedAmount');
        $PaymentMode = $this->request->getVar('PaymentMode');
        $Date = $this->request->getVar('Date');
        $Amount = $this->request->getVar('Amount');

        $PaymentRecord = array();
        $PaymentRecord['InvoiceUID'] = $InvoiceUID;
        $PaymentRecord['PaymentMode'] = $PaymentMode;
        $PaymentRecord['Date'] = date("Y-m-d", strtotime($Date));
        $PaymentRecord['Amount'] = $Amount;

        $PaymentRecordID = $Crud->AddRecord('invoices_payments', $PaymentRecord);
        if (isset($PaymentRecordID) && $PaymentRecordID > 0) {

            $ActualReceivedAmount = $ReceivedAmount + $Amount;
            $InvoiceStatus = (($InvoiceAmount == $ActualReceivedAmount) ? 'paid' : (($ActualReceivedAmount < $InvoiceAmount) ? 'partially-paid' : 'un-paid'));
            $Crud->UpdateRecord('invoices', array('ReceivedAmount' => $ActualReceivedAmount, 'Status' => $InvoiceStatus), array('UID' => $InvoiceUID));
            if ($InvoiceStatus == 'paid') {
                $Invoices->UpdateProductsProfileStatusToActive($InvoiceUID);
            }

            $response = array();
            $response['status'] = 'success';
            $response['message'] = 'Payment Added Successfully...!';
            echo json_encode($response);

        } else {

            $response = array();
            $response['status'] = 'fail';
            $response['message'] = 'Failed to create payment record...!';
            echo json_encode($response);
        }
    }

    public
    function get_invoice_payments_record()
    {

        $Crud = new Crud();
        $formattedRecords = array();
        $InvoiceID = $this->request->getVar('InvoiceID');

        $PaymentsRecord = $Crud->ListRecords('invoices_payments', array('InvoiceUID' => $InvoiceID), array('Date' => 'DESC'));
        if (count($PaymentsRecord) > 0) {
            $formattedRecords = array_map(function ($record) {
                return [
                    'UID' => $record['UID'] ?? null,
                    'Date' => date("d M, Y", strtotime($record['Date'])) ?? null,
                    'PaymentMode' => ucwords(str_replace('-', ' ', $record['PaymentMode'])) ?? null,
                    'Amount' => money($record['Amount'], false) ?? null
                ];
            }, $PaymentsRecord);
        }

        echo json_encode($formattedRecords);
    }


    public
    function per_invoice_ledgers()
    {

        $data = $this->data;
        $data['page'] = getSegment(2);

        echo view('header', $data);
        echo view('invoice/per-invoice-ledgers', $data);
        echo view('footer', $data);
    }

    public
    function per_prescription_ledgers()
    {

        $data = $this->data;
        $data['page'] = getSegment(2);

        echo view('header', $data);
        echo view('invoice/per-prescription-ledgers', $data);
        echo view('footer', $data);
    }

    public
    function fetch_per_invoice_ledgers()
    {
        $Invoices = new Invoices();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');
        $length = $_POST['length'];
        $start = $_POST['start'];
        $cnt = $_POST['start'];
        $DataArray = array();

        $Data = $Invoices->GetAllPerInvoiceLedgerRecord($keyword, $length, $start);
        $totalfilterrecords = $Invoices->GetAllPerInvoiceLedgerRecord($keyword);
        foreach ($Data as $record) {

            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = '<b>' . $record['ProfileName'] . '</b>';
            $data[] = (($record['MiniHims'] == 1) ? '<b>Mini Hims</b>' : '<b>' . ucwords($record['ProfileType']) . '</b>');
            $data[] = '<badge class="badge badge-primary">' . $record['TotalInvoices'] . '</badge>';
            $data[] = '<badge class="badge badge-info">' . money($record['TotalPayments'], false, true) . '</badge>';
            $data[] = '<badge class="badge badge-success">' . money($record['TotalReceived'], false, true) . '</badge>';
            $data[] = isset($record['Status']) ? '<badge style="color:white !important;" class="badge badge-' . (($record['Status'] == 'un-paid') ? 'danger' : (($record['Status'] == 'paid') ? 'success' : 'warning')) . '">' . ucwords(str_replace('-', ' ', $record['Status'])) . '</badge>' : '';
            $action = '<td class="text-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a style="cursor: pointer;" class="dropdown-item" onclick="LoadPerInvoiceLedgerPaymentModal(\'' . htmlspecialchars($record['ProfileName']) . '\', ' . htmlspecialchars($record['ProfileUID']) . ', ' . htmlspecialchars($record['UID']) . ', \'' . htmlspecialchars($record['TotalPayments']) . '\', \'' . htmlspecialchars($record['TotalReceived']) . '\', \'' . htmlspecialchars($record['Status']) . '\')">Payments</a>
                                </div>
                            </div>
                        </td>';
            $data[] = $action;

            $DataArray[] = $data;
        }

        $response = array(
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => count($Data),
            "recordsFiltered" => count($totalfilterrecords),
            "data" => $DataArray
        );
        echo json_encode($response);
    }

    public
    function per_invoice_payments_ledger_form_submit()
    {

        $Crud = new Crud();
        $Invoices = new Invoices();
        $ProfileUID = $this->request->getVar('ProfileUID');
        $InvoiceUID = $this->request->getVar('InvoiceID');
        $InvoiceAmount = $this->request->getVar('InvoiceAmount');
        $ReceivedAmount = $this->request->getVar('ReceivedAmount');
        $PaymentMode = $this->request->getVar('PaymentMode');
        $Date = $this->request->getVar('Date');
        $Amount = $this->request->getVar('Amount');

        $PaymentRecord = array();
        $PaymentRecord['ProfileUID'] = $ProfileUID;
        $PaymentRecord['LedgerUID'] = $InvoiceUID;
        $PaymentRecord['PaymentMode'] = $PaymentMode;
        $PaymentRecord['Date'] = date("Y-m-d", strtotime($Date));
        $PaymentRecord['Amount'] = $Amount;

        $PaymentRecordID = $Crud->AddRecordPG('public."invoice_ledger_payments"', $PaymentRecord);
        if (isset($PaymentRecordID) && $PaymentRecordID > 0) {

            $ActualReceivedAmount = $ReceivedAmount + $Amount;
            $InvoiceStatus = (($InvoiceAmount == $ActualReceivedAmount) ? 'paid' : (($ActualReceivedAmount < $InvoiceAmount) ? 'partially-paid' : 'un-paid'));
            $Crud->UpdateeRecord('public."profile_invoices_payments"', array('TotalReceived' => $ActualReceivedAmount, 'Status' => $InvoiceStatus), array('UID' => $InvoiceUID));

            $response = array();
            $response['status'] = 'success';
            $response['message'] = 'Payment Added Successfully...!';
            echo json_encode($response);

        } else {

            $response = array();
            $response['status'] = 'fail';
            $response['message'] = 'Failed to create payment record...!';
            echo json_encode($response);
        }
    }

    public
    function get_per_invoice_payments_ledgers_record()
    {

        $Crud = new Crud();
        $formattedRecords = array();
        $ProfileUID = $this->request->getVar('ProfileUID');
        $InvoiceID = $this->request->getVar('InvoiceID');

        $SQL = ' SELECT "invoice_ledger_payments".* FROM public."invoice_ledger_payments" 
                WHERE "ProfileUID" = ' . $ProfileUID . ' AND "LedgerUID" = ' . $InvoiceID . ' ORDER BY "SystemDate" DESC ';
        $PaymentsRecord = $Crud->ExecutePgSQL($SQL);
        if (count($PaymentsRecord) > 0) {
            $formattedRecords = array_map(function ($record) {
                return [
                    'UID' => $record['UID'] ?? null,
                    'Date' => date("d M, Y", strtotime($record['Date'])) ?? null,
                    'PaymentMode' => ucwords(str_replace('-', ' ', $record['PaymentMode'])) ?? null,
                    'Amount' => money($record['Amount'], false, true) ?? null
                ];
            }, $PaymentsRecord);
        }

        echo json_encode($formattedRecords);
    }

    public
    function fetch_per_prescription_ledgers()
    {
        $Invoices = new Invoices();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');
        $length = $_POST['length'];
        $start = $_POST['start'];
        $cnt = $_POST['start'];
        $DataArray = array();

        $Data = $Invoices->GetAllPerPrescriptionLedgerRecord($keyword, $length, $start);
        $totalfilterrecords = $Invoices->GetAllPerPrescriptionLedgerRecord($keyword);
        foreach ($Data as $record) {

            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = '<b>' . $record['ProfileName'] . '</b>';
            $data[] = (($record['MiniHims'] == 1) ? '<b>Mini Hims</b>' : '<b>' . ucwords($record['ProfileType']) . '</b>');
            $data[] = '<badge class="badge badge-primary">' . $record['TotalPrescriptions'] . '</badge>';
            $data[] = '<badge class="badge badge-info">' . money($record['TotalPayments'], false, true) . '</badge>';
            $data[] = '<badge class="badge badge-success">' . money($record['TotalReceived'], false, true) . '</badge>';
            $data[] = isset($record['Status']) ? '<badge style="color:white !important;" class="badge badge-' . (($record['Status'] == 'un-paid') ? 'danger' : (($record['Status'] == 'paid') ? 'success' : 'warning')) . '">' . ucwords(str_replace('-', ' ', $record['Status'])) . '</badge>' : '';
            $action = '<td class="text-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a style="cursor: pointer;" class="dropdown-item" onclick="LoadPerPrescriptionsLedgerPaymentModal(\'' . htmlspecialchars($record['ProfileName']) . '\', ' . htmlspecialchars($record['ProfileUID']) . ', ' . htmlspecialchars($record['UID']) . ', \'' . htmlspecialchars($record['TotalPayments']) . '\', \'' . htmlspecialchars($record['TotalReceived']) . '\', \'' . htmlspecialchars($record['Status']) . '\')">Payments</a>
                                </div>
                            </div>
                        </td>';
            $data[] = $action;

            $DataArray[] = $data;
        }

        $response = array(
            "draw" => intval($this->request->getPost('draw')),
            "recordsTotal" => count($Data),
            "recordsFiltered" => count($totalfilterrecords),
            "data" => $DataArray
        );
        echo json_encode($response);
    }

    public
    function per_prescriptions_payments_ledger_form_submit()
    {

        $Crud = new Crud();
        $Invoices = new Invoices();
        $ProfileUID = $this->request->getVar('ProfileUID');
        $PrescriptionsUID = $this->request->getVar('PrescriptionsID');
        $PrescriptionsAmount = $this->request->getVar('PrescriptionsAmount');
        $ReceivedAmount = $this->request->getVar('ReceivedAmount');
        $PaymentMode = $this->request->getVar('PaymentMode');
        $Date = $this->request->getVar('Date');
        $Amount = $this->request->getVar('Amount');

        $PaymentRecord = array();
        $PaymentRecord['ProfileUID'] = $ProfileUID;
        $PaymentRecord['LedgerUID'] = $PrescriptionsUID;
        $PaymentRecord['PaymentMode'] = $PaymentMode;
        $PaymentRecord['Date'] = date("Y-m-d", strtotime($Date));
        $PaymentRecord['Amount'] = $Amount;

        $PaymentRecordID = $Crud->AddRecordPG('public."prescriptions_ledger_payments"', $PaymentRecord);
        if (isset($PaymentRecordID) && $PaymentRecordID > 0) {

            $ActualReceivedAmount = $ReceivedAmount + $Amount;
            $PrescriptionsStatus = (($PrescriptionsAmount == $ActualReceivedAmount) ? 'paid' : (($ActualReceivedAmount < $PrescriptionsAmount) ? 'partially-paid' : 'un-paid'));
            $Crud->UpdateeRecord('public."profile_prescription_payments"', array('TotalReceived' => $ActualReceivedAmount, 'Status' => $PrescriptionsStatus), array('UID' => $PrescriptionsUID));

            $response = array();
            $response['status'] = 'success';
            $response['message'] = 'Payment Added Successfully...!';
            echo json_encode($response);

        } else {

            $response = array();
            $response['status'] = 'fail';
            $response['message'] = 'Failed to create payment record...!';
            echo json_encode($response);
        }
    }

    public
    function get_per_prescription_payments_ledgers_record()
    {

        $Crud = new Crud();
        $formattedRecords = array();
        $ProfileUID = $this->request->getVar('ProfileUID');
        $PrescriptionsID = $this->request->getVar('PrescriptionsID');

        $SQL = ' SELECT "prescriptions_ledger_payments".* FROM public."prescriptions_ledger_payments" 
                WHERE "ProfileUID" = ' . $ProfileUID . ' AND "LedgerUID" = ' . $PrescriptionsID . ' ORDER BY "SystemDate" DESC ';
        $PaymentsRecord = $Crud->ExecutePgSQL($SQL);
        if (count($PaymentsRecord) > 0) {
            $formattedRecords = array_map(function ($record) {
                return [
                    'UID' => $record['UID'] ?? null,
                    'Date' => date("d M, Y", strtotime($record['Date'])) ?? null,
                    'PaymentMode' => ucwords(str_replace('-', ' ', $record['PaymentMode'])) ?? null,
                    'Amount' => money($record['Amount'], false, true) ?? null
                ];
            }, $PaymentsRecord);
        }

        echo json_encode($formattedRecords);
    }

}
