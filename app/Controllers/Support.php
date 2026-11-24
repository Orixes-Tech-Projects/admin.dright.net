<?php

namespace App\Controllers;

use App\Models\Crud;
use App\Models\Main;
use App\Models\SupportModel;
use App\Models\SystemUser;

class Support extends BaseController
{
    var $data = array();
    protected $MainModel;

    public function __construct()
    {
        $this->MainModel = new Main();
        $this->data = $this->MainModel->DefaultVariable();
    }

    protected function denyJson()
    {
        echo json_encode([
            'status' => 'fail',
            'message' => 'Access denied',
        ]);
    }

    protected function videoUploadDir()
    {
        return ROOT . '/upload/clinta-support/videos/';
    }

    protected function allowedVideoExtensions()
    {
        return ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'];
    }

    public function index()
    {
        $data = $this->data;
        $system = new SystemUser();
        if (!$system->checkAccessKey('extended_clinta_support_list')) {
            echo view('header', $data);
            echo '<div class="card"><div class="card-body"><h4>Access denied</h4></div></div>';
            echo view('footer', $data);
            return;
        }

        $SupportModel = new SupportModel();
        $data['stats'] = $SupportModel->get_stats();
        $data['access_levels'] = $SupportModel->access_level_options();
        $data['can_add'] = $system->checkAccessKey('extended_clinta_support_add');
        $data['can_update'] = $system->checkAccessKey('extended_clinta_support_update');
        $data['can_delete'] = $system->checkAccessKey('extended_clinta_support_delete');

        echo view('header', $data);
        echo view('support/admin/list', $data);
        echo view('footer', $data);
    }

    public function datatable()
    {
        $system = new SystemUser();
        if (!$system->checkAccessKey('extended_clinta_support_list')) {
            $this->denyJson();
            return;
        }

        $SupportModel = new SupportModel();
        $filters = [
            'keyword' => $this->request->getPost('keyword'),
            'access' => $this->request->getPost('access'),
            'url' => $this->request->getPost('url'),
            'status' => $this->request->getPost('status'),
        ];

        $Data = $SupportModel->get_datatables($filters);
        $totalfilterrecords = $SupportModel->count_datatables($filters);
        $dataarr = [];
        $cnt = (int) ($this->request->getPost('start') ?? 0);
        $canUpdate = $system->checkAccessKey('extended_clinta_support_update');

        foreach ($Data as $record) {
            $Actions = [];
            if ($canUpdate) {
                $Actions[] = '<a class="dropdown-item" href="javascript:void(0);" onclick="EditSupportGuide(' . (int) $record['UID'] . ')">Update</a>';
            }
            if ($system->checkAccessKey('extended_clinta_support_delete')) {
                $Actions[] = '<a class="dropdown-item" href="javascript:void(0);" onclick="DeleteSupportGuide(' . (int) $record['UID'] . ')">Delete</a>';
            }

            $hasUpload = !empty($record['VideoUpload']);
            $hasLink = !empty($record['VideoLink']);
            if ($hasUpload) {
                $uploadUrl = PATH . 'upload/clinta-support/videos/' . $record['VideoUpload'];
                $videoHtml = '<a class="cs-badge cs-badge-video" href="' . htmlspecialchars($uploadUrl) . '" target="_blank" rel="noopener noreferrer" title="Open uploaded video">Upload</a>';
            } elseif ($hasLink) {
                $linkUrl = trim((string) $record['VideoLink']);
                $videoHtml = '<a class="cs-badge cs-badge-link" href="' . htmlspecialchars($linkUrl) . '" target="_blank" rel="noopener noreferrer" title="Open video link">Link</a>';
            } else {
                $videoHtml = '<span class="cs-badge cs-badge-none">None</span>';
            }

            $accessLevel = (string) ($record['AccessLevel'] ?? '');
            $accessClass = (strtoupper($accessLevel) === 'ALL') ? 'cs-badge-all' : 'cs-badge-role';
            $status = $SupportModel->normalize_status($record['Status'] ?? 'active');
            $uid = (int) $record['UID'];

            if ($canUpdate) {
                $statusHtml = '<select class="form-control form-control-sm cs-status-select cs-status-' . htmlspecialchars($status) . '"'
                    . ' data-prev="' . htmlspecialchars($status) . '"'
                    . ' aria-label="Guide status"'
                    . ' onchange="ChangeSupportStatus(' . $uid . ', this)">'
                    . '<option value="active"' . ($status === 'active' ? ' selected' : '') . '>Active</option>'
                    . '<option value="lock"' . ($status === 'lock' ? ' selected' : '') . '>Lock</option>'
                    . '</select>';
            } else {
                $statusHtml = '<span class="cs-badge ' . ($status === 'active' ? 'cs-badge-status-active' : 'cs-badge-status-lock') . '">'
                    . ($status === 'active' ? 'Active' : 'Lock')
                    . '</span>';
            }

            $cnt++;
            $row = [];
            $row[] = $cnt;
            $row[] = '<span class="cs-url" title="' . htmlspecialchars($record['URL'] ?? '') . '">' . htmlspecialchars($record['URL'] ?? '') . '</span>';
            $row[] = '<span class="cs-badge ' . $accessClass . '">' . htmlspecialchars($accessLevel) . '</span>';
            $row[] = '<div class="cs-question">' . htmlspecialchars($record['Question'] ?? '') . '</div>';
            $row[] = $videoHtml;
            $row[] = $statusHtml;
            $row[] = '
<td class="text-center">
    <div class="dropdown">
        <button type="button" class="btn btn-primary btn-sm btn-cs-action dropdown-toggle" data-toggle="dropdown">Actions</button>
        <div class="dropdown-menu dropdown-menu-right">' . implode(' ', $Actions) . '</div>
    </div>
</td>';
            $dataarr[] = $row;
        }

        echo json_encode([
            'draw' => intval($this->request->getPost('draw')),
            'recordsTotal' => count($Data),
            'recordsFiltered' => $totalfilterrecords,
            'data' => $dataarr,
        ]);
    }

