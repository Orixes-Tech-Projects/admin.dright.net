<?php

if (!function_exists('getSegment')) {

    function getSegment($number)
    {
        $uri = current_url(true);
        if ($uri->getTotalSegments() >= $number && $uri->getSegment($number)) {
            return $uri->getSegment($number);
        } else {
            return false;
        }
    }
}
if (!function_exists('BackAdminHost')) {
    function BackAdminHost($Host)
    {
        /// ----------------- GET FROM aPanel --------------------------------
//        $URL = 'https://builder-api.clinta.biz/branding/apanel-admin';
////        $URL = 'http://localhost/clintabuilder-apis/branding/apanel-admin';
//        $Request = '{ "host": "' . $Host . '" }';
//        $HostAdmin = CurlRequest($URL, $Request);
//        print_r($HostAdmin);
//        $HostAdmin = $HostAdmin['data']['domain'];
        /// ----------------- GET FROM aPanel --------------------------------
        switch ($Host) {
            case "admin-builder-v3.clinta.biz":
                $HostAdmin = "builder-v3.clinta.biz";
                break;
            case "admin-doctor-builder-v3.clinta.biz":
                $HostAdmin = "doctor-builder-v3.clinta.biz";
                break;
            case "admin.test.com":
                $HostAdmin = "test.com";
                break;
            case "admin.reliancehospital.org":
                $HostAdmin = "reliancehospital.org";
                break;
            case "localhost":
                $HostAdmin = "reliancehospital.org";
                //$HostAdmin = "drburhan.clinta.biz";
                break;
            case "admin-usmandoctor.clinta.biz":
                $HostAdmin = "usmandoctor.clinta.biz";
                break;
            case "admin-drburhan.clinta.biz":
                $HostAdmin = "drburhan.clinta.biz";
                break;
            case "admin-usmanhospital.clinta.biz":
                $HostAdmin = "usmanhospital.clinta.biz";
                break;
            case "admin-haadiyahdoctor.clinta.biz":
                $HostAdmin = "haadiyahdoctor.clinta.biz";
                break;
            case "admin-haadiyahhospital.clinta.biz":
                $HostAdmin = "haadiyahhospital.clinta.biz";
                break;
            case "admin-javeddoctor.clinta.biz":
                $HostAdmin = "javeddoctor.clinta.biz";
                break;
            case "admin-javedhospital.clinta.biz":
                $HostAdmin = "javedhospital.clinta.biz";
                break;
            case "admin-demohospital.clinta.biz":
                $HostAdmin = "demohospital.clinta.biz";
                break;
            case "admin.reliancehomehealth.org":
                $HostAdmin = "reliancehomehealth.org";
                break;
            case "admin.relianceaesthetics.com":
                $HostAdmin = "relianceaesthetics.com";
                break;

            case "admin.doct-theme3.clinta.biz":
                $HostAdmin = "doct-theme3.clinta.biz";
                break;
            case "admin.h-theme3.clinta.biz":
                $HostAdmin = "h-theme3.clinta.biz";
                break;

            default:
                $HostAdmin = "";
        }

        return $HostAdmin;
    }
}

if (!function_exists('promotion_material_file_download')) {
    function promotion_material_file_download($key)
    {
        global $CI;

        $path =PATH;

        $code = base64_encode($key);
        $code = str_replace("=", "", $code);
        $URL = $path . 'promotion_material_file_download/' . $code;
        return $URL;
    }
}

