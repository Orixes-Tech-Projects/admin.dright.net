<?php
/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION
    );
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;
    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

if ($_SERVER['HTTP_HOST'] == 'admin.dright.dev') {
    define('PATH', 'https://admin.dright.dev/');
    define('TEMPLATE', 'https://admin.dright.dev/template/');
    define('ROOT', dirname(__FILE__) . "/");
    define('PGDB_HOST', '127.0.0.1');
    define('PGDB_USER', 'dright_maindb');
    define('PGDB_PASS', 'drightPostgrSQL');

    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', 'ServBay.dev');
    define('DB_NAME', 'dright_clintamaindb');
} else if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('PATH', 'http://localhost/admin.dright.net/');
    define('TEMPLATE', 'http://localhost/admin.dright.net/template/');
    define('ROOT', dirname(__FILE__) . "/");
    define('PGDB_HOST', '127.0.0.1');
    define('PGDB_USER', 'clinta_postgre');
    define('PGDB_PASS', 'PostgreSql147');

    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'dright_clintamaindb');
//    define('DB_NAME', 'clinta_maindb');
} else {
    define('PATH', 'https://admin.dright.net/');
    define('TEMPLATE', 'https://admin.dright.net/template/');
    define('ROOT', dirname(__FILE__) . "/");
    define('PGDB_HOST', '127.0.0.1');
    define('PGDB_USER', 'dright_maindb');
    define('PGDB_PASS', 'drightPostgrSQL');
    
    define('DB_HOST', '127.0.0.1');
    define('DB_USER', 'dright_clintamaindb');
    define('DB_PASS', 'dright_clintamaindb');
    define('DB_NAME', 'dright_clintamaindb');
}

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . 'app/Config/Paths.php';

// ^^^ Change this line if you move your application folder

$paths = new Config\Paths();
// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';
exit(CodeIgniter\Boot::bootWeb($paths));