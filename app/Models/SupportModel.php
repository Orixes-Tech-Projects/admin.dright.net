<?php

namespace App\Models;

use CodeIgniter\Model;

class SupportModel extends Model
{
    protected $tableName = 'extended_software_support';

    public function tableName()
    {
        return $this->tableName;
    }

    public function NormalizeRelativeUrl($url)
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }

        $url = preg_replace('/[?#].*$/', '', $url);
        if (preg_match('#^https?://#i', $url)) {
            $parts = parse_url($url);
            $url = isset($parts['path']) ? $parts['path'] : '/';
        }

        $url = str_replace('\\', '/', $url);
        $url = preg_replace('#/+#', '/', $url);
        if ($url === '' || $url[0] !== '/') {
            $url = '/' . ltrim($url, '/');
        }
        if ($url !== '/' && substr($url, -1) === '/') {
            $url = rtrim($url, '/');
        }

        if (strlen($url) > 500) {
            $url = substr($url, 0, 500);
        }

        return $url;
    }

    public function SanitizeAnswerHtml($html)
    {
        $allowed = '<p><br><b><strong><i><em><u><ul><ol><li><a><h1><h2><h3><h4><h5><h6><span><div><table><thead><tbody><tfoot><tr><th><td><img><blockquote><pre><code>';
        return strip_tags((string) $html, $allowed);
    }

    public function access_level_options()
    {
        return [
            'ALL' => 'ALL',
            'administrator' => 'Administrator',
            'receptionist' => 'Receptionist',
            'accounts' => 'Accounts',
            'hr' => 'Human Resource',
            'lab-receptionist' => 'Lab Technision',
            'phlebotomist' => 'Phlebotomist',
            'nursing-station' => 'Nursing Station',
            'store-manager' => 'Store Manager',
            'operation-theater' => 'Operation Theater',
            'pharmacist' => 'Pharmacist',
            'pathologist' => 'Pathologist',
            'doctor' => 'Doctor',
            'super_user' => 'Super User',
        ];
    }

    public function escape_like($value)
    {
        $db = \Config\Database::connect();
        return $db->escapeLikeString((string) $value);
    }

    public function escape_str($value)
    {
        $db = \Config\Database::connect();
        return $db->escapeString((string) $value);
    }

    protected function build_list_sql($filters = [])
    {
        $SQL = 'SELECT * FROM `' . $this->tableName . '` WHERE `Archive` = 0';

        $keyword = isset($filters['keyword']) ? trim((string) $filters['keyword']) : '';
        $access = isset($filters['access']) ? trim((string) $filters['access']) : '';
        $url = isset($filters['url']) ? trim((string) $filters['url']) : '';
        $status = isset($filters['status']) ? trim((string) $filters['status']) : '';

        if ($keyword !== '') {
            $kw = $this->escape_like($keyword);
            $SQL .= ' AND (`Question` LIKE \'%' . $kw . '%\' ESCAPE \'!\' OR `Answer` LIKE \'%' . $kw . '%\' ESCAPE \'!\' OR `URL` LIKE \'%' . $kw . '%\' ESCAPE \'!\')';
        }
        if ($access !== '') {
            $SQL .= ' AND `AccessLevel` = \'' . $this->escape_str($access) . '\'';
        }
        if ($url !== '') {
            $normalized = $this->NormalizeRelativeUrl($url);
            $SQL .= ' AND `URL` LIKE \'%' . $this->escape_like($normalized !== '' ? $normalized : $url) . '%\' ESCAPE \'!\'';
        }
        if ($status !== '' && in_array($status, ['active', 'lock'], true)) {
            $SQL .= ' AND `Status` = \'' . $this->escape_str($status) . '\'';
        }

        $SQL .= ' ORDER BY `UID` DESC';
        return $SQL;
    }

    public function get_datatables($filters = [])
    {
        $Crud = new Crud();
        $SQL = $this->build_list_sql($filters);
        if (isset($_POST['length']) && (int) $_POST['length'] !== -1) {
            $SQL .= ' LIMIT ' . (int) $_POST['length'] . ' OFFSET ' . (int) ($_POST['start'] ?? 0);
        }
        return $Crud->ExecuteSQL($SQL);
    }

    public function count_datatables($filters = [])
    {
        $Crud = new Crud();
        $records = $Crud->ExecuteSQL($this->build_list_sql($filters));
        return is_array($records) ? count($records) : 0;
    }

    public function get_stats()
    {
        $Crud = new Crud();
        $stats = [
            'active' => 0,
            'with_video' => 0,
            'unique_urls' => 0,
        ];

        $active = $Crud->ExecuteSQL(
            'SELECT COUNT(*) AS cnt FROM `' . $this->tableName . '` WHERE `Archive` = 0 AND `Status` = \'active\''
        );
        $withVideo = $Crud->ExecuteSQL(
            'SELECT COUNT(*) AS cnt FROM `' . $this->tableName . '` WHERE `Archive` = 0
             AND (
                (`VideoUpload` IS NOT NULL AND `VideoUpload` <> \'\')
                OR (`VideoLink` IS NOT NULL AND `VideoLink` <> \'\')
             )'
        );
        $uniqueUrls = $Crud->ExecuteSQL(
            'SELECT COUNT(DISTINCT `URL`) AS cnt FROM `' . $this->tableName . '` WHERE `Archive` = 0'
        );

        $stats['active'] = isset($active[0]['cnt']) ? (int) $active[0]['cnt'] : 0;
        $stats['with_video'] = isset($withVideo[0]['cnt']) ? (int) $withVideo[0]['cnt'] : 0;
        $stats['unique_urls'] = isset($uniqueUrls[0]['cnt']) ? (int) $uniqueUrls[0]['cnt'] : 0;

        return $stats;
    }

    public function normalize_status($status)
    {
        $status = strtolower(trim((string) $status));
        return in_array($status, ['active', 'lock'], true) ? $status : 'active';
    }

    public function get_by_uid($uid)
    {
        $Crud = new Crud();
        return $Crud->SingleRecord($this->tableName, ['UID' => (int) $uid, 'Archive' => 0]);
    }
}