if ( !function_exists( 'LoadFile' ) ) {
    function LoadFile( $UID )
    {
        if ( $UID > 0 ) {
            $Code = base64_encode( substr( md5( $UID ), 4, 10 ) . "_" . $UID );
            $Code = str_replace( "=", "", $Code );
            return PATH . "file-cdn/" . $Code;
        } else {
            return "";
        }

    }
}
if (!function_exists('ping')) {
    function ping($host, $port = 80, $timeout = 6)
    {
        $fsock = fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$fsock) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
if (!function_exists('SeoUrl')) {
    /**
     * Create a URL string that is suitable for use in a URL or on the file system
     *
     * @param string $url The URL to be modified
     * @param boolean $path Set to false if this is not a path
     *
     * @return string The modified URL
     */
    function SeoUrl($url, $path = true)
    {
        $url = rtrim($url);
        $url = ltrim($url);
        $url = preg_replace('/[^a-zA-Z0-9_\/]/', '-', trim($url));
        $url = strtolower($url);
        $url = str_replace("/", "-", $url);
        $url = str_replace("\\", "-", $url);
        $url = str_replace("--", "-", $url);
        $url = str_replace("--", "-", $url);
        $url = str_replace("--", "-", $url);
        $url = str_replace("--", "-", $url);
        $url = str_replace("--", "-", $url);
        $url = ((substr($url, strlen($url) - 1, 1) == '-') ? substr($url, 0, strlen($url) - 1) : $url);
        if ($path) {
            return $url;
        } else {
            return $url;
        }
    }
}

if (!function_exists('CheckLogin')) {
    function CheckLogin($data)
    {
        $allowpages = array('login', 'use-login-submit');
        if (!in_array($data['segment_a'], $allowpages)) {
            $session = session();
            $session = $session->get();
            print_r($session);
//             if (!isset($session['UID'])) {
//                 // echo '<script type="text/javascript"> setTimeout(function(){ location.href="' . PATH . 'login" }, 100)</script>';
//                 // exit;
//             } else {
// //                if ($session['UserName'] != 'orixestech') {
// //                    if ($session['LoginStatus'] != 'admin') {
// //                        sleep(rand(2, 5));
// //                    }
//                 }
            }
        }
    }

if (!function_exists('load_image')) {
    function load_image($key)
    {
        global $CI;

        $path = PATH;
//        $template = Template;

        $code = base64_encode($key);
        $code = str_replace("=", "", $code);
        $URL = $path . 'load_image/' . $code;
        return $URL;
    }
}if (!function_exists('load_image_meta')) {
    function load_image_meta($key)
    {
        global $CI;

        $path = PATH;
//        $template = Template;

        $code = base64_encode($key);
        $code = str_replace("=", "", $code);
        $URL = $path . 'load_image_meta/' . $code;
        return $URL;
    }
}
if ( !function_exists( 'CheckdLogin' ) ) {
    function CheckdLogin( $data )
    {
        $allowpages = array ( 'login', 'login-form-submit' );
        if ( !in_array( $data[ 'segment_a' ], $allowpages ) ) {
            $session = session();
            $session = $session->get();
            //print_r($session);
            if ( !isset( $session[ 'UID' ] ) ) {
                echo '<script type="text/javascript"> setTimeout(function(){ location.href="' . PATH . 'login" }, 100)</script>';
                exit;
            } else {
                if ( $session[ 'Email' ] != 'info@orixestech.com' ) {
//                    if ( $session[ 'UserType' ] != 'admin' ) {
//                        sleep(rand(2, 5));
//                    }
                }
            }
        }
    }
}
if (!function_exists('ChecckLogin')) {
    /**
     * Check if user is logged in. If not, redirect to login page.
     *
     * @param array $data Data from current_url()
     */
    function ChecckLogin($data)
    {
        //print_r($data);
        $allowpages = array('login', 'login-form-submit', 'file-cdn');
        if (!in_array($data['segment_a'], $allowpages)) {
            $session = $data['session'];
            if (!isset($session['UID'])) {
                // echo "session not logged in";
                // echo '<script type="text/javascript"> setTimeout(function(){ location.href="' . PATH . 'login" }, 100)</script>';
                // exit;
            } else {
                if ($session['Email'] != 'info@orixestech.com') {
                    //                    if ( $session[ 'UserType' ] != 'admin' ) {
                    //                        sleep(rand(2, 5));
                    //                    }
                }
            }
        }
    }
}
if (!function_exists('DATEFORMAT')) {
    function DATEFORMAT($date)
    {
        if ($date != NULL && $date != '') {
            $str = date("d M, Y ", strtotime($date));
        } else {
            $str = "-";
        }

        return $str;
    }
}

if (!function_exists('TIMEFORMAT')) {
    function TIMEFORMAT($time)
    {
        if ($time != NULL && $time != '') {
            $str = date("h:i A", strtotime($time));
        } else {
            $str = "-";
        }
        return $str;
    }
}

if (!function_exists('NUMBER')) {
    function NUMBER($data)
    {
        if (trim($data) == 0) {
            $val = "-";
        } else {
            $val = number_format(trim($data), 0);
        }
        return $val;
    }
}



if (!function_exists('Code')) {
    function Code($id, $prefix = 'AIMS-')
    {
        $str = $prefix . str_repeat("0", 6 - strlen($id)) . $id;
        return $str;
    }
}
if (!function_exists('BlogSlug')) {
    function BlogSlug($Title, $ProjectID)
    {
        $Crud = new \App\Models\Crud();
        $Slug = SeoUrl($Title, false);
        $loopCNT = 1;
        $mainSlug = $Slug;
        do {
            $SQL = 'SELECT count("UID") as "CNT" FROM public."Blogs"
            WHERE "Slug" = \'' . $Slug . '\' AND "ProjectUID" = \'' . $ProjectID . '\' ';
            $records = $Crud->ExecuteSQL($SQL);
            $blogCNT = $records[0]['CNT'];
            if ($blogCNT > 0) {
                if ($loopCNT > 1) {
                    $Slug = preg_replace('/-\d+$/', '', $mainSlug);
                }
                $Slug = $Slug . '-' . $loopCNT;
            }
            $loopCNT++;
        } while ($blogCNT > 0);
        return $Slug;
    }
}
if (!function_exists('TreatmentSlug')) {
    function TreatmentSlug($Title)
    {
        $Crud = new \App\Models\Crud();
        $Slug = SeoUrl($Title, false);
        $loopCNT = 1;
        $mainSlug = $Slug;
        do {
            $SQL = 'SELECT count("UID") as "CNT" FROM community."treatments"
            WHERE "Slug" = \'' . $Slug . '\' ';
            $records = $Crud->ExecuteSQL($SQL);
            $blogCNT = $records[0]['CNT'];
            if ($blogCNT > 0) {
                if ($loopCNT > 1) {
                    $Slug = preg_replace('/-\d+$/', '', $mainSlug);
                }
                $Slug = $Slug . '-' . $loopCNT;
            }
            $loopCNT++;
        } while ($blogCNT > 0);
        return $Slug;
    }
}
if (!function_exists('ConditionSlug')) {
    function ConditionSlug($Title)
    {
        $Crud = new \App\Models\Crud();
        $Slug = SeoUrl($Title, false);
        $loopCNT = 1;
        $mainSlug = $Slug;
        do {
            $SQL = 'SELECT count("UID") as "CNT" FROM community."conditions"
            WHERE "Slug" = \'' . $Slug . '\' ';
            $records = $Crud->ExecuteSQL($SQL);
            $blogCNT = $records[0]['CNT'];
            if ($blogCNT > 0) {
                if ($loopCNT > 1) {
                    $Slug = preg_replace('/-\d+$/', '', $mainSlug);
                }
                $Slug = $Slug . '-' . $loopCNT;
            }
            $loopCNT++;
        } while ($blogCNT > 0);
        return $Slug;
    }
}


if (!function_exists('money')) {
    function money($data, bool $cur = true, bool $decimal = false): string
    {
        $data = (float)$data;
        if ($data == 0) {
            return '0';
        }
        $negative = $data < 0;
        $absolute = abs($data);
        $decimals = $decimal ? 2 : 0;
        $formatted = number_format($absolute, $decimals);
        if ($cur) {
            $prefix = $negative ? '(PKR ' : 'PKR ';
            $suffix = $negative ? ')' : '';
            return $negative ? "{$prefix}{$formatted}{$suffix}" : "{$prefix}{$formatted}";
        }
        return $negative ? "({$formatted})" : $formatted;
    }
}
