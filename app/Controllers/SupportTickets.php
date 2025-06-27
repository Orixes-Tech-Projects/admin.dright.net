<?php

namespace App\Controllers;

use App\Models\Crud;
use App\Models\ExtendedModel;
use App\Models\Main;
use App\Models\SupportTicketModel;

class SupportTickets extends BaseController
{
    var $data = array();

    public function __construct()
    {

        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
    }

    public function index()
    {        $Crud = new Crud();

        $data = $this->data;
        $data['page'] = getSegment(2);
        $data['PAGE'] = array();
        $SupportTicketModel= new SupportTicketModel();
        $ExtendedModel= new ExtendedModel();
        $SQL = $ExtendedModel->extended_profiles();
        $data['extended_profiles'] = $Crud->ExecuteSQL($SQL);

        echo view('header', $data);
        if ($data['page'] == 'pending') {
            echo view('support_ticket/pending', $data);
        }elseif ($data['page'] == 'tickets_reply'){
            $UID = getSegment(3);
            $data['TicketID'] = $UID;
            $Crud = new Crud();
            $TicketData = $Crud->SingleRecord('tasks', array("UID" => $UID));
            $data['TicketData'] = $TicketData;
            if ($data['TicketData']['Product'] == 'ClinTa_Extended') {
                $data['Profile'] = $SupportTicketModel->GetExtendedProfielDataByID($data['TicketData']['ProductProfielID']);
//           print_r(   $data['Profile'] );exit();

                $data['CreatedBy'] = $SupportTicketModel->GetExtendedUserDataByDBOrID($data['Profile'][0]['DatabaseName'], $data['TicketData']['CreatedBY']);
//           print_r(   $data['CreatedBy']);exit();
            }
            echo view('support_ticket/tickets_reply', $data);

        } elseif ($data['page'] == 'update') {
            echo view('support_ticket/main_form', $data);

        } else {
            echo view('support_ticket/index', $data);

        }
        echo view('footer', $data);
    }

