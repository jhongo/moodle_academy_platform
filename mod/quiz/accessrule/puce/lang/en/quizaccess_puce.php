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
 * Strings for the quizaccess_puce plugin.
 *
 * @package    quizaccess_puce
 * @copyright  2024 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'PUCE - Quiz access rule';
$string['privacy:metadata'] = 'The PUCE criteria for quiz access rule plugin does not store any personal data.';
$string['enableblocksession'] = 'Session policy';
$string['configenableblocksession'] = 'If enabled, the quiz access rule will block attempts to open the same quiz attempt in another session.';
$string['defaultsessiononattempt'] = 'Default session on attempt';
$string['configdefaultsessiononattempt'] = 'The maximum number of sessions allowed for a quiz attempt.';
$string['maxnumbersessions'] = 'Maximum session on attempt';
$string['onesession'] = 'Block concurrent connections';
$string['maxnumbersessions_help'] = 'If enabled, users can continue a quiz attempt only in the same browser session. Any attempts to open the same quiz attempt using another computer, device or browser will be blocked. This may be useful to be sure that no one helps a student by opening the same quiz attempt on other computer.';
$string['anothersession'] = 'You are trying to access quiz attempt from a computer, device or browser other than the one you used to start the quiz. If you have accidentally closed your browser, please, contact the teacher.';
$string['studentinfo'] = 'Attention! It is prohibited to change device while attempting this quiz. Please note that after beginning of quiz attempt any connections to this quiz using other computers, devices and browsers will be blocked. Do not close the browser window until the end of attempt, otherwise you will not be able to complete this quiz.';
$string['notificationsintro'] = 'The following notifications are sent by the quiz access rule plugin.';
$string['enablenotifications'] = 'Enable notifications';
$string['configenablenotifications'] = 'If enabled, notifications will be sent to students when a quiz attempt is blocked.';
$string['notifyprofiles_help'] = 'If enabled, notifications will be sent to students when a quiz attempt is blocked.';
$string['notifyprofiles'] = 'Recipients of notifications';
$string['confignotifyprofiles'] = 'Send notifications to selected profiles.';

$string['teacher'] = 'Teacher';
$string['student'] = 'Student';
$string['notifymessages'] = 'Notifications options';
$string['confignotifymessages'] = 'Select the cases when notifications will be sent.';
$string['notifymessages_help'] = 'Select the cases when notifications will be sent.';
$string['startattempt'] = 'Start or continue a quiz attempt';
$string['blockattempt'] = 'Block a quiz attempt';
$string['finishattempt'] = 'Finish a quiz attempt';

$string['notifyrolesenables'] = 'Enable notifications for roles';
$string['confignotifyrolesenables'] = 'Select the roles for which notifications will be sent.';
$string['notifyrolesdefault'] = 'Default roles for notifications';
$string['confignotifyrolesdefault'] = 'Select the default roles for which notifications will be sent.';

// Block attempt email
$string['emailblockattemptnotifysubject'] = 'Bloqueo de cuestionario: {$a->quizname}';
$string['emailblockattemptnotifybody'] = 'Hola {$a->username},
e ha bloqueado el cuestionario ({$a->quizurl
{$a->studentname} s}) en el curso \'{$a->coursename}\' por razones de seguridad del exámen.';
$string['emailblockattemptnotifysmall'] = '{$a->studentname} se ha bloqueado el cuestionario \'{$a->quizname}\'';