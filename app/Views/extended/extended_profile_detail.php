<?php


$ExtendedModel = new \App\Models\ExtendedModel();

?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Extended Profile</h6>
            </div>
        <div class="ks-content">
            <div class="ks-body ks-profile">
                <div class="ks-header">
                    <div class="ks-user">
                        <div class="ks-info">
                            <div class="ks-name"  style="margin-left: 50px;">
                                <h2><?=$HospitalData[0]['FullName']?></h2>
                            </div>
                            <?php
                            if( $HospitalData[0]['Email'] != '' ){
                                echo'<div class="ks-description" style="margin-left: 50px;">
                                           <strong>Email:</strong> '.$HospitalData[0]['Email'].'
                                        </div>';
                            }
                            if( $HospitalData[0]['ContactNo'] != '' ){
                                echo' <div class="ks-description"  style="margin-left: 50px;">
                                                <strong>ContactNo:</strong>  '.$HospitalData[0]['ContactNo'].'
                                            </div>';
                            }?>
                        </div>
                    </div>
                </div>
                <br>
                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile-tab-content" role="tab" aria-controls="profile-tab-content" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="discount-tab" data-toggle="tab" href="#discount-tab-content" role="tab" aria-controls="discount-tab-content" aria-selected="false">User</a>
                    </li>

                </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="profile-tab-content" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="ks-users-tab">

                                <div class="card panel panel-default ks-widget ks-widget-progress-list">
                                    <div class="card-header">
                                        Admin Settings

                                        <div class="ks-controls">
                                            <a href="#" class="ks-control ks-update"><span class="fa fa-repeat"></span></a>
                                        </div>
                                    </div>
                                    <div class="card-block" style="padding: 25px;">
                                        <form class="validate" role="form" enctype="multipart/form-data" onsubmit="UpdateAdminSettings(); return false;" id="AdminSettingsForm"
                                              name="AdminSettingsForm">
                                            <input type="hidden" name="DBName" id="DBName" value="<?=$HospitalData[0]['DatabaseName']?>">
                                            <?php
                                            $specialized_mode = array();
                                            $specialized_mode['general'] = 'General';
                                            $specialized_mode['cancer'] = 'Cancer Hospital';
                                            $specialized_mode['general_pharmacy'] = 'General Pharmacy';
                                            //echo'<pre>'; print_r( $HospitalAdminSettings );
                                            if( count( $HospitalAdminSettings ) > 0 ){
                                                foreach( $HospitalAdminSettings as $record ){

                                                    switch ($record['Key']) {
                                                        case 'app_version':
                                                            echo '<div class="form-group row">
                                                                        <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                        <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                            <select id="' . $record['Key'] . '" name="' . $record['Key'] . '" class="form-control">
                                                                                    <option '.( ( $record["Description"] == '' )? 'selected' : '' ).' value="">Select Version</option>
                                                                                    <option '.( ( $record["Description"] == 'ClinTa-Pharmacy' )? 'selected' : '' ).' value="ClinTa-Pharmacy">ClinTa Pharmacy</option>
                                                                                    <option '.( ( $record["Description"] == 'ClinTa-Extended' )? 'selected' : '' ).' value="ClinTa-Extended">ClinTa Extended</option>
                                                                                    <option '.( ( $record["Description"] == 'ClinTa-Laris' )? 'selected' : '' ).' value="ClinTa-Extended">ClinTa Laris</option>
                                                                            </select>
                                                                        </div>
                                                                  </div>';
                                                            break;
                                                        case 'lab_services': if($HospitalData[0]['DatabaseName'] !=''){
                                                            $serviceGroup = $ExtendedModel->GetExtendedLookupsDataByDBOrID( $HospitalData[0]['DatabaseName'], 'service_group');
                                                            echo '<div class="form-group row">
                                                                    <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                    <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                            <select id="' . $record['Key'] . '" name="' . $record['Key'] . '" class="form-control">
                                                                            <option value="">Select Group</option>';
                                                            foreach ($serviceGroup as $value) {
                                                                echo '<option value=' . $value['UID'] . ' ' . (($value["UID"] == $record["Description"]) ? "Selected" : "") . ' >' . $value['Name'] . '</option>';
                                                            }
                                                            echo '</select>
                                                                     </div>
                                                                </div>';
                                                           } break;
                                                        case 'radiology_service': if($HospitalData[0]['DatabaseName'] !=''){
                                                            $serviceGroup = $ExtendedModel->GetExtendedLookupsDataByDBOrID($HospitalData[0]['DatabaseName'], 'service_group');
                                                            echo '<div class="form-group row">
                                                                        <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                        <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                                <select id="' . $record['Key'] . '" name="' . $record['Key'] . '" class="form-control">
                                                                                <option value="">Select Group</option>';
                                                            foreach ($serviceGroup as $value) {
                                                                echo '<option value=' . $value['UID'] . ' ' . (($value["UID"] == $record["Description"]) ? "Selected" : "") . ' >' . $value['Name'] . '</option>';
                                                            }
                                                            echo '</select>
                                                                      </div>
                                                                  </div>';
                                                           } break;
                                                        case 'ot_services': if($HospitalData[0]['DatabaseName'] !=''){

                                                            $serviceGroup = $ExtendedModel->GetExtendedLookupsDataByDBOrID($HospitalData[0]['DatabaseName'], 'service_group');
                                                            echo '<div class="form-group row">
                                                                    <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                    <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                            <select id="' . $record['Key'] . '" name="' . $record['Key'] . '" class="form-control">
                                                                                <option value="">Select Group</option>';
                                                            foreach ($serviceGroup as $value) {
                                                                echo '<option value=' . $value['UID'] . ' ' . (($value["UID"] == $record["Description"]) ? "Selected" : "") . ' >' . $value['Name'] . '</option>';
                                                            }
                                                            echo '</select>
                                                                      </div>
                                                                   </div>';
                                                           } break;
                                                        case'pharma_prescription';
                                                            echo '<div class="form-group row">
                                                                        <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                      <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                            <div class="radio radio-inline">
                                                                                <label>
                                                                                    <input ' . (("1" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="1" >
                                                                                  Yes
                                                                                </label>
                                                                            </div>
                                                                             <div class="radio radio-inline">
                                                                                <label>
                                                                                    <input ' . (("0" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="0" >
                                                                                  No
                                                                                </label>
                                                                            </div>
                                                                      </div>
                                                                  </div>';
                                                            break;
                                                        case'invoice_signature';
                                                            echo '<div class="form-group row">
                                                                    <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                  <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                        <div class="radio radio-inline">
                                                                            <label>
                                                                                <input ' . (("1" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="1" >
                                                                              Yes
                                                                            </label>
                                                                        </div>
                                                                         <div class="radio radio-inline">
                                                                            <label>
                                                                                <input ' . (("0" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="0" >
                                                                              No
                                                                            </label>
                                                                        </div>
                                                                  </div>
                                                              </div>';
                                                            break;
                                                        case'application_mode';
                                                            echo '<div class="form-group row">
                                                                    <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                  <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                        <div class="radio radio-inline">
                                                                            <label>
                                                                                <input ' . (("Online" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="Online" >
                                                                              Online 
                                                                            </label>
                                                                        </div>
                                                                         <div class="radio radio-inline">
                                                                            <label>
                                                                                <input ' . (("Offline" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="Offline" >
                                                                              Offline
                                                                            </label>
                                                                        </div>
                                                                  </div>
                                                              </div>';
                                                            break;
                                                        case'pharmacy_setup';
                                                            echo '<div class="form-group row">
                                                                        <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                      <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                            <div class="radio radio-inline">
                                                                                <label>
                                                                                    <input ' . ((1 == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="1" >
                                                                                  Internal 
                                                                                </label>
                                                                            </div>
                                                                             <div class="radio radio-inline">
                                                                                <label>
                                                                                    <input ' . ((2 == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="2" >
                                                                                  External
                                                                                </label>
                                                                            </div>
                                                                      </div>
                                                                  </div>';
                                                            break;
                                                        case 'drug_category': if($HospitalData[0]['DatabaseName'] !='') {
                                                            $Category = $ExtendedModel->GetExtendedProductCategoriesByDBName($HospitalData[0]['DatabaseName']);
                                                            echo '<div class="form-group row">
                                                                <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                <select id="' . $record['Key'] . '" name="' . $record['Key'] . '" class="form-control">
                                                                    <option value="">Select Category</option>';
                                                            foreach ($Category as $C) {
                                                                echo '<option value=' . $C['UID'] . ' ' . (($C['UID'] == $record['Description']) ? "Selected" : " ") . '>' . $C['Title'] . '</option>';
                                                            }
                                                            echo '</select>
                                                                 </div>
                                                              </div>';
                                                        } break;
                                                        case 'specialized_mode':
                                                            //$specialized_mode = config_item('specialized_mode');
                                                            echo '<div class="form-group row">
                                                                <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                <select multiple="multiple"  id="' . $record['Key'] . '" name="' . $record['Key'] . '[]" class="form-control">
                                                                    <option value=" ">Select Mode</option>';
                                                            $options = explode(",", $record['Description']);
                                                            foreach ($specialized_mode as $sm => $sv) {
                                                                echo '<option value=' . $sm . ' ' . ((in_array($sm, $options)) ? "selected" : " ") . ' >' . $sv . '</option>';
                                                            }
                                                            echo '</select>
                                                              </div>
                                                              </div>';
                                                            break;
                                                        case'application_setup';
                                                            echo '<div class="form-group row">
                                                                        <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                      <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                            <div class="radio radio-inline">
                                                                                <label>
                                                                                    <input ' . (("Cloud" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="Cloud" >
                                                                                 Cloud
                                                                                </label>
                                                                            </div>
                                                                             <div class="radio radio-inline">
                                                                                <label>
                                                                                    <input ' . (("IN-House" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="IN-House" >
                                                                                  In House
                                                                                </label>
                                                                            </div>
                                                                      </div>
                                                                  </div>';
                                                            break;
                                                        case'sms_notification';
                                                            echo '<div class="form-group row">
                                                             <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                              <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                  <select id="' . $record['Key'] . '" name="' . $record['Key'] . '" class="form-control">
                                                                    <option value="" ' . (($record["Description"] == '') ? 'selected' : '') . '>Please Select</option>
                                                                    <option value="Yes" ' . (($record["Description"] == 'Yes') ? 'selected' : '') . '>Yes</option>
                                                                    <option value="No" ' . (($record["Description"] == 'No') ? 'selected' : '') . '>No</option>
                                                                  </select>                           
                                                              </div>
                                                             </div>';
                                                            break;
                                                        case'sms_notification_number';
                                                            echo '<div class="form-group row">
                                                             <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                              <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                  <input  value="' . $record['Description'] . '" type="text" maxlength="11" id="' . $record['Key'] . '" name="' . $record['Key'] . '"  class="form-control input-sm validate[custom[integer], minSize[11]]" />                        
                                                              </div>
                                                             </div>';
                                                            break;
                                                        case'profile_logo';
                                                            echo '<div class="form-group row">
                                                                  <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                  <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                      <input   type="file" id="' . $record['Key'] . '" name="' . $record['Key'] . '"  class="form-control input-sm" style="width: 50%;float: left;"/>
                                                                      <img src="" height="80" style="float: right;">
                                                                  </div>
                                                              </div>';
                                                            break;
                                                        case'pdf_header';
                                                        case'pdf_header_pharmacy';
                                                        case'pdf_footer_pharmacy';
                                                            echo '<div class="form-group row">
                                                                  <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                  <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                      <textarea rows="4" type="text" id="' . $record['Key'] . '" name="' . $record['Key'] . '" placeholder="Please enter your value" class="form-control">' . $record['Description'] . '</textarea>
                                                                      <small class="red">This content will appear in PDF same as enter maximum of four lines.</small>
                                                                  </div>
                                                              </div>';
                                                            break;
                                                        case'prescription_pharmacist';
                                                        case'opd_service_fee_editable';
                                                            echo '<div class="form-group row">
                                                                <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                              <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                    <div class="radio radio-inline">
                                                                        <label>
                                                                            <input ' . (("1" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="1" >
                                                                          Yes
                                                                        </label>
                                                                    </div>
                                                                     <div class="radio radio-inline">
                                                                        <label>
                                                                            <input ' . (("0" == $record["Description"]) ? "checked='checked'" : " ") . ' type="radio" id="' . $record['Key'] . '" name="' . $record['Key'] . '" value="0" >
                                                                          No
                                                                        </label>
                                                                    </div>
                                                              </div>
                                                          </div>';
                                                            break;
                                                        case 'pharmacy_printer':
                                                            echo '<div class="form-group row">
                                                                <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                    <select id="' . $record['Key'] . '" name="' . $record['Key'] . '" class="form-control">
                                                                        <option value="" ' . (($record['Description'] == '') ? 'selected' : '') . '>Select Pharmacy Printer Setup</option>
                                                                        <option value="Thermal" ' . (($record['Description'] == 'Thermal') ? 'selected' : '') . '>Thermal</option>
                                                                        <option ' . (($record['Description'] == 'A4') ? 'selected' : '') . ' value="A4" >A4</option>
                                                                    </select>
                                                                </div>
                                                              </div>';
                                                            break;
                                                        default:
                                                            echo '<div class="form-group row">
                                                                      <label class="col-sm-3 col-md-4 col-lg-2 col-xs-3 form-control-label">' . $record['Name'] . ':</label>
                                                                      <div class="col-sm-9 col-md-8 col-lg-6 col-xs-9">
                                                                      <input type="text" id="' . $record['Key'] . '" name="' . $record['Key'] . '" placeholder="Please enter your value" value="' . $record['Description'] . '" class="form-control input-sm" />
                                                                      </div>
                                                                  </div>';
                                                            break;
                                                    }
                                                }
                                            } ?>
                                            <div class="row">
                                                <div class="col-md-12" id="AdminSettingResponse"></div>
                                                <div class="col-md-8">
                                                    <button  type="button" onclick="UpdateAdminSettings();" class="btn btn-success btn-sm pull-right">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="discount-tab-content" role="tabpanel" aria-labelledby="discount-tab">
                            <div class="ks-users-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="UsersList" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th width="80">#</th>
                                                    <th>Name</th>
                                                    <th>UserName</th>
                                                    <th>Email</th>
                                                    <th>Mobile No</th>
                                                    <th width="120">Actions
                                                        <a onclick="AddNewUserModal('<?=$HospitalData[0]['DatabaseName']?>' );" title="Add New Admin User" class="pull-right btn btn-gradient-secondary btn-sm"><span class="fa fa-plus"></span></a>

                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $cnt = 0;
                                                foreach ($HospitalAdminUsers as $HAU) {
                                                    $cnt++;
                                                    echo '<tr>
            <td>' . $cnt . '</td>
            <td>' . $HAU['FullName'] . '</td>
            <td>' . $HAU['Username'] . '</td>
            <td>' . $HAU['Email'] . '</td>
            <td>' . $HAU['MobileNo'] . '</td>
            <td>
                <a title="Edit User" href="javascript:void(0);" onclick="ExtendedEditUserModal(\'' . $HospitalData[0]['DatabaseName'] . '\', ' . $HAU['UID'] . ');" class="btn btn-primary-outline ks-no-text"><span class="fa fa-pencil ks-icon"></span></a>
            </td>
          </tr>';
                                                }
                                                ?>