    public function builder_support()
    {
        $Crud = new Crud();

        $data = $this->data;
        $data['page'] = getSegment(2);
        $data['PAGE'] = array();
        $SupportTicketModel = new SupportTicketModel();


        echo view('header', $data);
        if ($data['page'] == 'pending') {
            echo view('support_ticket/pending', $data);
        }elseif ($data['page'] == 'builder_tickets_reply'){
            $UID = getSegment(3);
            $data['TicketID'] = $UID;
            $Crud = new Crud();
            $data['last_reply']=$SupportTicketModel->GetTicketAllCommentsLastReply($UID);
            $TicketData = $Crud->SingleeRecord('builder_support_ticket', array("UID" => $UID));
            $data['TicketData'] = $TicketData;

            echo view('support_ticket/builder_support_ticket_reply', $data);

        } else {
            echo view('support_ticket/builder_support_ticket', $data);

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

    public function items()
    {
        $data = $this->data;
        echo view('header', $data);
        echo view('support_ticket/items', $data);
        echo view('footer', $data);
    }

    public function fetch_data()
    {
        $Users = new SupportTicketModel();

        $Data = $Users->get_datatables();
//        print_r($Data);exit();
        $totalfilterrecords = $Users->count_datatables();
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $Profile = $Users->GetExtendedProfielDataByID($record['ProductProfielID'] );

            $CreatedBy = $Users->GetExtendedUserDataByDBOrID($Profile[0]['DatabaseName'], $record['CreatedBY'] );
//            print_r($Profile);exit();

            $LatestCommentData = $Users->GetLatestCommentDataByTicketID( $record['UID'] );
//            $CreatedBy['FullName']='';
//            $LatestCommentData[0]['SystemDate']='';
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($Profile[0]['FullName']) ? htmlspecialchars($Profile[0]['FullName']) : '';
            $data[] = isset($record['ModuleID']) ? htmlspecialchars($record['ModuleID']) : '';
            $data[] = isset($record['ModuleID'])
                ? '<a href="' . PATH . 'support-ticket/tickets_reply/' . $record['UID'] . '">#' . $record['UID'] . ' - ' . $record['Subject'] . '</a>'
                : '';
            $data[] = isset($CreatedBy[0]['FullName']) ? htmlspecialchars($CreatedBy[0]['FullName']) : '';

            $data[] = isset($record['SystemDate']) ? date("d M, Y h:i A", strtotime( $record['SystemDate'] )) : '';
            $data[] = isset($LatestCommentData[0]['SystemDate']) ? date("d M, Y h:i A", strtotime( $LatestCommentData[0]['SystemDate'] )) : '';
            $data[] = isset($record['DeadLine']) ? date("d M, Y h:i A", strtotime( $record['DeadLine'] )) : '';

            $data[] = isset($record['Status']) ? htmlspecialchars($record['Status']) : '';

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

    public function fetch_builder_data()
    {
        $session = session();
        $SessionLogin = $session->get();
        $LoginUserRole = $SessionLogin['AccessLevel'];

        $Users = new SupportTicketModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Users->get_builder_task_datatables($keyword);
        $totalfilterrecords = $Users->count_builder_task_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['ProfileName']) ? htmlspecialchars($record['ProfileName']) : '';
            $data[] = isset($record['Module'])
                ? '<a style="cursor:pointer; color:crimson;" title="Click To View Details" href="' . PATH . 'support-ticket/builder_tickets_reply/' . $record['UID'] . '">#' . $record['UID'] . ' - ' . ucwords($record['Module']) . '</a>'
                : '';
            $data[] = isset($record['SystemDate']) ? date("d M, Y h:i A", strtotime($record['SystemDate'])) : '';
            $data[] = ((isset($record['AssignedUser']) && $record['AssignedUser'] != '' && $record['AssignedUser'] != 0) ? ucwords($record['AssignedUser']) : '-');
            $data[] = isset($record['Priority']) ? '<b>' . ucwords(htmlspecialchars($record['Priority'])) . '</b>' : '';
            $data[] = isset($record['Status']) ? '<badge class="badge badge-' . (($record['Status'] == 'Close') ? 'danger' : 'success') . '">' . $record['Status'] . '</badge>' : '';

            $ActionButton = '';
            $TicketID = '#' . $record['UID'] . ' - ' . ucwords($record['Module']);
            if ($record['Status'] == 'Open') {
                $ActionButton = '<td class="text-end">
                            <div class="dropdown">
                                <button style="border-radius: 5px;" type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a style="cursor: pointer;" class="dropdown-item" onclick="ReplyTicket(' . htmlspecialchars($record['UID']) . ')">Ticket Reply</a>';
                if ($LoginUserRole != 'support_department') {
                    $ActionButton .= '<a onclick="LoadTicketAssigneeModal(' . $record['UID'] . ', \'' . htmlspecialchars($TicketID) . '\', ' . $record['AssignedUserUID'] . ' );" style="cursor: pointer;" class="dropdown-item">Assign Ticket</a>';
                }
                $ActionButton .= '</div>
                            </div>
                        </td>';

                $data[] = $ActionButton;
            } else {

                $data[] = '<badge style="padding: 10px 23px;" class="badge badge-danger">Closed</badge>';
            }

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


    public function item_form_submit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();

        $id = $this->request->getVar('UID');
        $Item = $this->request->getVar('Item');
        if (!empty($Item['Name'])) {
            if ($id == 0) {
                foreach ($Item as $key => $value) {
                    $record[$key] = ((isset($value)) ? $value : '');
                }
                $RecordId = $Crud->AddRecord("items", $record);
                if (isset($RecordId) && $RecordId > 0) {
                    $response['status'] = 'success';
                    $response['message'] = 'Added Successfully...!';
                } else {
                    $response['status'] = 'fail';
                    $response['message'] = 'Data Didnt Submitted Successfully...!';
                }
            }
            else {
                foreach ($Item as $key => $value) {
                    $record[$key] = $value;
                }
                $Crud->UpdateRecord("items", $record, array("UID" => $id));
                $response['status'] = 'success';
                $response['message'] = 'Updated Successfully...!';
            }

        }
        else{
            $response['status'] = 'fail';
            $response['message'] = 'Name Cant Be Empty...!';
        }

        echo json_encode($response);
    }

  public function TicketReplyFormSubmit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();
        $record2 = array();
        $record3 = array();

        $message = $this->request->getVar('message');
        $TaskID = $this->request->getVar('TaskID');
        $UserID = $_SESSION['UID'];
        $Image = $this->request->getFile('files');
//print_r($Image);exit();
      if ($Image->isValid() && !$Image->hasMoved()) {
          $fileImage = file_get_contents($Image->getTempName());

      }
      if ($message != '') {
          $record['TaskID'] = $TaskID;
          $record['ProjectUserID'] = 0;
          $record['StaffID'] = $UserID;
          $record['Message'] = $message;
          $TaskCommentID = $Crud->AddRecord("taskcomments", $record);
          $record2['Status'] = 'Open';
          $Crud->UpdateRecord("tasks", $record2, array("UID" => $TaskID));

          $record3['CommentID'] = $TaskCommentID;
          $record3['File'] = base64_encode($fileImage);
          ;
          $record3['FileExtension'] = '';

          $RecordId = $Crud->AddRecord("taskattachments", $record3);
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

 public function BuilderTicketReplyFormSubmit()
    {
        $Crud = new Crud();
        $Main = new Main();
        $response = array();
        $record = array();
        $record2 = array();
        $record3 = array();
        $fileID='';
        $message = $this->request->getVar('message');
        $TaskID = $this->request->getVar('TaskID');
        $UserID = $_SESSION['FullName'];
        if (!empty($_FILES['Image']['name'])) {
            $fileID = $Crud->UploadFile('Image', 'public."files"."Image"');
        }
//          print_r($fileID);exit();


      if ($message != '') {
          $record['TaskID'] = $TaskID;
          $record['User'] = $UserID;
          $record['Message'] = $message;
          $record['File'] = $fileID ;
          $record['To'] = 1 ;
          $record['From'] = 0 ;
          $RecordId = $Crud->AddRecordPG("builder_task_attachments", $record);
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
    function UpdateDeadLineFormSubmit()
    {
        $Crud = new Crud();
       $TaskID = $_POST['TaskID'];
        $Date = $_POST['edit_deadline'];
        $record=array();
//        print_r($TaskID);exit();
        $record['DeadLine']=date("Y-m-d H:i:s", strtotime($Date));
        $Crud->UpdateRecord("tasks", $record, array("UID" => $TaskID));
        $response = array();
        $response['status'] = 'success';
        $response['msg'] = "DeadLine Updated SuccessFully";
        echo json_encode($response);
    } public
    function UpdateBuilderDeadLineFormSubmit()
    {
        $Crud = new Crud();
       $TaskID = $_POST['TaskID'];
        $Date = $_POST['edit_deadline'];
        $record=array();
//        print_r($TaskID);exit();
        $record['DeadLine']=date("Y-m-d ", strtotime($Date));
        $Crud->UpdateeRecord("builder_support_ticket", $record, array("UID" => $TaskID));
        $response = array();
        $response['status'] = 'success';
        $response['msg'] = "DeadLine Updated SuccessFully";
        echo json_encode($response);
    }
    public
    function load_tickets_comments(){
        $SupportTicketModel = new SupportTicketModel();
        $html ='';
        $TicketID = $_POST['TicketID'];

        $Data = $SupportTicketModel->GetTicketAllCommentsData( $TicketID );
        $TicketData = $SupportTicketModel->GetTicketDataByID( $TicketID );
        if( count( $Data ) > 0 ){

            $html ='';$User = '';
            //echo'<pre>';print_r( $Data );

            $html.='<div class="card"><div class="card-header"><h4>Comments</h4></div>  
                        <div class="card-body">';

            foreach( $Data as $D ){

                $Attachments = $SupportTicketModel->GetAllAttachmentsByCommentID( $D['UID'] );

                $Profile = $SupportTicketModel->GetExtendedProfielDataByID( $TicketData[0]['ProductProfielID'] );
                $CreatedBy = $SupportTicketModel->GetExtendedUserDataByDBOrID( $Profile[0]['DatabaseName'], $TicketData[0]['CreatedBY']  );
//                    print_r($CreatedBy);exit();
                if( $D['ProjectUserID'] > 0 && $D['StaffID'] == 0 ){

                    $html.='<div class="ks-comment">
                                <div class="ks-body">
                                    <div class="ks-comment-box">
                                        <div class="ks-name">
                                            <a href="javascript:void(0);" style="color: blue;">'.(!empty($CreatedBy) && isset($CreatedBy[0]['FullName'])) ? $CreatedBy[0]['FullName'] : ''.'</a>
                                        </div>
                                        <div class="ks-message">'.$D['Message'].'</div >
                                    </div >
                                </div>';

                    if( count( $Attachments ) > 0 ){
                        $cnt = 0;

                        foreach( $Attachments as $A ){
                            $cnt++;
                            $html.=' <a downlaod="download" href="'.promotion_material_file_download( 'task-attachments_'.$A['UID'].'_'.$A['FileExtension'].'' ).'"><span  class="ks-status badge badge-'.( ( $cnt % 2 > 0 )? 'info' : 'success' ).'"><i class="fa fa-download"> Download '.$cnt.'</i></span></a>';
                        }
                    }

                    $html.='<footer class="blockquote-footer" > '. (!empty($CreatedBy) && isset($CreatedBy[0]['FullName'])) ? $CreatedBy[0]['FullName'] : ''
.'
                                <cite title = "Source Title" >'.date("d M, Y h:i A", strtotime( $D['SystemDate'] )).' </cite>
                                </footer>
                            </div><hr>';


                }else{

                    $User = 'Support Team';
                    $html.='<div class="ks-comment">
                                            <div class="ks-body">
                                                <div class="ks-comment-box">
                                                    <div class="ks-name">
                                                        <a  href="javascript:void(0);" style="color: green; font-weight: bold;">'.$User.'</a>
                                                    </div>
                                                    <div class="ks-message">'.$D['Message'].'</div >
                                                </div >
                                            </div >';

                    if( count( $Attachments ) > 0 ){
                        $cnt = 0;
                        foreach( $Attachments as $A ){
                            $cnt++;
                            $html.=' <a downlaod="download" href="'.promotion_material_file_download( 'task-attachments_'.$A['UID'].'_'.$A['FileExtension'].'' ).'"><span  class="ks-status badge badge-'.( ( $cnt % 2 > 0 )? 'info' : 'success' ).'"><i class="fa fa-download"> Download '.$cnt.'</i></span></a>';
                        }
                    }

                    $html.='<footer class="blockquote-footer" > '.$User.'
                                            <cite title = "Source Title" > '.date("d M, Y h:i A", strtotime( $D['SystemDate'] )).' </cite>
                                            </footer >
                                        </div><hr>';

                }


            }

            $html.='</div>
                            </div>';
        }

        echo $html;
    }

    public function load_builder_tickets_comments(){
        $SupportTicketModel = new SupportTicketModel();
        $html = '';
        $TicketID = $_POST['TicketID'];

        $Data = $SupportTicketModel->GetTicketAllCommentsDataBuilder($TicketID);
        if (count($Data) > 0) {

            foreach ($Data as $D) {
                $to = !empty($D['From']) && $D['From'] != 0 ? 'left' : 'right';
                $html .= '<div class="col-lg-12" style="text-align: ' . $to . '">';
                $html .= '<div class="ks-comment">
                        <div class="ks-body">
                            <div class="ks-comment-box">
                                <div class="ks-name">
                                    <a href="javascript:void(0);" style="color: green; font-weight: bold;">' . ucwords($D['User']) . '</a>
                                </div>
                                <div class="ks-message">' . $D['Message'] . '</div>
                            </div>
                        </div>';

                // Add download button if file exists
                if (!empty($D['File'])) {
                    $html .= '<a title="Click To View File" style="color:#fff; border-radius:5px; float:' . (($D['From'] != 0) ? 'right' : 'left') . ';" target="_blank" class="btn btn-sm btn-primary btn-sm" href="' . LoadFile($D['File']) . '"><i class="fa fa-download"> Download File</i></a>';
                }

                $html .= '<footer class="blockquote-footer">' . $D['User'] . '
                        <cite title="Source Title">' . date("d M, Y h:i A", strtotime($D['SystemDate'])) . '</cite>
                      </footer>
                    </div><hr></div>';
            }

        } else {
            $html .= '<p>No comments available.</p>'; // Message for no comments
        }

        echo $html; // Output the HTML content
    }

    public
    function search_filter()
    {
        $session = session();
//        $Key = $this->request->getVar( 'Key' );
        $city = $this->request->getVar('Profile');


        $AllFilter = array(
//            'Key' => $Key,
            'Profile' => $city,

        );


//        print_r($AllFilter);exit();
        $session->set('ExtendedFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }
    public
    function builder_support_search_filter()
    {
        $session = session();
        $CreatedDate = $this->request->getVar( 'CreatedDate' );
        $Status = $this->request->getVar('Status');


        $AllFilter = array(
            'CreatedDate' => $CreatedDate,
            'Status' => $Status,

        );


        $session->set('SupportTicketFilters', $AllFilter);

        $response['status'] = "success";
        $response['message'] = "Filters Updated Successfully";

        echo json_encode($response);
    }
    public
    function delete_item()
    {
        $Crud = new Crud();
        $id = $_POST['id'];
//        print_r($id);exit();
        $Crud->DeleteRecord('items', array("UID" => $id));
        $Main = new Main();

        $msg=$_SESSION['FullName'].' Delete Item Through Admin Dright';
        $logesegment='Item';
        $Main->adminlog($logesegment,$msg, $this->request->getIPAddress());
        $response = array();
        $response['status'] = 'success';
        $response['message'] = ' Deleted Successfully...!';
        echo json_encode($response);
    }

    public
    function get_item_record()
    {
        $Crud = new Crud();
        $id = $_POST['id'];

        $record = $Crud->SingleRecord("items", array("UID" => $id));
        $response = array();
        $response['status'] = 'success';
        $response['record'] = $record;
        $response['message'] = 'Record Get Successfully...!';
        echo json_encode($response);
    }
    public
    function fetch_items()
    {
        $Model = new SupportTicketModel();
        $keyword = ((isset($_POST['search']['value'])) ? $_POST['search']['value'] : '');

        $Data = $Model->get_item_datatables($keyword);
        $totalfilterrecords = $Model->count_item_datatables($keyword);
        $dataarr = array();
        $cnt = $_POST['start'];
        foreach ($Data as $record) {
            $cnt++;
            $data = array();
            $data[] = $cnt;
            $data[] = isset($record['Name']) ? '<b>'.htmlspecialchars($record['Name']).'</b>' : '';
            $data[] = isset($record['Code']) ? htmlspecialchars($record['Code']) : '';
            $data[] = isset($record['OriginalPrice']) ? htmlspecialchars($record['OriginalPrice']) : '';
            $data[] = isset($record['Discount']) ? $record['Discount'].'%' : '';
            $data[] = isset($record['Price']) ? htmlspecialchars($record['Price']) : '';
            $data[] = ((isset($record['Type']) && $record['Type'] != '') ? (($record['Type'] == 'monthly') ? '<b> '.ucwords($record['Type']).'</b> (' . $record['NoOfMonths'] . ' months) ' : ucwords($record['Type']) ) : '');
            $data[] = '
                    <td class="text-end">
                        <div class="dropdown">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                Actions
                            </button>
                            <div class="dropdown-menu">
                                <a style="cursor: pointer;" class="dropdown-item" onclick="LoadPackageUpdateModal(\'' . htmlspecialchars($record['UID']) . '\')">Update</a>
                                <a class="dropdown-item d-none" onclick="DeleteItem(\'' . htmlspecialchars($record['UID']) . '\')">Delete</a>
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
    function ticket_assignee_form_submit()
    {

        $Crud = new Crud();
        $session = session();
        $session = $session->get();

        $TicketID = $this->request->getVar('TicketUID');
        $AssignTo = $this->request->getVar('assign_to');
        $AssignBy = $session['FullName'];

        $AssignToRecord = $Crud->SingleRecord('system_users', array('UID' => $AssignTo));
        $AssignToName = ((isset($AssignToRecord['FullName']) && $AssignToRecord['FullName'] != '') ? $AssignToRecord['FullName'] : '');

        $record = array();
        $record['AssignedUser'] = $AssignToName;
        $record['AssignedBy'] = $AssignBy;
        $record['AssignedUserUID'] = $AssignTo;

        $Status = $Crud->UpdateeRecord('public."builder_support_ticket"', $record, array('UID' => $TicketID));
        if (isset($Status) && $Status == 1) {

            $response = array();
            $response['status'] = 'success';
            $response['message'] = 'Ticket Assign Successfully...!';
            echo json_encode($response);

        } else {

            $response = array();
            $response['status'] = 'fail';
            $response['message'] = 'Failed To Assign Ticket...!';
            echo json_encode($response);

        }

    }
}
