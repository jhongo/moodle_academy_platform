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
 * Rule that blocks attempt to open same quiz attempt in other session
 *
 * @package    quizaccess_puce
 * @copyright  2024 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace quizaccess_puce;

defined('MOODLE_INTERNAL') || die();

/**
 * Class for event observers
 *
 * @package    quizaccess_puce
 * @copyright  2024 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observers {

    /**
     * Remove unneeded session information when attempt finished, abandoned, overdue or deleted.
     *
     * @param \core\event\base $event
     */
    public static function unlock_attempt(\core\event\base $event)
    {
        global $DB;

        $attemptid = $event->objectid;
        if (!empty($attemptid)) {
            $DB->delete_records('quizaccess_puce_sess', array('attemptid' => $attemptid));
        }
    }

}