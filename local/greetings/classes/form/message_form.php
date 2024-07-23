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

namespace local_greetings\form;

use moodleform;

/**
 * Class message_form
 *
 * @package    local_greetings
 * @copyright  2024 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');


class message_form extends \moodleform {


public function definition(){
    $mForm = $this->_form;
    $mForm->addElement('header', 'header', get_string('sectioninput', 'local_greetings'));
    $mForm->addElement('textarea', 'message', get_string('yourmessage','local_greetings'));
    $mForm->setType('message', PARAM_TEXT);
    
    $submitLabel = get_string('submit');
    $mForm->addElement('submit', 'submitmessage', $submitLabel);
}

}
