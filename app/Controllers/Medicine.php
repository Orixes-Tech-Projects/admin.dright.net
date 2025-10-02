<?php

namespace App\Controllers;


use App\Models\Crud;
use App\Models\Main;
use App\Models\MedicineModel;

class Medicine extends BaseController
{
    var $data = array();

    public function __construct()
    {

        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
    }

    public function index()
    {
        $data = $this->data;
        $data['page'] = getSegment(2);
        $MedicineModel = new MedicineModel();
        $data['Company'] = $MedicineModel->ListAllCompanies();
//        print_r()
        echo view('header', $data);

        echo view('medicine/index', $data);

        echo view('footer', $data);
    }

    public function take_type()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('medicine/take_type', $data);
        echo view('footer', $data);
    }

    public function timing()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('medicine/timing', $data);
        echo view('footer', $data);
    }

    public function list()
    {
        $data = $this->data;
        $data['UID'] = getSegment(3);
        $MedicineModel = new MedicineModel();
        $data['Company'] = $MedicineModel->ListAllCompanies();
        echo view('header', $data);
        echo view('medicine/medicine_list_by_id', $data);
        echo view('footer', $data);
    }

    public function medicine_forms()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('medicine/forms', $data);
        echo view('footer', $data);
    }

    public function company()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('medicine/company', $data);
        echo view('footer', $data);
    }

    public function dashboard()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('support_ticket/dashboard', $data);
        echo view('footer', $data);
    }

    public function fetch_medicine()
    {
        $MedicineModel = new MedicineModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $MedicineModel->get_medicine_datatables($keyword);
        $totalfilterrecords = $MedicineModel->count_medicine_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['DosageForm']) ? htmlspecialchars($record['DosageForm']) : '';
            $data[] = isset($record['MedicineTitle']) ? htmlspecialchars($record['MedicineTitle']) : '';
            $data[] = isset($record['Packing']) ? htmlspecialchars($record['Packing']) : '';
            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="UpdateMedicine(' . htmlspecialchars($record['UID']) . ')">Update</a>
                                    <a class="dropdown-item" onclick="DeleteMedicine(' . htmlspecialchars($record['UID']) . ')">Delete</a>
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

    public function fetch_medicine_record_by_id()
    {
        $MedicineModel = new MedicineModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');
        $ID = $this->request->getVar('UID');

        $Data = $MedicineModel->get_med_by_id_datatables($keyword, $ID);
//        print_r($Data);exit();
        $totalfilterrecords = $MedicineModel->count_med_by_id_datatables($keyword, $ID);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['PharmaTitle']) ? htmlspecialchars($record['PharmaTitle']) : '';
            $data[] = isset($record['MedicineTitle']) ? htmlspecialchars($record['MedicineTitle']) : '';
            $data[] = isset($record['Ingredients']) ? htmlspecialchars($record['Ingredients']) : '';
            $data[] = isset($record['DosageForm']) ? htmlspecialchars($record['DosageForm']) : '';
            $data[] = isset($record['Packing']) ? htmlspecialchars($record['Packing']) : '';
            $data[] = isset($record['TradePrice']) ? htmlspecialchars($record['TradePrice']) : '';
            $data[] = isset($record['RetailPrice']) ? htmlspecialchars($record['RetailPrice']) : '';
            $data[] = '
    <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                Actions
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" onclick="UpdateMedicine(' . htmlspecialchars($record['UID']) . ')">Update</a>
                <a class="dropdown-item" onclick="DeleteMedicine(' . htmlspecialchars($record['UID']) . ')">Delete</a>

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

    public function fetch_medicine_take_type()
    {
        $MedicineModel = new MedicineModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $MedicineModel->get_medicine_take_type_datatables($keyword);
//        print_r($Data);exit();
        $totalfilterrecords = $MedicineModel->count_medicine_take_type_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['TakeTypeEng']) ? htmlspecialchars($record['TakeTypeEng']) : '-';
            $data[] = isset($record['TakeType']) ? htmlspecialchars($record['TakeType']) : '';

            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="UpdateMedicineTakeType(' . htmlspecialchars($record['UID']) . ')">Update</a>
                                    <a class="dropdown-item" onclick="DeleteMedicineTakeType(' . htmlspecialchars($record['UID']) . ')">Delete</a>
                    
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

    public function fetch_medicine_company()
    {
        $MedicineModel = new MedicineModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $MedicineModel->get_pharma_company_datatables($keyword);
//        print_r($Data);exit();
        $totalfilterrecords = $MedicineModel->count_pharma_company_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $TotalMedicines = $MedicineModel->GetMedicinesByPharmaID($record['UID']);
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['CompanyName']) ? htmlspecialchars($record['CompanyName']) : '';
            $data[] = '<a href="' . PATH . 'medicine/list/' . $record['UID'] . '" target="_blank">' . count($TotalMedicines) . '</a>';

            $data[] = '
    <td class="text-end">
        <div class="dropdown">
            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                Actions
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" onclick="UpdateMedicineCompany(' . htmlspecialchars($record['UID']) . ')">Update</a>
                <a class="dropdown-item" onclick="DeleteMedicineCompany(' . htmlspecialchars($record['UID']) . ')">Delete</a>
                <a class="dropdown-item" href="http://clinta.biz/druginfo/manual.php?cid=' . htmlspecialchars($record['UID']) . '" target="_blank">Check New Medicine</a>
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

    public function fetch_medicine_forms()
    {
        $MedicineModel = new MedicineModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $MedicineModel->get_medicine_forms_datatables($keyword);
        $totalfilterrecords = $MedicineModel->count_medicine_forms_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {

            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['EngName']) ? htmlspecialchars($record['EngName']) : '-';
            $data[] = isset($record['Name']) ? htmlspecialchars($record['Name']) : '';
            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="UpdateMedicineForms(' . htmlspecialchars($record['UID']) . ')">Update</a>
                                    <a class="dropdown-item" onclick="DeleteMedicineForms(' . htmlspecialchars($record['UID']) . ')">Delete</a>
                    
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

    public function fetch_medicine_timing()
    {
        $MedicineModel = new MedicineModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $MedicineModel->get_medicine_timing_datatables($keyword);
        $totalfilterrecords = $MedicineModel->count_medicine_timing_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['EngName']) ? htmlspecialchars($record['EngName']) : '-';
            $data[] = isset($record['Name']) ? htmlspecialchars($record['Name']) : '';
            $data[] = '<td class="text-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" onclick="UpdateMedicineTiming(' . htmlspecialchars($record['UID']) . ')">Update</a>
                                    <a class="dropdown-item" onclick="DeleteMedicineTiming(' . htmlspecialchars($record['UID']) . ')">Delete</a>
                    
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

    public function submit_medicine_form()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Medicine = $this->request->getVar('Medicine');


        if ($id == 0) {
            foreach ($Medicine as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }

            $RecordId = $Crud->AddRecord("medicines", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Medicine as $key => $value) {
                $record[$key] = $value;
            }
            $Crud->UpdateRecord("medicines", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function submit_medicine_take_type_form()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Medicine = $this->request->getVar('TakeType');

        if ($id == 0) {

            foreach ($Medicine as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }
            $RecordId = $Crud->AddRecord("medicines_take_types", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {

            foreach ($Medicine as $key => $value) {
                $record[$key] = $value;
            }
            $Crud->UpdateRecord("medicines_take_types", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function submit_medicine_forms()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Medicine = $this->request->getVar('Forms');


        if ($id == 0) {
            foreach ($Medicine as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }

            $RecordId = $Crud->AddRecord("medicines_forms", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Medicine as $key => $value) {
                $record[$key] = $value;
            }
            $Crud->UpdateRecord("medicines_forms", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function submit_medicine_company()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Medicine = $this->request->getVar('Company');


        if ($id == 0) {
            foreach ($Medicine as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }

            $RecordId = $Crud->AddRecord("pharma_company", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Medicine as $key => $value) {
                $record[$key] = $value;
            }
            $Crud->UpdateRecord("pharma_company", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function submit_medicine_timing()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Medicine = $this->request->getVar('timing');
        if ($id == 0) {

            foreach ($Medicine as $key => $value) {
                $record[$key] = ((isset($value)) ? $value : '');
            }
            $RecordId = $Crud->AddRecord("medicines_timings", $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Added Successfully...!';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Data Didnt Submitted Successfully...!';
            }
        } else {
            foreach ($Medicine as $key => $value) {
                $record[$key] = $value;
            }
            $Crud->UpdateRecord("medicines_timings", $record, array("UID" => $id));
            $response['status'] = 'success';
            $response['message'] = 'Updated Successfully...!';
        }

        echo json_encode($response);
    }

    public function delete()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();
        $table = "medicines";
        $record['Archive'] = 1;
        $where = array('UID' => $UID);
        $Crud->UpdateRecord($table, $record, $where);
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';

        echo json_encode($response);
    }

    public function delete_take_type()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();
        $table = "medicines_take_types";
        $where = array('UID' => $UID);
        $Crud->DeleteRecord($table, $where);
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';

        echo json_encode($response);
    }

    public function delete_form()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();
        $table = "medicines_forms";
        $where = array('UID' => $UID);
        $Crud->DeleteRecord($table, $where);
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';

        echo json_encode($response);
    }

    public function delete_pharma_company()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();
        $table = "pharma_company";
        $where = array('UID' => $UID);
        $Crud->DeleteRecord($table, $where);
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';

        echo json_encode($response);
    }

    public function delete_timing()
    {
        $data = $this->data;
        $UID = $this->request->getVar('id');
        $Crud = new Crud();
        $table = "medicines_timings";
        $where = array('UID' => $UID);
        $Crud->DeleteRecord($table, $where);
        $response['status'] = 'success';
        $response['message'] = 'Deleted Successfully...!';

        echo json_encode($response);
    }

    public function get_medicine_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("medicines", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function get_medicine_form_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("medicines_forms", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function get_medicine_pharma_company()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("pharma_company", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function get_medicine_timing_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("medicines_timings", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function get_medicine_take_type_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("medicines_take_types", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }

    public function search_filter()
    {
        $session = session();
        $MedicineName = $this->request->getVar('MedicineName');
//        $city = $this->request->getVar( 'city' );
        $Company = $this->request->getVar('Company');


        $AllFilter = array(
            'Company' => $Company,
            'MedicineName' => $MedicineName,

        );


//        print_r($AllFilter);exit();
        $session->set('MedicineFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }
}
