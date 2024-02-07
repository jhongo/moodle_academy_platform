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
 * TODO describe file settings
 *
 * @package    enrol_clientify
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_reportbuilder\external\columns\sort\get;

 defined('MOODLE_INTERNAL') || die();

 if ($ADMIN->fulltree) {


    // $settings->add(new admin_setting_heading('enrol_clientify','', get_string('pluginname_desc', 'enrol_clientify')));
    $settings->add(new admin_setting_heading('enrol_clientify', get_string('clientifyheader', 'enrol_clientify'), '' ));
    
    
    
    
    $courses = get_courses();
    foreach ($courses as $course) {
        $courselist[$course->id] = $course->fullname;
    }
    // var_dump($courselist);
    // die();
    $name =  'enrol_clientify/clientifyname';
    $title = get_string('clientifytitle', 'enrol_clientify');
    $description = get_string('clientifyitemname_desc','enrol_clientify');
    $default = '1';
    $setting = new admin_setting_configselect($name,$title, $description, $default, $courselist);
    $settings->add($setting);
    
    
    $name = 'enrol_clientify/clientifysettingname';
    $title = get_string('clientifysettings', 'enrol_clientify');
    $description = get_string('clientifysettingname','enrol_clientify');
    $default = 'Hello';
    $setting = new admin_setting_configtext($name,$title, $description, $default);
    $settings->add($setting);
    // $setting = new admin_setting_configselect($configname, $name, $description, $default, $courselist);
    

 }

