<?
if ( php_sapi_name() !== 'cli' ) exit;

define( 'HOME', realpath( getenv( 'DOCUMENT_ROOT' ) ) );
define( 'INPUT', file_get_contents( 'php://input' ) );
define( 'SETTINGS', include( HOME . '/settings.php' ) );

require_once HOME . '/core/root.php';

root::init();

//
