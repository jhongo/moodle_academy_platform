<?php

defined('MOODLE_INTERNAL') || die();

use core_courseformat\base as format_base;

class format_column extends format_base {

    public function uses_sections() {
        return true;
    }

    public function course_format_options($foreditform = false) {
        return array();
    }

    public function get_view_url($section, $options = array()) {
        return new moodle_url('/course/view.php', array('id' => $this->courseid));
    }

    public function get_renderer($page) {
        return $page->get_renderer('format_columns');
    }

    public function render_course() {
        global $PAGE;
        $renderer = $this->get_renderer($PAGE);
        $outputclass = $this->get_output_classname('content');
        $widget = new $outputclass($this);
        echo $renderer->render($widget);
    }
}