<!--                                                              <a title="Assign All AccessLevels" href="javascript:void(0);" onclick="AssignAllExtendedAccessToUser(\'' . $HospitalData[0]['DatabaseName'] . '\', ' . $HAU['UID'] . ');" class="btn btn-danger-outline ks-no-text"><span class="fa fa-recycle ks-icon"></span></a>-->

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <?php echo view( "extended/modal/add_user" ); ?>
    <?php echo view( "extended/modal/update_user" ); ?>
<!--    --><?php //$this->load->view( "inner-html/extended-edit-user-modal" ); ?>
    <script type="text/javascript">
        function AddNewUserModal(dbname) {

            $("#AddNewUserModal form#AddNewUserForm input#DBName").val( dbname );

            $('#AddNewUserModal').modal('show');

        }

        function ExtendedEditUserModal(DBNAME,UID) {

            $("#updateNewUserModal form#EditUserForm input#id").val(UID);
            $("#updateNewUserModal form#EditUserForm input#DBName").val(DBNAME);

            rslt = AjaxResponse( "extended/get_record", "dbname=" + DBNAME + "&uid=" + UID  );
            // alert(rslt);
            // console.log(rslt);
            if( rslt != '' ){

                $("#updateNewUserModal form#EditUserForm input#name").val( rslt.record.FullName );
                $("#updateNewUserModal form#EditUserForm input#user_name").val( rslt.record.Username );
                $("#updateNewUserModal form#EditUserForm input#email").val( rslt.record.Email );
                $("#updateNewUserModal form#EditUserForm input#contactno").val( rslt.record.MobileNo );
                $("#updateNewUserModal form#EditUserForm select#usertype").val( rslt.record.AccessLevel );
                if(  rslt.BranchID == 0 ){
                    $("#updateNewUserModal form#EditUserForm select#branch").val('');
                    $("#updateNewUserModal form#EditUserForm div#branchdiv").css( 'display', 'none' );
                }else{
                    $("#updateNewUserModal form#EditUserForm select#branch").val( rslt.record.BranchID );
                    $("#updateNewUserModal form#EditUserForm div#branchdiv").css( 'display', '' );
                }
            }

            $('#updateNewUserModal').modal('show');

        }



        function AssignAllExtendedAccessToUser( DBNAME, UID ) {

            rslt = ajaxRequest( "form_process/assign_extended_access_to_user", "dbname=" + DBNAME + "&id=" + UID );
            if( rslt.status == 'success' ){
                window.location.href = location.href;
            }

        }

        function UpdateAdminSettings() {

            var formData = new window.FormData($("form#AdminSettingsForm")[0]);
            var rslt = AjaxUploadResponse("extended/update_extended_admin_settings", formData);
            if (rslt.status == 'success') {

                $(" div#AdminSettingResponse").html( '<div class="alert alert-success ks-solid ks-active-border" role="alert">' + rslt.msg + '</div>' );
                setTimeout(function () {
                    window.location = location.href;
                }, 1000);

            } else {

                $(" div#AdminSettingResponse").html( '<div class="alert alert-danger ks-solid ks-active-border" role="alert">' + rslt.msg + '</div>' );
            }
        }
    </script>
