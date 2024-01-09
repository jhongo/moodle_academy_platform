<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle_academy';
$CFG->dbuser    = 'john';
$CFG->dbpass    = 'Jgo2205';
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
  'dbpersist' => 0,
  'dbport' => 3306,
  'dbsocket' => '',
  'dbcollation' => 'utf8mb4_0900_ai_ci',
);

$CFG->wwwroot   = 'https://moodle-academy.test';
$CFG->dataroot  = '/Users/John/Documents/Moodle_Project/02_MOODLES_PLATFORM/moodleAcademy/moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

//% Settings for enabling the debug mode
$CFG->depurar = E_ALL;
$CFG->debugdisplay = 1;
$CFG->langstringcache = 0;
$CFG->cachetemplates = 0;
$CFG->cachejs = 0;
$CFG->perfdebug = 15;
$CFG->debugpageinfo = 1; 

require_once(__DIR__ . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
