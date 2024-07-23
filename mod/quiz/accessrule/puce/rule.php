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
 * Implementation of the quizaccess_puce plugin.
 *
 * @package    quizaccess_puce
 * @copyright  2024 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');

/**
 * A rule implementing the PUCE Criteria check.
 *
 * @package    quizaccess_puce
 * @copyright  2024 Casen Xu <casenxu@exducereonline.com>
 * @copyright  Exducere Online <@link https://exducereonline.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_puce extends quiz_access_rule_base
{

    /**
     * The quiz that this rule is being applied to.
     * @var quiz
     */
    const SESSION_ATTEMPT_STATUS_ALLOW = 'allow';

    /**
     * The quiz that this rule is being applied to.
     * @var quiz
     */
    const SESSION_ATTEMPT_STATUS_BLOCK = 'block';


    /**
     * Return an appropriately configured instance of this rule, if it is applicable
     * to the given quiz, otherwise return null.
     * @param quiz $quizobj information about the quiz in question.
     * @param int $timenow the time that should be considered as 'now'.
     * @param bool $canignoretimelimits whether the current user is exempt from
     *      time limits by the mod/quiz:ignoretimelimits capability.
     * @return quiz_access_rule_base|null the rule, if applicable, else null.
     */
    public static function make(quiz $quizobj, $timenow, $canignoretimelimits)
    {
        if(!empty($quizobj->get_quiz()->enableblocksession) || !empty($quizobj->get_quiz()->enablenotifications)) {
            return new self($quizobj, $timenow);
        } else {
            return null;
        }
    }

    /**
     * Returns session hash based on moodle session, IP and browser info
     *
     * @return string
     * @throws dml_exception
     */
    private function get_session_hash()
    {
        $sessionstring = sesskey();

        $whitelist = get_config('quizaccess_puce', 'whitelist');
        $ipaddress = getremoteaddr();
        if (!address_in_subnet($ipaddress, $whitelist)) {
            $sessionstring .= $ipaddress;
        }

        $sessionstring .= $_SERVER['HTTP_USER_AGENT'];

        return md5($sessionstring);
    }

    /**
     * Whether the user should be blocked from starting a new attempt or continuing
     * an attempt now.
     *
     * @return string false if access should be allowed, a message explaining the
     *      reason if access should be prevented.
     * @throws coding_exception
     * @throws dml_exception
     */
    public function prevent_access()
    {
        global $DB, $USER;

        $attemptid = $DB->get_field('quiz_attempts', 'id', array('quiz' => $this->quiz->id, 'userid' => $USER->id, 'state' => quiz_attempt::IN_PROGRESS));
        $sessions = $DB->get_records('quizaccess_puce_sess', array('attemptid' => $attemptid));
        $sessionshash = [];
        foreach ($sessions as $session) {
            $sessionshash[] = $session->sessionhash;
        }

        if(count($sessionshash) > $this->quiz->maxnumbersessions)
        {
            $this->send_notification($attemptid, 'blockattempt');

            $blockmsg = \html_writer::start_tag('div', array('class' => 'alert alert-danger bd-callout bd-callout-danger text-justify'));
            $blockmsg .= \html_writer::tag('span', get_string('anothersession', 'quizaccess_puce'));
            $blockmsg .= \html_writer::end_tag('div');

            return $blockmsg;
        }

        return false;
    }

    /**
     * Is check before attempt start is required.
     *
     * @param int|null $attemptid the id of the current attempt, if there is one, otherwise null.
     *
     * @return bool whether a check is required before the user starts/continues their attempt.
     *
     * @throws coding_exception
     * @throws dml_exception
     */
    public function is_preflight_check_required($attemptid)
    {
        global $DB;

        if (is_null($attemptid)) {
            return false;
        }

        // Do not lock preview. We cannot clear quizaccess_puce_sess, because current_attempt_finished and event observers
        // are not called on preview finish.
        $attemptobj = quiz_attempt::create($attemptid);
        if ($attemptobj->is_preview()) {
            return false;
        }

        $sessions = $DB->get_records('quizaccess_puce_sess', array('attemptid' => $attemptid));
        $sessionshash = [];
        foreach ($sessions as $session) {
            if(!in_array($this->get_session_hash(), $sessionshash)){
                $sessionshash[] = $session->sessionhash;
            }
        }

        if(count($sessionshash) > $this->quiz->maxnumbersessions)
        {
            // Last record
            $session = new stdClass();
            $session->quizid = $this->quiz->id;
            $session->attemptid = $attemptid;
            $session->sessionhash = $this->get_session_hash();
            $session->ip = getremoteaddr();
            $session->status = self::SESSION_ATTEMPT_STATUS_BLOCK;
            $session->timecreated = time();

            $sessionattempt = $DB->get_record('quizaccess_puce_sess', array('quizid' => $this->quiz->id, 'attemptid' => $attemptid, 'sessionhash' => $this->get_session_hash()));
            if($sessionattempt) {
                $sessionattempt->status = self::SESSION_ATTEMPT_STATUS_BLOCK;
                $sessionattempt->timecreated = time();
                $DB->update_record('quizaccess_puce_sess', $sessionattempt);
            } else {
                $DB->insert_record('quizaccess_puce_sess', $session);
            }

            // Log error.
            $params = array(
                'objectid' => $attemptobj->get_attemptid(),
                'relateduserid' => $attemptobj->get_userid(),
                'courseid' => $attemptobj->get_courseid(),
                'context' => $attemptobj->get_quizobj()->get_context(),
                'other' => array(
                    'quizid' => $attemptobj->get_quizid()
                )
            );

            $event = \quizaccess_puce\event\attempt_blocked::create($params);
            $event->trigger();

            // We do not need preflight form. Just error.
            return true;
        } else {
            if(!in_array($this->get_session_hash(), $sessionshash))
            {
                $session = new stdClass();
                $session->quizid = $this->quiz->id;
                $session->attemptid = $attemptid;
                $session->sessionhash = $this->get_session_hash();
                $session->ip = getremoteaddr();
                $session->status = self::SESSION_ATTEMPT_STATUS_ALLOW;
                $session->timecreated = time();
                $DB->insert_record('quizaccess_puce_sess', $session);
            }
        }
    }

    /**
     * Information, such as might be shown on the quiz view page, relating to this restriction.
     * There is no obligation to return anything. If it is not appropriate to tell students
     * about this rule, then just return ''.
     *
     * @return mixed a message, or array of messages, explaining the restriction
     *         (maybe '' if no message is appropriate).
     *
     * @throws coding_exception
     */
    public function description()
    {
        $outputhtml = \html_writer::tag('div',
            get_string('studentinfo', 'quizaccess_puce'),
            array('class' => 'alert alert-warning bd-callout bd-callout-danger text-justify')
        );
        return $outputhtml;
    }

    /**
     * Get block with unlock attempt link
     *
     * @param int $attemptid the id of the current attempt.
     *
     * @return block_contents
     *
     * @throws coding_exception
     * @throws moodle_exception
     */
    private function get_attempt_unlock_block($attemptid)
    {
        $block = new block_contents();
        $block->attributes['id'] = 'quizaccess_onesession_unlockblock';
        $block->title = get_string('unlockthisattempt_header', 'quizaccess_onesession');
        $url = new moodle_url('/mod/quiz/accessrule/onesession/unlock.php', array('attempt' => $attemptid, 'sesskey' => sesskey()));
        $link = html_writer::link($url, get_string('unlockthisattempt', 'quizaccess_onesession'));
        $block->content = $link;
        return $block;
    }

    /**
     * Sets up the attempt (review or summary) page with any special extra
     * properties required by this rule. securewindow rule is an example of where
     * this is used.
     *
     * @param moodle_page $page the page object to initialise.
     */
    public function setup_attempt_page($page)
    {
        global $DB;

        if (!has_capability('quizaccess/puce:unlockattempt', $this->quizobj->get_context())) {
            return;
        }
        $attemptid = $page->url->param('attempt');
        if (empty($attemptid)) {
            return;
        }
        $attemptobj = quiz_attempt::create($attemptid);
        if ($attemptobj->is_preview()) {
            return;
        }
        if ($attemptobj->get_state() != quiz_attempt::IN_PROGRESS) {
            return;
        }
        if (!$DB->record_exists('quizaccess_onesession_sess', array('attemptid' => $attemptid))) {
            return;
        }
        $unlockblock = $this->get_attempt_unlock_block($attemptid);
        $regions = $page->blocks->get_regions();
        $page->blocks->add_fake_block($unlockblock, reset($regions));
    }

    /**
     * Add any fields that this rule requires to the quiz settings form. This
     * method is called from {@see mod_quiz_mod_form::definition()}, while the
     * security section is being built.
     *
     * @param mod_quiz_mod_form $quizform the quiz settings form that is being built.
     * @param MoodleQuickForm $mform the wrapped MoodleQuickForm.
     *
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function add_settings_form_fields(mod_quiz_mod_form $quizform, MoodleQuickForm $mform)
    {
        global $DB;

        $pluginconfig = get_config('quizaccess_puce');

        if($pluginconfig->enableblocksession) {
            // Number of session on attempt.
            $attemptoptions = array('0' => get_string('unlimited'));
            for ($i = 1; $i <= QUIZ_MAX_ATTEMPT_OPTION; $i++) {
                $attemptoptions[$i] = $i;
            }
            $select = $mform->addElement('select', 'maxnumbersessions', get_string('maxnumbersessions', 'quizaccess_puce'),
                $attemptoptions);
            $select->setSelected($pluginconfig->defaultsessiononattempt);
            $mform->addHelpButton('maxnumbersessions', 'maxnumbersessions', 'quizaccess_puce');
        }

        // Enable notifications.
        if($pluginconfig->enablenotifications)
        {
            // Recipients of notifications.
            $notifyprofilesgroup = [];
            $coursecontext = context_course::instance(SITEID);
            $assignableroles = get_assignable_roles($coursecontext);
            $defaultroles = explode(',', $pluginconfig->notifyrolesenables);
            foreach($assignableroles as $roleid => $rolename) {
                if(in_array($roleid, $defaultroles)) {
                    $notifyprofilesgroup[] = $mform->createElement('checkbox', "notifyprofiles[$roleid]", '', $rolename);
                }
            }

            $mform->addGroup($notifyprofilesgroup, 'notifyprofiles', get_string('notifyprofiles', 'quizaccess_puce'), '', false);
            $mform->addHelpButton('notifyprofiles', 'notifyprofiles', 'quizaccess_puce');
            if (!empty($pluginconfig->notifyrolesdefault)) {
                $defaultnotifyprofiles = explode(',', $pluginconfig->notifyrolesdefault);
            } else {
                $defaultnotifyprofiles = [];
            }
            foreach ($defaultnotifyprofiles as $recipientid) {
                $mform->setDefault("notifyprofiles[$recipientid]", 1);
            }

            // Options of notifications.
            $notifymessagesgroup = [];
            $notifymessagesgroup[] = $mform->createElement('checkbox', "notifymessages[startattempt]", '', get_string('startattempt', 'quizaccess_puce'));
            $notifymessagesgroup[] = $mform->createElement('checkbox', "notifymessages[blockattempt]", '', get_string('blockattempt', 'quizaccess_puce'));
            $notifymessagesgroup[] = $mform->createElement('checkbox', "notifymessages[finishattempt]", '', get_string('finishattempt', 'quizaccess_puce'));

            $mform->addGroup($notifymessagesgroup, 'notifymessages', get_string('notifymessages', 'quizaccess_puce'), '', false);
            $mform->addHelpButton('notifymessages', 'notifymessages', 'quizaccess_puce');
            if (!empty($pluginconfig->notifymessages)) {
                $defaultnotifymessages = explode(',', $pluginconfig->notifymessages);
            } else {
                $defaultnotifymessages = [];
            }
            foreach ($defaultnotifymessages as $messageid) {
                $mform->setDefault("notifymessages[$messageid]", 1);
            }
        }
    }

    /**
     * Save any submitted settings when the quiz settings form is submitted. This
     * is called from {@see quiz_after_add_or_update()} in lib.php.
     *
     * @param object $quiz the data from the quiz form, including $quiz->id
     *      which is the id of the quiz being saved.
     *
     * @throws dml_exception
     */
    public static function save_settings($quiz)
    {
        global $DB;

        $notifyprofiles = '';
        $notifymessages = '';
        $pluginconfig = get_config('quizaccess_puce');

        // Add select profiles
        if (!empty($quiz->notifyprofiles)) {
            foreach ($quiz->notifyprofiles as $profile => $unused) {
                $notifyprofiles .= $profile . ',';
            }
        }

        // Add select messages
        if (!empty($quiz->notifymessages)) {
            foreach ($quiz->notifymessages as $message => $unused) {
                $notifymessages .= $message . ',';
            }
        }

        // Save session settings.
        $record = new stdClass();
        $record->quizid = $quiz->id;
        $record->enableblocksession = $pluginconfig->enableblocksession;
        $record->maxnumbersessions = empty($quiz->maxnumbersessions)? 0 : $quiz->maxnumbersessions;
        $record->enablenotifications = $pluginconfig->enablenotifications;
        $record->notifyprofiles = $notifyprofiles;
        $record->notifymessages = $notifymessages;

        if($pluginconfig->enableblocksession || $pluginconfig->enablenotifications)
        {
            if (!$DB->record_exists('quizaccess_puce', array('quizid' => $quiz->id))) {
                $DB->insert_record('quizaccess_puce', $record);
            } else {
                $recordid = $DB->get_field('quizaccess_puce', 'id', array('quizid' => $quiz->id));
                $record->id = $recordid;
                $DB->update_record('quizaccess_puce', $record);
            }
        } else {
            $DB->delete_records('quizaccess_puce', array('quizid' => $quiz->id));
        }
    }

    /**
     * Delete any rule-specific settings when the quiz is deleted. This is called
     * from {@see quiz_delete_instance()} in lib.php.
     * @param object $quiz the data from the database, including $quiz->id
     *      which is the id of the quiz being deleted.
     * @since Moodle 2.7.1, 2.6.4, 2.5.7
     */
    public static function delete_settings($quiz)
    {
        global $DB;

        $DB->delete_records('quizaccess_puce', array('quizid' => $quiz->id));
        $DB->delete_records('quizaccess_puce_sess', array('quizid' => $quiz->id));
    }

    /**
     * You can use this method to load any extra settings your plugin has that
     * cannot be loaded efficiently with get_settings_sql().
     *
     * @param int $quizid the quiz id.
     *
     * @return array setting value name => value. The value names should all
     *      start with the name of your plugin to avoid collisions.
     *
     * @throws dml_exception
     */
    public static function get_extra_settings($quizid)
    {
        global $DB;

        $extrasettings  = [];
        $pluginsettings = $DB->get_record('quizaccess_puce', array('quizid' => $quizid));

        if(!empty($pluginsettings))
        {
            $extrasettings['enableblocksession'] = $pluginsettings->enableblocksession;
            $extrasettings['enablenotifications'] = $pluginsettings->enablenotifications;
            $extrasettings['maxnumbersessions'] = $pluginsettings->maxnumbersessions;
            $extrasettings['notifyprofiles'] = $pluginsettings->notifyprofiles;
            $extrasettings['notifymessages'] = $pluginsettings->notifymessages;
        }

        return $extrasettings;
    }


    /**
     * Sends notification messages to the interested parties that assign the role capability
     *
     * @param int $attemptid
     * @param String $typenotification
     *
     * @return int|false as for {@link message_send()}.
     * @throws coding_exception
     * @throws dml_exception
     */
    private function send_notification($attemptid, String $typenotification)
    {
        global $CFG, $USER;

        if(!$this->quiz->enablenotifications || empty($this->quiz->notifyprofiles)
        || empty($this->quiz->notifymessages)) {
            return false;
        }

        // List of user to send notification message for attempt.
        $usersroles = enrol_get_course_users_roles($this->quiz->course);
        $notifyroles = explode(',', $this->quiz->notifyprofiles);
        $listusers = [];
        foreach ($usersroles as $userid => $roles) {
            foreach ($roles as $role) {
                if (in_array($role->roleid, $notifyroles)){
                    if(has_capability('mod/quiz:grade', $this->quizobj->get_context(), $userid)) {
                        $listusers[] = \core_user::get_user($userid);
                    } else if($USER->id == $userid) {
                        $listusers[] = \core_user::get_user($userid);
                    }
                }
            }
        }

        // Type of message send to users
        $a = new stdClass();

        // Course info
        $course = get_course($this->quiz->course);
        $a->coursename = $course->fullname.' ('.$course->id.')';

        // Quiz info.
        $attemptobj = quiz_attempt::create($attemptid);
        $a->quizname = $this->quiz->name;
        $a->quizurl  = $CFG->wwwroot . '/mod/quiz/review.php?attempt=' . $attemptobj->get_attemptid();
        $a->quizreviewlink  = '<a href="' . $a->quizurl . '">' . format_string($this->quiz->name) . ' review</a>';

        // Recipient User
        $a->studentname = $USER->firstname.' '.$USER->lastname;

        foreach ($listusers as $recipient)
        {
            $a->username = $recipient->username;

            // Prepare the message.
            $message = new \core\message\message();
            $message->courseid          = $this->quiz->course;
            $message->component         = 'quizaccess_puce';
            $message->name              = 'blockattempt';
            $message->notification      = 1;

            $message->userfrom          = get_admin();
            $message->userto            = $recipient;
            $message->subject           = get_string('email'.$typenotification.'notifysubject', 'quizaccess_puce', $a);
            $message->fullmessage       = get_string('email'.$typenotification.'notifybody', 'quizaccess_puce', $a);
            $message->fullmessageformat = FORMAT_PLAIN;
            $message->fullmessagehtml   = '';

            $message->smallmessage      = get_string('email'.$typenotification.'notifysmall', 'quizaccess_puce', $a);
            $message->contexturlname    = $a->quizname;

            // ... and send it.
            message_send($message);
        }

        return true;
    }
}