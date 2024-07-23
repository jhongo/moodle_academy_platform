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

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree && $hassiteconfig)
{
    // Setting _PUCE_Heading
    $settings->add(new admin_setting_heading('quizaccess_puce/headingsettings',
        get_string('generalsettings', 'admin'), get_string('configintro', 'quiz')));

    // Setting _Session_Policy
    $name = 'quizaccess_puce/enableblocksession';
    $title = new lang_string('enableblocksession', 'quizaccess_puce');
    $description = new lang_string('configenableblocksession', 'quizaccess_puce');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settings->add($setting);

    // Setting _Default_Session_On_Attempt
    $name = 'quizaccess_puce/defaultsessiononattempt';
    $title = new lang_string('defaultsessiononattempt', 'quizaccess_puce');
    $description = new lang_string('configdefaultsessiononattempt', 'quizaccess_puce');
    $default = 2;
    $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_INT);
    $settings->add($setting);

    // Setting _PUCE_Heading_Notifications
    $settings->add(new admin_setting_heading('quizaccess_puce/headingnotifications',
        get_string('notifications', 'admin'), get_string('notificationsintro', 'quizaccess_puce')));

    // Setting _Notification_Messages
    $name = 'quizaccess_puce/enablenotifications';
    $title = new lang_string('enablenotifications', 'quizaccess_puce');
    $description = new lang_string('configenablenotifications', 'quizaccess_puce');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $settings->add($setting);

    //Setting _Notification_Roles_Enabled
    $coursecontext = context_course::instance(SITEID);
    $assignableroles = get_assignable_roles($coursecontext);
    $name = 'quizaccess_puce/notifyrolesenables';
    $title = new lang_string('notifyrolesenables', 'quizaccess_puce');
    $description = new lang_string('confignotifyrolesenables', 'quizaccess_puce');
    $default = ['student'];
    $choices = $assignableroles;
    $setting = new admin_setting_configmulticheckbox($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Setting _Notification_Default_Roles_Enable
    $name = 'quizaccess_puce/notifyrolesdefault';
    $title = new lang_string('notifyrolesdefault', 'quizaccess_puce');
    $description = new lang_string('confignotifyrolesdefault', 'quizaccess_puce');
    $default = ['student'];
    $choices = $assignableroles;
    $setting = new admin_setting_configmulticheckbox($name, $title, $description, $default, $choices);
    $settings->add($setting);

    // Setting _Notification_Receivers_Options
    $name = 'quizaccess_puce/notifymessages';
    $title = new lang_string('notifymessages', 'quizaccess_puce');
    $description = new lang_string('confignotifymessages', 'quizaccess_puce');
    $default = ['blockattempt'];
    $choices = [
        'startattempt' => get_string('startattempt',  'quizaccess_puce'),
        'blockattempt' => get_string('blockattempt',  'quizaccess_puce'),
        'finishattempt' => get_string('finishattempt',  'quizaccess_puce')
    ];
    $setting = new admin_setting_configmulticheckbox($name, $title, $description, $default, $choices);
    $settings->add($setting);
}