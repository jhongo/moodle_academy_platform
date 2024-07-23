<?php

require_once('../../config.php');

$courseid = required_param('id', PARAM_INT);
$column = required_param('column', PARAM_INT);

require_login($courseid);

$course = get_course($courseid);
$context = context_course::instance($courseid);

require_capability('moodle/course:manageactivities', $context);

$section = course_create_section($courseid);

redirect(new moodle_url('/course/view.php', array('id' => $courseid)));
