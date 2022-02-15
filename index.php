<?
define('HOME', realpath(getenv('DOCUMENT_ROOT')));
define('INPUT', file_get_contents('php://input'));
define('SETTINGS', include(HOME . '/settings.php'));

require_once HOME . '/root/root.php';

root::init();
root::request()->process();
root::request()->response->submit();
