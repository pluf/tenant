<?php
use function Composer\Autoload\includeFile;

// define ( "MODULE_BASE", dirname ( __FILE__ ) . '/../../src/Tenant' );

$cfg = array();
$cfg['test'] = false;
$cfg['timezone'] = 'Europe/Berlin';
// Set the debug variable to true to force the recompilation of all
// the templates each time during development
$cfg['debug'] = true;
$cfg['installed_apps'] = array('Pluf');
// Temporary folder where the script is writing the compiled templates,
// cached data and other temporary resources.
// It must be writeable by your webserver instance.
// It is mandatory if you are using the template system.
$cfg['tmp_folder'] = dirname(__FILE__).'/../tmp';

// The folder in which the templates of the application are located.
$cfg['templates_folder'] = array(dirname(__FILE__).'/../templates');

$cfg['pluf_use_rowpermission'] = true;

// Default mimetype of the document your application is sending.
// It can be overwritten for a given response if needed.
$cfg['mimetype'] = 'text/html';

// Some views for testing.
$cfg['app_views'] = dirname(__FILE__).'/views.php';

// Default database configuration. The database defined here will be
// directly accessible from Pluf::db() of course it is still possible
// to open any other number of database connections through Pluf_DB
$cfg['db_login'] = 'testpluf'; # not used with SQLite
$cfg['db_password'] = 'testpluf'; # not used with SQLite
$cfg['db_server'] = 'localhost'; # not used with SQLite
$cfg['db_database'] = dirname(__FILE__).'/../tmp/tmp.sqlite.db';

$cfg['app_base'] = '/testapp';
$cfg['url_format'] = 'simple';

$cfg['template_tags'] = array(
        'mytag' => 'Pluf_Template_Tag_Mytag',
);

// Must be shared by all the installed_apps and the core framework.
// That way you can have several installations of the core framework.
$cfg['db_table_prefix'] = 'pluf_unit_tests_';

// Starting version 4.1 of MySQL the utf-8 support is "correct".
// The reason of the db_version for MySQL is only for that.
$cfg['db_version'] = '5.0';
$cfg['db_engine'] = 'SQLite';