    public function form_submit()
    {
        $system = new SystemUser();
        $Crud = new Crud();
        $Main = new Main();
        $SupportModel = new SupportModel();
        $response = [];

        $id = (int) $this->request->getVar('UID');
        if ($id === 0) {
            if (!$system->checkAccessKey('extended_clinta_support_add')) {
                $this->denyJson();
                return;
            }
        } else {
            if (!$system->checkAccessKey('extended_clinta_support_update')) {
                $this->denyJson();
                return;
            }
        }

        $Guide = $this->request->getVar('Guide');
        if (!is_array($Guide)) {
            $Guide = [];
        }

        $url = $SupportModel->NormalizeRelativeUrl($Guide['URL'] ?? '');
        $accessLevel = trim((string) ($Guide['AccessLevel'] ?? 'ALL'));
        $question = trim((string) ($Guide['Question'] ?? ''));
        $answer = $SupportModel->SanitizeAnswerHtml($this->request->getVar('Answer') ?? ($Guide['Answer'] ?? ''));
        $videoLink = trim((string) ($Guide['VideoLink'] ?? ''));

        if ($url === '' || $question === '') {
            echo json_encode([
                'status' => 'fail',
                'message' => 'URL and Question are required.',
            ]);
            return;
        }

        if ($videoLink !== '' && !preg_match('#^https?://#i', $videoLink)) {
            echo json_encode([
                'status' => 'fail',
                'message' => 'Video link must start with http:// or https://',
            ]);
            return;
        }

        if ($accessLevel === '') {
            $accessLevel = 'ALL';
        }

        $record = [
            'URL' => $url,
            'AccessLevel' => $accessLevel,
            'Question' => $question,
            'Answer' => $answer,
            'VideoLink' => $videoLink !== '' ? $videoLink : null,
        ];

        $uploadError = $this->handleVideoUpload($Main, $record, $id, $SupportModel);
        if ($uploadError !== null) {
            echo json_encode([
                'status' => 'fail',
                'message' => $uploadError,
            ]);
            return;
        }

        $table = $SupportModel->tableName();
        if ($id === 0) {
            $record['Archive'] = 0;
            $record['Status'] = 'active';
            $RecordId = $Crud->AddRecord($table, $record);
            if (isset($RecordId) && $RecordId > 0) {
                $response['status'] = 'success';
                $response['message'] = 'Guide added successfully.';
            } else {
                $response['status'] = 'fail';
                $response['message'] = 'Failed to add guide.';
            }
        } else {
            $existing = $SupportModel->get_by_uid($id);
            if (empty($existing['UID'])) {
                echo json_encode([
                    'status' => 'fail',
                    'message' => 'Guide not found.',
                ]);
                return;
            }
            $Crud->UpdateRecord($table, $record, ['UID' => $id]);
            $response['status'] = 'success';
            $response['message'] = 'Guide updated successfully.';
        }

        echo json_encode($response);
    }

