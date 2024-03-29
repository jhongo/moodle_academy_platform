<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * TODO describe file index
 *
 * @package    local_welcome
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

global $SITE, $USER;

require_login();

$url = new moodle_url('/local/welcome/index.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$PAGE->set_title($SITE->fullname);


$var_site = $PAGE->set_heading(get_string('pluginname', 'local_welcome'));



function get_data() {
    global $USER;    
    $usename = $USER->firstname;
    return $usename;
}



echo $OUTPUT->header();

if (isloggedin()) {
    echo '<h3> Saludos usuario '. get_data() .'</h3>'; 
    
}


echo $OUTPUT->footer();
