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

 defined('MOODLE_INTERNAL') || die();

 if ($ADMIN->fulltree) {


    // $settings->add(new admin_setting_heading('enrol_clientify','', get_string('pluginname_desc', 'enrol_clientify')));
    $settings->add(new admin_setting_heading('enrol_clientify', get_string('clientifyheader', 'enrol_clientify'), ''));
    

    // $settings->add($setting);
    // $settings->add(new admin_setting_heading('enrol_flatfile_settings', '', get_string('pluginname_desc', 'enrol_flatfile')));


 }

