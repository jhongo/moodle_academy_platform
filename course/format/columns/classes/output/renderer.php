<?php

defined('MOODLE_INTERNAL') || die();

use core_courseformat\output\section_renderer;
use renderable as core_renderable;

class format_columns_renderer extends section_renderer {

    public function render(core_renderable $widget) {
        if (!$widget instanceof format_columns_content) {
            throw new coding_exception('Invalid renderable passed to format_columns_renderer::render');
        }

        global $OUTPUT;

        // Start output
        $output = '';

        // Table header
        $output .= html_writer::start_tag('table', array('class' => 'columns-table'));
        $output .= html_writer::start_tag('thead');
        $output .= html_writer::start_tag('tr');
        $output .= html_writer::tag('th', 'IDEAS CLAVE');
        $output .= html_writer::tag('th', 'CASOS PRÁCTICOS');
        $output .= html_writer::tag('th', 'LECTURAS');
        $output .= html_writer::tag('th', 'MÁS RECURSOS');
        $output .= html_writer::tag('th', 'TEST');
        $output .= html_writer::end_tag('tr');
        $output .= html_writer::end_tag('thead');

        // Table body
        $output .= html_writer::start_tag('tbody');
        $output .= html_writer::start_tag('tr');
        for ($i = 1; $i <= 5; $i++) {
            $output .= html_writer::start_tag('td');
            $output .= $widget->render_column($i);
            $output .= $widget->render_add_section_button($i);
            $output .= html_writer::end_tag('td');
        }
        $output .= html_writer::end_tag('tr');
        $output .= html_writer::end_tag('tbody');

        $output .= html_writer::end_tag('table');

        return $output;
    }
}