    protected function handleVideoUpload(Main $Main, array &$record, $id, SupportModel $SupportModel)
    {
        if (!isset($_FILES['VideoUpload']) || empty($_FILES['VideoUpload']['tmp_name'])) {
            return null;
        }

        $file = $_FILES['VideoUpload'];
        if (!empty($file['error']) && (int) $file['error'] !== UPLOAD_ERR_OK) {
            return 'Video upload failed.';
        }

        $maxBytes = 51200 * 1024;
        if (!empty($file['size']) && (int) $file['size'] > $maxBytes) {
            return 'Video file must be 50MB or smaller.';
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $this->allowedVideoExtensions(), true)) {
            return 'Allowed video types: mp4, webm, ogg, mov, avi, mkv.';
        }

        $uploaddir = $this->videoUploadDir();
        if (!is_dir($uploaddir)) {
            @mkdir($uploaddir, 0755, true);
        }

        $uploadfile = strtolower($Main->RandFileName() . '.' . $ext);
        if (!move_uploaded_file($file['tmp_name'], $uploaddir . $uploadfile)) {
            return 'Could not save uploaded video.';
        }

        if ($id > 0) {
            $existing = $SupportModel->get_by_uid($id);
            if (!empty($existing['VideoUpload'])) {
                $oldPath = $uploaddir . $existing['VideoUpload'];
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
        }

        $record['VideoUpload'] = $uploadfile;
        return null;
    }

    public function get_by_uid()
    {
        $system = new SystemUser();
        if (!$system->checkAccessKey('extended_clinta_support_list') && !$system->checkAccessKey('extended_clinta_support_update')) {
            $this->denyJson();
            return;
        }

        $SupportModel = new SupportModel();
        $id = (int) $this->request->getVar('id');
        $record = $SupportModel->get_by_uid($id);
        if (empty($record['UID'])) {
            echo json_encode([
                'status' => 'fail',
                'message' => 'Record not found.',
            ]);
            return;
        }

        $videoUrl = '';
        if (!empty($record['VideoUpload'])) {
            $videoUrl = PATH . 'upload/clinta-support/videos/' . $record['VideoUpload'];
        }

        $record['VideoUrl'] = $videoUrl;
        echo json_encode([
            'status' => 'success',
            'record' => $record,
            'message' => 'Record loaded.',
        ]);
    }

    public function delete()
    {
        $system = new SystemUser();
        if (!$system->checkAccessKey('extended_clinta_support_delete')) {
            $this->denyJson();
            return;
        }

        $SupportModel = new SupportModel();
        $Crud = new Crud();
        $UID = (int) $this->request->getVar('id');
        $Crud->UpdateRecord($SupportModel->tableName(), ['Archive' => 1], ['UID' => $UID]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Deleted successfully.',
        ]);
    }

    public function update_status()
    {
        $system = new SystemUser();
        if (!$system->checkAccessKey('extended_clinta_support_update')) {
            $this->denyJson();
            return;
        }

        $SupportModel = new SupportModel();
        $Crud = new Crud();
        $UID = (int) $this->request->getVar('id');
        $status = $SupportModel->normalize_status($this->request->getVar('status'));

        $existing = $SupportModel->get_by_uid($UID);
        if (empty($existing['UID'])) {
            echo json_encode([
                'status' => 'fail',
                'message' => 'Guide not found.',
            ]);
            return;
        }

        $Crud->UpdateRecord($SupportModel->tableName(), ['Status' => $status], ['UID' => $UID]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Status updated to ' . ($status === 'lock' ? 'Lock' : 'Active') . '.',
            'new_status' => $status,
        ]);
    }

    public function clear_video()
    {
        $system = new SystemUser();
        if (!$system->checkAccessKey('extended_clinta_support_update')) {
            $this->denyJson();
            return;
        }

        $SupportModel = new SupportModel();
        $Crud = new Crud();
        $UID = (int) $this->request->getVar('id');
        $existing = $SupportModel->get_by_uid($UID);
        if (empty($existing['UID'])) {
            echo json_encode([
                'status' => 'fail',
                'message' => 'Guide not found.',
            ]);
            return;
        }

        if (!empty($existing['VideoUpload'])) {
            $path = $this->videoUploadDir() . $existing['VideoUpload'];
            if (is_file($path)) {
                @unlink($path);
            }
        }

        $Crud->UpdateRecord($SupportModel->tableName(), ['VideoUpload' => null], ['UID' => $UID]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Uploaded video cleared.',
        ]);
    }
}
